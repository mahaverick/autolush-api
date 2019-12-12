<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Variation;

class CartStoreTest extends TestCase
{
    public function test_it_fails_if_user_not_authenticated()
    {
        $this->json('POST', 'api/v1/users/cart')->assertStatus(401);
    }

    public function test_it_requires_variations()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', 'api/v1/users/cart')
            ->assertJsonValidationErrors(['variations']);
    }

    public function test_it_requires_variations_to_have_an_id()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', 'api/v1/users/cart', [
            'variations' => [
                ['quantity' => 3],
            ],
        ])->assertJsonValidationErrors(['variations.0.id']);
    }

    public function test_it_requires_variations_to_exists()
    {
        $user = factory(User::class)->create();
        $this->jsonAs($user, 'POST', 'api/v1/users/cart', [
            'variations' => [
                ['id'=>1, 'quantity' => 3],
            ],
        ])->assertJsonValidationErrors(['variations.0.id']);
    }

    public function test_it_requires_variations_qunatity_to_be_numeric()
    {
        $user = factory(User::class)->create();
        $variation = factory(Variation::class)->create();
        $this->jsonAs($user, 'POST', 'api/v1/users/cart', [
            'variations' => [
                ['id'=> 1, 'quantity' => 'one'],
            ],
        ])->assertJsonValidationErrors(['variations.0.quantity']);
    }

    public function test_it_requires_variations_qunatity_to_be_atleast_one()
    {
        $user = factory(User::class)->create();
        $variation = factory(Variation::class)->create();
        $this->jsonAs($user, 'POST', 'api/v1/users/cart', [
            'variations' => [
                ['id'=> 1, 'quantity' => 0],
            ],
        ])->assertJsonValidationErrors(['variations.0.quantity']);
    }

    public function test_it_can_add_variations_to_the_users_cart()
    {
        $user = factory(User::class)->create();
        $variation = factory(Variation::class)->create();
        $response = $this->jsonAs($user, 'POST', 'api/v1/users/cart', [
            'variations' => [
                ['id'=> $variation->id, 'quantity' => 2],
            ],
        ]);

        $this->assertDatabaseHas('cart_user', [
            'user_id' => $user->id,
            'variation_id' => $variation->id,
            'quantity' => 2,
        ]);
    }
}
