<?php

namespace Tests\Feature;

use App\Enums\LocationType;
use App\Enums\UserRole;
use App\Models\Caregiver;
use App\Models\Location;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_render_successfully(): void
    {
        $publicRoutes = [
            '/',
            '/services',
            '/caregivers',
            '/how-it-works',
            '/about',
            '/faq',
            '/contact',
            '/login',
            '/register/client',
            '/become-caregiver',
            '/sitemap.xml',
            '/robots.txt',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }

        $caregiver = Caregiver::where('status', 'published')->firstOrFail();
        $this->get(route('marketplace.show', $caregiver->slug))->assertStatus(200);

        $service = Service::firstOrFail();
        $this->get(route('services.show', $service->slug))->assertStatus(200);
    }

    public function test_admin_can_access_all_admin_portals(): void
    {
        $admin = User::where('email', 'admin@caremate.com')->firstOrFail();

        $adminRoutes = [
            '/admin/dashboard',
            '/admin/applications',
            '/admin/caregivers',
            '/admin/hiring-requests',
            '/admin/bookings',
            '/admin/clients',
            '/admin/users',
            '/admin/payments',
            '/admin/payouts',
            '/admin/services',
            '/admin/locations',
            '/admin/support/tickets',
            '/admin/disputes',
            '/admin/reports',
            '/admin/settings',
            '/admin/audit-logs',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_client_can_access_client_portal_routes(): void
    {
        $clientUser = User::where('role', 'client')->firstOrFail();

        $clientRoutes = [
            '/client/dashboard',
            '/client/requests',
            '/client/bookings',
            '/client/favorites',
            '/client/support',
            '/client/profile',
        ];

        foreach ($clientRoutes as $route) {
            $response = $this->actingAs($clientUser)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_caregiver_can_access_caregiver_portal_routes(): void
    {
        $caregiverUser = User::where('role', 'caregiver')->firstOrFail();

        $caregiverRoutes = [
            '/caregiver/dashboard',
            '/caregiver/requests',
            '/caregiver/jobs',
            '/caregiver/availability',
            '/caregiver/earnings',
            '/caregiver/documents',
            '/caregiver/support',
            '/caregiver/profile',
        ];

        foreach ($caregiverRoutes as $route) {
            $response = $this->actingAs($caregiverUser)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_caregiver_can_update_profile_rates_and_information(): void
    {
        $caregiverUser = User::where('role', 'caregiver')->firstOrFail();
        $service = Service::firstOrFail();

        $response = $this->actingAs($caregiverUser)
            ->from(route('caregiver.profile'))
            ->put(route('caregiver.profile.update'), [
                'name' => 'Updated Caregiver Name',
                'phone' => '01711223344',
                'years_experience' => 6,
                'daily_rate' => 1950,
                'hourly_rate' => 280,
                'weekly_rate' => 13500,
                'monthly_rate' => 52000,
                'preferred_client_gender' => 'any',
                'employment_type' => 'both',
                'live_type' => 'live_in',
                'about' => 'Extensively updated professional caregiver with high ratings.',
                'bio' => 'Certified geriatric and child care nurse practitioner.',
                'specializations' => 'Elderly Care, Post-Op Care',
                'address' => 'E-14/X, Agargaon, Dhaka',
                'service_ids' => [$service->id],
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('caregiver.profile'));

        $caregiverUser->refresh();
        $this->assertEquals('Updated Caregiver Name', $caregiverUser->name);
        $this->assertEquals('01711223344', $caregiverUser->phone);

        $caregiver = $caregiverUser->caregiver->fresh();
        $this->assertEquals(6, $caregiver->years_experience);
        $this->assertEquals(1950.00, (float) $caregiver->daily_rate);
        $this->assertEquals(280.00, (float) $caregiver->hourly_rate);
        $this->assertEquals(13500.00, (float) $caregiver->weekly_rate);
        $this->assertEquals(52000.00, (float) $caregiver->monthly_rate);
        $this->assertEquals('live_in', $caregiver->live_type);
        $this->assertTrue($caregiver->services->contains($service->id));
    }

    public function test_all_divisions_and_districts_are_available_and_caregiver_can_save_barishal_and_bhola(): void
    {
        $this->assertEquals(8, Location::where('type', LocationType::Division)->count());
        $this->assertEquals(64, Location::where('type', LocationType::District)->count());

        $barishalDiv = Location::where('type', LocationType::Division)->where('name', 'Barishal')->firstOrFail();
        $bholaDist = Location::where('type', LocationType::District)->where('name', 'Bhola')->firstOrFail();
        $this->assertEquals($barishalDiv->id, $bholaDist->parent_id);

        $caregiverUser = User::where('role', 'caregiver')->firstOrFail();

        $response = $this->actingAs($caregiverUser)
            ->from(route('caregiver.register', ['step' => 3]))
            ->post(route('caregiver.register.step'), [
                'step' => 3,
                'present_address' => 'House 14, Road 7, Dhanmondi, Dhaka',
                'permanent_address' => 'bhola, barishal',
                'division_id' => $barishalDiv->id,
                'district_id' => $bholaDist->id,
                'city' => 'Bhola Sadar',
                'emergency_contact_name' => 'Alamin',
                'emergency_contact_phone' => '+8801766341730',
                'emergency_contact_relationship' => 'Brother',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('caregiver.register', ['step' => 4]));

        $caregiver = $caregiverUser->caregiver->fresh();
        $this->assertEquals($barishalDiv->id, $caregiver->division_id);
        $this->assertEquals($bholaDist->id, $caregiver->district_id);
        $this->assertEquals('bhola, barishal', $caregiver->permanent_address);
    }

    public function test_admin_can_view_edit_and_set_caregiver_sort_order(): void
    {
        $admin = User::where('email', 'admin@caremate.com')->firstOrFail();
        $caregiver = Caregiver::firstOrFail();

        // 1. View Caregiver Profile
        $showResponse = $this->actingAs($admin)->get(route('admin.caregivers.show', $caregiver->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($caregiver->user->name);

        // 2. Access Edit Form
        $editResponse = $this->actingAs($admin)->get(route('admin.caregivers.edit', $caregiver->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Display Sequence / Sort Order');

        // 3. Update Caregiver Profile & Sequencing
        $service = Service::firstOrFail();
        $updateResponse = $this->actingAs($admin)->put(route('admin.caregivers.update', $caregiver->id), [
            'name' => 'Dr. Admin Verified Caregiver',
            'email' => $caregiver->user->email,
            'phone' => $caregiver->user->phone,
            'gender' => 'female',
            'caregiver_type' => 'Executive Neonatal & Geriatric Specialist',
            'years_experience' => 10,
            'daily_rate' => 2500,
            'hourly_rate' => 350,
            'employment_type' => 'full_time',
            'live_type' => 'both',
            'preferred_client_gender' => 'any',
            'status' => 'published',
            'is_featured' => 1,
            'sort_order' => 1,
            'about' => 'Extremely dedicated care professional with 10 years pedigree.',
            'service_ids' => [$service->id],
            'primary_service_id' => $service->id,
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('admin.caregivers.show', $caregiver->id));

        $caregiver->refresh();
        $this->assertEquals('Dr. Admin Verified Caregiver', $caregiver->user->name);
        $this->assertEquals(1, $caregiver->sort_order);
        $this->assertTrue($caregiver->is_featured);
        $this->assertEquals(2500.00, (float) $caregiver->daily_rate);

        // 4. Quick Update Sort Order Endpoint
        $sortResponse = $this->actingAs($admin)->post(route('admin.caregivers.sort-order', $caregiver->id), [
            'sort_order' => 5,
        ]);
        $sortResponse->assertSessionHasNoErrors();
        $this->assertEquals(5, $caregiver->fresh()->sort_order);
    }

    public function test_marketplace_respects_caregiver_sort_order_sequencing(): void
    {
        $caregivers = Caregiver::where('status', 'published')->take(2)->get();
        $this->assertGreaterThanOrEqual(2, $caregivers->count());

        $firstCaregiver = $caregivers[0];
        $secondCaregiver = $caregivers[1];

        // Give secondCaregiver sort_order 1 (should appear first), firstCaregiver sort_order 2
        $secondCaregiver->update(['sort_order' => 1, 'is_featured' => true]);
        $firstCaregiver->update(['sort_order' => 2, 'is_featured' => true]);

        $response = $this->get(route('marketplace.index'));
        $response->assertStatus(200);

        $viewCaregivers = $response->viewData('caregivers');
        $this->assertEquals($secondCaregiver->id, $viewCaregivers->first()->id);
    }

    public function test_caregiver_registration_step_4_and_step_9_submit_cleanly(): void
    {
        $caregiverUser = User::where('role', 'caregiver')->firstOrFail();
        $service = Service::firstOrFail();

        // Step 4 without previous workplace/experience (tests null-safety)
        $resp4 = $this->actingAs($caregiverUser)->post(route('caregiver.register.step'), [
            'step' => 4,
            'caregiver_type' => 'Professional Palliative Caregiver',
            'service_ids' => [$service->id],
            'primary_service_id' => $service->id,
            'years_experience' => 4,
            'preferred_client_gender' => 'any',
        ]);
        $resp4->assertSessionHasNoErrors();
        $resp4->assertRedirect(route('caregiver.register', ['step' => 5]));

        // Step 9 final submission
        $resp9 = $this->actingAs($caregiverUser)->post(route('caregiver.register.step'), [
            'step' => 9,
        ]);
        $resp9->assertSessionHasNoErrors();
        $resp9->assertRedirect(route('caregiver.dashboard'));

        $this->assertEquals('pending_verification', $caregiverUser->caregiver->fresh()->status->value);
    }

    public function test_locale_switcher_changes_language_and_persists(): void
    {
        $response = $this->get('/locale/bn');
        $response->assertSessionHas('locale', 'bn');
        $response->assertCookie('caremate_locale', 'bn');

        // Test English switch back
        $responseEn = $this->get('/locale/en');
        $responseEn->assertSessionHas('locale', 'en');
        $responseEn->assertCookie('caremate_locale', 'en');
    }

    public function test_detect_location_api_returns_success_and_valid_structure(): void
    {
        // 1. Default / IP fallback
        $response = $this->getJson(route('api.detect-location'));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertNotEmpty($response->json('area'));
        $this->assertNotEmpty($response->json('display'));

        // 2. Dhanmondi GPS coordinates
        $gpsResponse = $this->getJson(route('api.detect-location', [
            'lat' => 23.7465,
            'lon' => 90.3760,
        ]));
        $gpsResponse->assertStatus(200);
        $gpsResponse->assertJson([
            'success' => true,
            'area' => 'Dhanmondi',
            'city' => 'Dhaka',
            'display' => 'Dhanmondi, Dhaka',
        ]);
    }

    public function test_admin_can_create_and_manage_users_with_roles(): void
    {
        $admin = User::where('email', 'admin@caremate.com')->firstOrFail();

        // 1. Create a new Admin user
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Support Manager',
            'email' => 'manager@caremate.com',
            'role' => 'admin',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'manager@caremate.com',
            'name' => 'Support Manager',
        ]);

        $newUser = User::where('email', 'manager@caremate.com')->firstOrFail();
        $this->assertEquals('admin', $newUser->role->value);

        // 2. Toggle status
        $this->actingAs($admin)->post(route('admin.users.toggle-status', $newUser))
            ->assertRedirect(route('admin.users.index'));
        $this->assertEquals('suspended', $newUser->fresh()->status->value);

        // 3. Delete user
        $this->actingAs($admin)->delete(route('admin.users.destroy', $newUser))
            ->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', [
            'id' => $newUser->id,
        ]);
    }

    public function test_user_can_request_password_reset_and_reset_password(): void
    {
        $user = User::where('email', 'client@caremate.com')->firstOrFail();

        // 1. View forgot password page
        $this->get(route('password.request'))->assertStatus(200);

        // 2. Request reset link
        $resp = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);
        $resp->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);

        $directUrl = session('direct_reset_url');
        $this->assertNotEmpty($directUrl);

        // Extract token from direct URL
        parse_str(parse_url($directUrl, PHP_URL_QUERY), $queryParams);
        $token = basename(parse_url($directUrl, PHP_URL_PATH));

        // 3. View reset password form
        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertStatus(200);

        // 4. Submit new password
        $resetResp = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewSecret2026!',
            'password_confirmation' => 'NewSecret2026!',
        ]);

        $resetResp->assertRedirect(route('login'));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);

        // 5. Verify user can login with new password
        $this->post(route('login.submit'), [
            'email' => $user->email,
            'password' => 'NewSecret2026!',
        ])->assertRedirect(route('client.dashboard'));
    }

    public function test_authenticated_user_can_change_password(): void
    {
        $admin = User::where('email', 'admin@caremate.com')->firstOrFail();

        $this->actingAs($admin)->get(route('password.change'))->assertStatus(200);

        $response = $this->actingAs($admin)->post(route('password.change.update'), [
            'current_password' => 'password',
            'password' => 'BrandNewAdminPass!',
            'password_confirmation' => 'BrandNewAdminPass!',
        ]);

        $response->assertSessionHas('success');

        // Logout and confirm new password works
        $this->post(route('logout'));
        $this->post(route('login.submit'), [
            'email' => 'admin@caremate.com',
            'password' => 'BrandNewAdminPass!',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_update_another_user_password(): void
    {
        $admin = User::where('email', 'admin@caremate.com')->firstOrFail();
        $targetUser = User::where('role', UserRole::Caregiver)->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.users.password', $targetUser), [
            'password' => 'ResetByAdmin999!',
            'password_confirmation' => 'ResetByAdmin999!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        // Logout and confirm caregiver can login with admin-set password
        $this->post(route('logout'));
        $this->post(route('login.submit'), [
            'email' => $targetUser->email,
            'password' => 'ResetByAdmin999!',
        ])->assertRedirect(route('caregiver.dashboard'));
    }

    public function test_admin_can_update_contact_settings_and_changes_reflect_on_public_pages(): void
    {
        $admin = User::where('email', 'admin@caremate.com')->firstOrFail();

        $newAddress = 'House 42, Road 11, Banani, Dhaka-1213, Bangladesh';
        $newPhone = '+880 1711-998877';
        $newHotline = '+880 1811-223344';
        $newEmail = 'support@carematebd.com';

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'platform_name' => 'CareMate BD',
            'platform_commission_rate' => 15.0,
            'min_payout_amount' => 1000,
            'office_address' => $newAddress,
            'contact_phone' => $newPhone,
            'emergency_hotline' => $newHotline,
            'contact_email' => $newEmail,
        ]);

        $response->assertSessionHas('success');

        $this->get(route('home'))
            ->assertStatus(200)
            ->assertSee($newAddress)
            ->assertSee($newPhone)
            ->assertSee($newEmail);

        $this->get(route('contact'))
            ->assertStatus(200)
            ->assertSee($newAddress)
            ->assertSee($newPhone)
            ->assertSee($newHotline)
            ->assertSee($newEmail);

        $this->get(route('about'))
            ->assertStatus(200)
            ->assertSee($newAddress)
            ->assertSee($newHotline)
            ->assertSee($newEmail);
    }
}
