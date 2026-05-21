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
                    ['label' => 'Start Custom Order', 'url' => route('preorder.landing'), 'primary' => false],
                ],
            ],
            [
                'img' => asset('assets/img/banner2.jpeg'),
                'title' => 'FULLY CUSTOMIZED TEAMWEAR',
                'text' => 'From design, fabric selection, sublimation printing, sewing, nameset, logo, and finishing - supported through local production in Kota Kinabalu.',
                'btns' => [
                    ['label' => 'Get Team Quotation', 'url' => 'https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear.%0AProduct:%0AQuantity:%0ADeadline:%0ADesign%20idea:%0ALocation:%0ACan%20you%20help%20me%20with%20quotation?', 'primary' => true],
                    ['label' => 'View Custom Process', 'url' => '#custom-process', 'primary' => false],
                ],
            ],
            [
                'img' => asset('assets/img/banner1.jpeg'),
                'title' => 'READY STOCK FOR SPORT AND LIFESTYLE',
                'text' => 'Explore MAXUMAX jerseys, pro jerseys, windbreakers, tracksuits, outdoor apparel, fishing series, run and training series, casual wear, jackets, golf shirts, pants, socks, and accessories.',
                'btns' => [
                    ['label' => 'Shop Ready Stock', 'url' => route('products.index'), 'primary' => true],
                ],
            ],
            [
                'img' => asset('assets/img/banner2.jpeg'),
                'title' => 'OFFICIAL TEAM AND CLUB COLLECTIONS',
                'text' => 'Explore selected official teamwear and limited-edition collections.',
                'btns' => [
                    ['label' => 'View Collections', 'url' => route('products.index', ['filter' => 'collections']), 'primary' => true],
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
            ['label' => 'Football Series', 'sport_param' => 'Football Series', 'img' => $img],
            ['label' => 'Golf Series', 'sport_param' => 'Golf Series', 'img' => $img],
            ['label' => 'Fishing Series', 'sport_param' => 'Fishing Series', 'img' => $img],
            ['label' => 'Basketball Series', 'sport_param' => 'Basketball Series', 'img' => $img],
            ['label' => 'Outdoor Series', 'sport_param' => 'Outdoor Series', 'img' => $img],
            ['label' => 'Run and Training Series', 'sport_param' => 'Run and Training Series', 'img' => $img],
            ['label' => 'Casual / Lifestyle', 'sport_param' => 'Casual / Lifestyle', 'img' => $img],
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
            ['label' => 'Run and Training Series', 'filter_param' => 'Run and Training Series', 'img' => $img],
            ['label' => 'Outdoor Series', 'filter_param' => 'Outdoor Series', 'img' => $img],
            ['label' => 'Sale / Clearance', 'filter_param' => 'Sale / Clearance', 'img' => $img],
        ];
    }
}
