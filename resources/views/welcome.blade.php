@extends('layouts.marketing')

@section('title', 'LetraPress — Public relations for books')
@section('description', 'Find the right media contacts, prepare press releases, and publish a newsroom from one quiet, focused workspace.')

@section('content')
<section class="mx-auto grid max-w-6xl gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1.15fr_.85fr] lg:items-center">
    <div>
        <p class="eyebrow mb-5">The quiet press desk</p>
        <h1 class="display-title max-w-4xl">Give your book<br><em class="font-normal">a proper introduction.</em></h1>
        <p class="lede mt-8 max-w-2xl">Research journalists and outlets, build useful contact lists, write a precise release, and keep every announcement in a handsome public newsroom.</p>
        <div class="mt-9 flex flex-wrap gap-3">
            @auth
                <a class="button-primary" href="{{ route('dashboard') }}">Open your press desk</a>
            @else
                <a class="button-primary" href="{{ route('register') }}">Start your press desk</a>
                @include('partials.demo-link')
            @endauth
            <a class="button-quiet px-2" href="{{ route('marketing.how-it-works') }}">See how it works →</a>
        </div>
    </div>
    <figure class="relative border border-stone-700 bg-[#faf6ec] p-3 shadow-print dark:border-stone-500 dark:bg-stone-900 dark:shadow-none">
        <img class="aspect-[4/3] w-full object-cover grayscale" src="{{ asset('images/marketing/author-writing.jpg') }}" alt="An author at work on a manuscript">
        <figcaption class="absolute -bottom-4 left-8 border border-stone-700 bg-paper px-4 py-2 text-xs font-bold uppercase tracking-[.16em] dark:border-stone-400 dark:bg-stone-950">Research · Write · Publish</figcaption>
    </figure>
</section>

<section class="border-y border-stone-500 bg-[#e7ddc8] dark:border-stone-700 dark:bg-stone-900">
    <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <div class="grid gap-5 lg:grid-cols-2 lg:items-end">
            <div><p class="eyebrow mb-3">One editorial workflow</p><h2 class="page-title">From research to publication.</h2></div>
            <p class="lede">Five useful rooms, joined by the same story. No vanity charts and no empty dashboard furniture.</p>
        </div>
        <div class="mt-10 grid border-l border-t border-stone-600 sm:grid-cols-2 lg:grid-cols-5 dark:border-stone-500">
            @foreach ([['01', 'Journalists', 'Find reporters by beat, language, location, and reach.'],['02', 'Outlets', 'Map the publications and channels that shape your market.'],['03', 'Contact lists', 'Keep focused, reusable groups for each campaign.'],['04', 'Press releases', 'Draft and revise clean, confident announcements.'],['05', 'Newsrooms', 'Give every public story a permanent, shareable home.']] as [$number, $title, $copy])
                <article class="border-b border-r border-stone-600 p-6 dark:border-stone-500"><span class="text-sm font-bold text-claret dark:text-red-300">{{ $number }}</span><h3 class="mt-8 text-xl font-black">{{ $title }}</h3><p class="mt-3 text-sm leading-6 text-stone-700 dark:text-stone-300">{{ $copy }}</p></article>
            @endforeach
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-20 sm:px-6">
    <p class="eyebrow">Read the field notes</p>
    <h2 class="page-title mt-3">A practical guide to literary outreach.</h2>
    <div class="mt-10 grid gap-6 md:grid-cols-3">
        <a class="panel group block" href="{{ route('marketing.how-it-works') }}"><img class="aspect-[16/8] w-full border border-stone-500 object-cover grayscale transition group-hover:grayscale-0" src="{{ asset('images/marketing/publishing-tools.jpg') }}" alt="Publishing research materials"><p class="eyebrow mt-5">The process</p><h3 class="mt-2 text-2xl font-black">How it works</h3><p class="mt-3 leading-6 text-stone-600 dark:text-stone-300">Follow a campaign from book profile and media research through release and newsroom.</p><span class="button-quiet mt-5">Read the guide →</span></a>
        <a class="panel group block" href="{{ route('marketing.documentation') }}"><img class="aspect-[16/8] w-full border border-stone-500 object-cover grayscale transition group-hover:grayscale-0" src="{{ asset('images/marketing/cover-design.jpg') }}" alt="Book cover design materials"><p class="eyebrow mt-5">The reference desk</p><h3 class="mt-2 text-2xl font-black">Author resources</h3><p class="mt-3 leading-6 text-stone-600 dark:text-stone-300">A concise index of marketing, publishing, design, editing, audio, and visual tools.</p><span class="button-quiet mt-5">Browse resources →</span></a>
        <a class="panel group block" href="{{ route('marketing.about') }}"><img class="aspect-[16/8] w-full border border-stone-500 object-cover grayscale transition group-hover:grayscale-0" src="{{ asset('images/marketing/book-editing.jpg') }}" alt="An edited book manuscript"><p class="eyebrow mt-5">Our purpose</p><h3 class="mt-2 text-2xl font-black">Why LetraPress?</h3><p class="mt-3 leading-6 text-stone-600 dark:text-stone-300">Learn why useful publicity begins with relevance, editorial judgment, and author control.</p><span class="button-quiet mt-5">Meet LetraPress →</span></a>
    </div>
</section>

<section class="border-t border-stone-500 bg-claret px-4 py-16 text-center text-white dark:bg-red-950">
    <p class="text-xs font-bold uppercase tracking-[.2em] text-red-100">A better working rhythm</p>
    <h2 class="mx-auto mt-4 max-w-3xl text-4xl font-black leading-tight sm:text-5xl">Put the story first. Let the software stay out of the way.</h2>
    <div class="mt-8 flex flex-wrap justify-center gap-3">@include('partials.demo-link', ['class' => 'button border-white bg-white text-claret hover:bg-transparent hover:text-white'])<a class="button border-white text-white hover:bg-white hover:text-claret" href="{{ route('register') }}">Create an account</a></div>
</section>
@endsection
