<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthApisTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successful_registration()
    {
        $email = $this->faker->unique()->safeEmail;
        $userData = [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => bcrypt('password'),
            "token_name" => $email
        ];

        $response = $this->post('/api/v1/register', $userData);

        $response->assertStatus(200);
//        $response->assertJson([
//            'message' => 'User registered successfully.',
//        ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
            "token_name" => $user->name
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    public function test_unauthorized_access()
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(401);
    }

    public function test_authorized_access()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->get('/api/v1/user');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
