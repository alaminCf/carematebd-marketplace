<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Elderly Care',
                'slug' => 'elderly-care',
                'short_description' => 'Compassionate daily living assistance, companionship, mobility support, and medication monitoring.',
                'description' => 'Our verified elderly care attendants offer empathetic assistance with bathing, dressing, meal preparation, mobility, cognitive stimulation, and medication prompts so your senior loved ones can thrive safely in the comfort of home.',
                'icon' => 'heart',
                'image_path' => '/images/services/elderly.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Child Care',
                'slug' => 'child-care',
                'short_description' => 'Trained, background-verified nannies, babysitters, and early childhood care providers.',
                'description' => 'Trust your little ones with thoroughly vetted caregivers skilled in infant feeding, hygiene, developmental play, educational routines, and gentle sleep schedules. Complete peace of mind for busy working parents.',
                'icon' => 'sparkles',
                'image_path' => '/images/services/child.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Nursing Care',
                'slug' => 'nursing-care',
                'short_description' => 'Licensed registered nurses for wound dressings, injections, vitals monitoring, and post-surgery rehabilitation.',
                'description' => 'Certified diploma and BSc nurses delivering clinical excellence at home: IV infusions, catheter care, post-operative recovery, diabetic blood sugar control, tracheostomy support, and doctor-prescribed clinical protocols.',
                'icon' => 'shield-check',
                'image_path' => '/images/services/nursing.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Medical Transportation',
                'slug' => 'medical-transportation',
                'short_description' => 'Safe hospital transfers, doctor visit escort, dialysis travel accompaniment, and wheelchair-accessible logistics.',
                'description' => 'Trained patient escort logistics designed for hospital appointments, chemotherapy sessions, dialysis trips, and physiotherapy visits. Caregivers manage wheelchair transfers, hospital queues, and return transit safely.',
                'icon' => 'truck',
                'image_path' => '/images/services/transport.jpg',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $svc) {
            Service::updateOrCreate(['slug' => $svc['slug']], $svc);
        }
    }
}
