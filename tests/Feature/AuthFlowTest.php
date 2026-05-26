<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_client_and_is_redirected_to_profile_onboarding(): void
    {
        $response = $this->post('/register', [
            'name' => 'Erick',
            'email' => 'erick@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/onboarding/profile');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'erick@example.com',
            'role' => User::ROLE_CLIENT,
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->client()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_incomplete_user_is_redirected_to_profile_onboarding_after_login(): void
    {
        $user = User::factory()->client()->create([
            'profile_completed_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/onboarding/profile');
    }

    public function test_client_with_pending_business_request_still_logs_in_to_dashboard(): void
    {
        $user = User::factory()->client()->create([
            'business_requested_at' => now(),
            'business_approved_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_google_redirect_uses_socialite(): void
    {
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get('/auth/google/redirect');

        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    public function test_google_callback_creates_a_client_and_redirects_to_onboarding(): void
    {
        $provider = Mockery::mock(Provider::class);
        $socialiteUser = Mockery::mock(SocialiteUser::class);

        $socialiteUser->shouldReceive('getId')->andReturn('google-123');
        $socialiteUser->shouldReceive('getEmail')->andReturn('google-user@example.com');
        $socialiteUser->shouldReceive('getName')->andReturn('Google User');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.png');

        $provider->shouldReceive('user')->once()->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/onboarding/profile');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'google-user@example.com',
            'role' => User::ROLE_CLIENT,
            'auth_provider' => 'google',
            'provider_id' => 'google-123',
        ]);
    }

    public function test_admin_can_approve_a_pending_business_request(): void
    {
        $admin = User::factory()->admin()->create();
        $businessApplicant = User::factory()->client()->create([
            'business_requested_at' => now(),
            'business_approved_at' => null,
        ]);

        $response = $this->actingAs($admin)->post(route('business-access.approve', $businessApplicant));

        $response->assertRedirect();
        $this->assertSame(User::ROLE_BUSINESS, $businessApplicant->fresh()->role);
        $this->assertNotNull($businessApplicant->fresh()->business_approved_at);
    }

    public function test_login_is_rate_limited_after_repeated_failures(): void
    {
        $user = User::factory()->client()->create();
        $key = Str::transliterate(Str::lower($user->email).'|127.0.0.1');

        RateLimiter::clear($key);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        RateLimiter::clear($key);
    }
}
