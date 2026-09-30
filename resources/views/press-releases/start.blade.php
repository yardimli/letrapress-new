@extends('layouts.app')
@section('title', 'Start a pitch or release')
@section('page', 'releases')
@section('content')
<div class="mx-auto max-w-6xl">
    <a class="button-quiet" href="{{ route('releases.page') }}">← Back to pitches &amp; releases</a>
    <section class="mt-6 border-y-4 border-double border-stone-800 py-10 dark:border-stone-300">
        <p class="eyebrow">Before the first sentence</p>
        <div class="mt-3 grid gap-8 lg:grid-cols-[1.2fr_.8fr] lg:items-end"><div><h1 class="display-title max-w-4xl">Give the story a clear job.</h1><p class="lede mt-6 max-w-3xl">A pitch asks one editor for attention. A release records the complete news. Either can feed your media outreach, your public newsroom, or both. Choose a useful starting shape below—you can change its type and destination in the editor.</p></div><div class="panel-flat p-5"><p class="label">A strong author announcement answers</p><ol class="mt-3 space-y-2 text-sm"><li><strong>1.</strong> What is genuinely new?</li><li><strong>2.</strong> Why does it matter now?</li><li><strong>3.</strong> Which readers or desks care?</li><li><strong>4.</strong> What can the journalist do next?</li></ol></div></div>
    </section>
    <div class="mt-10 flex items-end justify-between gap-4"><div><p class="eyebrow">Author templates</p><h2 class="page-title mt-2">Choose a starting point</h2></div><p class="hidden max-w-md text-right text-sm italic text-stone-600 dark:text-stone-300 sm:block">Every template opens as editable copy on its own page.</p></div>
    <div class="mt-6 grid gap-5 md:grid-cols-2">
        @foreach($templates as $key => $template)
        <article class="panel flex flex-col {{ $key === 'blank' ? 'md:col-span-2' : '' }}">
            <div class="flex items-start justify-between gap-4"><div><span class="badge">{{ $template['type'] }}</span><h3 class="mt-3 text-2xl font-black">{{ $template['name'] }}</h3></div><span class="text-3xl text-claret dark:text-red-300">✦</span></div>
            <p class="mt-4 flex-1 leading-7 text-stone-600 dark:text-stone-300">{{ $template['description'] }}</p>
            @if($template['subject'])<p class="mt-5 border-l-2 border-claret pl-3 text-sm italic">{{ $template['subject'] }}</p>@endif
            <div class="mt-6"><a class="button-primary" href="{{ route('releases.create', ['template' => $key]) }}">Use this template</a></div>
        </article>
        @endforeach
    </div>
</div>
@endsection
