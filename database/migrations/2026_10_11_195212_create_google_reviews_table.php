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
        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('google_review_id')->nullable()->unique();
            $table->string('author_name');
            $table->string('author_photo_url', 500)->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->text('comment');
            $table->string('relative_time')->nullable();
            $table->timestamp('review_datetime')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_reviews');
    }
};
