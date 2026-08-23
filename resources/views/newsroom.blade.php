<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $user->name }} — Newsroom</title>
    <script>if (localStorage.getItem('letrapress-theme') === 'dark' || (!localStorage.getItem('letrapress-theme') && matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="paper-texture flex min-h-screen flex-col">
<header class="masthead"><div class="mx-auto flex min-h-16 max-w-5xl items-center justify-between gap-3 px-4 sm:px-6"><div class="flex min-w-0 items-center gap-4">@include('partials.logo')<a class="hidden border-l border-stone-500 pl-4 text-sm font-black sm:inline" href="{{ route('newsroom.public', $user->newsroom_slug) }}">{{ $user->name }}</a></div><div class="flex items-center gap-3">@include('partials.demo-link', ['class' => 'button-secondary'])<button class="button-quiet no-underline" type="button" data-theme-toggle aria-label="Toggle color theme">◐</button></div></div></header>
<main class="mx-auto w-full max-w-5xl flex-1 px-4 py-14 sm:px-6">
    <p class="eyebrow">Official newsroom</p><h1 class="display-title mt-4">{{ $user->name }}</h1>
    <div class="mt-10 grid gap-6 md:grid-cols-2">
        @foreach($rooms as $room)<article class="panel">@if($room->featured_image)<img class="mb-5 aspect-[16/9] w-full border border-stone-500 object-cover" src="{{ $room->featured_image }}" alt="Artwork for {{ $room->subject }}">@endif<p class="eyebrow">{{ $room->date ?: optional($room->created_at)->format('F j, Y') }}</p><h2 class="mt-2 text-2xl font-black"><a class="hover:text-claret" href="{{ route('newsroom.show', [$user->newsroom_slug, $room]) }}">{{ $room->subject }}</a></h2><p class="mt-3 leading-7 text-stone-600 dark:text-stone-300">{{ $room->summary }}</p><a class="button-quiet mt-5" href="{{ route('newsroom.show', [$user->newsroom_slug, $room]) }}">Read story</a></article>@endforeach
    </div>
</main>
@include('partials.newsroom-footer', ['user' => $user])
</body>
</html>
