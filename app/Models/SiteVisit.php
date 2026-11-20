<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class SiteVisit extends Model
{
    protected static bool $tableVerified = false;

    protected $fillable = [
        'visitor_hash',
        'ip_address',
        'country',
        'country_code',
        'region',
        'user_id',
        'path',
        'route_name',
        'device_type',
        'referrer_source',
        'referrer_url',
        'visited_date',
    ];

    protected function casts(): array
    {
        return [
            'visited_date' => 'date',
        ];
    }

    /**
     * Ensure the site_visits table and geo columns exist in the database.
     */
    public static function ensureTableExists(): bool
    {
        if (static::$tableVerified) {
            return true;
        }

        try {
            if (! Schema::hasTable('site_visits')) {
                Schema::create('site_visits', function (Blueprint $table): void {
                    $table->id();
                    $table->string('visitor_hash', 64)->index();
                    $table->string('ip_address', 45)->nullable();
                    $table->string('country', 80)->nullable()->index();
                    $table->string('country_code', 10)->nullable();
                    $table->string('region', 100)->nullable()->index();
                    $table->foreignId('user_id')->nullable()->index();
                    $table->string('path', 255)->index();
                    $table->string('route_name', 120)->nullable()->index();
                    $table->string('device_type', 20)->default('mobile')->index();
                    $table->string('referrer_source', 40)->default('Direct')->index();
                    $table->string('referrer_url', 255)->nullable();
                    $table->date('visited_date')->index();
                    $table->timestamps();
                });
            } elseif (! Schema::hasColumn('site_visits', 'country')) {
                Schema::table('site_visits', function (Blueprint $table): void {
                    $table->string('country', 80)->nullable()->index()->after('ip_address');
                    $table->string('country_code', 10)->nullable()->after('country');
                    $table->string('region', 100)->nullable()->index()->after('country_code');
                });
            }

            static::$tableVerified = true;

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Resolve Country, Country Code, and Region/City from IP address with 24h caching.
     *
     * @return array{country: string, country_code: string, region: string}
     */
    public static function resolveGeoFromIp(string $ip, ?array $headerHints = null): array
    {
        if (! empty($headerHints['country']) && ! empty($headerHints['region'])) {
            return [
                'country' => substr((string) $headerHints['country'], 0, 80),
                'country_code' => substr(strtoupper((string) ($headerHints['country_code'] ?? 'BD')), 0, 10),
                'region' => substr((string) $headerHints['region'], 0, 100),
            ];
        }

        if ($ip === '' || in_array($ip, ['127.0.0.1', '::1'], true) || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'region' => 'Dhaka',
            ];
        }

        if (app()->environment('testing')) {
            return [
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'region' => 'Dhaka',
            ];
        }

        return Cache::remember('site_visit_geo_'.md5($ip), 86400, function () use ($ip, $headerHints): array {
            try {
                $response = Http::timeout(1.5)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city");
                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data) && ($data['status'] ?? '') === 'success') {
                        $city = trim((string) ($data['city'] ?? ''));
                        $regionName = trim((string) ($data['regionName'] ?? ''));
                        $regionLabel = $city !== '' && $regionName !== '' && $city !== $regionName
                            ? "{$city}, {$regionName}"
                            : ($city ?: ($regionName ?: 'Unknown Region'));

                        return [
                            'country' => substr(trim((string) ($data['country'] ?? 'Bangladesh')), 0, 80),
                            'country_code' => substr(strtoupper(trim((string) ($data['countryCode'] ?? 'BD'))), 0, 10),
                            'region' => substr($regionLabel, 0, 100),
                        ];
                    }
                }
            } catch (\Throwable) {
                // Fallback below
            }

            try {
                $response2 = Http::timeout(1.5)->get("https://ipwho.is/{$ip}");
                if ($response2->successful()) {
                    $data2 = $response2->json();
                    if (is_array($data2) && ! empty($data2['success'])) {
                        $city = trim((string) ($data2['city'] ?? ''));
                        $regionName = trim((string) ($data2['region'] ?? ''));
                        $regionLabel = $city !== '' && $regionName !== '' && $city !== $regionName
                            ? "{$city}, {$regionName}"
                            : ($city ?: ($regionName ?: 'Unknown Region'));

                        return [
                            'country' => substr(trim((string) ($data2['country'] ?? 'Bangladesh')), 0, 80),
                            'country_code' => substr(strtoupper(trim((string) ($data2['country_code'] ?? 'BD'))), 0, 10),
                            'region' => substr($regionLabel, 0, 100),
                        ];
                    }
                }
            } catch (\Throwable) {
                //
            }

            return [
                'country' => substr((string) ($headerHints['country'] ?? 'Bangladesh'), 0, 80),
                'country_code' => substr(strtoupper((string) ($headerHints['country_code'] ?? 'BD')), 0, 10),
                'region' => substr((string) ($headerHints['region'] ?? 'Unknown Region'), 0, 100),
            ];
        });
    }

    /**
     * Convert 2-letter ISO country code to flag emoji.
     */
    public static function countryFlag(?string $countryCode): string
    {
        $code = strtoupper(trim((string) $countryCode));
        if (strlen($code) !== 2 || ! ctype_alpha($code)) {
            return '🌍';
        }

        return mb_chr(ord($code[0]) + 127397, 'UTF-8').mb_chr(ord($code[1]) + 127397, 'UTF-8');
    }

    /**
     * Get comprehensive traffic analytics summary for Admin Dashboard & Reports.
     */
    public static function analyticsSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $empty = [
            'live_now' => 0,
            'today_views' => 0,
            'today_unique' => 0,
            'week_views' => 0,
            'week_unique' => 0,
            'period_views' => 0,
            'period_unique' => 0,
            'total_views' => 0,
            'total_unique' => 0,
            'top_pages' => [],
            'countries' => [],
            'regions' => [],
            'recent_visits' => [],
            'devices' => [
                'mobile' => ['count' => 0, 'percentage' => 0],
                'desktop' => ['count' => 0, 'percentage' => 0],
                'tablet' => ['count' => 0, 'percentage' => 0],
            ],
            'sources' => [
                'Direct' => ['count' => 0, 'percentage' => 0],
                'Google' => ['count' => 0, 'percentage' => 0],
                'Facebook' => ['count' => 0, 'percentage' => 0],
                'WhatsApp' => ['count' => 0, 'percentage' => 0],
                'Other' => ['count' => 0, 'percentage' => 0],
            ],
            'daily_trend' => [],
        ];

        if (! static::ensureTableExists()) {
            return $empty;
        }

        try {
            // Backfill any recent rows missing country
            $missingGeo = static::whereNull('country')->latest()->limit(5)->get();
            foreach ($missingGeo as $visit) {
                $geo = static::resolveGeoFromIp((string) $visit->ip_address);
                $visit->update([
                    'country' => $geo['country'],
                    'country_code' => $geo['country_code'],
                    'region' => $geo['region'],
                ]);
            }

            $today = now()->toDateString();
            $weekAgo = now()->subDays(6)->toDateString();
            $start = $startDate ?: now()->subDays(30)->toDateString();
            $end = $endDate ?: $today;

            $liveNow = (int) static::where('created_at', '>=', now()->subMinutes(15))
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $todayViews = (int) static::whereDate('visited_date', $today)->count();
            $todayUnique = (int) static::whereDate('visited_date', $today)
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $weekViews = (int) static::whereBetween('visited_date', [$weekAgo, $today])->count();
            $weekUnique = (int) static::whereBetween('visited_date', [$weekAgo, $today])
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $periodViews = (int) static::whereBetween('visited_date', [$start, $end])->count();
            $periodUnique = (int) static::whereBetween('visited_date', [$start, $end])
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $totalViews = (int) static::count();
            $totalUnique = (int) static::distinct('visitor_hash')->count('visitor_hash');

            // Top pages in period
            $topPages = static::whereBetween('visited_date', [$start, $end])
                ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT visitor_hash) as unique_visitors'))
                ->groupBy('path')
                ->orderByDesc('views')
                ->limit(8)
                ->get()
                ->map(fn ($row) => [
                    'path' => (string) $row->path,
                    'views' => (int) $row->views,
                    'unique_visitors' => (int) $row->unique_visitors,
                ])->all();

            // Top Countries Breakdown
            $countries = static::whereBetween('visited_date', [$start, $end])
                ->select(
                    DB::raw("COALESCE(country, 'Bangladesh') as country_name"),
                    DB::raw("COALESCE(country_code, 'BD') as code"),
                    DB::raw('COUNT(*) as views'),
                    DB::raw('COUNT(DISTINCT visitor_hash) as unique_visitors')
                )
                ->groupBy('country_name', 'code')
                ->orderByDesc('views')
                ->limit(8)
                ->get()
                ->map(fn ($row) => [
                    'country' => (string) $row->country_name,
                    'country_code' => (string) $row->code,
                    'flag' => static::countryFlag((string) $row->code),
                    'views' => (int) $row->views,
                    'unique_visitors' => (int) $row->unique_visitors,
                    'percentage' => $periodViews > 0 ? (int) round(((int) $row->views / $periodViews) * 100) : 0,
                ])->all();

            // Top Regions / Cities Breakdown
            $regions = static::whereBetween('visited_date', [$start, $end])
                ->select(
                    DB::raw("COALESCE(region, 'Dhaka') as region_name"),
                    DB::raw("COALESCE(country, 'Bangladesh') as country_name"),
                    DB::raw("COALESCE(country_code, 'BD') as code"),
                    DB::raw('COUNT(*) as views'),
                    DB::raw('COUNT(DISTINCT visitor_hash) as unique_visitors')
                )
                ->groupBy('region_name', 'country_name', 'code')
                ->orderByDesc('views')
                ->limit(8)
                ->get()
                ->map(fn ($row) => [
                    'region' => (string) $row->region_name,
                    'country' => (string) $row->country_name,
                    'flag' => static::countryFlag((string) $row->code),
                    'views' => (int) $row->views,
                    'unique_visitors' => (int) $row->unique_visitors,
                    'percentage' => $periodViews > 0 ? (int) round(((int) $row->views / $periodViews) * 100) : 0,
                ])->all();

            // Recent live visitor log
            $recentVisits = static::latest()
                ->limit(10)
                ->get()
                ->map(fn (self $v) => [
                    'path' => $v->path,
                    'country' => $v->country ?: 'Bangladesh',
                    'region' => $v->region ?: 'Dhaka',
                    'flag' => static::countryFlag($v->country_code ?: 'BD'),
                    'device_type' => $v->device_type,
                    'referrer_source' => $v->referrer_source,
                    'time_ago' => $v->created_at?->diffForHumans() ?? 'Just now',
                ])->all();

            // Device breakdown
            $deviceRows = static::whereBetween('visited_date', [$start, $end])
                ->select('device_type', DB::raw('COUNT(*) as cnt'))
                ->groupBy('device_type')
                ->pluck('cnt', 'device_type');

            $devices = [];
            foreach (['mobile', 'desktop', 'tablet'] as $dev) {
                $cnt = (int) ($deviceRows[$dev] ?? 0);
                $devices[$dev] = [
                    'count' => $cnt,
                    'percentage' => $periodViews > 0 ? (int) round(($cnt / $periodViews) * 100) : 0,
                ];
            }

            // Referrer source breakdown
            $sourceRows = static::whereBetween('visited_date', [$start, $end])
                ->select('referrer_source', DB::raw('COUNT(*) as cnt'))
                ->groupBy('referrer_source')
                ->pluck('cnt', 'referrer_source');

            $sources = [];
            foreach (['Direct', 'Google', 'Facebook', 'WhatsApp', 'Other'] as $src) {
                $cnt = (int) ($sourceRows[$src] ?? 0);
                $sources[$src] = [
                    'count' => $cnt,
                    'percentage' => $periodViews > 0 ? (int) round(($cnt / $periodViews) * 100) : 0,
                ];
            }

            // 7-day trend
            $trendRows = static::whereBetween('visited_date', [$weekAgo, $today])
                ->select('visited_date', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT visitor_hash) as uniq'))
                ->groupBy('visited_date')
                ->get()
                ->keyBy(fn ($item) => $item->visited_date instanceof CarbonInterface ? $item->visited_date->toDateString() : substr((string) $item->visited_date, 0, 10));

            $dailyTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i);
                $dStr = $d->toDateString();
                $row = $trendRows->get($dStr);
                $dailyTrend[] = [
                    'date' => $dStr,
                    'label' => $d->format('D, d M'),
                    'views' => $row ? (int) $row->views : 0,
                    'unique' => $row ? (int) $row->uniq : 0,
                ];
            }

            return [
                'live_now' => $liveNow,
                'today_views' => $todayViews,
                'today_unique' => $todayUnique,
                'week_views' => $weekViews,
                'week_unique' => $weekUnique,
                'period_views' => $periodViews,
                'period_unique' => $periodUnique,
                'total_views' => $totalViews,
                'total_unique' => $totalUnique,
                'top_pages' => $topPages,
                'countries' => $countries,
                'regions' => $regions,
                'recent_visits' => $recentVisits,
                'devices' => $devices,
                'sources' => $sources,
                'daily_trend' => $dailyTrend,
            ];
        } catch (\Throwable) {
            return $empty;
        }
    }
}
