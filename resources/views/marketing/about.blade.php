@extends('layouts.marketing')

@section('title', 'About — LetraPress')
@section('description', 'Why LetraPress puts relevance, clarity, and author control at the center of literary publicity.')

@section('content')
<section class="mx-auto grid max-w-6xl gap-12 px-4 py-16 sm:px-6 sm:py-24 lg:grid-cols-[1fr_.8fr] lg:items-center">
    <div><p class="eyebrow">Why LetraPress?</p><h1 class="display-title mt-5">Good books deserve thoughtful introductions.</h1><p class="lede mt-8">Publicity works best when the message is accurate, the recipient is relevant, and the author remains close to the process. LetraPress was shaped around those three ideas.</p></div>
    <img class="aspect-[4/3] w-full border border-stone-700 object-cover shadow-print grayscale dark:border-stone-500 dark:shadow-none" src="{{ asset('images/marketing/author-writing.jpg') }}" alt="An author developing a manuscript">
</section>

<section class="border-y border-stone-500 bg-[#e7ddc8] dark:border-stone-700 dark:bg-stone-900">
    <div class="mx-auto grid max-w-6xl gap-px border-x border-stone-600 bg-stone-600 sm:grid-cols-3 dark:border-stone-500 dark:bg-stone-500">
        @foreach ([['Our approach','Give authors a transparent workspace for researching media, shaping each campaign, and reviewing everything before it is public.'],['Our mission','Help serious books reach appropriate readers through precise information and respectful, well-targeted outreach.'],['Our standard','Prefer useful context over volume, durable media relationships over shortcuts, and real editorial work over empty metrics.']] as [$title,$copy])
            <article class="bg-[#faf6ec] p-8 sm:py-12 dark:bg-stone-900"><h2 class="text-3xl font-black">{{ $title }}</h2><p class="mt-5 leading-7 text-stone-600 dark:text-stone-300">{{ $copy }}</p></article>
        @endforeach
    </div>
</section>

<section class="mx-auto grid max-w-6xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[.8fr_1.2fr] lg:items-center">
    <img class="w-full border border-stone-700 object-cover grayscale dark:border-stone-500" src="{{ asset('images/marketing/book-editing.jpg') }}" alt="Book manuscript and editing materials">
    <div><p class="eyebrow">A working partnership</p><h2 class="page-title mt-3">Bring the book into focus before bringing it into the spotlight.</h2><p class="lede mt-6">The strongest campaign begins with a candid understanding of the manuscript, its audience, and the conversation it can honestly enter.</p><p class="mt-5 leading-7 text-stone-600 dark:text-stone-300">Authors choose the contacts, control the copy, and decide what appears in the public newsroom. LetraPress supplies a disciplined structure for doing that work efficiently, while preserving the judgment that thoughtful publicity requires.</p></div>
</section>

<section class="border-y-4 border-double border-stone-800 bg-claret text-white dark:border-stone-300 dark:bg-red-950">
    <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6"><p class="text-xs font-bold uppercase tracking-[.2em] text-red-100">Our promise</p><blockquote class="mt-5 text-3xl font-black leading-tight sm:text-4xl">“Fewer careless pitches. Better-prepared stories. A press desk the author can understand.”</blockquote></div>
</section>

<section class="mx-auto max-w-4xl px-4 py-20 text-center sm:px-6"><h2 class="page-title">See the principle in practice.</h2><p class="lede mx-auto mt-5 max-w-2xl">The demo account follows a science-fiction novelist through journalists, outlets, contact lists, releases, and a complete public newsroom.</p><div class="mt-8 flex flex-wrap justify-center gap-3">@include('partials.demo-link', ['class' => 'button-primary'])<a class="button-secondary" href="{{ route('register') }}">Create your account</a></div></section>
@endsection
