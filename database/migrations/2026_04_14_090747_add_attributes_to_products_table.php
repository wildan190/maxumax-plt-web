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
        Schema::table('products', function (Blueprint $table) {
            $table->json('collections')->nullable()->after('collection');
            $table->string('material')->nullable()->after('collections');
            $table->string('gender')->nullable()->after('material');
            $table->string('fit')->nullable()->after('gender');
            $table->string('color')->nullable()->after('fit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['collections', 'material', 'gender', 'fit', 'color']);
        });
    }
};
