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
        Schema::create('hiring_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('hours_per_day')->default(8);
            $table->foreignId('division_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->text('service_address')->nullable();
            $table->string('patient_name');
            $table->unsignedTinyInteger('patient_age')->nullable();
            $table->string('patient_gender', 10)->nullable();
            $table->text('care_requirements');
            $table->text('special_requirements')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('budget_type', 20)->default('daily');
            $table->string('preferred_schedule')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('status', 40)->default('pending_admin_review')->index();
            $table->text('admin_message')->nullable();
            $table->text('caregiver_brief')->nullable();
            $table->text('client_response')->nullable();
            $table->text('caregiver_response_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('caregiver_notified_at')->nullable();
            $table->timestamp('caregiver_responded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['client_id', 'status']);
            $table->index(['caregiver_id', 'status']);
        });

        Schema::create('hiring_request_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note');
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('hiring_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('caregiver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->unsignedTinyInteger('hours_per_day')->default(8);
            $table->foreignId('division_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('rate_type', 20)->default('daily');
            $table->decimal('service_amount', 12, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('caregiver_amount', 12, 2);
            $table->string('status', 30)->default('confirmed')->index();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completion_requested_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['caregiver_id', 'status', 'start_date', 'end_date']);
            $table->index(['client_id', 'status']);
        });

        Schema::create('booking_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiring_request_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_status', 40)->nullable();
            $table->string('to_status', 40);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_logs');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('hiring_request_notes');
        Schema::dropIfExists('hiring_requests');
    }
};
