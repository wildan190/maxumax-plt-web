<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_hero_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('image_path')->nullable();
            $table->string('title');
            $table->text('body')->nullable();
            $table->json('buttons')->nullable();
            $table->timestamps();
        });

        Schema::create('landing_shop_by_sport_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('image_path')->nullable();
            $table->string('label');
            $table->string('sport_param');
            $table->timestamps();
        });

        Schema::create('landing_featured_collection_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('image_path')->nullable();
            $table->string('label');
            $table->string('filter_param');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_featured_collection_items');
        Schema::dropIfExists('landing_shop_by_sport_items');
        Schema::dropIfExists('landing_hero_slides');
    }
};
