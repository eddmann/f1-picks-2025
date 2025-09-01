<?php

use App\Models\Pick;
use App\Models\Round;
use App\Models\Type;
use App\Models\User;
use Database\Factories\PickFactory;

it('shows user profile filtered by year', function () {
    // Arrange
    $user = User::factory()->create();
    $year = 2034;
    $round = Round::factory()->create(['year' => $year]);
    Pick::factory()->create(['user_id' => $user->id, 'round_id' => $round->id, 'type' => Type::RACE->value, 'score' => 2]);

    // Act
    $response = $this->get(route('users.show', $user)."?year=$year");

    // Assert
    $response->assertOk();
    expect((int) $response->viewData('year'))->toBe($year);
    expect($response->viewData('totalScore'))->toBe(2);
});

it('switching year changes the results shown on user profile', function () {
    // Arrange
    $user = User::factory()->create();
    $yearA = 2034;
    $yearB = 2035;
    $roundA = Round::factory()->create(['year' => $yearA]);
    $roundB = Round::factory()->create(['year' => $yearB]);
    PickFactory::new()->create(['user_id' => $user->id, 'round_id' => $roundA->id, 'type' => Type::RACE->value]);
    PickFactory::new()->create(['user_id' => $user->id, 'round_id' => $roundB->id, 'type' => Type::RACE->value]);

    // Act
    $responseA = $this->get(route('users.show', ['user' => $user->id, 'year' => $yearA]));
    $responseB = $this->get(route('users.show', ['user' => $user->id, 'year' => $yearB]));

    // Assert
    $responseA->assertOk();
    $responseB->assertOk();
    $picksA = $responseA->viewData('picks');
    $picksB = $responseB->viewData('picks');
    foreach ($picksA as $group) {
        foreach ($group as $p) {
            expect($p->year)->toBe($yearA);
        }
    }
    foreach ($picksB as $group) {
        foreach ($group as $p) {
            expect($p->year)->toBe($yearB);
        }
    }
});

it('groups picks by round name in the view', function () {
    // Arrange
    $user = User::factory()->create();
    $year = 2034;
    $round = Round::factory()->create(['year' => $year, 'name' => 'Test GP']);
    Pick::factory()->create(['user_id' => $user->id, 'round_id' => $round->id, 'type' => Type::RACE->value]);

    // Act
    $response = $this->get(route('users.show', $user)."?year=$year");

    // Assert
    $response->assertOk();
    $picks = $response->viewData('picks');
    expect($picks->keys())->toContain('Test GP');
});

it('orders rounds by round number ascending for the selected year', function () {
    // Arrange
    $user = User::factory()->create();
    $round2 = Round::factory()->create(['year' => 2034, 'round' => 2, 'name' => 'Round 2']);
    $round1 = Round::factory()->create(['year' => 2034, 'round' => 1, 'name' => 'Round 1']);
    Pick::factory()->create(['user_id' => $user->id, 'round_id' => $round2->id, 'type' => Type::RACE->value]);
    Pick::factory()->create(['user_id' => $user->id, 'round_id' => $round1->id, 'type' => Type::RACE->value]);

    // Act
    $response = $this->get(route('users.show', ['user' => $user->id, 'year' => 2034]));

    // Assert
    $response->assertOk();
    $picks = $response->viewData('picks');
    expect($picks->keys()->values()->toArray())->toBe(['Round 1', 'Round 2']);
});

it('shows the total score for the selected year', function () {
    // Arrange
    $user = User::factory()->create();
    $roundA = Round::factory()->create(['year' => 2034]);
    $roundB = Round::factory()->create(['year' => 2034]);
    Pick::factory()->create(['user_id' => $user->id, 'round_id' => $roundA->id, 'type' => Type::RACE->value, 'score' => 2]);
    Pick::factory()->create(['user_id' => $user->id, 'round_id' => $roundB->id, 'type' => Type::RACE->value, 'score' => 1]);

    // Act
    $response = $this->get(route('users.show', ['user' => $user->id, 'year' => 2034]));

    // Assert
    $response->assertOk();
    $response->assertViewHas('totalScore', 3);
});
