@extends('layouts.guest')
@section('title', 'Verify email — LetraPress')
@section('content')
<div class="panel"><p class="eyebrow">One last detail</p><h1 class="page-title mt-2">Verify your email</h1><p class="mt-3 leading-6 text-stone-600 dark:text-stone-300">Use the link we sent to your inbox. If it did not arrive, request another below.</p>@if (session('status') === 'verification-link-sent')<div class="flash mt-5">A fresh verification link has been sent.</div>@endif<form class="mt-7" method="POST" action="{{ route('verification.send') }}">@csrf<button class="button-primary w-full" type="submit">Send another link</button></form><form class="mt-4 text-center" method="POST" action="{{ route('logout') }}">@csrf<button class="button-quiet" type="submit">Log out</button></form></div>
@endsection
