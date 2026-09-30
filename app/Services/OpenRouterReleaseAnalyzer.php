<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenRouterReleaseAnalyzer
{
    public function analyze(string $subject, string $content): array
    {
        $key = (string) config('services.openrouter.key');
        if ($key === '') {
            throw new RuntimeException('OpenRouter is not configured. Add OPENROUTER_API_KEY to the server environment.');
        }

        $response = Http::withToken($key)
            ->acceptJson()
            ->asJson()
            ->timeout(60)
            ->retry(2, 500)
            ->withHeaders([
                'HTTP-Referer' => (string) config('app.url'),
                'X-Title' => (string) config('app.name', 'LetraPress'),
            ])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => config('services.openrouter.model', 'openai/gpt-5.6-luna'),
                'temperature' => 0.2,
                'max_tokens' => 900,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a book publicity editor. Analyze author pitches and press releases for media discovery. Return practical subject keywords, not vague marketing adjectives. Prefer genres, themes, settings, professions, technologies, cultural issues, formats, and event types that could correspond to a media-directory topic. Do not force every phrase to match a database category.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Headline:\n{$subject}\n\nCopy:\n".strip_tags($content),
                    ],
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'book_publicity_analysis',
                        'strict' => true,
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'summary' => ['type' => 'string'],
                                'release_type' => ['type' => 'string', 'enum' => ['pitch', 'release']],
                                'keywords' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 5, 'maxItems' => 16],
                                'media_angles' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 2, 'maxItems' => 5],
                            ],
                            'required' => ['summary', 'release_type', 'keywords', 'media_angles'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
            ]);

        try {
            $response->throw();
        } catch (RequestException $exception) {
            throw new RuntimeException('OpenRouter could not analyze this copy right now.', 0, $exception);
        }

        $content = $response->json('choices.0.message.content');
        if (is_array($content)) {
            $content = collect($content)->pluck('text')->filter()->implode('');
        }

        $analysis = json_decode((string) $content, true);
        if (! is_array($analysis)) {
            throw new RuntimeException('OpenRouter returned an unreadable analysis. Please try again.');
        }

        return [
            'summary' => trim((string) ($analysis['summary'] ?? '')),
            'release_type' => in_array($analysis['release_type'] ?? null, ['pitch', 'release'], true) ? $analysis['release_type'] : 'release',
            'keywords' => collect($analysis['keywords'] ?? [])->map(fn ($keyword) => trim((string) $keyword))->filter()->unique()->take(16)->values()->all(),
            'media_angles' => collect($analysis['media_angles'] ?? [])->map(fn ($angle) => trim((string) $angle))->filter()->take(5)->values()->all(),
        ];
    }
}
