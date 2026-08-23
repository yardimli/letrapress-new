@extends('layouts.marketing')

@section('title', 'Author resources — LetraPress')
@section('description', 'A concise reference shelf for book marketing, publishing, design, editing, audio, and visual production.')

@section('content')
<section class="mx-auto grid max-w-6xl gap-10 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1fr_.45fr] lg:items-end">
    <div><p class="eyebrow">The reference desk</p><h1 class="display-title mt-5">Useful tools for the road from manuscript to market.</h1><p class="lede mt-8 max-w-3xl">A compact starting point for authors assembling a professional book, a recognizable identity, and a credible publicity campaign.</p></div>
    <p class="border-y-4 border-double border-stone-800 py-5 text-sm leading-6 dark:border-stone-300">The tools change. The standard does not: clear presentation, careful editing, accurate information, and respect for the reader.</p>
</section>

<section class="border-y border-stone-500 bg-[#e7ddc8] dark:border-stone-700 dark:bg-stone-900">
    <div class="mx-auto grid max-w-6xl gap-6 px-4 py-16 sm:grid-cols-2 sm:px-6 lg:grid-cols-3">
        @foreach ([
            ['Marketing tools','Plan social posts, email campaigns, media materials, and measurement around one clear audience.','publishing-tools.jpg'],
            ['Publishing tools','Prepare print and digital editions, manage metadata, and compare distribution routes beyond a single storefront.','writing-characters.jpg'],
            ['Cover design','Use strong typography, purposeful imagery, and correct production specifications for print and ebook covers.','cover-design.jpg'],
            ['Book editing','Combine structural review, copy editing, proofreading, and final formatting before publicity begins.','book-editing.jpg'],
            ['Audiobooks','Record, edit, master, and package spoken-word editions to the standards required by distributors.','audiobooks.jpg'],
            ['Graphic resources','Build a licensed library of type, photography, templates, and campaign graphics that remain consistent.','graphic-resources.jpg']
        ] as [$title,$copy,$image])
            <article class="panel overflow-hidden p-0"><img class="aspect-[16/7] w-full border-b border-stone-600 object-cover grayscale" src="{{ asset('images/marketing/'.$image) }}" alt=""><div class="p-6"><h2 class="text-2xl font-black">{{ $title }}</h2><p class="mt-4 leading-7 text-stone-600 dark:text-stone-300">{{ $copy }}</p></div></article>
        @endforeach
    </div>
</section>

<section class="mx-auto grid max-w-6xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[.75fr_1.25fr] lg:items-start">
    <div><p class="eyebrow">Before outreach</p><h2 class="page-title mt-3">A six-point readiness check.</h2><p class="lede mt-6">A contact list cannot compensate for an unfinished press package. Check the fundamentals before approaching a journalist.</p></div>
    <ol class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
        @foreach (['The book metadata and author biography are current.','The cover and author images are high-resolution and cleared for use.','The release states genuine news, not only that a book exists.','Every fact, quotation, link, and publication date has been checked.','The selected contacts demonstrably cover the subject or genre.','The newsroom gives media contacts one stable place for materials.'] as $index => $item)
            <li class="flex gap-4 border-t border-stone-500 pt-4"><span class="font-black text-claret dark:text-red-300">0{{ $index + 1 }}</span><span class="leading-6">{{ $item }}</span></li>
        @endforeach
    </ol>
</section>

<section class="border-t border-stone-500 bg-[#faf6ec] px-4 py-16 text-center dark:border-stone-700 dark:bg-stone-900"><h2 class="page-title">Ready to put the materials to work?</h2><div class="mt-7 flex flex-wrap justify-center gap-3">@include('partials.demo-link', ['class' => 'button-primary'])<a class="button-secondary" href="{{ route('marketing.how-it-works') }}">Follow the workflow</a></div></section>
@endsection
