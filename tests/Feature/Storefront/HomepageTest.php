<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('PERFORMANCE SPORTSWEAR');
    }

    public function test_homepage_displays_new_arrivals()
    {
        Product::create([
            'name' => 'Test Product 1',
            'uuid' => 'test-product-1',
            'slug' => 'test-product-1',
            'price' => 100,
            'is_active' => true,
            'available_for_preorder' => false,
            'add_to_homepage' => true,
            'stock' => 10,
        ]);

        Product::create([
            'name' => 'Test Product 2',
            'uuid' => 'test-product-2',
            'slug' => 'test-product-2',
            'price' => 200,
            'is_active' => true,
            'available_for_preorder' => true, // Preorders shouldn't show in new arrivals for retail
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('products');

        $products = $response->viewData('products');
        // Because of how CatalogLandingService is set up, it fetches new arrivals where is_active is true.
        // Let's check if Test Product 1 is there.
        $this->assertContains('Test Product 1', $products->pluck('name'));
    }
}
