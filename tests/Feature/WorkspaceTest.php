<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_pages_are_private_and_render_for_a_user(): void
    {
        $paths = ['/apps-journalist-list', '/apps-outlet-list', '/apps-contact-list-inbox', '/apps-press-releases', '/apps-news-rooms', '/profile'];

        foreach ($paths as $path) {
            $this->get($path)->assertRedirect('/login');
        }

        $user = User::factory()->create();
        foreach ($paths as $path) {
            $this->actingAs($user)->get($path)->assertOk()->assertDontSee('<x-', false);
        }
    }

    public function test_new_accounts_receive_their_default_press_workspace(): void
    {
        $user = User::factory()->create();

        $this->assertSame(4, DB::table('press_releases_folder')->where('user_id', $user->id)->count());
        $this->assertSame(4, DB::table('news_rooms_folder')->where('user_id', $user->id)->count());
        $this->assertSame(4, DB::table('press_releases_label')->where('user_id', $user->id)->count());
        $this->assertSame(3, DB::table('news_rooms_label')->where('user_id', $user->id)->count());
    }

    public function test_contact_lists_can_be_created_updated_and_deleted_over_ajax(): void
    {
        $user = User::factory()->create();

        $created = $this->actingAs($user)->postJson('/ajax/contact-lists', [
            'name' => 'Launch editors',
            'description' => 'Editors for the autumn launch.',
        ])->assertCreated()->json('data');

        $this->actingAs($user)->putJson('/ajax/contact-lists/'.$created['id'], [
            'name' => 'Launch desk',
            'description' => 'A tighter list.',
        ])->assertOk();

        $this->assertDatabaseHas('contact_list', ['id' => $created['id'], 'name' => 'Launch desk', 'user_id' => $user->id]);
        $this->actingAs($user)->deleteJson('/ajax/contact-lists/'.$created['id'])->assertOk();
        $this->assertDatabaseMissing('contact_list', ['id' => $created['id']]);
    }

    public function test_directory_results_are_sorted_sized_and_capped_at_990(): void
    {
        $user = User::factory()->create();
        $records = collect(range(1, 1001))->map(fn (int $number) => [
            'user_id' => $user->id,
            'journalist_name' => 'Reporter '.str_pad((string) $number, 4, '0', STR_PAD_LEFT),
            'influence_score' => $number,
        ]);
        $records->chunk(100)->each(fn ($chunk) => DB::table('prowly_journalists')->insert($chunk->all()));

        $firstPage = $this->actingAs($user)->getJson('/ajax/journalists?per_page=99&sort=score_desc')
            ->assertOk()
            ->assertJsonPath('records_total', 1001)
            ->assertJsonPath('total', 990)
            ->assertJsonPath('per_page', 99)
            ->assertJsonPath('last_page', 10);

        $this->assertCount(99, $firstPage->json('data'));
        $this->assertSame(1001, $firstPage->json('data.0.influence_score'));

        $beyondLimit = $this->actingAs($user)->getJson('/ajax/journalists?per_page=99&sort=name_asc&page=999')
            ->assertOk()
            ->assertJsonPath('current_page', 10)
            ->assertJsonPath('next_page_url', null);

        $this->assertCount(99, $beyondLimit->json('data'));
    }

    public function test_directory_counts_and_result_pages_are_cached_as_json(): void
    {
        $user = User::factory()->create();
        $largeCountry = DB::table('prowly_countries')->insertGetId(['country' => 'Large Market', 'record_count' => 0]);
        $smallCountry = DB::table('prowly_countries')->insertGetId(['country' => 'Small Market', 'record_count' => 0]);

        foreach (range(1, 30) as $number) {
            DB::table('prowly_journalists')->insert([
                'user_id' => $user->id,
                'journalist_name' => 'Cached Reporter '.$number,
                'influence_score' => $number,
                'country_id' => $number <= 20 ? $largeCountry : $smallCountry,
            ]);
        }

        $this->actingAs($user)->get('/apps-journalist-list')->assertOk()
            ->assertSeeInOrder(['Large Market (20)', 'Small Market (10)']);

        $endpoint = '/ajax/journalists?per_page=24&sort=score_desc';
        $this->actingAs($user)->getJson($endpoint)->assertOk()->assertJsonPath('cache_hit', false);
        $this->actingAs($user)->getJson($endpoint)->assertOk()->assertJsonPath('cache_hit', true);

        $files = Storage::disk('local')->allFiles('directory-cache/testing/user-'.$user->id.'/journalist');
        $this->assertTrue(collect($files)->contains(fn ($file) => str_ends_with($file, 'filter-counts.json')));
        $this->assertTrue(collect($files)->contains(fn ($file) => str_contains($file, 'country-all_media-all_topic-all_language-all_pagesize-24_page-1')));
    }

    public function test_directory_list_hides_private_contact_fields_until_human_check(): void
    {
        $user = User::factory()->create();
        $journalistId = DB::table('prowly_journalists')->insertGetId([
            'user_id' => $user->id,
            'journalist_name' => 'Nova Reed',
            'influence_score' => 712,
            'email' => 'nova@example.test',
            'phone' => '+1 555 0100',
        ]);
        DB::table('prowly_social_medias')->insert([
            'journalist_id' => $journalistId,
            'social_type' => 'Bluesky',
            'social_link' => 'https://bsky.app/profile/nova.example',
        ]);

        $directory = $this->actingAs($user)->getJson('/ajax/journalists')->assertOk();
        $this->assertArrayNotHasKey('email', $directory->json('data.0'));
        $this->assertArrayNotHasKey('phone', $directory->json('data.0'));

        $this->actingAs($user)->getJson('/ajax/directory/journalists/'.$journalistId)
            ->assertForbidden();
        $this->actingAs($user)->postJson('/ajax/directory/human-verify', ['slider' => 99])
            ->assertUnprocessable();
        $this->actingAs($user)->postJson('/ajax/directory/human-verify', ['slider' => 100])
            ->assertOk()->assertJsonPath('verified', true);

        $this->actingAs($user)->getJson('/ajax/directory/journalists/'.$journalistId)
            ->assertOk()
            ->assertJsonPath('email', 'nova@example.test')
            ->assertJsonPath('phone', '+1 555 0100')
            ->assertJsonPath('social_links.0.type', 'Bluesky');
    }

    public function test_press_release_and_newsroom_records_are_user_scoped(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $pressFolder = DB::table('press_releases_folder')->where('user_id', $user->id)->first();
        $roomFolder = DB::table('news_rooms_folder')->where('user_id', $user->id)->first();

        $release = $this->actingAs($user)->postJson('/ajax/press-releases', [
            'subject' => 'A clear headline', 'content' => 'A useful press release.', 'folder_id' => $pressFolder->id,
        ])->assertCreated()->json('data');
        $this->actingAs($other)->putJson('/ajax/press-releases/'.$release['id'], [
            'subject' => 'Changed', 'content' => 'No.', 'folder_id' => $pressFolder->id,
        ])->assertNotFound();

        $room = $this->actingAs($user)->postJson('/ajax/news-rooms', [
            'subject' => 'Company bulletin', 'summary' => 'The short version.', 'content' => 'The complete story.', 'folder_id' => $roomFolder->id,
        ])->assertCreated()->json('data');
        $this->actingAs($other)->deleteJson('/ajax/news-rooms/'.$room['id'])->assertNotFound();
    }

    public function test_public_newsroom_uses_a_friendly_slug_and_redirects_the_legacy_name_url(): void
    {
        $user = User::factory()->create(['name' => 'Future Author', 'newsroom_slug' => 'future-books']);
        $folderId = DB::table('news_rooms_folder')->where('user_id', $user->id)->where('folder_name', 'Go Public')->value('id');
        $roomId = DB::table('news_rooms')->insertGetId([
            'user_id' => $user->id,
            'folder_id' => $folderId,
            'subject' => 'A new world',
            'summary' => 'A public story.',
            'featured_image' => '',
            'content' => 'The complete story.',
            'order' => 0,
            'date' => '2026-08-23',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/newsroom/future-books')->assertOk()->assertSee('Future Author');
        $this->get('/newsroom/future-books/'.$roomId)->assertOk()->assertSee('A new world');
        $this->get('/newsroom/'.rawurlencode('Future Author'))->assertRedirect('/newsroom/future-books');
    }
}
