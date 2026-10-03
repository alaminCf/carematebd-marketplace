<?php

namespace Database\Seeders;

use App\Enums\CaregiverStatus;
use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Caregiver;
use App\Models\CaregiverAvailability;
use App\Models\CaregiverCertificate;
use App\Models\CaregiverDocument;
use App\Models\Client;
use App\Models\Location;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAndCaregiverSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN USER
        $admin = User::firstOrCreate(
            ['email' => 'admin@caremate.com'],
            [
                'name' => 'CareMate Operations Admin',
                'phone' => '+8801700000001',
                'role' => UserRole::Admin,
                'status' => UserStatus::Active,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Fetch location references
        $dhakaDiv = Location::where('slug', 'dhaka')->where('type', 'division')->first();
        $sylhetDiv = Location::where('slug', 'sylhet')->where('type', 'division')->first();
        $ctgDiv = Location::where('slug', 'chattogram')->where('type', 'division')->first();

        $dhanmondi = Location::where('name', 'Dhanmondi')->first();
        $gulshan = Location::where('name', 'Gulshan')->first();
        $uttara = Location::where('name', 'Uttara')->first();
        $mirpur = Location::where('name', 'Mirpur')->first();
        $mohammadpur = Location::where('name', 'Mohammadpur')->first();
        $zindabazar = Location::where('name', 'Zindabazar')->first();

        // Fetch services
        $elderlyService = Service::where('slug', 'elderly-care')->first();
        $childService = Service::where('slug', 'child-care')->first();
        $nursingService = Service::where('slug', 'nursing-care')->first();
        $transportService = Service::where('slug', 'medical-transportation')->first();

        // 2. CLIENT USERS
        $client1User = User::firstOrCreate(
            ['email' => 'client@caremate.com'],
            [
                'name' => 'Tanvir Ahmed',
                'phone' => '+8801711223344',
                'role' => UserRole::Client,
                'status' => UserStatus::Active,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $client1 = Client::firstOrCreate(
            ['user_id' => $client1User->id],
            [
                'present_address' => 'House 14, Road 7, Dhanmondi, Dhaka',
                'division_id' => $dhakaDiv?->id,
                'district_id' => $dhanmondi?->parent_id,
                'area_id' => $dhanmondi?->id,
                'city' => 'Dhaka',
                'emergency_contact_name' => 'Sabrina Ahmed',
                'emergency_contact_phone' => '+8801711998877',
                'emergency_contact_relationship' => 'Spouse',
            ]
        );

        $client2User = User::firstOrCreate(
            ['email' => 'farhana@caremate.com'],
            [
                'name' => 'Dr. Farhana Rahman',
                'phone' => '+8801722334455',
                'role' => UserRole::Client,
                'status' => UserStatus::Active,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $client2 = Client::firstOrCreate(
            ['user_id' => $client2User->id],
            [
                'present_address' => 'Road 45, Gulshan-2, Dhaka',
                'division_id' => $dhakaDiv?->id,
                'district_id' => $gulshan?->parent_id,
                'area_id' => $gulshan?->id,
                'city' => 'Dhaka',
                'emergency_contact_name' => 'Imtiaz Rahman',
                'emergency_contact_phone' => '+8801722667788',
                'emergency_contact_relationship' => 'Brother',
            ]
        );

        // 3. CAREGIVERS DATA (6 Approved & Published, 1 Pending Verification)
        $caregiversData = [
            [
                'name' => 'Nusrat Jahan, RN',
                'email' => 'nusrat@caremate.com',
                'phone' => '+8801733445566',
                'avatar' => '/images/seed/f1.jpg',
                'gender' => 'female',
                'dob' => '1993-04-12',
                'nid' => '19932694500001',
                'type' => 'Registered Clinical Nurse',
                'experience' => 8,
                'division' => $dhakaDiv,
                'area' => $gulshan,
                'city' => 'Dhaka',
                'hourly' => 200,
                'daily' => 1400,
                'monthly' => 38000,
                'emp_type' => 'full_time',
                'live_type' => 'live_out',
                'about' => 'Diploma Registered Nurse with 8 years of intensive ICU and clinical home healthcare experience. Passionate about providing dignified, empathetic recovery support for elderly patients with chronic ailments and post-surgical rehabilitation.',
                'bio' => 'Licensed nurse registered with the Bangladesh Nursing & Midwifery Council (BNMC). Trained at Dhaka Medical College Hospital with deep proficiency in IV therapy, catheterization, vital signs monitoring, and stroke recovery.',
                'skills' => ['Post-op Rehabilitation', 'IV & Injections', 'Vital Signs Tracking', 'Catheterization', 'Diabetic Insulin Management', 'Oxygen Therapy'],
                'specializations' => ['Geriatric Nursing', 'Post-Surgical Care', 'Palliative Support'],
                'languages' => ['Bengali', 'English'],
                'edu_qual' => 'Diploma in Nursing Science & Midwifery',
                'edu_inst' => 'Dhaka Nursing College',
                'edu_year' => 2015,
                'primary_service' => $nursingService,
                'secondary_service' => $elderlyService,
                'status' => CaregiverStatus::Published,
                'rating_avg' => 5.0,
                'rating_count' => 18,
                'completed_jobs' => 24,
                'featured' => true,
            ],
            [
                'name' => 'Rafiqul Islam',
                'email' => 'rafiq@caremate.com',
                'phone' => '+8801744556677',
                'avatar' => '/images/seed/m1.jpg',
                'gender' => 'male',
                'dob' => '1989-08-20',
                'nid' => '19892694500002',
                'type' => 'Senior Elderly Care Attendant',
                'experience' => 6,
                'division' => $dhakaDiv,
                'area' => $dhanmondi,
                'city' => 'Dhaka',
                'hourly' => 150,
                'daily' => 1100,
                'monthly' => 28000,
                'emp_type' => 'full_time',
                'live_type' => 'live_in',
                'about' => 'Reliable and patient male caregiver specializing in Parkinson\'s, Alzheimer\'s, and full mobility transfer assistance for elderly fathers and grandfathers.',
                'bio' => 'Worked over 6 years with several prominent families in Dhanmondi and Gulshan. Certified in CPR, first aid, and patient ergonomics. Fluent in managing daily routines, wheelchair assistance, and bedtime companionship.',
                'skills' => ['Mobility & Transfer Assistance', 'Personal Hygiene & Bathing', 'Physical Exercise Support', 'Medication Reminders', 'Dementia Care'],
                'specializations' => ['Parkinson\'s Care', 'Alzheimer\'s Supervision', 'Mobility Support'],
                'languages' => ['Bengali'],
                'edu_qual' => 'HSC / Certificate in Patient Care',
                'edu_inst' => 'Bangladesh Red Crescent Society',
                'edu_year' => 2017,
                'primary_service' => $elderlyService,
                'secondary_service' => $transportService,
                'status' => CaregiverStatus::Published,
                'rating_avg' => 4.9,
                'rating_count' => 14,
                'completed_jobs' => 19,
                'featured' => true,
            ],
            [
                'name' => 'Salma Begum',
                'email' => 'salma@caremate.com',
                'phone' => '+8801755667788',
                'avatar' => '/images/seed/f2.jpg',
                'gender' => 'female',
                'dob' => '1984-02-14',
                'nid' => '19842694500003',
                'type' => 'Palliative Care Specialist',
                'experience' => 10,
                'division' => $dhakaDiv,
                'area' => $mirpur,
                'city' => 'Dhaka',
                'hourly' => 160,
                'daily' => 1200,
                'monthly' => 30000,
                'emp_type' => 'full_time',
                'live_type' => 'live_out',
                'about' => 'Compassionate 10-year veteran caregiver with a gentle maternal presence. Expert in helping bedridden senior patients maintain hygiene, dignity, nutrition, and peaceful routines.',
                'bio' => 'Trained extensively in hospital ward assistance and geriatric nutrition. Known for her calm, reassuring manner with elderly mothers and grandmothers recovering from surgery or suffering memory loss.',
                'skills' => ['Bedridden Patient Turning', 'Bedsore Prevention', 'Feeding Assistance', 'Blood Pressure Check', 'Diabetic Monitoring'],
                'specializations' => ['Bedridden Senior Care', 'Palliative & End-of-Life', 'Stroke Rehabilitation'],
                'languages' => ['Bengali'],
                'edu_qual' => 'Certified Caregiver Course',
                'edu_inst' => 'BRAC Institute of Skills',
                'edu_year' => 2014,
                'primary_service' => $elderlyService,
                'secondary_service' => $nursingService,
                'status' => CaregiverStatus::Published,
                'rating_avg' => 4.95,
                'rating_count' => 22,
                'completed_jobs' => 31,
                'featured' => true,
            ],
            [
                'name' => 'Anika Tabassum',
                'email' => 'anika@caremate.com',
                'phone' => '+8801766778899',
                'avatar' => '/images/seed/f3.jpg',
                'gender' => 'female',
                'dob' => '1998-11-05',
                'nid' => '19982694500004',
                'type' => 'Certified Early Childhood Nanny',
                'experience' => 4,
                'division' => $dhakaDiv,
                'area' => $uttara,
                'city' => 'Dhaka',
                'hourly' => 140,
                'daily' => 950,
                'monthly' => 24000,
                'emp_type' => 'full_time',
                'live_type' => 'live_out',
                'about' => 'Enthusiastic and nurturing child care provider with a diploma in Early Childhood Development. Loving, observant, and structured in engaging toddlers and preschoolers.',
                'bio' => '4 years experience with expatriate and busy professional families in Dhaka. Certified in pediatric first aid, interactive storytelling, motor-skill games, and healthy meal preparation for toddlers.',
                'skills' => ['Infant Feeding & Hygiene', 'Developmental Activities', 'Bedtime Routines', 'Pediatric First Aid', 'Homework Assistance'],
                'specializations' => ['Toddler Care', 'Infant Care', 'Educational Play'],
                'languages' => ['Bengali', 'English'],
                'edu_qual' => 'BA in Early Childhood Education',
                'edu_inst' => 'University of Dhaka',
                'edu_year' => 2020,
                'primary_service' => $childService,
                'secondary_service' => null,
                'status' => CaregiverStatus::Published,
                'rating_avg' => 4.88,
                'rating_count' => 12,
                'completed_jobs' => 16,
                'featured' => true,
            ],
            [
                'name' => 'Asif Mahmud',
                'email' => 'asif@caremate.com',
                'phone' => '+8801777889900',
                'avatar' => '/images/seed/m2.jpg',
                'gender' => 'male',
                'dob' => '1995-05-18',
                'nid' => '19952694500005',
                'type' => 'Emergency Escort & Critical Care Nurse',
                'experience' => 7,
                'division' => $dhakaDiv,
                'area' => $mohammadpur,
                'city' => 'Dhaka',
                'hourly' => 220,
                'daily' => 1600,
                'monthly' => 42000,
                'emp_type' => 'full_time',
                'live_type' => 'live_out',
                'about' => 'Experienced registered nurse trained in critical care accompaniment, dialysis transport, and complicated post-operative patient management.',
                'bio' => '7 years working between private hospital ICUs and home nursing assignments. Skilled at managing oxygen concentrators, ventilators, and emergency patient transfers.',
                'skills' => ['Medical Escort & Transport', 'Wound Management', 'Ryle\'s Tube Feeding', 'Vital Monitoring', 'Oxygen Therapy'],
                'specializations' => ['Dialysis Transit Escort', 'Post-Op Surgical', 'Tracheostomy Care'],
                'languages' => ['Bengali', 'English'],
                'edu_qual' => 'BSc in Nursing',
                'edu_inst' => 'National Institute of Advanced Nursing',
                'edu_year' => 2018,
                'primary_service' => $transportService,
                'secondary_service' => $nursingService,
                'status' => CaregiverStatus::Published,
                'rating_avg' => 4.92,
                'rating_count' => 16,
                'completed_jobs' => 21,
                'featured' => false,
            ],
            [
                'name' => 'Rabeya Khatun',
                'email' => 'rabeya@caremate.com',
                'phone' => '+8801788990011',
                'avatar' => '/images/seed/f4.jpg',
                'gender' => 'female',
                'dob' => '1979-09-25',
                'nid' => '19792694500006',
                'type' => 'Senior Geriatric Nurse & Attendant',
                'experience' => 12,
                'division' => $sylhetDiv,
                'area' => $zindabazar,
                'city' => 'Sylhet',
                'hourly' => 180,
                'daily' => 1350,
                'monthly' => 34000,
                'emp_type' => 'full_time',
                'live_type' => 'live_out',
                'about' => '12 years of devoted service to elderly patients and families in Sylhet and Dhaka. Known for warmth, precision with medication, and deep spiritual companionship.',
                'bio' => 'Holds a senior nursing diploma and geriatric care credentials. Highly trusted by overseas Bangladeshi families looking after elderly parents back home in Sylhet.',
                'skills' => ['Elderly Companionship', 'Medication Administration', 'Pressure Sore Management', 'Special Diet Cooking', 'Mobility Walking'],
                'specializations' => ['Geriatric Palliative', 'Cardiac Patient Care', 'Post-Stroke Recovery'],
                'languages' => ['Bengali', 'Sylheti', 'English'],
                'edu_qual' => 'Senior Nursing Diploma',
                'edu_inst' => 'Sylhet MAG Osmani Medical College Nursing Unit',
                'edu_year' => 2011,
                'primary_service' => $elderlyService,
                'secondary_service' => $nursingService,
                'status' => CaregiverStatus::Published,
                'rating_avg' => 5.0,
                'rating_count' => 25,
                'completed_jobs' => 38,
                'featured' => true,
            ],
            // 7. PENDING VERIFICATION APPLICANT (For testing Admin Verification workflow)
            [
                'name' => 'Hasan Tariq',
                'email' => 'hasan@caremate.com',
                'phone' => '+8801799001122',
                'avatar' => null,
                'gender' => 'male',
                'dob' => '1996-03-10',
                'nid' => '19962694500007',
                'type' => 'Trained Patient Attendant',
                'experience' => 3,
                'division' => $dhakaDiv,
                'area' => $mirpur,
                'city' => 'Dhaka',
                'hourly' => 120,
                'daily' => 900,
                'monthly' => 22000,
                'emp_type' => 'full_time',
                'live_type' => 'live_out',
                'about' => 'Newly certified nursing attendant with 3 years practical experience in local clinic ward service. Eager to provide patient care and daily assistance.',
                'bio' => 'Completed 6-month Patient Care Attendant course. Reliable, punctual, and disciplined.',
                'skills' => ['Vital Signs', 'Patient Mobility', 'Medication Reminders'],
                'specializations' => ['General Elderly Care'],
                'languages' => ['Bengali'],
                'edu_qual' => 'HSC / Certificate in Patient Attending',
                'edu_inst' => 'Mirpur Healthcare Training Center',
                'edu_year' => 2021,
                'primary_service' => $elderlyService,
                'secondary_service' => null,
                'status' => CaregiverStatus::PendingVerification,
                'rating_avg' => 0,
                'rating_count' => 0,
                'completed_jobs' => 0,
                'featured' => false,
            ],
        ];

        foreach ($caregiversData as $cgData) {
            $user = User::firstOrCreate(
                ['email' => $cgData['email']],
                [
                    'name' => $cgData['name'],
                    'phone' => $cgData['phone'],
                    'role' => UserRole::Caregiver,
                    'status' => UserStatus::Active,
                    'avatar_path' => $cgData['avatar'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $caregiver = Caregiver::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'slug' => Str::slug($cgData['name']).'-'.Str::lower(Str::random(4)),
                    'gender' => $cgData['gender'],
                    'date_of_birth' => $cgData['dob'],
                    'nid_number' => $cgData['nid'],
                    'nid_hash' => hash('sha256', $cgData['nid']),
                    'present_address' => 'Road 12, '.($cgData['area']?->name ?? 'Dhaka'),
                    'permanent_address' => 'Village Home, '.($cgData['division']?->name ?? 'Dhaka'),
                    'division_id' => $cgData['division']?->id,
                    'district_id' => $cgData['area']?->parent_id,
                    'area_id' => $cgData['area']?->id,
                    'city' => $cgData['city'],
                    'emergency_contact_name' => 'Family Contact',
                    'emergency_contact_phone' => '+8801700998877',
                    'emergency_contact_relationship' => 'Kin',
                    'caregiver_type' => $cgData['type'],
                    'years_experience' => $cgData['experience'],
                    'hourly_rate' => $cgData['hourly'],
                    'daily_rate' => $cgData['daily'],
                    'monthly_rate' => $cgData['monthly'],
                    'employment_type' => $cgData['emp_type'],
                    'live_type' => $cgData['live_type'],
                    'is_available' => true,
                    'about' => $cgData['about'],
                    'bio' => $cgData['bio'],
                    'skills' => $cgData['skills'],
                    'specializations' => $cgData['specializations'],
                    'languages' => $cgData['languages'],
                    'education_qualification' => $cgData['edu_qual'],
                    'education_institution' => $cgData['edu_inst'],
                    'education_passing_year' => $cgData['edu_year'],
                    'status' => $cgData['status'],
                    'application_step' => 9,
                    'submitted_at' => now()->subDays(10),
                    'approved_at' => $cgData['status'] === CaregiverStatus::Published ? now()->subDays(9) : null,
                    'published_at' => $cgData['status'] === CaregiverStatus::Published ? now()->subDays(9) : null,
                    'reviewed_by' => $admin->id,
                    'rating_avg' => $cgData['rating_avg'],
                    'rating_count' => $cgData['rating_count'],
                    'completed_jobs_count' => $cgData['completed_jobs'],
                    'is_featured' => $cgData['featured'],
                    'search_text' => implode(' ', array_merge([$cgData['name'], $cgData['type'], $cgData['city']], $cgData['skills'])),
                ]
            );

            // Attach services
            if ($cgData['primary_service']) {
                $caregiver->services()->syncWithoutDetaching([
                    $cgData['primary_service']->id => ['is_primary' => true],
                ]);
            }
            if (! empty($cgData['secondary_service'])) {
                $caregiver->services()->syncWithoutDetaching([
                    $cgData['secondary_service']->id => ['is_primary' => false],
                ]);
            }

            // Attach weekly availability (Sunday - Thursday, 8am - 6pm)
            for ($day = 0; $day <= 6; $day++) {
                if ($day !== 5) { // Friday off for some
                    CaregiverAvailability::firstOrCreate([
                        'caregiver_id' => $caregiver->id,
                        'day_of_week' => $day,
                    ], [
                        'start_time' => '08:00',
                        'end_time' => '18:00',
                        'is_full_day' => false,
                    ]);
                }
            }

            // Add sample verified certificate
            CaregiverCertificate::firstOrCreate([
                'caregiver_id' => $caregiver->id,
                'name' => $cgData['edu_qual'],
            ], [
                'institution' => $cgData['edu_inst'],
                'certificate_number' => 'CERT-'.strtoupper(Str::random(6)),
                'issue_year' => $cgData['edu_year'],
                'is_public' => true,
                'is_verified' => true,
            ]);

            // Add sample document record (NID front/back)
            CaregiverDocument::firstOrCreate([
                'caregiver_id' => $caregiver->id,
                'type' => DocumentType::NidFront,
            ], [
                'title' => 'National ID Card (Front)',
                'path' => 'private/sample_nid_front.jpg',
                'original_name' => 'nid_front.jpg',
                'mime_type' => 'image/jpeg',
                'size' => 1024 * 350,
                'status' => $cgData['status'] === CaregiverStatus::Published ? DocumentStatus::Verified : DocumentStatus::Pending,
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(9),
            ]);
        }
    }
}
