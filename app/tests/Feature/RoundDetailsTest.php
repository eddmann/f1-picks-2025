<?php

use App\Models\Round;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('includes sprint types on sprint weekends', function () {
    // Arrange
    $round = Round::factory()->sprintWeekend()->create(['year' => 2031]);

    // Act
    $response = $this->get(route('rounds.show', $round));

    // Assert
    $response->assertOk();
    $types = $response->viewData('types');
    $ids = array_map(fn ($t) => $t['id'], $types);
    expect($ids)->toContain(Type::SPRINT_QUALIFYING);
    expect($ids)->toContain(Type::SPRINT_RACE);
});

it('ensure not open for picks when guest users', function () {
    // Arrange
    Date::setTestNow(now());
    $round = Round::factory()->create([
        'year' => 2031,
        'race_qualifying_at' => now()->addHour(),
        'race_at' => now()->addHours(2),
    ]);

    // Act
    $guestResponse = $this->get(route('rounds.show', $round));

    // Assert
    $guestResponse->assertOk();
    $types = $guestResponse->viewData('types');
    foreach ($types as $t) {
        expect($t['isOpenForPicks'])->toBeFalse();
    }
});

it('ensure open for picks when authenticated users', function () {
    // Arrange
    Date::setTestNow(now());
    $round = Round::factory()->create([
        'year' => 2031,
        'race_qualifying_at' => now()->addHour(),
        'race_at' => now()->addHours(2),
    ]);
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $response = $this->get(route('rounds.show', $round));

    // Assert
    $response->assertOk();
    $types = $response->viewData('types');
    $raceQ = collect($types)->firstWhere('id', Type::RACE_QUALIFYING);
    expect($raceQ['isOpenForPicks'])->toBeTrue();
});

it('omits sprint types from details when round is not a sprint weekend', function () {
    // Arrange
    $round = Round::factory()->nonSprintWeekend()->create(['year' => 2031]);

    // Act
    $response = $this->get(route('rounds.show', $round));

    // Assert
    $response->assertOk();
    $types = $response->viewData('types');
    $ids = array_map(fn ($t) => $t['id'], $types);
    expect($ids)->toBe([Type::RACE_QUALIFYING, Type::RACE]);
});
