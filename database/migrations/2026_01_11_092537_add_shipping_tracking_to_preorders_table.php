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
            $table->string('tracking_number')->nullable()->after('refund_reason');
            $table->string('shipping_status')->nullable()->after('tracking_number'); // pending, packing, shipped, delivered
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'shipping_status']);
        });
    }
};
