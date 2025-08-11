<?php

use App\Http\Controllers\ClientRoleController;
use App\Http\Requests\UpdateClientRoleRequest;
use App\Http\Resources\ClientRoleResource;
use \App\Models\ClientRole;
use App\Models\User;
use App\Models\UserRole;
use Dotenv\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(
    TestCase::class,
    RefreshDatabase::class
);

test('can create clientRole', function () {
    $clientRole = ClientRole::factory()->create([
        'name' => 'Test Role',
    ]);

    expect($clientRole)->toBeInstanceOf(ClientRole::class)
        ->and($clientRole->name)->toBe('Test Role');
});

test('admin user has admin privileges', function () {
    $adminRole = UserRole::factory()->create(['name' => 'admin']);

    $adminUser = User::factory()->create(['user_role_id' => $adminRole->id]);

    expect($adminUser->isAdmin())->toBeTrue();
});

test('returns client role resource when role exists', function () {
    $role = ClientRole::factory()->create();

    $response = (new ClientRoleController())->show($role->id);

    expect($response)
        ->toBeInstanceOf(ClientRoleResource::class)
        ->and($response->resource->id)
        ->toBe($role->id);
});

test('update client role resource when role exists', function () {
    $role = ClientRole::factory()->create(['name' => 'Old Name']);

    $request = Mockery::mock(UpdateClientRoleRequest::class);
    $request->shouldReceive('validated')->andReturn([
        'name' => 'New Name',
    ]);

    $response = (new ClientRoleController())->update($request, $role->id);

    expect($response)
        ->toBeInstanceOf(ClientRoleResource::class)
        ->and($response->resource->name)
        ->toBe('New Name')
        ->and($role->fresh()->name)
        ->toBe('New Name');
});
