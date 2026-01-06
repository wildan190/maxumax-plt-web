<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('jersey_type');
            $table->string('size')->nullable();
            $table->boolean('long_sleeve')->default(false);
            $table->boolean('nameset')->default(false);
            $table->string('nameset_text')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('MYR');
            $table->string('status')->default('pending'); // pending / paid / cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preorders');
    }
};
