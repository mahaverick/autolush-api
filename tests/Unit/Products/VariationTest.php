<?php

namespace Tests\Unit\Products;

use Tests\TestCase;
use App\Models\Stock;
use App\Models\Product;
use App\Services\Money;
use App\Models\Variation;
use App\Models\VariationType;

class VariationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_belongs_to_a_variation_type()
    {
        $variation = factory(Variation::class)->create();

        $this->assertInstanceOf(VariationType::class, $variation->type);
    }

    public function test_it_belongs_to_a_product()
    {
        $variation = factory(Variation::class)->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }

    public function test_it_returns_money_instace_for_price()
    {
        $variation = factory(Variation::class)->create();

        $this->assertInstanceOf(Money::class, $variation->price);
    }

    public function test_it_returns_formatted_price()
    {
        $variation = factory(Variation::class)->create([
            'price' => 100.00,
        ]);

        $this->assertEquals($variation->formatted_price, '£100.00');
    }

    public function test_it_returns_product_price_if_price_is_null()
    {
        $product = factory(Product::class)->create();
        $variation = factory(Variation::class)->create([
            'product_id' => $product->id,
            'price' => null,
        ]);

        $this->assertEquals($product->price->amount(), $variation->price->amount());
    }

    public function test_it_can_check_if_the_variation_price_is_different()
    {
        $product = factory(Product::class)->create([
            'price' => 2000,
        ]);
        $variation = factory(Variation::class)->create([
            'product_id' => $product->id,
            'price' => 1000,
        ]);

        $this->assertTrue($variation->priceVaries());
    }

    public function test_it_has_many_stocks()
    {
        $variation = factory(Variation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertInstanceOf(Stock::class, $variation->stocks->first());
    }

    public function test_it_has_stock_information()
    {
        $variation = factory(Variation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertInstanceOf(Variation::class, $variation->stock->first());
    }

    public function test_it_has_stock_count_within_stock_information()
    {
        $variation = factory(Variation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => $quantity = 5,
            ])
        );

        $this->assertEquals($variation->stock->first()->pivot->stock, $quantity);
    }

    public function test_it_has_in_stock_within_stock_information()
    {
        $variation = factory(Variation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        //because mysql db stores it as 1/0
        $this->assertEquals($variation->stock->first()->pivot->in_stock, 1);
    }

    public function test_it_can_check_if_its_in_stock()
    {
        $variation = factory(Variation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        //because mysql db stores it as 1/0
        $this->assertTrue($variation->inStock());
    }

    public function test_it_can_get_the_stock()
    {
        $variation = factory(Variation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5,
            ])
        );

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => 5,
            ])
        );

        //because mysql db stores it as 1/0
        $this->assertEquals($variation->stockCount(), 10);
    }
}
