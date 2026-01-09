<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('preorders', 'order_number')) {
            Schema::table('preorders', function (Blueprint $table) {
                $table->string('order_number', 20)->nullable()->after('id');
            });
        }

        DB::table('preorders')->where(function ($q) {
            $q->whereNull('order_number')->orWhere('order_number', '');
        })->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                $code = null;
                do {
                    $candidate = 'MM-'.strtoupper(Str::random(8));
                    $exists = DB::table('preorders')->where('order_number', $candidate)->exists();
                    if (! $exists) {
                        $code = $candidate;
                    }
                } while (! $code);
                DB::table('preorders')->where('id', $row->id)->update(['order_number' => $code]);
            }
        });

        Schema::table('preorders', function (Blueprint $table) {
            $table->unique('order_number');
        });
    }

    public function down(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
