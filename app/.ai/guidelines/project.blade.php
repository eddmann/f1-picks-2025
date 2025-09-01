# Project Guidelines

## Structure

- Laravel 12 app in `app/`.
- Local Docker config in `docker/`.
- Serverless Framework 3 config in `app/serverless.yml`.
- Project-specific targets in the root `Makefile`.

**NOTE:** Prefer the root `Makefile` over ad-hoc commands.
Run `make help` to determine if there is a suitable command before attempting ad-hoc commands.
If an ad-hoc command is required, invoke the command using `make shell/"<command>"`.

## Conventions

- Refrain from unnecessary abbreviated variable names. For example, use `$response` instead of `$resp`, and `$connection` instead of `$conn`.
- Prefer descriptive, full-word names; use meaningful domain terms.

## Testing

- Run all tests: `make test`.
- Run filtered tests by name, class, method, or file path: `make test/<filter>`.
  - Examples: `make test/ResultsFeatureTest`, `make test/tests/Feature/ResultsFeatureTest.php`, `make test/it_creates_a_result`.

### Testing Guidelines (Critically Important)

- Test behaviour, not implementation!
- Tests should verify expected behaviour, treating implementation as a black box.
- Test through the public API exclusively - internals should be invisible to tests.
- No 1:1 mapping between test files and implementation files.
- Tests must document expected business behaviour.
- Ensure the test name clearly describes the business behaviour we are testing for.
- Name tests by business behaviour (what), not method names (how).
- All tests must follow AAA (Arrange, Act, Assert), like so:

  ```php
  it('creates a result', function () {
    // Arrange
    $user = \App\Models\User::factory()->create();
    $round = \App\Models\Round::factory()->create();

    // Act
    $response = $this
      ->actingAs($user)
      ->post(route('results.store'), [
        'round_id' => $round->id,
      ]);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('results', ['round_id' => $round->id]);
  });
  ```

## Code Style

- Lint the application: `make lint`.
- Fix code style issues: `make fmt`.

## Commit & PR Hygiene

- Make small, focused commits with a clear, single responsibility.
- Write descriptive commit messages; link the related issue when applicable.
- Keep diffs minimal and avoid drive-by refactors outside the task scope.

## Release Gate (Critically Important)

MUST PASS before declaring a change complete: `make can-release` (fix failures and re-run until green).

### Failure Handling

- If tests fail: reproduce with `make test` or `make test/<filter>`, fix and re-run `make can-release`.
- If code style fails: run `make fmt`, and re-run `make can-release`.
