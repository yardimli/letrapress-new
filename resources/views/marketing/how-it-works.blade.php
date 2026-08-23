@extends('layouts.marketing')

@section('title', 'How it works — LetraPress')
@section('description', 'A practical book publicity workflow, from media research to a published newsroom.')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24">
    <div class="max-w-4xl"><p class="eyebrow">How it works</p><h1 class="display-title mt-5">A clear route from finished book to <em class="font-normal">earned attention.</em></h1><p class="lede mt-8 max-w-3xl">LetraPress keeps the essential publicity work together: understand the story, identify relevant media, prepare the announcement, and give interested readers a trustworthy place to learn more.</p></div>
</section>

<section class="border-y border-stone-500 bg-[#e7ddc8] dark:border-stone-700 dark:bg-stone-900">
    <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
        <ol class="grid gap-px border border-stone-600 bg-stone-600 lg:grid-cols-4 dark:border-stone-500 dark:bg-stone-500">
            @foreach ([['01','Describe the book','Record the title, genre, synopsis, author details, and the angle that makes this story timely.'],['02','Find the right desks','Search journalists and outlets by subject, language, country, media type, and influence—then collect only the relevant names.'],['03','Prepare the release','Write a concise announcement with a useful headline, real news value, supporting details, and a clear next step.'],['04','Publish the newsroom','Place approved releases and images in a friendly public newsroom that media contacts can return to.']] as [$number,$title,$copy])
                <li class="bg-[#faf6ec] p-7 dark:bg-stone-900"><span class="text-4xl font-black text-claret dark:text-red-300">{{ $number }}</span><h2 class="mt-8 text-2xl font-black">{{ $title }}</h2><p class="mt-4 leading-7 text-stone-600 dark:text-stone-300">{{ $copy }}</p></li>
            @endforeach
        </ol>
    </div>
</section>

<section class="mx-auto grid max-w-6xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center">
    <img class="w-full border border-stone-700 object-cover shadow-print grayscale dark:border-stone-500 dark:shadow-none" src="{{ asset('images/marketing/publishing-tools.jpg') }}" alt="Publishing and media research materials">
    <div><p class="eyebrow">Editorial judgment, assisted</p><h2 class="page-title mt-3">Use the database without losing the human point of view.</h2><p class="lede mt-6">A large directory is valuable only when it leads to a short, thoughtful list. Filter broadly, preserve a consistent relevance order, and inspect each journalist or outlet before adding them to a campaign.</p><p class="mt-5 leading-7 text-stone-600 dark:text-stone-300">The result is a calmer pitch process: fewer irrelevant messages, stronger context for every recipient, and a better chance that your book reaches someone who already cares about its subject.</p></div>
</section>

<section class="border-y border-stone-500 bg-[#faf6ec] dark:border-stone-700 dark:bg-stone-900">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-16 sm:px-6 md:grid-cols-3">
        @foreach ([['Discoverability','Relevant coverage gives readers another route to find a book beyond a retailer listing.'],['Credibility','A clear newsroom and a well-prepared release make it easier for media contacts to verify the story.'],['Momentum','Reusable lists, organized releases, and durable public links make each later campaign faster.']] as [$title,$copy])
            <article><h2 class="border-b-2 border-stone-800 pb-3 text-2xl font-black dark:border-stone-300">{{ $title }}</h2><p class="mt-4 leading-7 text-stone-600 dark:text-stone-300">{{ $copy }}</p></article>
        @endforeach
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-20 text-center sm:px-6"><p class="eyebrow">See the complete desk</p><h2 class="page-title mt-4">Try the full workflow with a read-only science-fiction author account.</h2><div class="mt-8 flex flex-wrap justify-center gap-3">@include('partials.demo-link', ['class' => 'button-primary'])<a class="button-secondary" href="{{ route('marketing.documentation') }}">Browse author resources</a></div></section>
@endsection
