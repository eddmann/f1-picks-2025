<?php

use App\Models\Driver;
use App\Models\User;

it('forbids non-admin from viewing drivers page', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $response = $this->get(route('drivers.index'));

    // Assert
    $response->assertForbidden();
});

it('allows admin to view drivers page', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    Driver::factory()->count(3)->create();

    // Act
    $response = $this->get(route('drivers.index'));

    // Assert
    $response->assertOk();
    $response->assertViewHas('drivers');
});

it('allows admin to update driver active flags', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $drivers = Driver::factory()->count(3)->create(['active' => false]);

    // Act
    $response = $this->post(route('drivers.update'), [
        'drivers' => [
            $drivers[0]->id => ['id' => $drivers[0]->id, 'active' => 1],
            $drivers[1]->id => ['id' => $drivers[1]->id, 'active' => 0],
            $drivers[2]->id => ['id' => $drivers[2]->id, 'active' => 1],
        ],
    ]);

    // Assert
    $response->assertRedirect(route('drivers.index'));
    $this->assertDatabaseHas('drivers', ['id' => $drivers[0]->id, 'active' => 1]);
    $this->assertDatabaseHas('drivers', ['id' => $drivers[1]->id, 'active' => 0]);
    $this->assertDatabaseHas('drivers', ['id' => $drivers[2]->id, 'active' => 1]);
});
