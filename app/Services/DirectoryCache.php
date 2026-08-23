<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DirectoryCache
{
    public function filterCounts(int $userId, string $kind): array
    {
        $path = $this->basePath($userId, $kind).'/filter-counts.json';
        if ($cached = $this->read($path)) {
            return $cached;
        }

        $recordTable = $kind === 'journalist' ? 'prowly_journalists' : 'prowly_outlets';
        $topicPivot = $kind === 'journalist' ? 'prowly_topic_list' : 'prowly_outlet_topic_list';
        $topicForeign = $kind === 'journalist' ? 'journalist_id' : 'outlet_id';
        $languagePivot = $kind === 'journalist' ? 'prowly_language_list' : 'prowly_outlet_language_list';
        $languageForeign = $kind === 'journalist' ? 'journalist_id' : 'outlet_id';
        $userIds = array_values(array_unique([1, $userId]));

        $countryCounts = $this->columnCounts($recordTable, 'country_id', $userIds);
        $mediaCounts = $this->columnCounts($recordTable, 'media_type_id', $userIds);
        $topicCounts = $this->pivotCounts($recordTable, $topicPivot, $topicForeign, 'topic_id', $userIds);
        $languageCounts = $this->pivotCounts($recordTable, $languagePivot, $languageForeign, 'language_id', $userIds);

        $data = [
            'countries' => $this->labels('prowly_countries', 'country', $countryCounts),
            'media_types' => $this->labels('prowly_outlet_types', 'outlet_type', $mediaCounts),
            'topics' => $this->labels('prowly_topics', 'topic', $topicCounts),
            'languages' => $this->labels('prowly_languages', 'language', $languageCounts),
            'generated_at' => now()->toIso8601String(),
        ];

        $this->write($path, $data);

        return $data;
    }

    public function resultPath(int $userId, string $kind, Request $request, int $perPage, int $page, string $sort): string
    {
        $value = fn (string $key) => $request->integer($key) ?: 'all';
        $search = $request->string('search')->trim()->value();
        $searchKey = $search === '' ? 'none' : substr(sha1(mb_strtolower($search)), 0, 12);
        $sortKey = str_replace('_', '-', $sort);

        $filename = sprintf(
            'country-%s_media-%s_topic-%s_language-%s_pagesize-%d_page-%d_sort-%s_search-%s.json',
            $value('country_id'),
            $value('media_type_id'),
            $value('topic_id'),
            $value('language_id'),
            $perPage,
            $page,
            $sortKey,
            $searchKey,
        );

        return $this->basePath($userId, $kind).'/results/'.$filename;
    }

    public function readResult(string $path): ?array
    {
        return $this->read($path);
    }

    public function writeResult(string $path, array $data): void
    {
        $this->write($path, $data + ['cached_at' => now()->toIso8601String()]);
    }

    private function columnCounts(string $table, string $column, array $userIds): array
    {
        return DB::table($table)->whereIn('user_id', $userIds)->where($column, '>', 0)
            ->selectRaw($column.', COUNT(*) as aggregate')->groupBy($column)
            ->pluck('aggregate', $column)->map(fn ($count) => (int) $count)->all();
    }

    private function pivotCounts(string $recordTable, string $pivotTable, string $recordForeign, string $filterForeign, array $userIds): array
    {
        return DB::table($pivotTable.' as pivot')->join($recordTable.' as records', 'records.id', '=', 'pivot.'.$recordForeign)
            ->whereIn('records.user_id', $userIds)
            ->selectRaw('pivot.'.$filterForeign.', COUNT(DISTINCT records.id) as aggregate')
            ->groupBy('pivot.'.$filterForeign)
            ->pluck('aggregate', $filterForeign)->map(fn ($count) => (int) $count)->all();
    }

    private function labels(string $table, string $labelColumn, array $counts): array
    {
        if ($counts === []) {
            return [];
        }

        $items = DB::table($table)->whereIn('id', array_keys($counts))->get(['id', $labelColumn])
            ->map(fn ($item) => ['id' => (int) $item->id, 'label' => $item->{$labelColumn}, 'count' => $counts[$item->id] ?? 0])
            ->filter(fn ($item) => $item['count'] > 0 && filled($item['label']))->values()->all();

        usort($items, fn ($a, $b) => $b['count'] <=> $a['count'] ?: strcasecmp($a['label'], $b['label']));

        return $items;
    }

    private function basePath(int $userId, string $kind): string
    {
        return 'directory-cache/'.app()->environment().'/user-'.$userId.'/'.$kind;
    }

    private function read(string $path): ?array
    {
        if (! Storage::disk('local')->exists($path)) {
            return null;
        }

        $decoded = json_decode(Storage::disk('local')->get($path), true);

        return is_array($decoded) ? $decoded : null;
    }

    private function write(string $path, array $data): void
    {
        Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
