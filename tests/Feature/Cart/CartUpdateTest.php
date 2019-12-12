<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Services\Cart;
use App\Models\Variation;

class CartUpdateTest extends TestCase
{
    public function test_it_fails_if_user_not_authenticated()
    {
        $this->json('PATCH', 'api/v1/users/cart/1')->assertStatus(401);
    }

    public function test_it_fails_if_variation_cant_be_found()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'PATCH', 'api/v1/users/cart/1')->assertStatus(404);
    }

    public function test_it_requires_a_quantity()
    {
        $user = factory(User::class)->create();
        $variation = factory(Variation::class)->create();
        $this->jsonAs($user, 'PATCH', 'api/v1/users/cart/'.$variation->id)
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_quantity_should_be_numeric()
    {
        $user = factory(User::class)->create();
        $variation = factory(Variation::class)->create();
        $this->jsonAs($user, 'PATCH', 'api/v1/users/cart/'.$variation->id, [
            'quantity' => 'two',
        ])->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_quantity_to_be_atleast_one()
    {
        $user = factory(User::class)->create();
        $variation = factory(Variation::class)->create();
        $this->jsonAs($user, 'PATCH', 'api/v1/users/cart/'.$variation->id, [
            'quantity' => 0,
        ])->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_can_update_quantity_in_user_cart()
    {
        $variation = factory(Variation::class)->create();

        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach($variation, [
            'quantity' => 3,
        ]);

        $this->jsonAs($user, 'PATCH', 'api/v1/users/cart/'.$variation->id, [
            'quantity' => 5,
        ]);

        $this->assertDatabaseHas('cart_user', [
            'variation_id' => $variation->id,
            'quantity' => 5,
        ]);
    }
}
