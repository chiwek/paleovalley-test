<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{

    /** @test */
    public function test_bad_credentials_fail() {
        User::factory()->create([
            'email' => 'foo@bar.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/api/login', [
            'email' => 'foo@bar.com',
            'password' => 'password1'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_email_field_is_required_fail() {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'password' => bcrypt('password')
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('The email field is required.');
    }

    /** @test */
    public function test_email_value_must_be_a_valid_email_address_fail() {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'foobar.com',
            'password' => bcrypt('password')
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('The email field must be a valid email address.');
    }

    /** @test */
    public function test_password_field_is_required_fail() {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'foo@bar.com'
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('The password field is required.');
    }

    /** @test */
    public function test_login_success() {
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'foo@bar.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/api/login', [
            'email' => 'foo@bar.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('accessToken');
    }

    /** @test */
    public function test_register_success() {

        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'foo@bar.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('accessToken');
    }
}
