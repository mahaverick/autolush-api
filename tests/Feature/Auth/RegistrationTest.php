<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
    public function test_it_requires_a_name()
    {
        $this->json('POST', 'api/v1/users/auth/register')
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/v1/users/auth/register')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('POST', 'api/v1/users/auth/register', [
            'email' => 'nope',
        ])->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_unique_email()
    {
        $user = factory(User::class)->create();
        $this->json('POST', 'api/v1/users/auth/register', [
            'email' => $user->email,
        ])->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/v1/users/auth/register')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST', 'api/v1/users/auth/register', [
            'name' => $name = 'Abhijeet',
            'email' => $email = 'abhijeet@gmail.com',
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function test_it_is_returning_a_user()
    {
        $this->json('POST', 'api/v1/users/auth/register', [
            'name' => $name = 'Abhijeet',
            'email' => $email = 'abhijeet@gmail.com',
            'password' => 'secret',
        ])->assertJsonFragment([
            'name' => $name,
            'email' => $email,
        ]);
    }
}
