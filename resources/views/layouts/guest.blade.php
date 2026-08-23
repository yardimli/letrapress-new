<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'LetraPress'))</title>
    <script>if (localStorage.getItem('letrapress-theme') === 'dark' || (!localStorage.getItem('letrapress-theme') && matchMedia('(prefers-color-scheme: dark)').matches)) document.documentElement.classList.add('dark')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="paper-texture">
    <header class="masthead">
        <div class="mx-auto flex min-h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            @include('partials.logo')
            <div class="flex items-center gap-3">@include('partials.demo-link', ['class' => 'button-secondary'])<button class="button-quiet no-underline" type="button" data-theme-toggle aria-label="Toggle color theme">◐</button></div>
        </div>
    </header>
    <main class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-6xl place-items-center px-4 py-12 sm:px-6">
        <section class="w-full max-w-md">
            @yield('content')
        </section>
    </main>
</body>
</html>
