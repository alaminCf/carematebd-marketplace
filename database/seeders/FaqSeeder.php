<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'How does CareMate BD ensure the safety and authenticity of caregivers?',
                'answer' => 'Every single caregiver on CareMate BD undergoes a mandatory 5-stage verification process: government National ID (NID) verification, background reference checks, training certificate validation, nursing registration checks (where applicable), and an in-person orientation before their profile is approved.',
                'audience' => 'client',
                'sort_order' => 1,
            ],
            [
                'question' => 'Can I directly contact the caregiver by phone or WhatsApp?',
                'answer' => 'No. To ensure maximum safety, quality control, transparent billing, and zero fraud, CareMate BD is an admin-mediated platform. All hiring requests, scheduling, and payments are coordinated through CareMate Support, protecting your personal privacy.',
                'audience' => 'general',
                'sort_order' => 2,
            ],
            [
                'question' => 'How does the hiring process work for families?',
                'answer' => 'You search verified caregivers by service and location, click "Request Care", specify your patient details and dates, and submit. CareMate Admin reviews your requirements within minutes, confirms caregiver availability, and completes the booking.',
                'audience' => 'client',
                'sort_order' => 3,
            ],
            [
                'question' => 'How do caregiver earnings and platform commissions work?',
                'answer' => 'CareMate BD charges a transparent platform service commission (e.g. 15%) from the total booking amount. Caregivers retain 85% of their gross earnings, credited directly to their available balance upon successful service completion.',
                'audience' => 'caregiver',
                'sort_order' => 4,
            ],
            [
                'question' => 'What if I am unhappy with the care service or need a replacement?',
                'answer' => 'CareMate provides a dedicated Support & Dispute mediation system. If an issue arises or you need a caregiver reassignment, our 24/7 care coordination desk will resolve it promptly or assign a replacement caregiver.',
                'audience' => 'client',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
