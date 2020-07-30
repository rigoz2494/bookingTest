<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testRegistration() {
        $response = $this->postJson('/api/auth/registration', [
            'name'  => 'John',
            'email' => 'john@mail.com',
            'password' => Hash::make('snow22')
        ]);

        $response->assertStatus(200);
    }

    public function testRegistrationWrongEmail() {
        $response = $this->postJson('/api/auth/registration', [
            'name'  => 'John',
            'email' => 'johnwww',
            'password' => 'password'
        ]);

        $response->assertStatus(404);
    }

    public function testLogin()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function testLoginWrongPassword()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrongPass'
        ]);

        $response->assertStatus(401);
    }
}
