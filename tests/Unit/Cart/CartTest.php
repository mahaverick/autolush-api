<?php

namespace Tests\Unit\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Services\Cart;
use App\Models\Variation;

class CartTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_can_add_variations_to_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $variation = factory(Variation::class)->create();

        $cart->add([
            ['id' => $variation->id, 'quantity' => 1],
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    public function test_it_can_increment_variation_quantity()
    {
        $variation = factory(Variation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $cart->add([
            ['id' => $variation->id, 'quantity' => 2],
        ]);

        $cart = new Cart($user->fresh());

        $cart->add([
            ['id' => $variation->id, 'quantity' => 2],
        ]);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 4);
    }

    public function test_it_can_update_quantities_for_cart_variations()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $variation = factory(Variation::class)->create(), [
                'quantity' => 2,
            ]
        );

        $cart->update($variation->id, 5);

        $this->assertEquals($user->cart->first()->pivot->quantity, 5);
    }

    public function test_it_can_delete_variation_from_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $variation = factory(Variation::class)->create(), [
                'quantity' => 2,
            ]
        );

        $cart->destroy($variation->id);

        $this->assertCount(0, $user->fresh()->cart);
    }

    public function test_it_can_empty_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $variation = factory(Variation::class)->create(), [
                'quantity' => 2,
            ]
        );

        $user->cart()->attach(
            $variation = factory(Variation::class)->create(), [
                'quantity' => 2,
            ]
        );

        $cart->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }
}
