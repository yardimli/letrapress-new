<?php

namespace Database\Seeders;

use App\Models\ContactList;
use App\Models\Journalist;
use App\Models\NewsRoom;
use App\Models\Outlet;
use App\Models\PressRelease;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'elara.voss@demo.letrapress.test'],
            [
                'name' => 'Dr. Elara Voss',
                'password' => Hash::make(Str::random(64)),
                'email_verified_at' => now(),
                'is_demo' => true,
                'newsroom_slug' => 'elara-voss',
            ]
        );

        $pressFolder = $this->folder('press_releases_folder', $user->id, 'Go Public');
        $newsFolder = $this->folder('news_rooms_folder', $user->id, 'Go Public');

        $books = [
            [
                'title' => 'The Cartographer of Dying Suns',
                'image' => '/images/demo/cartographer-dying-suns.png',
                'date' => '2026-09-15',
                'summary' => 'A disgraced stellar cartographer discovers that the galaxy’s collapsing suns are spelling a warning meant for humanity.',
                'release' => 'Award-winning speculative novelist Dr. Elara Voss announces The Cartographer of Dying Suns, a sweeping science-fiction mystery about maps, mortality, and the messages hidden inside failing stars.',
                'story' => "When survey pilot Mara Quill is sent to catalogue a chain of unstable stars, she finds impossible geometry in their final light. The pattern points toward Earth—and toward a choice no civilization has survived.\n\nThe Cartographer of Dying Suns is Dr. Elara Voss’s fifth novel, combining astronomical wonder with an intimate story about grief, truth, and who controls the map.",
            ],
            [
                'title' => 'A Memory of Europa',
                'image' => '/images/demo/memory-europa.png',
                'date' => '2024-04-02',
                'summary' => 'Beneath Europa’s ice, an oceanographer finds a machine that remembers every visitor—except her.',
                'release' => 'A Memory of Europa, the haunting fourth novel from Dr. Elara Voss, is now available in paperback with a new author essay on memory, exploration, and the ethics of first contact.',
                'story' => "In the black ocean beneath Europa, Commander Imani Vale discovers an archive built before human history. It knows her crew’s secrets, but insists that Imani has visited before.\n\nA Memory of Europa is a lyrical first-contact thriller about unreliable memory and the dangerous comfort of being known.",
            ],
            [
                'title' => 'Signal at the End of Time',
                'image' => '/images/demo/signal-end-time.png',
                'date' => '2022-10-18',
                'summary' => 'The last radio astronomer receives a transmission from the final minute of the universe.',
                'release' => 'Signal at the End of Time has been selected for the Meridian Science Fiction Book Club, bringing Dr. Elara Voss’s meditation on loneliness and cosmic time to a new community of readers.',
                'story' => "At an abandoned lunar observatory, Sera Nyx hears a signal no instrument should be able to receive. It comes from trillions of years in the future—and it is asking her to answer now.\n\nPart mystery and part elegy, Signal at the End of Time asks what one voice can mean across an impossible distance.",
            ],
            [
                'title' => 'The Glass Moon Protocol',
                'image' => '/images/demo/glass-moon-protocol.png',
                'date' => '2020-06-09',
                'summary' => 'A courier enters a transparent moon and uncovers the treaty that has kept three worlds at peace.',
                'release' => 'North Meridian Pictures has optioned The Glass Moon Protocol, Dr. Elara Voss’s acclaimed political space thriller, for development as a limited television series.',
                'story' => "Courier Ren Tallow carries a sealed protocol into the transparent moon of Pelagos. Inside, every corridor is visible—and every allegiance is hidden.\n\nThe Glass Moon Protocol is a taut story of diplomacy, surveillance, and the private bargains beneath public peace.",
            ],
            [
                'title' => 'Gardens Beneath Titan',
                'image' => '/images/demo/gardens-beneath-titan.png',
                'date' => '2018-03-20',
                'summary' => 'On Saturn’s largest moon, a botanist learns that the first alien garden was planted for humanity.',
                'release' => 'Gardens Beneath Titan, the hopeful debut novel by scientist-turned-author Dr. Elara Voss, celebrates its anniversary with a new illustrated edition and a nationwide library discussion guide.',
                'story' => "Botanist Lio Arendt tends Earth’s final seed bank under Titan’s amber sky. When unfamiliar flowers appear beyond the habitat glass, Lio must decide whether they are an invitation or a warning.\n\nGardens Beneath Titan launched Elara Voss’s five-book career and remains a reader favorite for its hopeful vision of survival through care.",
            ],
        ];

        foreach ($books as $order => $book) {
            PressRelease::query()->updateOrCreate(
                ['user_id' => $user->id, 'subject' => $book['title'].' — '.$this->releaseHeadline($order)],
                ['folder_id' => $pressFolder, 'content' => $book['release']."\n\nAbout the author\nDr. Elara Voss writes literary science fiction about memory, discovery, and the futures people build together. She is available for interviews, features, podcasts, and festival appearances."]
            );

            NewsRoom::query()->updateOrCreate(
                ['user_id' => $user->id, 'subject' => $book['title']],
                [
                    'folder_id' => $newsFolder,
                    'summary' => $book['summary'],
                    'featured_image' => $book['image'],
                    'content' => $book['story'],
                    'order' => $order,
                    'date' => $book['date'],
                ]
            );
        }

        $this->seedLists($user);
    }

    private function folder(string $table, int $userId, string $name): int
    {
        $existing = DB::table($table)->where('user_id', $userId)->where('folder_name', $name)->value('id');
        if ($existing) {
            return (int) $existing;
        }

        return (int) DB::table($table)->insertGetId([
            'user_id' => $userId,
            'folder_name' => $name,
            'order' => 0,
            'type' => 'system',
        ]);
    }

    private function seedLists(User $user): void
    {
        $groups = [
            ['Demo · Science Fiction & Book Reviewers', 'Reviewers and editors covering books, publishing, speculative fiction, and author interviews.', ['Books']],
            ['Demo · Science, Space & Future Technology', 'Reporters and publications interested in astronomy, exploration, emerging technology, and future society.', ['Science', 'Popular Science', 'Technology', 'Computers & Technology']],
            ['Demo · Culture, Podcasts & Entertainment', 'Culture desks and entertainment voices suited to profiles, adaptations, podcasts, and festival coverage.', ['Entertainment']],
        ];

        foreach ($groups as [$name, $description, $topics]) {
            $list = ContactList::query()->updateOrCreate(
                ['name' => $name],
                ['user_id' => $user->id, 'description' => $description]
            );

            $journalistIds = Journalist::query()
                ->whereHas('j_topics', fn ($query) => $query->whereIn('topic', $topics))
                ->whereNotNull('email')->where('email', '<>', '')
                ->orderByDesc('influence_score')->limit(12)->pluck('id');

            $outletIds = Outlet::query()
                ->whereHas('j_topics', fn ($query) => $query->whereIn('topic', $topics))
                ->orderByDesc('influence_score')->limit(8)->pluck('id');

            $list->journalists()->sync($journalistIds);
            $list->outlets()->sync($outletIds);
        }
    }

    private function releaseHeadline(int $index): string
    {
        return [
            'New novel announced for September publication',
            'Paperback edition arrives with a new author essay',
            'Selected for the Meridian Science Fiction Book Club',
            'Optioned for limited television series',
            'Anniversary illustrated edition announced',
        ][$index];
    }
}
