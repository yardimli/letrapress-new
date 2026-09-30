@extends('layouts.app')
@section('title', $release ? 'Edit pitch or release' : 'Write a pitch or release')
@section('page', 'releases')
@section('content')
@php
    $subject = $release?->subject ?? $template['subject'];
    $content = $release?->content ?? $template['content'];
    $releaseType = $release?->release_type ?? $template['type'];
    $distribution = $release?->distribution ?? 'both';
@endphp
<div id="release-editor" data-save-url="{{ $release ? route('press-releases.update', $release) : route('press-releases.store') }}" data-save-method="{{ $release ? 'PUT' : 'POST' }}" data-analyze-url="{{ route('press-releases.analyze') }}" data-list-url="{{ route('releases.page') }}" data-readonly="{{ auth()->user()->is_demo ? 'true' : 'false' }}">
    <script id="release-analysis-data" type="application/json">@json($release?->analysis)</script>
    <script id="release-recipient-data" type="application/json">@json($selectedRecipients)</script>
    <div class="mb-8 flex flex-col justify-between gap-4 border-b-4 border-double border-stone-800 pb-6 dark:border-stone-300 sm:flex-row sm:items-end"><div><a class="button-quiet" href="{{ route('releases.page') }}">← Back to pitches &amp; releases</a><p class="eyebrow mt-5">{{ $release ? 'Revise the copy' : 'New from template' }}</p><h1 class="page-title mt-2">{{ $release ? 'Edit pitch or release' : $template['name'] }}</h1></div><div class="flex gap-3"><span class="badge" data-save-state>{{ $release?->status ?? 'Unsaved draft' }}</span><button class="button-primary" type="button" data-release-save>Save draft</button></div></div>
    @if(auth()->user()->is_demo)<div class="mb-6 border-l-4 border-amber-700 bg-amber-50 p-4 text-sm font-bold text-amber-950 dark:bg-amber-950 dark:text-amber-100">Demo mode lets you use the editor and analysis, but saving remains disabled.</div>@endif
    <form id="release-editor-form" class="grid gap-7 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="space-y-7">
            <section class="panel"><div class="flex items-start justify-between gap-4"><div><p class="eyebrow">1 · Edit content</p><h2 class="mt-2 text-2xl font-black">Write the story</h2></div><a class="button-quiet" href="{{ route('releases.start') }}">Change template</a></div>
                <div class="mt-6 grid gap-5 sm:grid-cols-2"><div><label class="label" for="release-type">Format</label><select class="field" id="release-type" name="release_type"><option value="release" @selected($releaseType === 'release')>Press release</option><option value="pitch" @selected($releaseType === 'pitch')>Media pitch</option></select></div><div><label class="label" for="release-folder">Drawer</label><select class="field" id="release-folder" name="folder_id">@foreach($folders as $folder)<option value="{{ $folder->id }}" @selected(($release?->folder_id ?? null) === $folder->id)>{{ $folder->folder_name }}</option>@endforeach</select></div></div>
                <input name="template_key" type="hidden" value="{{ $templateKey ?: 'blank' }}">
                <div class="mt-5"><label class="label" for="release-subject">Headline or email subject</label><input class="field text-lg font-bold" id="release-subject" name="subject" value="{{ $subject }}" maxlength="500" required></div>
                <div class="mt-5"><div class="mb-2 flex items-end justify-between gap-3"><label class="label mb-0" for="release-content">Copy</label><span class="text-xs italic text-stone-500"><span data-word-count>0</span> words</span></div><textarea class="field min-h-[34rem] resize-y leading-7" id="release-content" name="content" required>{{ $content }}</textarea></div>
            </section>
            <section class="panel"><div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"><div><p class="eyebrow">2 · Analyze &amp; match</p><h2 class="mt-2 text-2xl font-black">Find the useful signals</h2><p class="mt-2 max-w-2xl text-stone-600 dark:text-stone-300">AI extracts specific subjects from the copy, then LetraPress compares them with topics already in the media database. Partial matches are useful; nothing is forced.</p></div><button class="button-secondary shrink-0" type="button" data-release-analyze>Analyze copy</button></div>
                <div id="release-analysis" class="mt-6 border-t border-stone-400 pt-6"><p class="italic text-stone-500">Run the analysis when your draft has enough detail.</p></div>
                <div id="release-recommendations" class="mt-6"></div>
            </section>
        </div>
        <aside class="space-y-7 lg:sticky lg:top-24 lg:self-start">
            <section class="panel"><p class="eyebrow">3 · Choose its use</p><h2 class="mt-2 text-2xl font-black">Destination</h2><div class="mt-5 space-y-3">
                @foreach([['outreach','Media outreach','Select lists and recommended contacts.'],['newsroom','Public newsroom','Publish it without sending to recipients.'],['both','Both','Keep the public record and prepare targeted outreach.']] as [$value,$label,$description])
                <label class="block cursor-pointer border border-stone-400 p-3 has-[:checked]:border-claret has-[:checked]:bg-red-50 dark:border-stone-700 dark:has-[:checked]:bg-red-950/40"><span class="flex gap-3"><input class="mt-1 text-claret focus:ring-claret" type="radio" name="distribution" value="{{ $value }}" @checked($distribution === $value)><span><strong class="block">{{ $label }}</strong><span class="mt-1 block text-sm text-stone-600 dark:text-stone-300">{{ $description }}</span></span></span></label>
                @endforeach
            </div></section>
            <section class="panel" data-recipient-panel><p class="eyebrow">Saved lists</p><h2 class="mt-2 text-2xl font-black">Recipients</h2><p class="mt-2 text-sm text-stone-600 dark:text-stone-300">Choose established lists now; individual AI matches can be added after analysis.</p><div class="mt-5 space-y-2">
                @forelse($contactLists as $list)<label class="flex cursor-pointer items-start gap-3 border-b border-stone-300 py-3 dark:border-stone-700"><input class="mt-1 text-claret focus:ring-claret" type="checkbox" data-recipient-type="list" data-recipient-id="{{ $list->id }}"><span><strong class="block">{{ $list->name }}</strong><span class="text-xs text-stone-500">{{ $list->journalists_count }} journalists · {{ $list->outlets_count }} outlets</span></span></label>@empty<p class="italic text-stone-500">No saved lists yet.</p>@endforelse
            </div><p class="mt-5 border-t border-stone-300 pt-4 text-sm"><strong data-recipient-count>0</strong> recipient selections</p></section>
            <section class="panel-flat p-5"><p id="release-editor-errors" class="field-error"></p><button class="button-primary w-full" type="submit">Save &amp; apply choices</button><a class="button-secondary mt-3 w-full" href="{{ route('releases.page') }}">Return without saving</a></section>
        </aside>
    </form>
</div>
@endsection
