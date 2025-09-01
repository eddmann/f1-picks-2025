<?php

use App\Jobs\CalculatePickScoresForResult;
use App\Models\Driver;
use App\Models\Pick;
use App\Models\Result;
use App\Models\Round;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

it('forbids non-admin access to view results page', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $response = $this->get(route('results.create'));

    // Assert
    $response->assertForbidden();
});

it('forbids non-admin access to store results', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $response = $this->post(route('results.store'));

    // Assert
    $response->assertForbidden();
});

it('allows admin to view results page', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);

    // Act
    $response = $this->get(route('results.create'));

    // Assert
    $response->assertOk();
    $response->assertViewHasAll(['rounds', 'drivers']);
});

it('marks sprint rounds and sprint types in results page', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $sprintRound = Round::factory()->sprintWeekend()->create(['year' => 2031]);
    $nonSprintRound = Round::factory()->nonSprintWeekend()->create(['year' => 2031]);

    // Act
    $response = $this->get(route('results.create'));

    // Assert
    $response->assertOk();
    $html = $response->getContent();
    expect($html)->toContain('option value="'.$sprintRound->id.'" data-sprint="1"');
    expect($html)->toContain('option value="'.$nonSprintRound->id.'" data-sprint="0"');
});

it('saves a round result', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $round = Round::factory()->sprintWeekend()->create(['year' => 2030]);
    $drivers = Driver::factory()->count(3)->create();

    // Act
    $response = $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);

    // Assert
    $response->assertRedirect(route('results.create'));
    $response->assertSessionHas('success');
    $this->assertDatabaseHas('results', [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
    ]);
});

it('dispatches scoring job when result is saved', function () {
    // Arrange
    Queue::fake();
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $round = Round::factory()->sprintWeekend()->create(['year' => 2030]);
    $drivers = Driver::factory()->count(3)->create();

    // Act
    $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);

    // Assert
    Queue::assertPushed(CalculatePickScoresForResult::class);
});

it('calculates pick score after publishing matching result', function () {
    // Arrange
    $userA = User::factory()->create(['name' => 'Alice']);
    $round = Round::factory()->create([
        'year' => 2030,
        'race_qualifying_at' => now()->addDay(),
        'race_at' => now()->addDays(2),
    ]);
    $drivers = Driver::factory()->count(3)->create();
    $alicePick = Pick::factory()->create([
        'user_id' => $userA->id,
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[2]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[0]->id,
    ]);

    // Act
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[2]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[0]->id,
    ]);

    // Assert
    $alicePick->refresh();
    expect($alicePick->score)->toBe(6);
});

it('updates calculated pick score after publishing updated matching result', function () {
    // Arrange
    $userA = User::factory()->create(['name' => 'Alice']);
    $round = Round::factory()->create([
        'year' => 2030,
        'race_qualifying_at' => now()->addDay(),
        'race_at' => now()->addDays(2),
    ]);
    $drivers = Driver::factory()->count(3)->create();
    $alicePick = Pick::factory()->create([
        'user_id' => $userA->id,
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[2]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[0]->id,
    ]);

    // Act
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[2]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[0]->id,
    ]);
    $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);

    // Assert
    $alicePick->refresh();
    expect($alicePick->score)->toBe(4);
});

it('rejects sprint results for non-sprint weekends', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $round = Round::factory()->nonSprintWeekend()->create(['year' => 2030]);
    $drivers = Driver::factory()->count(3)->create();

    // Act
    $response = $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::SPRINT_RACE->value,
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[1]->id,
        'driver3_id' => $drivers[2]->id,
    ]);

    // Assert
    $response->assertSessionHasErrors();
});

it('rejects invalid type when posting results', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $round = Round::factory()->sprintWeekend()->create(['year' => 2030]);
    $drivers = Driver::factory()->count(2)->create();

    // Act
    $response = $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => 'invalid',
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[0]->id,
        'driver3_id' => $drivers[1]->id,
    ]);

    // Assert
    $response->assertSessionHasErrors();
});

it('rejects duplicate drivers when posting results', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $round = Round::factory()->sprintWeekend()->create(['year' => 2030]);
    $drivers = Driver::factory()->count(2)->create();

    // Act
    $response = $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $drivers[0]->id,
        'driver2_id' => $drivers[0]->id,
        'driver3_id' => $drivers[1]->id,
    ]);

    // Assert
    $response->assertSessionHasErrors();
});

it('updates an existing result when posting again for same round and type', function () {
    // Arrange
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $round = Round::factory()->create(['year' => 2030]);
    $driversA = Driver::factory()->count(3)->create();
    $driversB = Driver::factory()->count(3)->create();
    $existing = Result::factory()->create([
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $driversA[0]->id,
        'driver2_id' => $driversA[1]->id,
        'driver3_id' => $driversA[2]->id,
    ]);

    // Act
    $response = $this->post(route('results.store'), [
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $driversB[0]->id,
        'driver2_id' => $driversB[1]->id,
        'driver3_id' => $driversB[2]->id,
    ]);

    // Assert
    $response->assertRedirect(route('results.create'));
    $this->assertDatabaseHas('results', [
        'id' => $existing->id,
        'round_id' => $round->id,
        'type' => Type::RACE->value,
        'driver1_id' => $driversB[0]->id,
        'driver2_id' => $driversB[1]->id,
        'driver3_id' => $driversB[2]->id,
    ]);
});
