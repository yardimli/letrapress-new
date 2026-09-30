@extends('layouts.app')
@section('title', 'Pitches & releases')
@section('page', 'releases')
@section('content')
<div id="releases-app" data-index-url="{{ route('press-releases.index') }}" data-edit-base="{{ url('/apps-press-releases') }}">
    <div class="mb-9 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div><p class="eyebrow">Outreach copy desk</p><h1 class="page-title mt-2">Pitches &amp; releases</h1><p class="lede mt-3 max-w-3xl">Write once, then choose the right life for the story: a personal media pitch, a public newsroom release, or both.</p></div>
        <a class="button-primary" href="{{ route('releases.start') }}">New release</a>
    </div>
    <div class="mb-7 grid gap-px border border-stone-500 bg-stone-500 sm:grid-cols-3">
        <div class="bg-paper p-4 dark:bg-stone-900"><p class="eyebrow">01 · Write</p><p class="mt-2 text-sm">Begin with an author template or a blank page.</p></div>
        <div class="bg-paper p-4 dark:bg-stone-900"><p class="eyebrow">02 · Match</p><p class="mt-2 text-sm">Analyze the copy and find relevant media topics.</p></div>
        <div class="bg-paper p-4 dark:bg-stone-900"><p class="eyebrow">03 · Use</p><p class="mt-2 text-sm">Target recipients, publish to the newsroom, or do both.</p></div>
    </div>
    <div class="panel-flat">
        <div class="grid gap-3 border-b border-stone-400 p-4 sm:grid-cols-[1fr_14rem]"><div><label class="label" for="release-search">Search copy</label><input class="field" id="release-search" placeholder="Search headlines…"></div><div><label class="label" for="release-folder-filter">Drawer</label><select class="field" id="release-folder-filter"><option value="">All drawers</option>@foreach($folders as $folder)<option value="{{ $folder->id }}">{{ $folder->folder_name }}</option>@endforeach</select></div></div>
        <div class="overflow-x-auto"><table class="w-full min-w-[58rem]"><thead><tr class="table-head"><th class="p-4">Headline</th><th class="p-4">Use</th><th class="p-4">Recipients</th><th class="p-4">Status</th><th class="p-4">Revised</th><th class="p-4 text-right">Actions</th></tr></thead><tbody id="release-rows" class="divide-y divide-stone-300 dark:divide-stone-700"></tbody></table></div>
        <div id="release-pagination" class="border-t border-stone-400 p-4"></div>
    </div>
</div>
@endsection
