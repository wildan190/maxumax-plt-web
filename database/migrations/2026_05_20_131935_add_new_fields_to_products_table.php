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
            $table->boolean('add_to_homepage')->default(false)->after('available_for_preorder');
            $table->boolean('on_sale')->default(false)->after('price');
            $table->decimal('discounted_price', 10, 2)->nullable()->after('on_sale');
            $table->string('size_guide')->nullable()->after('image_path');
            $table->integer('position')->default(0)->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['add_to_homepage', 'on_sale', 'discounted_price', 'size_guide', 'position']);
        });
    }
};
