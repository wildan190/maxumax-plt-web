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
        Schema::table('preorders', function (Blueprint $table) {
            $table->string('shipping_courier_name')->nullable()->after('shipping_status');
            $table->string('shipping_courier_logo')->nullable()->after('shipping_courier_name');
            $table->string('shipping_service_name')->nullable()->after('shipping_courier_logo');
            $table->string('shipping_service_id')->nullable()->after('shipping_service_name');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier_name',
                'shipping_courier_logo',
                'shipping_service_name',
                'shipping_service_id',
                'shipping_cost'
            ]);
        });
    }
};
