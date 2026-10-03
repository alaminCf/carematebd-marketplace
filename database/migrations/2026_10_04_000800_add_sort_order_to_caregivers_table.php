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
        if (! Schema::hasColumn('caregivers', 'sort_order')) {
            Schema::table('caregivers', function (Blueprint $table): void {
                $table->unsignedInteger('sort_order')->default(0)->after('is_featured')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caregivers', function (Blueprint $table): void {
            $table->dropColumn('sort_order');
        });
    }
};
