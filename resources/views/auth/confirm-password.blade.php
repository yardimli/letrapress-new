@extends('layouts.guest')
@section('title', 'Confirm password — LetraPress')
@section('content')
<div class="panel"><p class="eyebrow">Private account action</p><h1 class="page-title mt-2">Confirm your password</h1><p class="mt-3 text-stone-600 dark:text-stone-300">Please confirm your password before continuing.</p><form class="mt-7 space-y-5" method="POST" action="{{ route('password.confirm') }}">@csrf<div><label class="label" for="password">Password</label><input class="field" id="password" name="password" type="password" required autocomplete="current-password">@error('password')<p class="field-error">{{ $message }}</p>@enderror</div><button class="button-primary w-full" type="submit">Confirm</button></form></div>
@endsection
