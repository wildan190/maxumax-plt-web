<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_product_to_cart()
    {
        $product = Product::create([
            'name' => 'Cart Product',
            'uuid' => 'cart-product',
            'slug' => 'cart-product',
            'price' => 100,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
            'size' => 'M',
        ]);

        $response->assertRedirect(route('cart.show'));
        $response->assertSessionHas('cart');
        
        $cart = session('cart');
        $this->assertArrayHasKey($product->id . '-M', $cart);
        $this->assertEquals(2, $cart[$product->id . '-M']['quantity']);
    }

    public function test_cannot_add_out_of_stock_product_to_cart()
    {
        $product = Product::create([
            'name' => 'Out of Stock Product',
            'uuid' => 'oos-product',
            'slug' => 'oos-product',
            'price' => 100,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 1,
        ]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 5, // Trying to add more than stock
            'size' => 'L',
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertFalse(session()->has('cart'));
    }

    public function test_can_update_cart_quantity()
    {
        $product = Product::create([
            'name' => 'Update Product',
            'uuid' => 'update-product',
            'slug' => 'update-product',
            'price' => 100,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        // Add to cart first
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'S',
        ]);

        $key = $product->id . '-S';

        // Update quantity
        $response = $this->post('/cart/update', [
            'key' => $key,
            'quantity' => 3,
        ]);

        $response->assertSessionHas('success');
        $cart = session('cart');
        $this->assertEquals(3, $cart[$key]['quantity']);
    }

    public function test_can_remove_item_from_cart()
    {
        $product = Product::create([
            'name' => 'Remove Product',
            'uuid' => 'remove-product',
            'slug' => 'remove-product',
            'price' => 100,
            'is_active' => true,
            'available_for_preorder' => false,
            'stock' => 10,
        ]);

        // Add to cart first
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'S',
        ]);

        $key = $product->id . '-S';

        // Remove from cart
        $response = $this->post('/cart/remove', [
            'key' => $key,
        ]);

        $response->assertSessionHas('success');
        $cart = session('cart');
        $this->assertArrayNotHasKey($key, $cart);
    }
}
