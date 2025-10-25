<?php

use App\Models\Gym;
use App\Models\Member;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

function createGym(array $overrides = []): Gym
{
    return Gym::create(array_merge([
        'name' => 'Test Gym',
        'slug' => 'test-gym-'.Str::random(5),
        'timezone' => config('app.timezone', 'UTC'),
    ], $overrides));
}

function createReceptionistWithGym(): array
{
    $gym = createGym();

    $user = User::factory()->create([
        'role' => 'receptionist',
        'default_gym_id' => $gym->id,
    ]);

    // Attach the user to the gym with receptionist role
    $user->gyms()->attach($gym->id, ['role' => 'receptionist']);

    return [$user, $gym];
}

test('receptionist can access payments and attendances index', function () {
    [$user, $gym] = createReceptionistWithGym();

    $this->actingAs($user)
        ->get(route('payments.index'))
        ->assertOk();

    $this->actingAs($user)
        ->get(route('attendances.index'))
        ->assertOk();
});

test('receptionist cannot edit or delete payments', function () {
    [$user, $gym] = createReceptionistWithGym();

    // Create a member in the same gym
    $member = Member::create([
        'gym_id' => $gym->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane.doe@example.com',
        'phone' => '1234567890',
        'join_date' => now()->toDateString(),
    ]);

    // Create a payment in the same gym
    $payment = Payment::create([
        'gym_id' => $gym->id,
        'member_id' => $member->id,
        'subscription_id' => null,
        'amount' => 50.00,
        'method' => 'cash',
        'payment_date' => now()->toDateString(),
    ]);

    // Receptionist should be forbidden from editing
    $this->actingAs($user)
        ->get(route('payments.edit', $payment))
        ->assertStatus(403);

    // Receptionist should be forbidden from deleting
    $this->actingAs($user)
        ->delete(route('payments.destroy', $payment))
        ->assertStatus(403);
});

test('receptionist sees helpful actions on 403 page', function () {
    [$user, $gym] = createReceptionistWithGym();

    $response = $this->actingAs($user)->get('/members');

    $response->assertStatus(403);
    $response->assertSee('Payments');
    $response->assertSee('Attendance');
});
