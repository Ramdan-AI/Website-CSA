<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpiredLoginFormTest extends TestCase
{
    use RefreshDatabase;

    private const STALE_FORM = ['_token' => 'stale', 'email' => 'rina@example.test', 'password' => 'password'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(ValidateCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends ValidateCsrfToken
        {
            protected function runningUnitTests()
            {
                return false;
            }
        });
    }

    public function test_second_submission_after_login_lands_on_the_dashboard(): void
    {
        $role = Role::create(['name' => 'Ketua', 'slug' => 'ketua']);
        $user = User::create(['role_id' => $role->id, 'name' => 'Rina', 'email' => 'rina@example.test', 'password' => 'password']);

        $this->actingAs($user)->post('/login', self::STALE_FORM)
            ->assertRedirect(route('admin.dashboard', ['roleSlug' => 'ketua']));
    }

    public function test_guest_with_an_expired_form_returns_to_login_with_a_message(): void
    {
        $this->post('/login', self::STALE_FORM)
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'Sesi formulir sudah habis. Silakan masuk lagi.']);

        $this->assertGuest();
    }

    public function test_other_forms_still_reject_a_stale_token(): void
    {
        $this->post('/logout', ['_token' => 'stale'])->assertStatus(419);
    }
}
