<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Variation;

class CartIndexTest extends TestCase
{
    public function test_it_fails_if_user_not_authenticated()
    {
        $this->json('GET', 'api/v1/users/cart')->assertStatus(401);
    }

    public function test_it_shows_variations_in_the_user_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync(
            $variation = factory(Variation::class)->create()
        );

        $this->jsonAs($user, 'GET', 'api/v1/users/cart')
            ->assertJsonFragment([
                'id' => $variation->id,
            ]);
    }
}
