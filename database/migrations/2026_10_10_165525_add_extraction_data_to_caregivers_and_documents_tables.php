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
        Schema::table('caregivers', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('slug');
            $table->string('auto_fill_status', 30)->default('none')->after('application_step');
            $table->json('extracted_profile_data')->nullable()->after('auto_fill_status');
            $table->json('field_review_status')->nullable()->after('extracted_profile_data');
            $table->timestamp('extracted_at')->nullable()->after('field_review_status');
        });

        Schema::table('caregiver_documents', function (Blueprint $table) {
            $table->string('extraction_status', 30)->default('none')->after('status');
            $table->json('extracted_data')->nullable()->after('extraction_status');
            $table->text('extraction_notes')->nullable()->after('extracted_data');
            $table->timestamp('extracted_at')->nullable()->after('extraction_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caregiver_documents', function (Blueprint $table) {
            $table->dropColumn(['extraction_status', 'extracted_data', 'extraction_notes', 'extracted_at']);
        });

        Schema::table('caregivers', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'auto_fill_status', 'extracted_profile_data', 'field_review_status', 'extracted_at']);
        });
    }
};
