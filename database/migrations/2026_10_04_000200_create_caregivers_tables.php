<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('caregivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('gender', 10)->nullable()->index();
            $table->date('date_of_birth')->nullable();

            // Identity (private, encrypted at rest). nid_hash enables duplicate detection.
            $table->text('nid_number')->nullable();
            $table->string('nid_hash', 64)->nullable()->unique();

            // Personal / address (private, encrypted at rest)
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->foreignId('division_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('city')->nullable();
            $table->text('emergency_contact_name')->nullable();
            $table->text('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship', 60)->nullable();

            // Professional
            $table->string('caregiver_type', 60)->nullable();
            $table->unsignedTinyInteger('years_experience')->default(0)->index();
            $table->string('previous_workplace')->nullable();
            $table->text('previous_experience')->nullable();
            $table->json('skills')->nullable();
            $table->json('specializations')->nullable();
            $table->json('languages')->nullable();
            $table->string('preferred_client_gender', 10)->default('any');

            // Education
            $table->string('education_qualification')->nullable();
            $table->string('education_institution')->nullable();
            $table->unsignedSmallInteger('education_passing_year')->nullable();

            // Work preferences
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable()->index();
            $table->decimal('weekly_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->string('employment_type', 20)->nullable()->index();
            $table->string('live_type', 20)->nullable()->index();
            $table->string('preferred_hours')->nullable();
            $table->boolean('is_available')->default(true)->index();

            // Profile
            $table->text('about')->nullable();
            $table->text('bio')->nullable();
            $table->text('experience_description')->nullable();
            $table->text('special_skills')->nullable();

            // Workflow
            $table->string('status', 30)->default('draft')->index();
            $table->text('status_reason')->nullable();
            $table->unsignedTinyInteger('application_step')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('identity_verified_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            // Denormalised marketplace metrics
            $table->decimal('rating_avg', 3, 2)->default(0)->index();
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('completed_jobs_count')->default(0)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->text('search_text')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'division_id', 'district_id']);
        });

        Schema::create('caregiver_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['caregiver_id', 'service_id']);
        });

        Schema::create('caregiver_service_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['caregiver_id', 'location_id']);
        });

        Schema::create('caregiver_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->string('type', 40)->index();
            $table->string('title')->nullable();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedInteger('size');
            $table->string('status', 20)->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('caregiver_certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('institution');
            $table->string('certificate_number')->nullable();
            $table->unsignedSmallInteger('issue_year')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('caregiver_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->string('workplace');
            $table->string('position')->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('caregiver_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_full_day')->default(false);
            $table->timestamps();
            $table->unique(['caregiver_id', 'day_of_week']);
            $table->index('day_of_week');
        });

        Schema::create('caregiver_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->unique(['caregiver_id', 'date']);
        });

        Schema::create('caregiver_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 40);
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30)->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->index(['caregiver_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caregiver_verification_logs');
        Schema::dropIfExists('caregiver_blocked_dates');
        Schema::dropIfExists('caregiver_availabilities');
        Schema::dropIfExists('caregiver_experiences');
        Schema::dropIfExists('caregiver_certificates');
        Schema::dropIfExists('caregiver_documents');
        Schema::dropIfExists('caregiver_service_areas');
        Schema::dropIfExists('caregiver_services');
        Schema::dropIfExists('caregivers');
    }
};
