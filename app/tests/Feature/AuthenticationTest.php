<?php

use App\Models\User;
use Laravel\Socialite\Contracts\Provider as SocialProviderContract;
use Laravel\Socialite\Contracts\User as SocialUserContract;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

it('redirects user to google for sign in', function () {
    // Arrange
    $provider = Mockery::mock(SocialProviderContract::class);
    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    $provider->shouldReceive('redirect')->andReturn(new RedirectResponse($redirect = '/fake-google'));

    // Act
    $response = $this->get(route('login'));

    // Assert
    $response->assertRedirect($redirect);
});

it('logs user in via google callback and redirects home', function () {
    // Arrange
    $socialUser = Mockery::mock(SocialUserContract::class);
    $socialUser->shouldReceive('getEmail')->andReturn($email = 'test@example.com');
    $socialUser->shouldReceive('getName')->andReturn('Test User');
    $provider = Mockery::mock(SocialProviderContract::class);
    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    $provider->shouldReceive('stateless')->andReturnSelf();
    $provider->shouldReceive('user')->andReturn($socialUser);

    // Act
    $response = $this->get('/auth/callback');

    // Assert
    $response->assertRedirect('/');
    $this->assertAuthenticated();
    expect(User::where('email', $email)->exists())->toBeTrue();
});

it('logs out the user and redirects home', function () {
    // Arrange
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act
    $response = $this->post(route('logout'));

    // Assert
    $response->assertRedirect('/');
    $this->assertGuest();
});

it('updates existing user name from oauth identity on callback', function () {
    // Arrange
    $existing = User::factory()->create([
        'email' => 'test@example.com',
        'name' => 'OldName',
    ]);
    $socialUser = Mockery::mock(SocialUserContract::class);
    $socialUser->shouldReceive('getEmail')->andReturn('test@example.com');
    $socialUser->shouldReceive('getName')->andReturn('New Name');
    $provider = Mockery::mock(SocialProviderContract::class);
    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    $provider->shouldReceive('stateless')->andReturnSelf();
    $provider->shouldReceive('user')->andReturn($socialUser);

    // Act
    $response = $this->get('/auth/callback');

    // Assert
    $response->assertRedirect('/');
    $this->assertAuthenticated();
    $this->assertSame('New', $existing->refresh()->name);
});
