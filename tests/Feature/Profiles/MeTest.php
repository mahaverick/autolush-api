<?php

namespace Tests\Feature\Profiles;

use Tests\TestCase;
use App\Models\User;

class MeTest extends TestCase
{
    public function test_it_fails_if_user_isnt_authenticated()
    {
        $this->json('GET', 'api/v1/users/profiles/me')->assertStatus(401);
    }

    public function test_it_return_authenticated_user_data()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', 'api/v1/users/profiles/me')
            ->assertJsonFragment(['email' => $user->email]);
    }
}
