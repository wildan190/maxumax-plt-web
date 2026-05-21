<?php

namespace Tests\Feature;

use App\Models\Preorder;
use App\Models\Product;
use Tests\TestCase;

class CartRaceConditionTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_cart_add_respects_free_stock_with_reserved_preorders(): void
    {
        $product = Product::create([
            'name' => 'Test Jersey',
            'description' => 'Desc',
            'jersey_type' => 'Player Home',
            'price' => 100.00,
            'is_active' => true,
            'slug' => 'test-jersey',
            'image_path' => null,
            'available_for_preorder' => false,
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'stock' => 5,
        ]);

        Preorder::create([
            'order_number' => 'MM-OR-RESV1',
            'product_id' => $product->id,
            'name' => 'Alice',
            'email' => null,
            'phone' => null,
            'jersey_type' => $product->jersey_type,
            'size' => 'M',
            'long_sleeve' => false,
            'quantity' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'currency' => 'MYR',
            'status' => 'pending',
            'notes' => null,
        ]);

        $resp1 = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 3,
            'size' => 'M',
            'long_sleeve' => 0,
        ]);
        $resp1->assertSessionHasErrors('quantity');

        $resp2 = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'size' => 'M',
            'long_sleeve' => 0,
        ]);
        $resp2->assertSessionHas('success');

        $cart = app('session')->get('cart', []);
        $key = $product->id . '-M'; // Key format: productId-size (no variant)
        $this->assertArrayHasKey($key, $cart);
        $this->assertSame(2, (int) $cart[$key]['quantity']);
    }
}
