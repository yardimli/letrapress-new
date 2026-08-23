<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use App\Models\press_releases_folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PressReleaseController extends Controller
{
    public function page(Request $request): View
    {
        return view('apps-press-releases', ['folders' => $this->folders($request)]);
    }

    public function index(Request $request): JsonResponse
    {
        $releases = PressRelease::query()->with('folder')->where('user_id', $request->user()->id)
            ->when($request->integer('folder_id'), fn ($q, $id) => $q->where('folder_id', $id))
            ->when($request->string('search')->trim()->value(), fn ($q, $search) => $q->where('subject', 'like', "%{$search}%"))
            ->latest()->paginate(10);

        return response()->json($releases);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $release = PressRelease::create($data + ['user_id' => $request->user()->id]);

        return response()->json(['message' => 'Press release saved.', 'data' => $release], 201);
    }

    public function update(Request $request, PressRelease $pressRelease): JsonResponse
    {
        $this->authorizeRelease($request, $pressRelease);
        $pressRelease->update($this->validated($request));

        return response()->json(['message' => 'Press release updated.', 'data' => $pressRelease]);
    }

    public function destroy(Request $request, PressRelease $pressRelease): JsonResponse
    {
        $this->authorizeRelease($request, $pressRelease);
        $pressRelease->delete();

        return response()->json(['message' => 'Press release deleted.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate(['subject' => ['required', 'string', 'max:500'], 'content' => ['required', 'string'], 'folder_id' => ['required', 'integer']]);
        press_releases_folder::query()->where('user_id', $request->user()->id)->findOrFail($data['folder_id']);
        return $data;
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
