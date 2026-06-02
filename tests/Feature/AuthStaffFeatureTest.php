<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthStaffFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_cannot_be_accessed_without_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_staff_can_login_and_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Petugas',
            'email' => 'petugas@perpustakaan.test',
            'password' => 'password',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->get(route('dashboard'))->assertStatus(200);
    }
}

