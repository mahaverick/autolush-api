<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;

class ProductShowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_throws_404_if_product_not_found()
    {
        $response = $this->json('GET', 'api/v1/users/products/nope');

        $response->assertStatus(404);
    }

    public function test_it_shows_product()
    {
        $product = factory(Product::class)->create();

        $this->json('GET', 'api/v1/users/products/'.$product->slug)->assertJsonFragment([
            'slug' => $product->slug,
        ]);
    }
}
