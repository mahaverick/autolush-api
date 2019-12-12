<?php

namespace Tests\Unit\Products;

use Tests\TestCase;
use App\Models\Stock;
use App\Models\Product;
use App\Services\Money;
use App\Models\Category;
use App\Models\Variation;

class ProductTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_uses_slug_for_the_route_key_name()
    {
        $product = new Product;

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    public function test_it_has_many_categories()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            $category = factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    public function test_it_has_many_variations()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $category = factory(Variation::class)->create()
        );

        $this->assertInstanceOf(Variation::class, $product->variations->first());
    }

    public function test_it_returns_money_instace_for_price()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    public function test_it_returns_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price' => 100.00,
        ]);

        $this->assertEquals($product->formatted_price, '£100.00');
    }

    public function test_it_can_check_if_product_is_in_stock()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(Variation::class)->create()
        );

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertTrue($product->inStock());
    }

    public function test_it_can_get_the_product_stock_count()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(Variation::class)->create()
        );

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5,
            ])
        );

        $this->assertEquals($product->stockCount(), 5);
    }
}
