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
            [
                'name' => 'POLO GOLF (ADULT)',
                'data' => [
                    'headers' => ['Size', 'Chest Width', 'Body Length', 'Sleeve Length'],
                    'rows' => [
                        ['XS', '46', '66', '20'],
                        ['S', '48', '68', '21'],
                        ['M', '51', '71', '22'],
                        ['L', '53', '74', '23'],
                        ['XL', '56', '76', '24'],
                        ['2XL', '58', '79', '25'],
                    ]
                ]
            ],
            [
                'name' => 'POLO GOLF (KID)',
                'data' => [
                    'headers' => ['Size', 'Chest Width', 'Body Length'],
                    'rows' => [
                        ['24', '34', '46'],
                        ['26', '36', '49'],
                        ['28', '38', '52'],
                        ['30', '40', '55'],
                        ['32', '42', '58'],
                    ]
                ]
            ],
            [
                'name' => 'RAGLAN CUT (ADULT)',
                'data' => [
                    'headers' => ['Size', 'Chest Width', 'Body Length'],
                    'rows' => [
                        ['XS', '46', '66'],
                        ['S', '48', '68'],
                        ['M', '51', '71'],
                        ['L', '53', '74'],
                        ['XL', '56', '76'],
                        ['2XL', '58', '79'],
                    ]
                ]
            ],
            [
                'name' => 'SABAH FA WOMEN’S TEAM',
                'data' => [
                    'headers' => ['Size', 'Chest Width', 'Body Length'],
                    'rows' => [
                        ['XS', '44', '64'],
                        ['S', '46', '66'],
                        ['M', '48', '68'],
                        ['L', '50', '70'],
                        ['XL', '52', '72'],
                    ]
                ]
            ]
        ];

        foreach ($guides as $index => $guide) {
            SizeGuide::updateOrCreate(
                ['slug' => Str::slug($guide['name'])],
                [
                    'name' => $guide['name'],
                    'data' => $guide['data'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
