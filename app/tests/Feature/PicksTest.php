<?php

use App\Models\Driver;
use App\Models\Round;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('requires authentication to access picks create page', function () {
    // Arrange
    $round = Round::factory()->create(['year' => 2033]);

    // Act
    $response = $this->get(route('picks.create', [$round, Type::RACE->value]));

    // Assert
    $response->assertRedirect(route('login'));
});

it('shows picks create page during open window', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    Driver::factory()->count(3)->create();

    // Act
    $response = $this->get(route('picks.create', [$round, Type::RACE->value]));

    // Assert
    $response->assertOk();
    $response->assertViewHasAll(['round', 'type', 'drivers']);
});

it('rejects picks outside the open window with an error', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addDays(3),
        'race_at' => now()->addDays(4),
    ]);

    // Act
    $response = $this->get(route('picks.create', [$round, Type::RACE->value]));

    // Assert
    $response->assertSessionHasErrors();
});

it('creates a pick during the open window', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    $drivers = Driver::factory()->count(4)->create();

    // Act
    $create = $this->post(route('picks.store', [$round, Type::RACE->value]), [
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);
    // Assert
    $create->assertRedirect(route('rounds.show', $round->id));
    $create->assertSessionHas('success');
    $this->assertDatabaseHas('picks', [
        'user_id' => $user->id,
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);
});

it('updates an existing pick for the same round and type', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    $drivers = Driver::factory()->count(4)->create();
    $this->post(route('picks.store', [$round, Type::RACE->value]), [
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);

    // Act
    $update = $this->post(route('picks.store', [$round, Type::RACE->value]), [
        'driver1_id' => $drivers[1]->id,
        'driver2_id' => $drivers[2]->id,
        'driver3_id' => $drivers[3]->id,
    ]);

    // Assert
    $update->assertRedirect(route('rounds.show', $round->id));
    $this->assertDatabaseHas('picks', [
        'user_id' => $user->id,
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[1]->id,
        'driver2_id' => $drivers[2]->id,
        'driver3_id' => $drivers[3]->id,
    ]);
});

it('validates drivers exist when storing picks', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    $drivers = Driver::factory()->count(2)->create();

    // Act
    $invalid = $this->post(route('picks.store', [$round, Type::RACE->value]), [
        'driver1_id' => $unkownDriverId = 999999,
        'driver2_id' => $drivers[0]->id,
        'driver3_id' => $drivers[1]->id,
    ]);

    // Assert
    $invalid->assertSessionHasErrors();
});

it('validates drivers are distinct when storing picks', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    $drivers = Driver::factory()->count(2)->create();

    // Act
    $duplicate = $this->post(route('picks.store', [$round, Type::RACE->value]), [
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[0]->id,
        'driver3_id' => $drivers[1]->id,
    ]);

    // Assert
    $duplicate->assertSessionHasErrors();
});

it('returns 404 for invalid type in route', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->create(['year' => 2033]);

    // Act
    $response = $this->get("/rounds/{$round->id}/invalid_type/picks");

    // Assert
    $response->assertNotFound();
});

it('allows picks at window start boundary', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $raceQualifyingAt = now()->addDay();
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => $raceQualifyingAt,
        'race_at' => $raceQualifyingAt->addDay(),
    ]);

    // Act
    Date::setTestNow($raceQualifyingAt->copy()->subDay());
    $atStart = $this->get(route('picks.create', [$round, Type::RACE_QUALIFYING->value]));

    // Assert
    $atStart->assertOk();
});

it('rejects picks at window end boundary', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $raceQualifyingAt = now()->addDay();
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => $raceQualifyingAt,
        'race_at' => $raceQualifyingAt->addDay(),
    ]);

    // Act
    Date::setTestNow($raceQualifyingAt->copy()->subMinutes(5));
    $atEnd = $this->get(route('picks.create', [$round, Type::RACE_QUALIFYING->value]));

    // Assert
    $atEnd->assertSessionHasErrors();
});

it('allows picks just before window end boundary', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $raceQualifyingAt = now()->addDay();
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => $raceQualifyingAt,
        'race_at' => $raceQualifyingAt->addDay(),
    ]);

    // Act
    Date::setTestNow($raceQualifyingAt->copy()->subMinutes(5)->subSecond());
    $justBeforeEnd = $this->get(route('picks.create', [$round, Type::RACE_QUALIFYING->value]));

    // Assert
    $justBeforeEnd->assertOk();
});

it('rejects picks before window opens', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $raceQualifyingAt = now()->addDay();
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => $raceQualifyingAt,
        'race_at' => $raceQualifyingAt->addDay(),
    ]);

    // Act
    Date::setTestNow($raceQualifyingAt->copy()->subDay()->subSecond());
    $before = $this->get(route('picks.create', [$round, Type::RACE_QUALIFYING->value]));

    // Assert
    $before->assertSessionHasErrors();
});

it('rejects picks after window closes', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $raceQualifyingAt = now()->addDay();
    $round = Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => $raceQualifyingAt,
        'race_at' => $raceQualifyingAt->addDay(),
    ]);

    // Act
    Date::setTestNow($raceQualifyingAt->copy()->subMinutes(5)->addSecond());
    $after = $this->get(route('picks.create', [$round, Type::RACE_QUALIFYING->value]));

    // Assert
    $after->assertSessionHasErrors();
});

it('rejects sprint type pick create on non-sprint weekend', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->nonSprintWeekend()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addDay(),
        'race_at' => now()->addDays(2),
    ]);
    $drivers = Driver::factory()->count(3)->create();

    // Act
    $create = $this->get(route('picks.create', [$round, Type::SPRINT_RACE->value]));

    // Assert
    $create->assertSessionHasErrors();
});

it('rejects sprint type pick store on non-sprint weekend', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = Round::factory()->nonSprintWeekend()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addDay(),
        'race_at' => now()->addDays(2),
    ]);
    $drivers = Driver::factory()->count(3)->create();

    // Act
    $store = $this->post(route('picks.store', [$round, Type::SPRINT_RACE->value]), [
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);

    // Assert
    $store->assertSessionHasErrors();
});

it('filters inactive drivers from picks selection list', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = \App\Models\Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    $activeDrivers = Driver::factory()->count(3)->create(['active' => true]);
    $inactive = Driver::factory()->create(['active' => false]);

    // Act
    $response = $this->get(route('picks.create', [$round, \App\Models\Type::RACE->value]));

    // Assert
    $response->assertOk();
    $drivers = $response->viewData('drivers');
    expect($drivers->pluck('id'))->not()->toContain($inactive->id);
});

it('rejects pick which includes an inactive driver', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);
    $round = \App\Models\Round::factory()->create([
        'year' => 2033,
        'race_qualifying_at' => now()->addHours(2),
        'race_at' => now()->addDay(),
    ]);
    $activeDrivers = Driver::factory()->count(3)->create(['active' => true]);
    $inactive = Driver::factory()->create(['active' => false]);

    // Act
    $store = $this->post(route('picks.store', [$round, \App\Models\Type::RACE->value]), [
        'driver1_id' => $activeDrivers[0]->id,
        'driver2_id' => $inactive->id,
        'driver3_id' => $activeDrivers[2]->id,
    ]);

    // Assert
    $store->assertSessionHasErrors();
});
