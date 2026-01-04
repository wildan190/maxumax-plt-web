<?php

/**
 * Breadcrumb Helper Functions
 */

if (!function_exists('breadcrumbs')) {
    /**
     * Generate breadcrumb array
     *
     * @param array $items
     * @return array
     */
    function breadcrumbs(...$items): array
    {
        $breadcrumbs = [];

        // Always add home
        $breadcrumbs[] = [
            'label' => 'Home',
            'url' => url('/dashboard'),
        ];

        // Add items
        foreach ($items as $item) {
            if (is_array($item)) {
                $breadcrumbs[] = $item;
            } elseif (is_string($item)) {
                $breadcrumbs[] = [
                    'label' => $item,
                    'url' => '#',
                ];
            }
        }

        return $breadcrumbs;
    }
}

if (!function_exists('active_route')) {
    /**
     * Determine if route is active
     *
     * @param string $route
     * @param string $class
     * @return string
     */
    function active_route(string $route, string $class = 'active'): string
    {
        return request()->routeIs($route) ? $class : '';
    }
}

if (!function_exists('page_breadcrumbs')) {
    /**
     * Set breadcrumbs in view
     *
     * @param array $breadcrumbs
     * @return void
     */
    function page_breadcrumbs(array $breadcrumbs): void
    {
        view()->share('breadcrumbs', $breadcrumbs);
    }
}
