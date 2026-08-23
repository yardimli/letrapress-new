<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LetraPress — The independent press desk')</title>
    <meta name="description" content="@yield('description', 'A focused press outreach desk for authors, publishers, and thoughtful marketing teams.')">
    <script>if (localStorage.getItem('letrapress-theme') === 'dark' || (!localStorage.getItem('letrapress-theme') && matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="paper-texture flex min-h-screen flex-col">
<header class="masthead sticky top-0 z-40">
    <div class="mx-auto flex min-h-20 max-w-6xl flex-wrap items-center justify-between gap-x-6 gap-y-3 px-4 py-3 sm:px-6">
        @include('partials.logo')
        <nav class="order-3 flex w-full items-center gap-4 overflow-x-auto sm:order-none sm:w-auto" aria-label="Main navigation">
            <a class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="nav-link {{ request()->routeIs('marketing.how-it-works') ? 'nav-link-active' : '' }}" href="{{ route('marketing.how-it-works') }}">How it works</a>
            <a class="nav-link {{ request()->routeIs('marketing.documentation') ? 'nav-link-active' : '' }}" href="{{ route('marketing.documentation') }}">Resources</a>
            <a class="nav-link {{ request()->routeIs('marketing.about') ? 'nav-link-active' : '' }}" href="{{ route('marketing.about') }}">About</a>
        </nav>
        <div class="flex items-center gap-3">
            <button class="button-quiet no-underline" type="button" data-theme-toggle aria-label="Toggle color theme" title="Toggle light and dark mode">◐</button>
            @auth
                <a class="button-secondary" href="{{ route('dashboard') }}">Open desk</a>
            @else
                <a class="button-quiet hidden sm:inline-flex" href="{{ route('login') }}">Log in</a>
                @include('partials.demo-link', ['class' => 'button-secondary px-3'])
            @endauth
        </div>
    </div>
</header>
<main class="flex-1">@yield('content')</main>
<footer class="border-t-4 border-double border-stone-800 bg-[#e7ddc8] dark:border-stone-300 dark:bg-stone-900">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-[1fr_auto] md:items-end">
        <div>
            @include('partials.logo')
            <p class="mt-4 max-w-xl text-sm leading-6 text-stone-600 dark:text-stone-300">A focused press desk for authors and publishers who prefer careful research, clear writing, and lasting relationships.</p>
        </div>
        <div class="flex flex-wrap gap-x-5 gap-y-3 text-sm font-bold">
            <a class="button-quiet" href="{{ route('marketing.how-it-works') }}">How it works</a>
            <a class="button-quiet" href="{{ route('marketing.documentation') }}">Resources</a>
            <a class="button-quiet" href="{{ route('marketing.about') }}">About</a>
            <a class="button-quiet" href="{{ route('login') }}">Account</a>
        </div>
    </div>
    <p class="border-t border-stone-500 px-4 py-4 text-center text-xs font-bold uppercase tracking-[.16em] dark:border-stone-700">© {{ date('Y') }} LetraPress · The independent press desk</p>
</footer>
</body>
</html>
