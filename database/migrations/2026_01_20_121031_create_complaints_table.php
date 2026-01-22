<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preorder_id')->constrained('preorders')->onDelete('cascade');
            $table->enum('type', ['refund', 'replacement']); // Buyer's choice
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'expired'])->default('pending');
            $table->text('reason'); // Customer's reason for complaint
            $table->decimal('refund_amount', 10, 2)->nullable(); // For refund type
            $table->string('return_tracking_number')->nullable(); // Customer's return shipment tracking
            $table->string('replacement_order_number')->nullable(); // For replacement type
            $table->timestamp('expires_at'); // Complaint must be filed before this date
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Admin who approved/rejected
            $table->text('admin_notes')->nullable(); // Admin's internal notes
            $table->text('rejection_reason')->nullable(); // Reason if rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
