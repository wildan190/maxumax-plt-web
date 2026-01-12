<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        // Populate existing records
        $preorders = DB::table('preorders')->whereNull('uuid')->get();
        foreach ($preorders as $preorder) {
            DB::table('preorders')
                ->where('id', $preorder->id)
                ->update(['uuid' => Str::uuid()]);
        }

        // Make it not nullable and unique after population
        Schema::table('preorders', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
