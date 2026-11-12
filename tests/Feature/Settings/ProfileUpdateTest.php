<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
    $response->assertSee('Profil Saya');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'username' => 'testuser',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->username)->toBe('testuser');
});

test('username is normalized to lowercase', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'username' => 'TestUser',
        ]);

    $response->assertSessionHasNoErrors();

    expect($user->refresh()->username)->toBe('testuser');
});

test('username must be unique', function () {
    $other = User::factory()->create(['username' => 'taken']);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'username' => 'TAKEN',
        ]);

    $response->assertSessionHasErrors('username');
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh())->not->toBeNull();
});

test('profile photo can be updated and the old file is removed', function () {
    Storage::fake('public');

    $user = User::factory()->withBiodata()->create();
    $oldPath = UploadedFile::fake()->image('old.jpg')->store('avatars', 'public');
    $user->biodata->update(['avatar' => $oldPath]);

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'avatar' => UploadedFile::fake()->image('new.jpg'),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $newPath = $user->fresh()->biodata->avatar;

    expect($newPath)->not->toBeNull();
    Storage::disk('public')->assertExists($newPath);
    Storage::disk('public')->assertMissing($oldPath);
});
