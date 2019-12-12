<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/v1/users/auth/login')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/v1/users/auth/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_returns_validation_error_if_credentials_dont_match()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'api/v1/users/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ])->assertJsonValidationErrors(['email']);
    }

    public function test_it_return_token_if_credentials_match()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'api/v1/users/auth/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertJsonStructure(['meta'=>['token']]);
    }

    public function test_it_return_user_if_credentials_match()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'api/v1/users/auth/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertJsonFragment([
            'email'=> $user->email,
        ]);
    }
}
