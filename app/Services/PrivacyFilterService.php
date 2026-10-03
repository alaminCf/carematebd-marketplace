<?php

namespace App\Services;

use App\Models\Caregiver;
use App\Models\Client;
use App\Models\HiringRequest;

class PrivacyFilterService
{
    /**
     * Return safe public caregiver profile data for client & visitor view.
     * ZERO contact information, ZERO NID, ZERO private address.
     *
     * @return array<string, mixed>
     */
    public function publicCaregiverProfile(Caregiver $caregiver): array
    {
        return [
            'id' => $caregiver->id,
            'slug' => $caregiver->slug,
            'name' => $caregiver->user->name,
            'avatar_url' => $caregiver->user->avatarUrl(),
            'gender' => $caregiver->gender,
            'caregiver_type' => $caregiver->caregiver_type,
            'years_experience' => $caregiver->years_experience,
            'location' => [
                'area' => $caregiver->area?->name,
                'city' => $caregiver->city,
                'district' => $caregiver->district?->name,
                'division' => $caregiver->division?->name,
                'summary' => $caregiver->locationSummary(),
            ],
            'location_summary' => $caregiver->locationSummary(),
            'rates' => [
                'hourly' => $caregiver->hourly_rate,
                'daily' => $caregiver->daily_rate,
                'weekly' => $caregiver->weekly_rate,
                'monthly' => $caregiver->monthly_rate,
                'starting' => $caregiver->startingPriceFormatted(),
            ],
            'hourly_rate' => $caregiver->hourly_rate,
            'daily_rate' => $caregiver->daily_rate,
            'weekly_rate' => $caregiver->weekly_rate,
            'monthly_rate' => $caregiver->monthly_rate,
            'starting_price' => $caregiver->startingPriceFormatted(),
            'employment_type' => $caregiver->employment_type,
            'live_type' => $caregiver->live_type,
            'is_available' => $caregiver->is_available,
            'is_verified' => $caregiver->isVerified(),
            'ratings' => [
                'average' => (float) $caregiver->rating_avg,
                'count' => (int) $caregiver->rating_count,
            ],
            'rating_avg' => (float) $caregiver->rating_avg,
            'rating_count' => (int) $caregiver->rating_count,
            'completed_jobs_count' => $caregiver->completed_jobs_count,
            'about' => $caregiver->about,
            'bio' => $caregiver->bio,
            'experience_description' => $caregiver->experience_description,
            'special_skills' => $caregiver->special_skills,
            'skills' => $caregiver->skills ?? [],
            'specializations' => $caregiver->specializations ?? [],
            'languages' => $caregiver->languages ?? [],
            'education_qualification' => $caregiver->education_qualification,
            'education_institution' => $caregiver->education_institution,
            'education_passing_year' => $caregiver->education_passing_year,
            'services' => $caregiver->services->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'icon' => $s->icon,
                'is_primary' => (bool) $s->pivot?->is_primary,
            ]),
            'certificates' => $caregiver->certificates->where('is_public', true)->map(fn ($c) => [
                'name' => $c->name,
                'institution' => $c->institution,
                'issue_year' => $c->issue_year,
                'is_verified' => $c->is_verified,
            ]),
            'experiences' => $caregiver->experiences->map(fn ($e) => [
                'workplace' => $e->workplace,
                'position' => $e->position,
                'start_year' => $e->start_year,
                'end_year' => $e->end_year,
                'description' => $e->description,
            ]),
            'availabilities' => $caregiver->availabilities->map(fn ($a) => [
                'day_name' => $a->dayName(),
                'start_time' => $a->start_time,
                'end_time' => $a->end_time,
                'is_full_day' => $a->is_full_day,
            ]),
        ];
    }

    /**
     * Safe brief for a caregiver viewing an approved hiring request.
     * The caregiver NEVER sees client phone, client email, or exact residential address.
     *
     * @return array<string, mixed>
     */
    public function caregiverRequestBrief(HiringRequest $request): array
    {
        return [
            'reference' => $request->reference,
            'service_name' => $request->service?->name,
            'start_date' => $request->start_date->format('M d, Y'),
            'end_date' => $request->end_date->format('M d, Y'),
            'days_count' => $request->daysCount(),
            'hours_per_day' => $request->hours_per_day,
            'general_location' => $request->generalLocation(),
            'patient_gender' => $request->patient_gender,
            'patient_age' => $request->patient_age,
            'care_requirements' => $request->care_requirements,
            'special_requirements' => $request->special_requirements,
            'admin_notes' => $request->caregiver_brief,
            'budget' => $request->budget ? '৳'.number_format($request->budget, 0) : 'Standard Platform Rate',
            'preferred_schedule' => $request->preferred_schedule,
            'status' => $request->status->value,
            'status_label' => $request->status->label(),
        ];
    }
}
