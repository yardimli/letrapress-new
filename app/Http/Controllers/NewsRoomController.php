<?php

namespace App\Http\Controllers;

use App\Models\NewsRoom;
use App\Models\User;
use App\Models\news_rooms_folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsRoomController extends Controller
{
    public function page(Request $request): View
    {
        return view('apps-news-rooms', ['folders' => $this->folders($request)]);
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(NewsRoom::query()->with('folder')->where('user_id', $request->user()->id)
            ->when($request->string('search')->trim()->value(), fn ($q, $search) => $q->where('subject', 'like', "%{$search}%"))
            ->orderBy('order')->latest()->paginate(10));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $nextOrder = (int) NewsRoom::query()->where('user_id', $request->user()->id)->max('order') + 1;
        $room = NewsRoom::create($data + ['user_id' => $request->user()->id, 'order' => $nextOrder]);

        return response()->json(['message' => 'Newsroom story saved.', 'data' => $room], 201);
    }

    public function update(Request $request, NewsRoom $newsRoom): JsonResponse
    {
        $this->authorizeRoom($request, $newsRoom);
        $newsRoom->update($this->validated($request));

        return response()->json(['message' => 'Newsroom story updated.', 'data' => $newsRoom]);
    }

    public function destroy(Request $request, NewsRoom $newsRoom): JsonResponse
    {
        $this->authorizeRoom($request, $newsRoom);
        $newsRoom->delete();

        return response()->json(['message' => 'Newsroom story deleted.']);
    }

    public function publicIndex(string $slug): View|RedirectResponse
    {
        [$user, $legacy] = $this->publicOwner($slug);
        if ($legacy) {
            return redirect()->route('newsroom.public', $user->newsroom_slug, 301);
        }

        $rooms = NewsRoom::query()->where('user_id', $user->id)
            ->whereHas('folder', fn ($q) => $q->where('folder_name', 'Go Public'))->orderBy('order')->get();
        abort_if($rooms->isEmpty(), 404);
        return view('newsroom', compact('rooms', 'user'));
    }

    public function publicShow(string $slug, NewsRoom $newsRoom): View|RedirectResponse
    {
        [$user, $legacy] = $this->publicOwner($slug);
        abort_unless($newsRoom->user_id === $user->id && $newsRoom->folder?->folder_name === 'Go Public', 404);
        if ($legacy) {
            return redirect()->route('newsroom.show', [$user->newsroom_slug, $newsRoom], 301);
        }

        return view('newsroom-post', compact('newsRoom', 'user'));
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:500'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'featured_image' => ['nullable', 'url', 'max:2000'],
            'content' => ['required', 'string'],
            'date' => ['nullable', 'date'],
            'folder_id' => ['required', 'integer'],
        ]);
        news_rooms_folder::query()->where('user_id', $request->user()->id)->findOrFail($data['folder_id']);
        $data['date'] = $data['date'] ?? now()->toDateString();
        $data['featured_image'] = $data['featured_image'] ?? '';
        $data['summary'] = $data['summary'] ?? '';
        return $data;
    }

    private function folders(Request $request)
    {
        return news_rooms_folder::query()->where('user_id', $request->user()->id)->orderBy('order')->get();
    }

    private function authorizeRoom(Request $request, NewsRoom $room): void
    {
        abort_unless($room->user_id === $request->user()->id, 404);
    }

    private function publicOwner(string $slug): array
    {
        $user = User::query()->where('newsroom_slug', $slug)->first();
        if ($user) {
            return [$user, false];
        }

        return [User::query()->where('name', $slug)->firstOrFail(), true];
    }
}
