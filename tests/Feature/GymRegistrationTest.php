<?php

use App\Models\Gym;
use App\Models\User;

test('gym registration page can be accessed by guests', function () {
    $response = $this->get(route('gym.register'));
    
    $response->assertStatus(200);
    $response->assertSee('Register Your Gym');
});

test('authenticated users cannot access gym registration page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('gym.register'));
    
    $response->assertRedirect(route('dashboard'));
});

test('gym can be registered with valid data', function () {
    $gymData = [
        'gym_name' => 'FitPro Fitness Center',
        'gym_address' => '123 Main St, New York, NY 10001',
        'gym_phone' => '+1 (555) 123-4567',
        'gym_email' => 'info@fitpro.com',
        'gym_timezone' => 'America/New_York',
        'gym_description' => 'A premium fitness center with state-of-the-art equipment',
        'owner_name' => 'John Doe',
        'owner_email' => 'john.doe@example.com',
        'owner_password' => 'SecurePassword123!',
        'owner_password_confirmation' => 'SecurePassword123!',
        'terms' => '1',
    ];

    $response = $this->post(route('gym.register.store'), $gymData);

    // Assert gym was created
    $this->assertDatabaseHas('gyms', [
        'name' => 'FitPro Fitness Center',
        'email' => 'info@fitpro.com',
        'subscription_tier' => 'trial',
        'is_verified' => false,
    ]);

    // Assert owner user was created
    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'role' => 'admin',
    ]);

    // Assert owner is attached to gym
    $gym = Gym::where('email', 'info@fitpro.com')->first();
    $owner = User::where('email', 'john.doe@example.com')->first();
    
    expect($gym)->not->toBeNull();
    expect($owner)->not->toBeNull();
    expect($gym->owner_user_id)->toBe($owner->id);
    expect($owner->default_gym_id)->toBe($gym->id);
    expect($owner->gyms()->where('gyms.id', $gym->id)->exists())->toBeTrue();

    // Assert user is authenticated
    $this->assertAuthenticated();

    // Assert redirected to dashboard
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success');
});

test('gym registration requires all mandatory fields', function () {
    $response = $this->post(route('gym.register.store'), []);

    $response->assertSessionHasErrors([
        'gym_name',
        'gym_address',
        'gym_phone',
        'gym_email',
        'gym_timezone',
        'owner_name',
        'owner_email',
        'owner_password',
        'terms',
    ]);
});

test('gym registration validates email uniqueness for owner', function () {
    // Create existing user
    User::factory()->create(['email' => 'existing@example.com']);

    $gymData = [
        'gym_name' => 'Test Gym',
        'gym_address' => '123 Test St',
        'gym_phone' => '555-0000',
        'gym_email' => 'gym@test.com',
        'gym_timezone' => 'UTC',
        'owner_name' => 'Test Owner',
        'owner_email' => 'existing@example.com', // Duplicate email
        'owner_password' => 'Password123!',
        'owner_password_confirmation' => 'Password123!',
        'terms' => '1',
    ];

    $response = $this->post(route('gym.register.store'), $gymData);

    $response->assertSessionHasErrors(['owner_email']);
});

test('gym registration requires password confirmation', function () {
    $gymData = [
        'gym_name' => 'Test Gym',
        'gym_address' => '123 Test St',
        'gym_phone' => '555-0000',
        'gym_email' => 'gym@test.com',
        'gym_timezone' => 'UTC',
        'owner_name' => 'Test Owner',
        'owner_email' => 'owner@test.com',
        'owner_password' => 'Password123!',
        'owner_password_confirmation' => 'DifferentPassword!', // Mismatch
        'terms' => '1',
    ];

    $response = $this->post(route('gym.register.store'), $gymData);

    $response->assertSessionHasErrors(['owner_password']);
});

test('gym registration requires terms acceptance', function () {
    $gymData = [
        'gym_name' => 'Test Gym',
        'gym_address' => '123 Test St',
        'gym_phone' => '555-0000',
        'gym_email' => 'gym@test.com',
        'gym_timezone' => 'UTC',
        'owner_name' => 'Test Owner',
        'owner_email' => 'owner@test.com',
        'owner_password' => 'Password123!',
        'owner_password_confirmation' => 'Password123!',
        // Missing 'terms' => '1',
    ];

    $response = $this->post(route('gym.register.store'), $gymData);

    $response->assertSessionHasErrors(['terms']);
});

test('gym slug is auto-generated and unique', function () {
    // Create first gym
    $response1 = $this->post(route('gym.register.store'), [
        'gym_name' => 'FitPro Gym',
        'gym_address' => '123 Main St',
        'gym_phone' => '555-1111',
        'gym_email' => 'gym1@test.com',
        'gym_timezone' => 'UTC',
        'owner_name' => 'Owner One',
        'owner_email' => 'owner1@test.com',
        'owner_password' => 'Password123!',
        'owner_password_confirmation' => 'Password123!',
        'terms' => '1',
    ]);

    // Log out first user
    $this->post(route('logout'));

    // Create second gym with same name
    $response2 = $this->post(route('gym.register.store'), [
        'gym_name' => 'FitPro Gym', // Same name
        'gym_address' => '456 Oak St',
        'gym_phone' => '555-2222',
        'gym_email' => 'gym2@test.com',
        'gym_timezone' => 'UTC',
        'owner_name' => 'Owner Two',
        'owner_email' => 'owner2@test.com',
        'owner_password' => 'Password123!',
        'owner_password_confirmation' => 'Password123!',
        'terms' => '1',
    ]);

    $gyms = Gym::where('name', 'FitPro Gym')->get();
    expect($gyms)->toHaveCount(2);
    
    // Slugs should be different
    expect($gyms[0]->slug)->not->toBe($gyms[1]->slug);
});

test('gym gets 30 day trial period on registration', function () {
    $this->post(route('gym.register.store'), [
        'gym_name' => 'Trial Gym',
        'gym_address' => '123 Trial St',
        'gym_phone' => '555-3333',
        'gym_email' => 'trial@test.com',
        'gym_timezone' => 'UTC',
        'owner_name' => 'Trial Owner',
        'owner_email' => 'trial.owner@test.com',
        'owner_password' => 'Password123!',
        'owner_password_confirmation' => 'Password123!',
        'terms' => '1',
    ]);

    $gym = Gym::where('email', 'trial@test.com')->first();
    
    expect($gym->subscription_tier)->toBe('trial');
    expect($gym->trial_ends_at)->not->toBeNull();
    expect($gym->trial_ends_at->isFuture())->toBeTrue();
    
    // Check trial is approximately 30 days (allow 1 day margin for test timing)
    $daysUntilExpiry = now()->diffInDays($gym->trial_ends_at, false);
    expect($daysUntilExpiry)->toBeGreaterThanOrEqual(29);
    expect($daysUntilExpiry)->toBeLessThanOrEqual(31);
});
