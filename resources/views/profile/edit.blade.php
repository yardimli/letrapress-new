@extends('layouts.app')
@section('title', 'Account')
@section('page', 'profile')
@section('content')
<div class="mb-10 max-w-3xl"><p class="eyebrow">Breeze account</p><h1 class="page-title mt-2">Your account</h1><p class="lede mt-3">The essentials only: identity, public newsroom address, password, and account control.</p></div>
@if (session('status') === 'profile-updated')<div class="flash max-w-3xl">Profile details updated.</div>@endif
@if (session('status') === 'password-updated')<div class="flash max-w-3xl">Password updated.</div>@endif
@if ($errors->has('demo'))<div class="error-box max-w-3xl">{{ $errors->first('demo') }}</div>@endif
<div class="grid max-w-5xl gap-7 lg:grid-cols-2">
    <section class="panel">
        <p class="eyebrow">Identity</p><h2 class="mt-2 text-2xl font-black">Profile details</h2>
        <form class="mt-6 space-y-5" method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')
            <div><label class="label" for="name">Name</label><input class="field" id="name" name="name" value="{{ old('name', $user->name) }}" required>@error('name')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="email">Email address</label><input class="field" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>@error('email')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div>
                <label class="label" for="newsroom_slug">Newsroom URL</label>
                <div class="flex items-stretch"><span class="inline-flex items-center border border-r-0 border-stone-500 bg-stone-200 px-3 text-sm text-stone-600 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300">{{ url('/newsroom') }}/</span><input class="field min-w-0" id="newsroom_slug" name="newsroom_slug" value="{{ old('newsroom_slug', $user->newsroom_slug) }}" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" required></div>
                <p class="mt-2 text-sm text-stone-600 dark:text-stone-300">Use lowercase letters, numbers, and single hyphens.</p>
                @error('newsroom_slug')<p class="field-error">{{ $message }}</p>@enderror
                <a class="button-quiet mt-2" href="{{ route('newsroom.public', $user->newsroom_slug) }}" target="_blank">View public newsroom</a>
            </div>
            <button class="button-primary" type="submit">Save profile</button>
        </form>
    </section>
    <section class="panel">
        <p class="eyebrow">Security</p><h2 class="mt-2 text-2xl font-black">Change password</h2>
        <form class="mt-6 space-y-5" method="POST" action="{{ route('password.update') }}">
            @csrf @method('PUT')
            <div><label class="label" for="current_password">Current password</label><input class="field" id="current_password" name="current_password" type="password" autocomplete="current-password">@error('current_password', 'updatePassword')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="password">New password</label><input class="field" id="password" name="password" type="password" autocomplete="new-password">@error('password', 'updatePassword')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="password_confirmation">Repeat password</label><input class="field" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"></div>
            <button class="button-primary" type="submit">Update password</button>
        </form>
    </section>
    <section class="panel border-red-900 lg:col-span-2">
        <p class="eyebrow">Permanent action</p><h2 class="mt-2 text-2xl font-black">Delete account</h2><p class="mt-3 max-w-2xl text-stone-600 dark:text-stone-300">This permanently removes your account. Enter your password to confirm.</p>
        <form class="mt-6 flex flex-col gap-3 sm:flex-row" method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Permanently delete this account?')">@csrf @method('DELETE')<div class="flex-1"><label class="sr-only" for="delete_password">Password</label><input class="field" id="delete_password" name="password" type="password" placeholder="Current password" required>@error('password', 'userDeletion')<p class="field-error">{{ $message }}</p>@enderror</div><button class="button border-red-900 bg-red-900 text-white hover:bg-red-800" type="submit">Delete account</button></form>
    </section>
</div>
@endsection
