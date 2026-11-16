<?php

namespace Tests\Feature;

use App\Enums\CaregiverStatus;
use App\Enums\UserRole;
use App\Models\Caregiver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CaregiverAutoFillRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_caregiver_registration_page_renders_manual_form(): void
    {
        $response = $this->get(route('caregiver.register'));

        $response->assertStatus(200);
        $response->assertSee('Become a Caregiver at CareMate BD');
        $response->assertSee('Step 1 of 9');
        $response->assertDontSee('Upload Documents for Auto Form Fill');
    }

    public function test_extract_document_endpoint_validates_request(): void
    {
        $response = $this->postJson(route('caregiver.register.extract'), []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['document', 'doc_type']);
    }

    public function test_extract_document_endpoint_handles_profile_photo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('headshot.jpg', 400, 400);

        $response = $this->postJson(route('caregiver.register.extract'), [
            'document' => $file,
            'doc_type' => 'profile_photo',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'doc_type' => 'profile_photo',
        ]);
        $this->assertNotEmpty($response->json('preview_url'));
    }

    public function test_extract_document_endpoint_processes_nid_and_returns_structured_data(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('nid_scan.png', 500, 'image/png');

        $response = $this->postJson(route('caregiver.register.extract'), [
            'document' => $file,
            'doc_type' => 'nid_front',
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('status', $response->json());
        $this->assertArrayHasKey('extracted', $response->json());
        $this->assertArrayHasKey('confidence', $response->json());
    }

    public function test_admin_application_view_displays_ocr_audit_panel(): void
    {
        $admin = User::where('role', UserRole::Admin->value)->firstOrFail();

        $user = User::factory()->create([
            'name' => 'Fatema Khatun',
            'email' => 'fatema@test.com',
            'phone' => '+8801712000000',
            'role' => UserRole::Caregiver->value,
        ]);

        $caregiver = Caregiver::create([
            'user_id' => $user->id,
            'name_bn' => 'ফাতেমা খাতুন',
            'gender' => 'female',
            'date_of_birth' => '1995-05-15',
            'status' => CaregiverStatus::PendingVerification,
            'auto_fill_status' => 'success',
            'extracted_profile_data' => [
                'name' => 'Fatema Khatun',
                'name_bn' => 'ফাতেমা খাতুন',
                'nid_number' => '199526955000000',
                'date_of_birth' => '1995-05-15',
            ],
            'nid_number' => '199526955000000',
            'daily_rate' => 1800,
            'years_experience' => 3,
            'caregiver_type' => 'Senior Care Specialist',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.applications.show', $caregiver->id));

        $response->assertStatus(200);
        $response->assertSee('Document Extraction Audit');
        $response->assertSee('ফাতেমা খাতুন');
        $response->assertSee('Extracted vs Submitted Form Data');
    }
}
