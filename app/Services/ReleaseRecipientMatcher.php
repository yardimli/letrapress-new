<?php

namespace App\Services;

use App\Models\Journalist;
use App\Models\Outlet;
use App\Models\prowly_topics;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReleaseRecipientMatcher
{
    public function recommendations(array $keywords, int $userId): array
    {
        $topicScores = $this->topicScores($keywords);
        if ($topicScores->isEmpty()) {
            return ['matched_topics' => [], 'journalists' => [], 'outlets' => []];
        }

        $topicIds = $topicScores->keys()->all();

        $journalists = Journalist::query()
            ->whereIn('user_id', [1, $userId])
            ->whereHas('j_topics', fn ($query) => $query->whereIn('prowly_topics.id', $topicIds))
            ->with(['j_topics', 'j_title', 'j_countries'])
            ->orderByDesc('influence_score')->limit(80)->get()
            ->map(fn (Journalist $record) => $this->shape($record, 'journalist', $topicScores))
            ->sortByDesc('match_score')->take(12)->values();

        $outlets = Outlet::query()
            ->whereIn('user_id', [1, $userId])
            ->whereHas('j_topics', fn ($query) => $query->whereIn('prowly_topics.id', $topicIds))
            ->with(['j_topics', 'j_media_types', 'j_countries'])
            ->orderByDesc('influence_score')->limit(80)->get()
            ->map(fn (Outlet $record) => $this->shape($record, 'outlet', $topicScores))
            ->sortByDesc('match_score')->take(12)->values();

        return [
            'matched_topics' => $topicScores->map(fn ($score, $id) => [
                'id' => (int) $id,
                'topic' => prowly_topics::query()->whereKey($id)->value('topic'),
                'score' => $score,
            ])->values()->all(),
            'journalists' => $journalists->all(),
            'outlets' => $outlets->all(),
        ];
    }

    private function topicScores(array $keywords): Collection
    {
        $normalizedKeywords = collect($keywords)->map(fn ($keyword) => $this->normalize($keyword))->filter();

        return prowly_topics::query()->whereNotNull('topic')->get(['id', 'topic'])
            ->mapWithKeys(function ($topic) use ($normalizedKeywords) {
                $normalizedTopic = $this->normalize($topic->topic);
                $score = $normalizedKeywords->map(fn ($keyword) => $this->similarity($keyword, $normalizedTopic))->max() ?? 0;

                return $score >= 35 ? [$topic->id => $score] : [];
            })
            ->sortDesc()->take(30);
    }

    private function shape($record, string $kind, Collection $topicScores): array
    {
        $matched = $record->j_topics->filter(fn ($topic) => $topicScores->has($topic->id))
            ->sortByDesc(fn ($topic) => $topicScores->get($topic->id))->values();
        $topicScore = (int) ($matched->max(fn ($topic) => $topicScores->get($topic->id)) ?? 0);
        $influenceBonus = min(10, (int) floor(((int) $record->influence_score) / 100));

        return [
            'kind' => $kind,
            'id' => $record->id,
            'name' => $kind === 'journalist' ? $record->journalist_name : $record->outlet_name,
            'role' => $kind === 'journalist' ? $record->j_title?->title : $record->j_media_types?->outlet_type,
            'country' => $record->j_countries?->country,
            'influence_score' => $record->influence_score,
            'match_score' => min(100, $topicScore + $influenceBonus),
            'match_reason' => 'Matched '.Str::limit($matched->pluck('topic')->implode(', '), 150),
            'matched_topics' => $matched->pluck('topic')->all(),
        ];
    }

    private function normalize(string $value): string
    {
        return trim(Str::of($value)->lower()->ascii()->replaceMatches('/[^a-z0-9]+/', ' ')->squish()->value());
    }

    private function similarity(string $keyword, string $topic): int
    {
        if ($keyword === $topic) {
            return 100;
        }
        if (Str::contains($keyword, $topic) || Str::contains($topic, $keyword)) {
            return 82;
        }

        $left = collect(explode(' ', $keyword))->filter(fn ($word) => strlen($word) > 2)->unique();
        $right = collect(explode(' ', $topic))->filter(fn ($word) => strlen($word) > 2)->unique();
        $union = $left->merge($right)->unique()->count();

        return $union ? (int) round(($left->intersect($right)->count() / $union) * 100) : 0;
    }
}
