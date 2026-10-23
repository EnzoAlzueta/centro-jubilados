<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function datos(): array
    {
        return [
            'name' => 'Operador', 'email' => 'operador@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123',
            'security_question' => '¿Mi primera mascota?', 'security_answer' => 'Luna',
        ];
    }

    public function test_guests_cannot_access_or_submit_registration(): void
    {
        $this->get('/register')->assertRedirect('/login');
        $this->post('/register', $this->datos())->assertRedirect('/login');
        $this->assertDatabaseMissing('users', ['email' => 'operador@example.com']);
    }

    public function test_regular_users_cannot_access_or_submit_registration(): void
    {
        $user = User::where('email', 'test@test.com')->firstOrFail();
        $this->actingAs($user)->get('/register')->assertForbidden();
        $this->post('/register', $this->datos())->assertForbidden();
        $this->get('/dashboard')->assertOk()->assertDontSee('Crear usuario');
        $this->assertDatabaseMissing('users', ['email' => 'operador@example.com']);
    }

    public function test_admin_can_create_regular_users_without_changing_session(): void
    {
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();
        $this->assertTrue($admin->is_admin);
        $this->actingAs($admin)->get('/register')->assertOk()->assertSee('Crear usuario');
        $this->post('/register', $this->datos() + ['is_admin' => true])
            ->assertRedirect('/register')->assertSessionHas('success');
        $this->assertAuthenticatedAs($admin);
        $user = User::where('email', 'operador@example.com')->firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertTrue(Hash::check('Luna', $user->security_answer));
        $this->post('/logout');
        $this->post('/login', ['email' => $user->email, 'password' => 'password123']);
        $this->assertAuthenticatedAs($user);
        $this->get('/register')->assertForbidden();
    }

    public function test_regular_users_cannot_grant_admin_permission_through_profile(): void
    {
        $user = User::where('email', 'test@test.com')->firstOrFail();
        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name, 'email' => $user->email,
            'security_question' => $user->security_question, 'is_admin' => true,
        ])->assertSessionHasNoErrors();
        $this->assertFalse($user->fresh()->is_admin);
        $this->get('/register')->assertForbidden();
    }

    public function test_admin_registration_validates_required_fields_and_duplicate_email(): void
    {
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();
        $this->actingAs($admin)->post('/register', [])->assertSessionHasErrors([
            'name', 'email', 'password', 'security_question', 'security_answer',
        ]);
        $datos = $this->datos();
        $datos['email'] = $admin->email;
        $datos['password_confirmation'] = 'otra';
        $this->post('/register', $datos)->assertSessionHasErrors(['email', 'password']);
        $this->assertAuthenticatedAs($admin);
    }
}
