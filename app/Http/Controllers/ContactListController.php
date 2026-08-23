<?php

namespace App\Http\Controllers;

use App\Models\ContactList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactListController extends Controller
{
    public function page(): View
    {
        return view('apps-contact-list-inbox');
    }

    public function index(Request $request): JsonResponse
    {
        $lists = ContactList::query()->where('user_id', $request->user()->id)
            ->withCount(['journalists', 'outlets'])->latest()->get();

        return response()->json($lists);
    }

    public function show(Request $request, ContactList $contactList): JsonResponse
    {
        $this->authorizeList($request, $contactList);

        return response()->json($contactList->load([
            'journalists.j_title', 'journalists.j_countries', 'journalists.j_media_types', 'journalists.j_topics',
            'outlets.j_countries', 'outlets.j_media_types', 'outlets.j_topics',
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('contact_list', 'name')],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $list = ContactList::create($data + ['user_id' => $request->user()->id]);

        return response()->json(['message' => 'Contact list created.', 'data' => $list], 201);
    }

    public function update(Request $request, ContactList $contactList): JsonResponse
    {
        $this->authorizeList($request, $contactList);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('contact_list', 'name')->ignore($contactList->id)],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
        $contactList->update($data);

        return response()->json(['message' => 'Contact list updated.', 'data' => $contactList]);
    }

    public function destroy(Request $request, ContactList $contactList): JsonResponse
    {
        $this->authorizeList($request, $contactList);
        DB::transaction(function () use ($contactList): void {
            DB::table('contact_list_journalist_ref')->where('contact_list_id', $contactList->id)->delete();
            DB::table('contact_list_outlet_ref')->where('contact_list_id', $contactList->id)->delete();
            $contactList->delete();
        });

        return response()->json(['message' => 'Contact list deleted.']);
    }

    public function detach(Request $request, ContactList $contactList): JsonResponse
    {
        $this->authorizeList($request, $contactList);
        $data = $request->validate(['kind' => ['required', 'in:journalist,outlet'], 'record_id' => ['required', 'integer']]);
        $table = $data['kind'] === 'journalist' ? 'contact_list_journalist_ref' : 'contact_list_outlet_ref';
        $column = $data['kind'] === 'journalist' ? 'journalist_id' : 'outlet_id';
        DB::table($table)->where('contact_list_id', $contactList->id)->where($column, $data['record_id'])->delete();

        return response()->json(['message' => 'Contact removed.']);
    }

    private function authorizeList(Request $request, ContactList $contactList): void
    {
        abort_unless($contactList->user_id === $request->user()->id, 404);
    }
}
