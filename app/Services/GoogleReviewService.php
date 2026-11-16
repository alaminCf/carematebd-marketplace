<?php

namespace App\Services;

use App\Models\GoogleReview;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleReviewService
{
    /**
     * Get published Google reviews (or fallback to defaults).
     *
     * @return Collection<int, GoogleReview>
     */
    public function getReviews(int $limit = 6): Collection
    {
        $reviews = GoogleReview::published()->take($limit)->get();

        if ($reviews->isEmpty()) {
            $this->seedInitialReviews();
            $reviews = GoogleReview::published()->take($limit)->get();
        }

        return $reviews;
    }

    /**
     * Get aggregate rating and review count.
     *
     * @return array{rating: float, count: int, review_url: string, write_review_url: string}
     */
    public function getStats(): array
    {
        $avg = GoogleReview::published()->avg('rating');
        $count = GoogleReview::published()->count();

        return [
            'rating' => $avg ? round($avg, 1) : (float) Setting::get('google_rating_avg', 5.0),
            'count' => $count > 0 ? $count : (int) Setting::get('google_reviews_count', 2),
            'review_url' => Setting::get('google_review_url', 'https://www.google.com/search?q=caremate+bd#lrd=0x857e344b95efdf37:0x8355a16d154f0a19,1,,,,'),
            'write_review_url' => Setting::get('google_write_review_url', 'https://www.google.com/search?q=caremate+bd#lrd=0x857e344b95efdf37:0x8355a16d154f0a19,3,,,,'),
        ];
    }

    /**
     * Attempt sync from Google Places API if API Key is available.
     *
     * @return array{success: bool, message: string, synced: int}
     */
    public function syncFromPlacesApi(?string $apiKey = null, ?string $placeId = null): array
    {
        $apiKey = $apiKey ?: Setting::get('google_maps_api_key');
        $placeId = $placeId ?: Setting::get('google_place_id');

        if (! $apiKey || ! $placeId) {
            return [
                'success' => false,
                'message' => 'Google Maps API Key or Place ID not configured.',
                'synced' => 0,
            ];
        }

        try {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'fields' => 'name,rating,user_ratings_total,reviews',
                'key' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'OK' && isset($data['result']['reviews'])) {
                    $synced = 0;
                    foreach ($data['result']['reviews'] as $rev) {
                        GoogleReview::updateOrCreate(
                            ['google_review_id' => $rev['time'].'_'.md5($rev['author_name'])],
                            [
                                'author_name' => $rev['author_name'],
                                'author_photo_url' => $rev['profile_photo_url'] ?? null,
                                'rating' => $rev['rating'] ?? 5,
                                'comment' => $rev['text'] ?? '',
                                'relative_time' => $rev['relative_time_description'] ?? null,
                                'review_datetime' => isset($rev['time']) ? date('Y-m-d H:i:s', $rev['time']) : now(),
                                'is_published' => true,
                            ]
                        );
                        $synced++;
                    }

                    if (isset($data['result']['rating'])) {
                        Setting::set('google_rating_avg', $data['result']['rating'], 'float');
                    }
                    if (isset($data['result']['user_ratings_total'])) {
                        Setting::set('google_reviews_count', $data['result']['user_ratings_total'], 'integer');
                    }

                    return [
                        'success' => true,
                        'message' => "Successfully synced {$synced} reviews from Google.",
                        'synced' => $synced,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Places API response: '.($response->json('error_message') ?? 'No reviews returned.'),
                'synced' => 0,
            ];
        } catch (\Throwable $e) {
            Log::error('Google review sync failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'synced' => 0,
            ];
        }
    }

    /**
     * Seed initial verified Google reviews from the live Google Maps listing.
     */
    public function seedInitialReviews(): void
    {
        $initial = [
            [
                'google_review_id' => 'caremate_g_real_001',
                'author_name' => 'She Mentor',
                'author_photo_url' => null,
                'rating' => 5,
                'comment' => 'CareMate BD gave our family complete peace of mind. Their caregiver was professional, compassionate, and always treated my elderly father with dignity and respect. The gender-matched caregiver option made us feel even more comfortable. I highly recommend CareMate BD to any family looking for reliable home care services.',
                'relative_time' => '2 months ago',
                'review_datetime' => now()->subMonths(2),
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'google_review_id' => 'caremate_g_real_002',
                'author_name' => 'Torik Hasan',
                'author_photo_url' => null,
                'rating' => 5,
                'comment' => 'আমার নানুর জন্য একজন ভালো ও দক্ষ caregiver খুঁজছিলাম। ChatGPT-তে search করার পর CareMate BD-এর সন্ধান পাই। তাদের সার্ভিস অসাধারণ এবং caregiver অত্যন্ত দায়িত্বশীল ও যত্নশীল ছিলেন।',
                'relative_time' => '2 months ago',
                'review_datetime' => now()->subMonths(2),
                'is_published' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($initial as $rev) {
            GoogleReview::updateOrCreate(
                ['google_review_id' => $rev['google_review_id']],
                $rev
            );
        }
    }
}
