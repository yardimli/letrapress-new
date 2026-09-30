<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Desk') — {{ config('app.name', 'LetraPress') }}</title>
    <script>if (localStorage.getItem('letrapress-theme') === 'dark' || (!localStorage.getItem('letrapress-theme') && matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="paper-texture flex min-h-screen flex-col" data-page="@yield('page')" data-readonly="{{ auth()->user()->is_demo ? 'true' : 'false' }}">
    <header class="masthead sticky top-0 z-30">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex min-h-16 flex-wrap items-center justify-between gap-3 py-2">
                @include('partials.logo', ['href' => route('journalists.page')])
                <nav class="order-3 flex w-full gap-4 overflow-x-auto border-t border-stone-400 sm:order-2 sm:w-auto sm:border-0" aria-label="Workspace">
                    <a class="nav-link {{ request()->routeIs('journalists.page') ? 'nav-link-active' : '' }}" href="{{ route('journalists.page') }}">Journalists</a>
                    <a class="nav-link {{ request()->routeIs('outlets.page') ? 'nav-link-active' : '' }}" href="{{ route('outlets.page') }}">Outlets</a>
                    <a class="nav-link {{ request()->routeIs('contacts.page') ? 'nav-link-active' : '' }}" href="{{ route('contacts.page') }}">Lists</a>
                    <a class="nav-link {{ request()->routeIs('releases.page') ? 'nav-link-active' : '' }}" href="{{ route('releases.page') }}">Releases</a>
                    <a class="nav-link {{ request()->routeIs('newsrooms.page') ? 'nav-link-active' : '' }}" href="{{ route('newsrooms.page') }}">Newsrooms</a>
                </nav>
                <div class="order-2 flex items-center gap-3 sm:order-3">
                    @if(auth()->user()->is_demo)<a class="badge hidden lg:inline-flex" href="{{ route('journalists.page') }}">Read-only demo</a>@else @include('partials.demo-link', ['class' => 'button-quiet hidden lg:inline-flex']) @endif
                    <button class="button-quiet no-underline" type="button" data-theme-toggle aria-label="Toggle color theme">◐</button>
                    <a class="text-sm font-bold" href="{{ route('profile.edit') }}">{{ Str::limit(auth()->user()->name, 18) }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="button-quiet" type="submit">Log out</button></form>
                </div>
            </div>
        </div>
    </header>
    @if(auth()->user()->is_demo)
        <div class="border-b border-amber-800 bg-amber-100 px-4 py-2 text-center text-sm font-bold text-amber-950 dark:border-amber-300 dark:bg-amber-950 dark:text-amber-100" role="status">
            You are exploring Dr. Elara Voss’s demo press desk. Everything is read-only; no changes can be saved.
        </div>
    @endif
    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 sm:py-12">
        @yield('content')
    </main>
    @include('partials.app-footer')
    <div id="toast" class="fixed bottom-5 right-5 z-50 hidden max-w-sm border border-stone-900 bg-stone-950 px-4 py-3 text-sm font-bold text-white shadow-xl" role="status"></div>
</body>
</html>
