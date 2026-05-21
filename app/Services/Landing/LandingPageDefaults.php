<?php

namespace App\Services\Landing;

class LandingPageDefaults
{
    /**
     * @return array<int, array{img: string, title: string, text: string, btns: array<int, array{label: string, url: string, primary: bool}>}>
     */
    public static function heroSlides(): array
    {
        return [
            [
                'img' => asset('assets/img/banner1.jpeg'),
                'title' => 'BORN IN SABAH. BUILT FOR PERFORMANCE.',
                'text' => 'Ready stock apparel and fully customized teamwear for athletes, clubs, schools, companies, events, and sports organizations.',
                'btns' => [
                    ['label' => 'Shop Ready Stock', 'url' => route('products.index'), 'primary' => true],
                    ['label' => 'Start Custom Order', 'url' => 'https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear.', 'primary' => false],
                ],
            ],
        ];
    }

    /**
     * @return array<int, array{label: string, sport_param: string, img: string}>
     */
    public static function shopBySport(): array
    {
        $img = asset('assets/img/banner1.jpeg');

        return [
            ['label' => 'Football', 'sport_param' => 'Football', 'img' => $img],
            ['label' => 'Golf', 'sport_param' => 'Golf', 'img' => $img],
            ['label' => 'Fishing', 'sport_param' => 'Fishing', 'img' => $img],
            ['label' => 'Run & Training', 'sport_param' => 'Run & Training', 'img' => $img],
            ['label' => 'Outdoor', 'sport_param' => 'Outdoor', 'img' => $img],
            ['label' => 'Lifestyle', 'sport_param' => 'Lifestyle', 'img' => $img],
        ];
    }

    /**
     * @return array<int, array{label: string, filter_param: string, img: string}>
     */
    public static function featuredCollections(): array
    {
        $img = asset('assets/img/banner1.jpeg');

        return [
            ['label' => 'Official Team Collections', 'filter_param' => 'Official Team Collections', 'img' => $img],
            ['label' => 'Football Series', 'filter_param' => 'Football Series', 'img' => $img],
            ['label' => 'Golf Series', 'filter_param' => 'Golf Series', 'img' => $img],
            ['label' => 'Fishing Series', 'filter_param' => 'Fishing Series', 'img' => $img],
            ['label' => 'Run & Training Series', 'filter_param' => 'Run & Training Series', 'img' => $img],
            ['label' => 'Outdoor Series', 'filter_param' => 'Outdoor Series', 'img' => $img],
            ['label' => 'Sale / Clearance', 'filter_param' => 'Sale / Clearance', 'img' => $img],
        ];
    }

    /**
     * @return array<int, array{category: string, title: string, description: string, img: string}>
     */
    public static function trustedProjects(): array
    {
        return [
            [
                'category' => 'Futsal',
                'title' => 'Football Association of Brunei Darussalam',
                'description' => 'FABD Futsal Team Season 2025/2026 teamwear and equipment.',
                'img' => asset('assets/img/banner2.jpeg'),
            ],
            [
                'category' => 'Football',
                'title' => "Sabah Football Association Women's Team",
                'description' => 'Teamwear apparel and equipment for Liga Wanita Nasional projects.',
                'img' => asset('assets/img/banner1.jpeg'),
            ],
            [
                'category' => 'Government',
                'title' => 'JKR Sabah',
                'description' => 'Teamwear apparel including trackset for Sukan JKR Se-Malaysia 2024.',
                'img' => asset('assets/img/banner1.jpeg'),
            ],
            [
                'category' => 'Government',
                'title' => 'Lembaga Sukan Negeri Sabah',
                'description' => 'Fully customized windbreaker, cap, and polo shirt for official event.',
                'img' => asset('assets/img/banner2.jpeg'),
            ],
        ];
    }

    /**
     * @return array<int, array{title: string, description: string, icon: string}>
     */
    public static function whyChoose(): array
    {
        return [
            [
                'title' => 'Premium Quality',
                'description' => 'High-performance fabrics engineered for maximum comfort and durability.',
                'icon' => 'quality',
            ],
            [
                'title' => 'Full Customization',
                'description' => 'End-to-end design, fabric selection, and sublimation for your team.',
                'icon' => 'custom',
            ],
            [
                'title' => 'Sabah Pride',
                'description' => 'Proudly rooted and produced locally in Kota Kinabalu, supporting local sports.',
                'icon' => 'local',
            ],
            [
                'title' => 'Expert Support',
                'description' => 'Our expert team works closely with you to ensure your vision is executed.',
                'icon' => 'support',
            ],
        ];
    }
}
