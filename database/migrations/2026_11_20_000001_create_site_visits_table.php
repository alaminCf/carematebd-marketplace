<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_visits')) {
            Schema::create('site_visits', function (Blueprint $table): void {
                $table->id();
                $table->string('visitor_hash', 64)->index();
                $table->string('ip_address', 45)->nullable();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('path', 255)->index();
                $table->string('route_name', 120)->nullable()->index();
                $table->string('device_type', 20)->default('mobile')->index();
                $table->string('referrer_source', 40)->default('Direct')->index();
                $table->string('referrer_url', 255)->nullable();
                $table->date('visited_date')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
