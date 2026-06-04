<?php

namespace Database\Seeders;

use App\Models\LandingFeaturedCollectionItem;
use App\Models\LandingHeroSlide;
use App\Models\LandingProjectItem;
use App\Models\LandingShopBySportItem;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hero Slides
        $heroSlides = [
            [
                'title' => 'BORN IN SABAH. BUILT FOR PERFORMANCE.',
                'body' => 'Ready stock apparel and fully customized teamwear for athletes, clubs, schools, companies, events, and sports organizations.',
                'buttons' => [
                    ['label' => 'Shop Ready Stock', 'url' => '/products', 'primary' => true],
                    ['label' => 'Start Custom Order', 'url' => 'https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear.', 'primary' => false],
                ],
            ],
        ];

        foreach ($heroSlides as $index => $slide) {
            LandingHeroSlide::updateOrCreate(
                ['title' => $slide['title']],
                [
                    'sort_order' => $index,
                    'body' => $slide['body'],
                    'buttons' => $slide['buttons'],
                    'image_path' => null,
                ]
            );
        }

        // 2. Shop By Sport (Main Categories)
        $shopBySport = [
            ['label' => 'Football', 'sport_param' => 'Football'],
            ['label' => 'Golf', 'sport_param' => 'Golf'],
            ['label' => 'Fishing', 'sport_param' => 'Fishing'],
            ['label' => 'Run & Training', 'sport_param' => 'Run & Training'],
            ['label' => 'Outdoor', 'sport_param' => 'Outdoor'],
            ['label' => 'Lifestyle', 'sport_param' => 'Lifestyle'],
        ];

        foreach ($shopBySport as $index => $item) {
            LandingShopBySportItem::updateOrCreate(
                ['label' => $item['label']],
                [
                    'sort_order' => $index,
                    'sport_param' => $item['sport_param'],
                    'image_path' => null,
                ]
            );
        }

        // 3. Featured Collections
        $featured = [
            ['label' => 'Official Team Collections', 'filter_param' => 'Official Team Collections'],
            ['label' => 'Football Series', 'filter_param' => 'Football Series'],
            ['label' => 'Golf Series', 'filter_param' => 'Golf Series'],
            ['label' => 'Fishing Series', 'filter_param' => 'Fishing Series'],
            ['label' => 'Run & Training Series', 'filter_param' => 'Run & Training Series'],
            ['label' => 'Outdoor Series', 'filter_param' => 'Outdoor Series'],
            ['label' => 'Sale / Clearance', 'filter_param' => 'Sale / Clearance'],
        ];

        foreach ($featured as $index => $item) {
            LandingFeaturedCollectionItem::updateOrCreate(
                ['label' => $item['label']],
                [
                    'sort_order' => $index,
                    'filter_param' => $item['filter_param'],
                    'image_path' => null,
                ]
            );
        }

        // 4. Trusted Projects
        $projects = [
            [
                'category' => 'Futsal',
                'title' => 'Football Association of Brunei Darussalam',
                'description' => 'FABD Futsal Team Season 2025/2026 teamwear and equipment.',
            ],
            [
                'category' => 'Football',
                'title' => "Sabah Football Association Women's Team",
                'description' => 'Teamwear apparel and equipment for Liga Wanita Nasional projects.',
            ],
            [
                'category' => 'Corporate',
                'title' => 'JKR Sabah',
                'description' => 'Teamwear apparel including trackset for Sukan JKR Se-Malaysia 2024.',
            ],
            [
                'category' => 'Corporate',
                'title' => 'Lembaga Sukan Negeri Sabah',
                'description' => 'Fully customized windbreaker, cap, and polo shirt for official event.',
            ],
        ];

        foreach ($projects as $index => $item) {
            LandingProjectItem::updateOrCreate(
                ['title' => $item['title']],
                [
                    'sort_order' => $index,
                    'category' => $item['category'],
                    'description' => $item['description'],
                    'image_path' => null,
                ]
            );
        }
    }
}
