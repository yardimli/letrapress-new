<?php

namespace App\Http\Controllers;

use App\Models\ContactList;
use App\Models\Journalist;
use App\Models\Outlet;
use App\Models\contact_list_journalist_ref;
use App\Models\contact_list_outlet_ref;
use App\Services\DirectoryCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    private const RESULT_LIMIT = 990;

    public function __construct(private readonly DirectoryCache $cache)
    {
    }

    public function journalistsPage(Request $request): View
    {
        return view('apps-journalist-list', $this->pageData($request, 'journalist'));
    }

    public function outletsPage(Request $request): View
    {
        return view('apps-outlet-list', $this->pageData($request, 'outlet'));
    }

    public function journalists(Request $request): JsonResponse
    {
        $query = Journalist::query()->with(['j_title', 'j_cities', 'j_countries', 'j_media_types', 'j_topics', 'j_languages'])
            ->whereIn('user_id', [1, $request->user()->id]);

        $this->applyFilters($query, $request, 'journalist_name');

        return response()->json($this->paginateDirectory($query, $request, 'journalist_name'));
    }

    public function outlets(Request $request): JsonResponse
    {
        $query = Outlet::query()->with(['j_cities', 'j_countries', 'j_media_types', 'j_topics', 'j_languages'])
            ->whereIn('user_id', [1, $request->user()->id]);

        $this->applyFilters($query, $request, 'outlet_name');

        return response()->json($this->paginateDirectory($query, $request, 'outlet_name'));
    }

    public function addToList(Request $request): JsonResponse
    {
        $data = $request->validate([
            'contact_list_id' => ['required', 'integer'],
            'kind' => ['required', 'in:journalist,outlet'],
            'record_id' => ['required', 'integer'],
        ]);

        ContactList::query()->where('user_id', $request->user()->id)->findOrFail($data['contact_list_id']);

        if ($data['kind'] === 'journalist') {
            Journalist::query()->whereIn('user_id', [1, $request->user()->id])->findOrFail($data['record_id']);
            contact_list_journalist_ref::firstOrCreate([
                'contact_list_id' => $data['contact_list_id'],
                'journalist_id' => $data['record_id'],
            ]);
        } else {
            Outlet::query()->whereIn('user_id', [1, $request->user()->id])->findOrFail($data['record_id']);
            contact_list_outlet_ref::firstOrCreate([
                'contact_list_id' => $data['contact_list_id'],
                'outlet_id' => $data['record_id'],
            ]);
        }

        return response()->json(['message' => 'Added to contact list.']);
    }

    private function pageData(Request $request, string $kind): array
    {
        $counts = $this->cache->filterCounts($request->user()->id, $kind);

        return [
            'contactLists' => ContactList::query()->where('user_id', $request->user()->id)->orderBy('name')->get(),
            'countries' => $this->filterObjects($counts['countries'], 'country'),
            'languages' => $this->filterObjects($counts['languages'], 'language'),
            'topics' => $this->filterObjects($counts['topics'], 'topic'),
            'mediaTypes' => $this->filterObjects($counts['media_types'], 'outlet_type'),
        ];
    }

    private function applyFilters(Builder $query, Request $request, string $nameColumn): void
    {
        $query->when($request->string('search')->trim()->value(), fn (Builder $q, string $search) => $q->where($nameColumn, 'like', "%{$search}%"))
            ->when($request->integer('country_id'), fn (Builder $q, int $id) => $q->where('country_id', $id))
            ->when($request->integer('media_type_id'), fn (Builder $q, int $id) => $q->where('media_type_id', $id))
            ->when($request->integer('topic_id'), fn (Builder $q, int $id) => $q->whereHas('j_topics', fn (Builder $relation) => $relation->where('prowly_topics.id', $id)))
            ->when($request->integer('language_id'), fn (Builder $q, int $id) => $q->whereHas('j_languages', fn (Builder $relation) => $relation->where('prowly_languages.id', $id)));
    }

    private function paginateDirectory(Builder $query, Request $request, string $nameColumn): array
    {
        $request->validate([
            'per_page' => ['nullable', 'integer', 'in:12,24,48,99'],
            'sort' => ['nullable', 'in:score_desc,score_asc,name_asc,name_desc'],
        ]);

        $perPage = $request->integer('per_page') ?: 24;
        $sort = $request->string('sort')->value() ?: 'score_desc';

        match ($sort) {
            'score_asc' => $query->orderBy('influence_score')->orderBy($nameColumn),
            'name_asc' => $query->orderBy($nameColumn)->orderByDesc('influence_score'),
            'name_desc' => $query->orderByDesc($nameColumn)->orderByDesc('influence_score'),
            default => $query->orderByDesc('influence_score')->orderBy($nameColumn),
        };
        $query->orderBy('id');

        $maximumPage = max(1, (int) ceil(self::RESULT_LIMIT / $perPage));
        $page = min(max(1, $request->integer('page') ?: 1), $maximumPage);
        $kind = $nameColumn === 'journalist_name' ? 'journalist' : 'outlet';
        $cachePath = $this->cache->resultPath($request->user()->id, $kind, $request, $perPage, $page, $sort);
        $cached = $this->cache->readResult($cachePath);

        if ($cached) {
            $recordsTotal = (int) $cached['records_total'];
            $cappedTotal = min($recordsTotal, self::RESULT_LIMIT);
            $page = (int) $cached['page'];
            $items = collect($cached['items']);
        } else {
            $recordsTotal = (clone $query)->reorder()->count();
            $cappedTotal = min($recordsTotal, self::RESULT_LIMIT);
            $lastPage = max(1, (int) ceil($cappedTotal / $perPage));
            $page = min($page, $lastPage);
            $offset = ($page - 1) * $perPage;
            $take = min($perPage, max(0, self::RESULT_LIMIT - $offset));
            $items = $take > 0 ? $query->offset($offset)->limit($take)->get() : collect();
            $this->cache->writeResult($cachePath, [
                'records_total' => $recordsTotal,
                'page' => $page,
                'items' => $items->toArray(),
            ]);
        }

        $paginator = new LengthAwarePaginator($items, $cappedTotal, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->except('page'),
        ]);

        return $paginator->toArray() + [
            'records_total' => $recordsTotal,
            'result_limit' => self::RESULT_LIMIT,
            'cache_hit' => (bool) $cached,
        ];
    }

    private function filterObjects(array $items, string $labelKey)
    {
        return collect($items)->map(fn ($item) => (object) [
            'id' => $item['id'],
            $labelKey => $item['label'],
            'record_count' => $item['count'],
        ]);
    }
}
