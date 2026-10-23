<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlquilerNotificacionesTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_uses_one_notification_container(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['success' => 'Alquiler actualizado.'])
            ->get('/alquileres')->assertOk()
            ->assertSee('id="alquiler-alert-container"', false)
            ->assertSee('data-success="true"', false)
            ->assertSee('Alquiler actualizado.')
            ->assertDontSee('ajax-alert-container');
    }

    public function test_error_takes_priority_over_previous_success_and_stays_visible(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['success' => 'Aviso anterior', 'error' => 'No se pudo guardar'])
            ->get('/alquileres')->assertOk()
            ->assertSee('data-success="false"', false)
            ->assertSee('No se pudo guardar')
            ->assertDontSee('Aviso anterior');
    }
}
