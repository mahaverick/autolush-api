<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Services\Cart;
use App\Models\Variation;

class CartDestroyTest extends TestCase
{
    public function test_it_fails_if_user_not_authenticated()
    {
        $this->json('DELETE', 'api/v1/users/cart/1')->assertStatus(401);
    }

    public function test_it_fails_if_variation_cant_be_found()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'DELETE', 'api/v1/users/cart/1')->assertStatus(404);
    }

    public function test_it_deletes_variation_from_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $variation = factory(Variation::class)->create();

        $user->cart()->sync($variation->id, [
            'quantity' => '3',
        ]);

        $this->jsonAs($user, 'DELETE', 'api/v1/users/cart/'.$variation->id);

        $this->assertDatabaseMissing('cart_user', [
            'variation_id' => $variation->id,
        ]);
    }
}
