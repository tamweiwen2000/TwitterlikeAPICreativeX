<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class LogoutTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * Test successful user logout.
     *
     * @return void
     */
    public function testLogoutSuccessfully()
    {
        // Create a test user
        $user = User::factory()->create();

        // Set the current user for the application
        Sanctum::actingAs(
            $user,
            ['*']
        );

        // Call the logout endpoint
        $response = $this->postJson('/api/logout');

        // Assert the user was logged out
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logged out',
        ]);

        // Assert the user's tokens were deleted
        $this->assertCount(0, $user->tokens);
    }
    /**
     * Test unsuccessful user logout.
     *
     * @return void
     */
    public function testLogoutUnsuccessfully()
    {
        // Call the logout endpoint without an authenticated user
        $response = $this->postJson('/api/logout');

        // Assert the user was not logged out
        $response->assertStatus(401); // 401 Unauthorized
    }
}
