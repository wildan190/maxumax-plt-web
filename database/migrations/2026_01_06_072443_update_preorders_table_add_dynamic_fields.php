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
        Schema::table('preorders', function (Blueprint $table) {
            $table->json('custom_fields')->nullable()->after('status');
            $table->dropColumn(['nameset', 'nameset_text']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn('custom_fields');
            $table->boolean('nameset')->default(false);
            $table->string('nameset_text')->nullable();
        });
    }
};
