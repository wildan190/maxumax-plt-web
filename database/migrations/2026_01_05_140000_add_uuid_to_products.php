<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('uuid', 36)->nullable()->unique()->after('id');
        });

        // populate existing products with UUIDs
        $products = \Illuminate\Support\Facades\DB::table('products')->select('id')->get();
        foreach ($products as $p) {
            \Illuminate\Support\Facades\DB::table('products')->where('id', $p->id)->update([
                'uuid' => (string) \Illuminate\Support\Str::uuid()
            ]);
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
