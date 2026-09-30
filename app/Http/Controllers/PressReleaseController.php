<?php

namespace App\Http\Controllers;

use App\Models\ContactList;
use App\Models\Journalist;
use App\Models\NewsRoom;
use App\Models\Outlet;
use App\Models\PressRelease;
use App\Models\PressReleaseRecipient;
use App\Models\news_rooms_folder;
use App\Models\press_releases_folder;
use App\Services\OpenRouterReleaseAnalyzer;
use App\Services\ReleaseRecipientMatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class PressReleaseController extends Controller
{
    public function page(Request $request): View
    {
        return view('apps-press-releases', ['folders' => $this->folders($request)]);
    }

    public function start(): View
    {
        return view('press-releases.start', ['templates' => config('release_templates')]);
    }

    public function create(Request $request): View
    {
        $templates = config('release_templates');
        $templateKey = $request->string('template')->value();
        $template = $templates[$templateKey] ?? $templates['blank'];

        return view('press-releases.editor', $this->editorData($request) + compact('templates', 'templateKey', 'template'));
    }

    public function edit(Request $request, PressRelease $pressRelease): View
    {
        $this->authorizeRelease($request, $pressRelease);
        $templates = config('release_templates');
        $templateKey = $pressRelease->template_key ?: 'blank';

        return view('press-releases.editor', $this->editorData($request, $pressRelease) + [
            'templates' => $templates,
            'templateKey' => $templateKey,
            'template' => $templates[$templateKey] ?? $templates['blank'],
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $releases = PressRelease::query()->with('folder')->where('user_id', $request->user()->id)
            ->when($request->integer('folder_id'), fn ($query, $id) => $query->where('folder_id', $id))
            ->when($request->string('search')->trim()->value(), fn ($query, $search) => $query->where('subject', 'like', "%{$search}%"))
            ->latest()->paginate(10);

        return response()->json($releases);
    }

    public function store(Request $request): JsonResponse
    {
        $release = DB::transaction(function () use ($request) {
            $data = $this->validated($request);
            $recipients = Arr::pull($data, 'recipients', []);
            $release = PressRelease::create($data + ['user_id' => $request->user()->id]);
            $this->finishRelease($request, $release, $recipients);

            return $release->fresh(['folder', 'newsroom']);
        });

        return response()->json(['message' => 'Draft saved.', 'data' => $release, 'redirect' => route('releases.edit', $release)], 201);
    }

    public function update(Request $request, PressRelease $pressRelease): JsonResponse
    {
        $this->authorizeRelease($request, $pressRelease);
        $release = DB::transaction(function () use ($request, $pressRelease) {
            $data = $this->validated($request);
            $recipients = Arr::pull($data, 'recipients', []);
            $pressRelease->update($data);
            $this->finishRelease($request, $pressRelease, $recipients);

            return $pressRelease->fresh(['folder', 'newsroom']);
        });

        return response()->json(['message' => 'Draft updated.', 'data' => $release]);
    }

    public function destroy(Request $request, PressRelease $pressRelease): JsonResponse
    {
        $this->authorizeRelease($request, $pressRelease);
        DB::transaction(function () use ($pressRelease) {
            PressReleaseRecipient::query()->where('press_release_id', $pressRelease->id)->delete();
            $pressRelease->delete();
        });

        return response()->json(['message' => 'Press release deleted.']);
    }

    public function analyze(Request $request, OpenRouterReleaseAnalyzer $analyzer, ReleaseRecipientMatcher $matcher): JsonResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:80', 'max:50000'],
        ]);

        try {
            $analysis = $analyzer->analyze($data['subject'], $data['content']);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 502);
        }

        return response()->json([
            'analysis' => $analysis,
            'recommendations' => $matcher->recommendations($analysis['keywords'], $request->user()->id),
        ]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:50000'],
            'folder_id' => ['required', 'integer'],
            'release_type' => ['required', 'in:pitch,release'],
            'distribution' => ['required', 'in:outreach,newsroom,both'],
            'template_key' => ['nullable', 'string', 'max:100'],
            'analysis' => ['nullable', 'array'],
            'analysis.summary' => ['nullable', 'string', 'max:1000'],
            'analysis.release_type' => ['nullable', 'in:pitch,release'],
            'analysis.keywords' => ['nullable', 'array', 'max:16'],
            'analysis.keywords.*' => ['string', 'max:100'],
            'analysis.media_angles' => ['nullable', 'array', 'max:5'],
            'analysis.media_angles.*' => ['string', 'max:300'],
            'recipients' => ['nullable', 'array', 'max:100'],
            'recipients.*.type' => ['required_with:recipients', 'in:list,journalist,outlet'],
            'recipients.*.id' => ['required_with:recipients', 'integer'],
            'recipients.*.source' => ['nullable', 'in:manual,ai'],
            'recipients.*.score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'recipients.*.reason' => ['nullable', 'string', 'max:255'],
        ]);
        press_releases_folder::query()->where('user_id', $request->user()->id)->findOrFail($data['folder_id']);
        if (! isset(config('release_templates')[$data['template_key'] ?? 'blank'])) {
            $data['template_key'] = 'blank';
        }

        return $data;
    }

    private function finishRelease(Request $request, PressRelease $release, array $recipients): void
    {
        $validRecipients = $this->validRecipients($request, $recipients);
        PressReleaseRecipient::query()->where('press_release_id', $release->id)->delete();
        foreach ($validRecipients as $recipient) {
            PressReleaseRecipient::create([
                'press_release_id' => $release->id,
                'recipient_type' => $recipient['type'],
                'recipient_id' => $recipient['id'],
                'source' => $recipient['source'] ?? 'manual',
                'match_score' => $recipient['score'] ?? null,
                'match_reason' => $recipient['reason'] ?? null,
            ]);
        }

        $newsroomId = $release->news_room_id;
        if (in_array($release->distribution, ['newsroom', 'both'], true)) {
            $newsroomId = $this->publishToNewsroom($request, $release);
        }

        $recipientCount = count($validRecipients);
        $release->forceFill([
            'recipient_count' => $recipientCount,
            'targeted_at' => $recipientCount > 0 ? now() : null,
            'news_room_id' => $newsroomId,
            'status' => $recipientCount > 0 ? 'targeted' : ($newsroomId ? 'published' : 'draft'),
        ])->save();
    }

    private function validRecipients(Request $request, array $recipients): array
    {
        return collect($recipients)->filter(function ($recipient) use ($request) {
            return match ($recipient['type']) {
                'list' => ContactList::query()->where('user_id', $request->user()->id)->whereKey($recipient['id'])->exists(),
                'journalist' => Journalist::query()->whereIn('user_id', [1, $request->user()->id])->whereKey($recipient['id'])->exists(),
                'outlet' => Outlet::query()->whereIn('user_id', [1, $request->user()->id])->whereKey($recipient['id'])->exists(),
            };
        })->unique(fn ($recipient) => $recipient['type'].'-'.$recipient['id'])->values()->all();
    }

    private function publishToNewsroom(Request $request, PressRelease $release): int
    {
        $folderId = news_rooms_folder::query()->where('user_id', $request->user()->id)->where('folder_name', 'Go Public')->value('id');
        abort_unless($folderId, 422, 'A public newsroom drawer is required.');
        $room = $release->news_room_id ? NewsRoom::query()->where('user_id', $request->user()->id)->find($release->news_room_id) : null;
        $summary = data_get($release->analysis, 'summary') ?: Str::limit(trim(strip_tags($release->content)), 260);
        $attributes = [
            'user_id' => $request->user()->id,
            'folder_id' => $folderId,
            'subject' => $release->subject,
            'summary' => $summary,
            'featured_image' => $room?->featured_image ?: '',
            'content' => $release->content,
            'order' => $room?->order ?? ((int) NewsRoom::query()->where('user_id', $request->user()->id)->max('order') + 1),
            'date' => now()->toDateString(),
        ];
        $room ? $room->update($attributes) : $room = NewsRoom::create($attributes);

        return $room->id;
    }

    private function editorData(Request $request, ?PressRelease $release = null): array
    {
        $recipients = $release ? PressReleaseRecipient::query()->where('press_release_id', $release->id)->get()->map(fn ($item) => [
            'type' => $item->recipient_type,
            'id' => $item->recipient_id,
            'source' => $item->source,
            'score' => $item->match_score,
            'reason' => $item->match_reason,
        ])->values() : collect();

        return [
            'release' => $release,
            'folders' => $this->folders($request),
            'contactLists' => ContactList::query()->where('user_id', $request->user()->id)
                ->withCount(['journalists', 'outlets'])->orderBy('name')->get(),
            'selectedRecipients' => $recipients,
        ];
    }

    private function folders(Request $request)
    {
        return press_releases_folder::query()->where('user_id', $request->user()->id)->orderBy('order')->get();
    }

    private function authorizeRelease(Request $request, PressRelease $release): void
    {
        abort_unless($release->user_id === $request->user()->id, 404);
    }
}
