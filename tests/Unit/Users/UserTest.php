<?php

namespace Tests\Unit\Users;

use Tests\TestCase;
use App\Models\User;
use App\Models\Variation;

class UserTest extends TestCase
{
    public function test_it_has_many_cart_products()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(Variation::class)->create()
        );

        $this->assertInstanceOf(Variation::class, $user->cart->first());
    }

    public function test_it_has_a_quantity_for_each_cart_product()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(Variation::class)->create(), [
                'quantity' => $quantity = 5,
            ]
        );

        $this->assertEquals($user->cart->first()->pivot->quantity, $quantity);
    }
}
