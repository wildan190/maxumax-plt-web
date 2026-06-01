<?php

namespace Database\Seeders;

use App\Models\SizeGuide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SizeGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            ['name' => 'POLO GOLF (ADULT)'],
            ['name' => 'POLO GOLF (KID)'],
            ['name' => 'RAGLAN CUT (ADULT)'],
            ['name' => 'SABAH FA WOMEN’S TEAM'],
        ];

        foreach ($guides as $index => $guide) {
            SizeGuide::updateOrCreate(
                ['slug' => Str::slug($guide['name'])],
                [
                    'name' => $guide['name'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
