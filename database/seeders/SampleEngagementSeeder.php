<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\EarningStatus;
use App\Enums\HiringRequestStatus;
use App\Enums\PaymentStatus;
use App\Enums\ReviewStatus;
use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Booking;
use App\Models\Caregiver;
use App\Models\CaregiverEarning;
use App\Models\Client;
use App\Models\HiringRequest;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Service;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleEngagementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $client = Client::first();
        $caregiver1 = Caregiver::where('status', 'published')->first(); // Nusrat
        $caregiver2 = Caregiver::where('status', 'published')->skip(1)->first(); // Rafiq
        $caregiver3 = Caregiver::where('status', 'published')->skip(2)->first(); // Salma
        $nursingService = Service::where('slug', 'nursing-care')->first();
        $elderlyService = Service::where('slug', 'elderly-care')->first();

        if (! $client || ! $caregiver1 || ! $caregiver2) {
            return;
        }

        // 1. COMPLETED BOOKING WITH REVIEW & EARNINGS
        $hrCompleted = HiringRequest::create([
            'reference' => 'REQ-CMP881',
            'client_id' => $client->id,
            'caregiver_id' => $caregiver1->id,
            'service_id' => $nursingService->id,
            'start_date' => now()->subDays(14)->toDateString(),
            'end_date' => now()->subDays(10)->toDateString(),
            'hours_per_day' => 8,
            'division_id' => $client->division_id,
            'district_id' => $client->district_id,
            'area_id' => $client->area_id,
            'service_address' => 'House 14, Road 7, Dhanmondi, Dhaka',
            'patient_name' => 'Mrs. Jahanara Ahmed (Mother)',
            'patient_age' => 74,
            'patient_gender' => 'female',
            'care_requirements' => 'Post-knee replacement surgical wound dressing and vitals monitoring.',
            'budget' => 7000,
            'status' => HiringRequestStatus::AdminConfirmed,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subDays(15),
            'caregiver_notified_at' => now()->subDays(15),
            'caregiver_responded_at' => now()->subDays(15),
        ]);

        $bkCompleted = Booking::create([
            'reference' => 'BK-99201',
            'hiring_request_id' => $hrCompleted->id,
            'client_id' => $client->id,
            'caregiver_id' => $caregiver1->id,
            'service_id' => $nursingService->id,
            'start_date' => now()->subDays(14)->toDateString(),
            'end_date' => now()->subDays(10)->toDateString(),
            'hours_per_day' => 8,
            'division_id' => $client->division_id,
            'district_id' => $client->district_id,
            'area_id' => $client->area_id,
            'rate_type' => 'daily',
            'service_amount' => 7000.00,
            'commission_rate' => 15.00,
            'commission_amount' => 1050.00,
            'caregiver_amount' => 5950.00,
            'status' => BookingStatus::Completed,
            'confirmed_by' => $admin->id,
            'confirmed_at' => now()->subDays(15),
            'started_at' => now()->subDays(14),
            'completed_at' => now()->subDays(10),
        ]);

        CaregiverEarning::create([
            'caregiver_id' => $caregiver1->id,
            'booking_id' => $bkCompleted->id,
            'gross_amount' => 7000.00,
            'commission_amount' => 1050.00,
            'net_amount' => 5950.00,
            'status' => EarningStatus::Available,
            'available_at' => now()->subDays(10),
        ]);

        Payment::create([
            'booking_id' => $bkCompleted->id,
            'client_id' => $client->id,
            'amount' => 7000.00,
            'commission_amount' => 1050.00,
            'caregiver_amount' => 5950.00,
            'status' => PaymentStatus::Paid,
            'method' => 'bkash',
            'paid_at' => now()->subDays(15),
            'recorded_by' => $admin->id,
        ]);

        Review::create([
            'booking_id' => $bkCompleted->id,
            'client_id' => $client->id,
            'caregiver_id' => $caregiver1->id,
            'rating' => 5,
            'professionalism' => 5,
            'communication' => 5,
            'reliability' => 5,
            'care_quality' => 5,
            'comment' => 'Nurse Nusrat was exceptional with my mother after her orthopedic surgery. Extremely hygienic, always on time, and gave our family complete reassurance. CareMate\'s coordination made the process completely painless!',
            'status' => ReviewStatus::Published,
        ]);

        // 2. ACTIVE CONFIRMED BOOKING
        $hrActive = HiringRequest::create([
            'reference' => 'REQ-ACT342',
            'client_id' => $client->id,
            'caregiver_id' => $caregiver2->id,
            'service_id' => $elderlyService->id,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'hours_per_day' => 8,
            'division_id' => $client->division_id,
            'district_id' => $client->district_id,
            'area_id' => $client->area_id,
            'service_address' => 'House 14, Road 7, Dhanmondi, Dhaka',
            'patient_name' => 'Mr. Enamul Ahmed (Father)',
            'patient_age' => 78,
            'patient_gender' => 'male',
            'care_requirements' => 'Parkinson mobility support and gentle daily walking assistance.',
            'budget' => 7700,
            'status' => HiringRequestStatus::AdminConfirmed,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subDays(2),
        ]);

        Booking::create([
            'reference' => 'BK-88402',
            'hiring_request_id' => $hrActive->id,
            'client_id' => $client->id,
            'caregiver_id' => $caregiver2->id,
            'service_id' => $elderlyService->id,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'hours_per_day' => 8,
            'division_id' => $client->division_id,
            'district_id' => $client->district_id,
            'area_id' => $client->area_id,
            'rate_type' => 'daily',
            'service_amount' => 7700.00,
            'commission_rate' => 15.00,
            'commission_amount' => 1155.00,
            'caregiver_amount' => 6545.00,
            'status' => BookingStatus::InProgress,
            'confirmed_by' => $admin->id,
            'confirmed_at' => now()->subDays(2),
            'started_at' => now()->subDays(1),
        ]);

        // 3. PENDING ADMIN REVIEW REQUEST
        HiringRequest::create([
            'reference' => 'REQ-PND109',
            'client_id' => $client->id,
            'caregiver_id' => $caregiver3->id,
            'service_id' => $elderlyService->id,
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(8)->toDateString(),
            'hours_per_day' => 8,
            'division_id' => $client->division_id,
            'district_id' => $client->district_id,
            'area_id' => $client->area_id,
            'service_address' => 'Mirpur DOHS, Dhaka',
            'patient_name' => 'Mrs. Rokeya Begum (Aunt)',
            'patient_age' => 71,
            'patient_gender' => 'female',
            'care_requirements' => 'Palliative daily routine support and blood glucose monitoring.',
            'budget' => 7200,
            'status' => HiringRequestStatus::PendingAdminReview,
        ]);

        // 4. FAVORITE CAREGIVER
        $client->favoriteCaregivers()->syncWithoutDetaching([$caregiver1->id, $caregiver2->id]);

        // 5. SUPPORT TICKET
        $ticket = SupportTicket::create([
            'ticket_number' => 'TCK-55410',
            'user_id' => $client->user_id,
            'category' => TicketCategory::Booking,
            'subject' => 'Extending upcoming care schedule by 3 additional days',
            'message' => 'Hello CareMate team, our doctor advised my father to continue mobility therapy for another 3 days next week. Could you please check with Brother Rafiq and assist with extending booking BK-88402?',
            'priority' => TicketPriority::Normal,
            'status' => TicketStatus::InReview,
            'assigned_to' => $admin->id,
            'last_reply_at' => now()->subHours(2),
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $client->user_id,
            'message' => 'Hello CareMate team, our doctor advised my father to continue mobility therapy for another 3 days next week. Could you please check with Brother Rafiq and assist with extending booking BK-88402?',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'message' => 'Dear Tanvir, thank you for reaching out. We are verifying Brother Rafiq\'s availability calendar for those additional dates and will update your booking shortly!',
        ]);
    }
}
