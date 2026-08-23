@extends('layouts.guest')
@section('title', 'Reset password — LetraPress')
@section('content')
<div class="panel"><p class="eyebrow">Account recovery</p><h1 class="page-title mt-2">Reset your password</h1><p class="mt-3 leading-6 text-stone-600 dark:text-stone-300">Enter the email tied to your account and we will send a secure reset link.</p>@if (session('status'))<div class="flash mt-5">{{ session('status') }}</div>@endif<form class="mt-7 space-y-5" method="POST" action="{{ route('password.email') }}">@csrf<div><label class="label" for="email">Email address</label><input class="field" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>@error('email')<p class="field-error">{{ $message }}</p>@enderror</div><button class="button-primary w-full" type="submit">Email the reset link</button></form></div><p class="mt-6 text-center"><a class="button-quiet" href="{{ route('login') }}">Back to log in</a></p>
@endsection
