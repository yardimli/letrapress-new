<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.favicon')
    <title>{{ $newsRoom->subject }} — {{ $user->name }}</title>
    <script>if (localStorage.getItem('letrapress-theme') === 'dark' || (!localStorage.getItem('letrapress-theme') && matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="paper-texture flex min-h-screen flex-col">
<header class="masthead"><div class="mx-auto flex min-h-16 max-w-4xl items-center justify-between gap-3 px-4 sm:px-6"><div class="flex min-w-0 items-center gap-4">@include('partials.logo')<a class="hidden border-l border-stone-500 pl-4 text-sm font-black sm:inline" href="{{ route('newsroom.public', $user->newsroom_slug) }}">{{ $user->name }}</a></div><div class="flex items-center gap-3">@include('partials.demo-link', ['class' => 'button-secondary'])<button class="button-quiet no-underline" type="button" data-theme-toggle aria-label="Toggle color theme">◐</button></div></div></header>
<main class="mx-auto w-full max-w-3xl flex-1 px-4 py-14 sm:px-6"><a class="button-quiet" href="{{ route('newsroom.public', $user->newsroom_slug) }}">← All newsroom stories</a><article class="mt-10"><p class="eyebrow">{{ $newsRoom->date ?: optional($newsRoom->created_at)->format('F j, Y') }}</p><h1 class="mt-4 text-4xl font-black leading-tight sm:text-6xl">{{ $newsRoom->subject }}</h1>@if($newsRoom->summary)<p class="lede mt-6 border-l-4 border-claret pl-5">{{ $newsRoom->summary }}</p>@endif @if($newsRoom->featured_image)<img class="my-9 w-full border border-stone-600" src="{{ $newsRoom->featured_image }}" alt="Artwork for {{ $newsRoom->subject }}">@endif<div class="mt-8 whitespace-pre-line text-lg leading-8">{{ $newsRoom->content }}</div></article></main>
@include('partials.newsroom-footer', ['user' => $user])
</body>
</html>
