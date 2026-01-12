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
            $table->string('stripe_payment_intent_id')->nullable()->after('status');
            $table->string('stripe_session_id')->nullable()->after('stripe_payment_intent_id');
            $table->string('refund_status')->nullable()->after('stripe_session_id'); // pending, approved, rejected, completed
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_status');
            $table->string('stripe_refund_id')->nullable()->after('refund_amount');
            $table->text('refund_reason')->nullable()->after('stripe_refund_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'stripe_session_id',
                'refund_status',
                'refund_amount',
                'stripe_refund_id',
                'refund_reason',
            ]);
        });
    }
};
