<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_enter_the_demo_without_credentials(): void
    {
        $demo = User::factory()->create([
            'name' => 'Dr. Elara Voss',
            'is_demo' => true,
            'email_verified_at' => now(),
        ]);

        $this->post(route('demo.login'))
            ->assertRedirect(route('journalists.page'));

        $this->assertAuthenticatedAs($demo);
    }

    public function test_demo_user_can_browse_but_cannot_save_changes(): void
    {
        $demo = User::factory()->create(['is_demo' => true, 'email_verified_at' => now()]);

        $this->actingAs($demo)->get(route('contacts.page'))->assertOk();

        $this->actingAs($demo)->postJson(route('contact-lists.store'), [
            'name' => 'A forbidden change',
            'description' => 'This must never be stored.',
        ])->assertForbidden()->assertJson([
            'message' => 'This is a read-only demo. Changes are disabled.',
        ]);

        $this->assertDatabaseMissing('contact_list', ['name' => 'A forbidden change']);
    }

    public function test_demo_user_can_complete_the_session_only_human_check(): void
    {
        $demo = User::factory()->create(['is_demo' => true, 'email_verified_at' => now()]);

        $this->actingAs($demo)
            ->postJson(route('ajax.directory.human-verify'), ['slider' => 100])
            ->assertOk()
            ->assertJsonPath('verified', true);

        $this->assertTrue(session('directory_human_verified'));
    }

    public function test_demo_user_can_log_out(): void
    {
        $demo = User::factory()->create(['is_demo' => true, 'email_verified_at' => now()]);

        $this->actingAs($demo)->post(route('logout'))->assertRedirect('/');

        $this->assertGuest();
    }
}
