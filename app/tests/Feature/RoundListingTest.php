<?php

use App\Models\Pick;
use App\Models\Round;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('lists rounds for the selected year', function () {
    // Arrange
    $year = 2032;
    $otherYear = 2031;
    Round::factory()->count(3)->create(['year' => $year]);
    Round::factory()->count(2)->create(['year' => $otherYear]);

    // Act
    $response = $this->get('/?year='.$year);

    // Assert
    $response->assertOk();
    $rounds = $response->viewData('rounds');
    foreach ($rounds as $round) {
        expect($round->year)->toBe($year);
    }
});

it('shows leaderboard for the selected year', function () {
    // Arrange
    $year = 2032;
    $round = Round::factory()->create(['year' => $year]);
    $alice = User::factory()->create(['name' => 'Alice']);
    $bob = User::factory()->create(['name' => 'Bob']);
    Pick::factory()->create(['user_id' => $alice->id, 'round_id' => $round->id, 'type' => Type::RACE->value, 'score' => 2]);
    Pick::factory()->create(['user_id' => $bob->id, 'round_id' => $round->id, 'type' => Type::RACE->value, 'score' => 5]);

    // Act
    $response = $this->get('/?year='.$year);

    // Assert
    $response->assertOk();
    $scores = $response->viewData('scores');
    expect($scores)->toHaveCount(2);
    expect($scores[0]->name)->toBe('Bob');
    expect((int) $scores[0]->score)->toBe(5);
    expect($scores[1]->name)->toBe('Alice');
    expect((int) $scores[1]->score)->toBe(2);
});

it('filters rounds by year parameter', function () {
    // Arrange
    $yearA = 2032;
    $yearB = 2033;
    Round::factory()->count(2)->create(['year' => $yearA]);
    Round::factory()->count(2)->create(['year' => $yearB]);

    // Act
    $responseA = $this->get('/?year='.$yearA);
    $responseB = $this->get('/?year='.$yearB);

    // Assert
    $responseA->assertOk();
    $responseB->assertOk();
    $roundsA = $responseA->viewData('rounds');
    $roundsB = $responseB->viewData('rounds');
    foreach ($roundsA as $round) {
        expect($round->year)->toBe($yearA);
    }
    foreach ($roundsB as $round) {
        expect($round->year)->toBe($yearB);
    }
});

it('orders leaderboard by total score descending', function () {
    // Arrange
    $year = 2032;
    $round = Round::factory()->create(['year' => $year]);
    $alice = User::factory()->create(['name' => 'Alice']);
    $bob = User::factory()->create(['name' => 'Bob']);
    Pick::factory()->create(['user_id' => $alice->id, 'round_id' => $round->id, 'type' => Type::RACE->value, 'score' => 2]);
    Pick::factory()->create(['user_id' => $bob->id, 'round_id' => $round->id, 'type' => Type::RACE->value, 'score' => 5]);

    // Act
    $response = $this->get('/?year='.$year);

    // Assert
    $response->assertOk();
    $scores = $response->viewData('scores');
    expect($scores)->toHaveCount(2);
    expect($scores[0]->name)->toBe('Bob');
    expect($scores[1]->name)->toBe('Alice');
    expect((int) $scores[0]->score)->toBe(5);
    expect((int) $scores[1]->score)->toBe(2);
});

it('shows first page of rounds', function () {
    // Arrange
    $year = 2032;
    for ($i = 1; $i <= 6; $i++) {
        Round::factory()->create(['year' => $year, 'round' => $i]);
    }

    // Act
    $page1 = $this->get('/?year='.$year.'&round_page=1');

    // Assert
    $page1->assertOk();
    $rounds = $page1->viewData('rounds');
    expect($rounds->pluck('round')->values()->all())->toBe([1, 2, 3, 4, 5]);
});

it('shows second page of rounds', function () {
    // Arrange
    $year = 2032;
    for ($i = 1; $i <= 6; $i++) {
        Round::factory()->create(['year' => $year, 'round' => $i]);
    }

    // Act
    $page2 = $this->get('/?year='.$year.'&round_page=2');

    // Assert
    $page2->assertOk();
    $rounds = $page2->viewData('rounds');
    expect($rounds->pluck('round')->values()->all())->toBe([6]);
});

it('defaults to the page containing the next upcoming round', function () {
    // Arrange
    $year = 2034;
    Date::setTestNow($now = now());
    Round::factory()->create(['year' => $year, 'round' => 1, 'race_qualifying_at' => $now->copy()->subDays(12), 'race_at' => $now->copy()->subDays(11)]);
    Round::factory()->create(['year' => $year, 'round' => 2, 'race_qualifying_at' => $now->copy()->subDays(10), 'race_at' => $now->copy()->subDays(9)]);
    Round::factory()->create(['year' => $year, 'round' => 3, 'race_qualifying_at' => $now->copy()->subDays(8), 'race_at' => $now->copy()->subDays(7)]);
    Round::factory()->create(['year' => $year, 'round' => 4, 'race_qualifying_at' => $now->copy()->subDays(6), 'race_at' => $now->copy()->subDays(5)]);
    $activeRound = Round::factory()->create(['year' => $year, 'round' => 5, 'race_qualifying_at' => $now->copy()->subDay(), 'race_at' => $now->copy()]);
    Round::factory()->create(['year' => $year, 'round' => 6, 'race_qualifying_at' => $now->copy()->addDay(), 'race_at' => $now->copy()->addDays(2)]);

    // Act
    $response = $this->get('/?year='.$year);

    // Assert
    $response->assertOk();
    $rounds = $response->viewData('rounds');
    expect($rounds->pluck('round'))->toContain($activeRound->round);
});

it('highlights the active round row', function () {
    // Arrange
    $year = 2035;
    $past = \App\Models\Round::factory()->create([
        'year' => $year,
        'round' => 1,
        'race_qualifying_at' => now()->copy()->subDays(3),
        'race_at' => now()->copy()->subDay(),
    ]);
    $next = \App\Models\Round::factory()->create([
        'year' => $year,
        'round' => 2,
        'race_qualifying_at' => now()->copy()->addDay(),
        'race_at' => now()->copy()->addDays(2),
    ]);

    // Act
    $response = $this->get('/?year='.$year);

    // Assert
    $response->assertOk();
    $response->assertSee('Current', false);
    $response->assertSee((string) $next->name, false);
});
