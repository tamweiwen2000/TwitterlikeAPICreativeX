<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterSuccessfully()
    {
        $user = [
            'name' => 'Domskie Bacasnot',
            'username' => 'tamweiwen',
            'email' => 'cassieross89@example.net',
            'password' => 'holefire123!',
            'password_confirmation' => 'holefire123!'
        ];

        $response = $this->post('/api/register', $user);

        $response->assertStatus(201);

        // Assert specific attributes within the 'user' object
        $response->assertJsonStructure([
            'user' => [
                'name',
                'username',
                'email',
                'updated_at',
                'created_at',
                'id'
            ],
            'token'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Domskie Bacasnot',
            'username' => 'tamweiwen',
            'email' => 'cassieross89@example.net',
        ]);
    }

    public function testRegisterFailsWithInvalidEmail()
    {
        $user = [
            'name' => 'Domskies Bacasnot',
            'username' => 'tamweiwen',
            'email' => 'cassieross89',
            'password' => 'holefire123!',
            'password_confirmation' => 'holefire123!'
        ];

        $response = $this->json('POST', '/api/register', $user);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function testRegisterFailsWithUsedEmail()
    {
        $user1 = [
            'name' => 'Domskies Bacasnot',
            'username' => 'tamweiwen',
            'email' => 'cassieross89@gmail.com',
            'password' => 'holefire123!',
            'password_confirmation' => 'holefire123!'
        ];

        //first Sign-up attempt succeeds
        $response1 = $this->json('POST', '/api/register', $user1);
        $response1->assertStatus(201);

        $user2 = [
            'name' => 'Donnes Bacs',
            'username' => 'dcvb14',
            'email' => 'cassieross89@gmail.com',
            'password' => 'holefire123!',
            'password_confirmation' => 'holefire123!'
        ];

        //second sign-up attempt fails due to used email
        $response2 = $this->json('POST', '/api/register', $user2);
        $response2->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function testRegisterFailsWithInvalidPasswordConfirmation()
    {
        $user = [
            'name' => 'Domskies Bacasnot',
            'username' => 'tamweiwen',
            'email' => 'cassieross89@gmail.com',
            'password' => 'holefire123!',
            'password_confirmation' => 'Holefirea123!'
        ];

        $response = $this->json('POST', '/api/register', $user);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
