<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;

class ProductIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_a_collection_of_products()
    {
        $products = factory(Product::class, 2)->create();

        $this->json('GET', 'api/v1/users/products')->assertJsonFragment([
            'slug' => $products[0]->slug,
            'slug' => $products[1]->slug,
        ]);
    }

    public function test_it_has_paginated_data()
    {
        $this->json('GET', 'api/v1/users/products')->assertJsonStructure([
            'meta',
        ]);
    }
}
