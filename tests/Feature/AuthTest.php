<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user registration', function () {
    $this->artisan('migrate');

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'token',
        ]);
});

test('user login', function () {
    $this->artisan('migrate');

    $user = User::factory()->create([
        'password' => Hash::make('password')
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'token',
        ]);
});
