<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_loads_successfully()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_catalog_filters_by_category()
    {
        Product::create([
            'name' => 'Football Jersey',
            'uuid' => 'football-jersey',
            'slug' => 'football-jersey',
            'category' => 'Jerseys',
            'price' => 100,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        Product::create([
            'name' => 'Running Pants',
            'uuid' => 'running-pants',
            'slug' => 'running-pants',
            'category' => 'Pants',
            'price' => 120,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        $response = $this->get('/products?category=Jerseys');

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertCount(1, $products);
        $this->assertEquals('Football Jersey', $products->first()->name);
    }

    public function test_catalog_search_functionality()
    {
        Product::create([
            'name' => 'Maxumax Elite Polo',
            'uuid' => 'elite-polo',
            'slug' => 'elite-polo',
            'category' => 'Polos',
            'price' => 150,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        Product::create([
            'name' => 'Basic T-Shirt',
            'uuid' => 'basic-tshirt',
            'slug' => 'basic-tshirt',
            'category' => 'Shirts',
            'price' => 50,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        $response = $this->get('/products?search=Elite');

        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        $this->assertCount(1, $products);
        $this->assertEquals('Maxumax Elite Polo', $products->first()->name);
    }
}
