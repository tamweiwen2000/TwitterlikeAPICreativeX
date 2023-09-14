<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $testUser = [
        'name' => 'Domskie Bacasnot',
        'username' => 'tamweiwen',
        'email' => 'cassieross89@example.net',
        'password' => 'holefire123!',
        'password_confirmation' => 'holefire123!'
    ];

    public function testLoginSuccessfullyUsingEmail()
    {
        $response = $this->post('/api/register', $this->testUser);
        $response->assertStatus(201);

        //Test login using email with correct credentials
        $logincredentials = [
            'username' => 'cassieross89@example.net',
            'password' => 'holefire123!'
        ];
        $loginresponse1 = $this->post('/api/login', $logincredentials);
        $loginresponse1->assertStatus(201);
    }

    public function testLoginSuccessfullyUsingUsername()
    {
        $response = $this->post('/api/register', $this->testUser);
        $response->assertStatus(201);

        //Test login using username or twitter handle with correct credentials
        $logincredentials = [
            'username' => 'tamweiwen',
            'password' => 'holefire123!'
        ];
        $loginresponse2 = $this->post('/api/login', $logincredentials);
        $loginresponse2->assertStatus(201);
    }

    public function testLoginUnsuccessfullyUsingEmail()
    {
        $response = $this->post('/api/register', $this->testUser);
        $response->assertStatus(201);

        //Test login using email with wrong credentials
        $logincredentials = [
            'username' => 'cassie@example.net',
            'password' => 'holefire123!'
        ];
        $loginresponse1 = $this->post('/api/login', $logincredentials);
        $loginresponse1->assertStatus(401);
    }

    public function testLoginUnsuccessfullyUsingUsername()
    {
        $response = $this->post('/api/register', $this->testUser);
        $response->assertStatus(201);

        //Test login using username or twitter handle with wrong credentials
        $logincredentials = [
            'username' => 'tamweiwens',
            'password' => 'holefire123!'
        ];
        $loginresponse2 = $this->post('/api/login', $logincredentials);
        $loginresponse2->assertStatus(401);
    }
}
