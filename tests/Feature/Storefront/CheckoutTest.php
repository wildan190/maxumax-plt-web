<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** Shared COD payload with all required fields. */
    private function codPayload(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'John Doe',
            'phone'                 => '0123456789',
            'email'                 => 'john@example.com',
            'address_detail'        => '123 Fake Street',
            'city'                  => 'Kota Kinabalu',
            'region'                => 'Sabah',
            'province'              => 'Sabah',
            'postal_code'           => '88000',
            'shipping_courier_name' => 'Poslaju',
            'shipping_service_name' => 'Parcel',
            'shipping_service_id'   => 'PARCEL',
            'shipping_cost'         => 10.00,
        ], $overrides);
    }

    /** Build a fake cart session for a given product. */
    private function fakeCart(Product $product, int $qty = 2, string $size = 'L'): array
    {
        $key = $product->id . '-' . $size;

        return [
            'cart' => [
                $key => [
                    'key'                => $key,
                    'product_id'         => $product->id,
                    'product_variant_id' => null,
                    'name'               => $product->name,
                    'jersey_type'        => $product->jersey_type,
                    'price'              => (float) $product->price,
                    'quantity'           => $qty,
                    'size'               => $size,
                    'long_sleeve'        => false,
                    'image'              => $product->image_path,
                    'is_preorder'        => false,
                ],
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Validation
    // -------------------------------------------------------------------------

    public function test_checkout_validation_fails_with_empty_data()
    {
        $response = $this->post('/checkout/cod', []);

        $response->assertSessionHasErrors([
            'name', 'phone', 'region', 'province', 'city',
            'postal_code', 'address_detail',
            'shipping_courier_name', 'shipping_service_name',
            'shipping_service_id', 'shipping_cost',
        ]);
    }

    // -------------------------------------------------------------------------
    // Order creation
    // -------------------------------------------------------------------------

    public function test_checkout_cod_creates_order_in_database()
    {
        $product = Product::create([
            'name'                  => 'Checkout COD Product',
            'uuid'                  => 'checkout-cod-product',
            'slug'                  => 'checkout-cod-product',
            'price'                 => 100,
            'is_active'             => true,
            'available_for_preorder'=> false,
            'stock'                 => 10,
        ]);

        // Inject cart directly into the session (bypasses array-driver persistence issues)
        $response = $this
            ->withSession($this->fakeCart($product, 2, 'L'))
            ->post('/checkout/cod', $this->codPayload());

        $response->assertStatus(200);
        $response->assertViewIs('cart.thankyou');

        // The preorders table column is 'name' (not 'customer_name')
        $this->assertDatabaseHas('preorders', [
            'name'   => 'John Doe',
            'email'  => 'john@example.com',
            'status' => 'pending',
        ]);
    }

    // -------------------------------------------------------------------------
    // Cart is cleared after checkout
    // -------------------------------------------------------------------------

    public function test_checkout_cod_clears_cart_after_success()
    {
        $product = Product::create([
            'name'                  => 'Cart Clear Product',
            'uuid'                  => 'cart-clear-product',
            'slug'                  => 'cart-clear-product',
            'price'                 => 80,
            'is_active'             => true,
            'available_for_preorder'=> false,
            'stock'                 => 5,
        ]);

        $response = $this
            ->withSession($this->fakeCart($product, 1, 'S'))
            ->post('/checkout/cod', $this->codPayload([
                'name'  => 'Jane Doe',
                'email' => 'jane@example.com',
            ]));

        $response->assertStatus(200);

        // After checkout the 'cart' key should be absent/empty in the session
        $response->assertSessionMissing('cart');
    }
}
