@extends('layouts.app')
@section('title', 'Contact lists')
@section('page', 'contacts')
@section('content')
<div id="contacts-app" data-index-url="{{ route('contact-lists.index') }}">
    <div class="mb-9 flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><p class="eyebrow">Outreach book</p><h1 class="page-title mt-2">Contact lists</h1><p class="lede mt-3 max-w-2xl">Build a short, deliberate list for every pitch instead of sending one more message into the void.</p></div><button class="button-primary" type="button" data-contact-create>New list</button></div>
    <div class="grid min-h-[30rem] gap-6 lg:grid-cols-[21rem_1fr]">
        <aside class="panel-flat"><div class="border-b border-stone-400 p-4"><label class="label" for="contact-search">Find a list</label><input class="field" id="contact-search" placeholder="Search lists…"></div><div id="contact-lists" class="divide-y divide-stone-400 dark:divide-stone-700"></div></aside>
        <section id="contact-detail" class="panel-flat p-6"><div class="grid min-h-64 place-items-center text-center"><div><p class="text-4xl">✦</p><h2 class="mt-4 text-2xl font-black">Choose a contact list</h2><p class="mt-2 text-stone-600 dark:text-stone-300">Its journalists and outlets will appear here.</p></div></div></section>
    </div>
</div>
<dialog id="contact-dialog" class="modal"><form id="contact-form" class="modal-body"><input name="id" type="hidden"><div class="flex items-start justify-between gap-4"><div><p class="eyebrow">Outreach book</p><h2 class="mt-2 text-2xl font-black" data-modal-title>New contact list</h2></div><button class="button-quiet" type="button" data-dialog-close>Close</button></div><div class="mt-6 space-y-5"><div><label class="label" for="contact-name">List name</label><input class="field" id="contact-name" name="name" required maxlength="255"></div><div><label class="label" for="contact-description">Purpose</label><textarea class="field" id="contact-description" name="description" rows="4" placeholder="Who belongs here, and for which story?"></textarea></div><div id="contact-errors" class="field-error"></div><button class="button-primary" type="submit">Save list</button></div></form></dialog>
<dialog id="contact-member-dialog" class="modal"><div class="modal-body"><div class="flex items-start justify-between gap-4"><div><p class="eyebrow" data-member-kind>Media contact</p><h2 class="mt-2 text-3xl font-black" data-member-name></h2></div><button class="button-secondary" type="button" data-member-close>Close</button></div><div class="mt-7 grid gap-5 border-y border-stone-400 py-6 sm:grid-cols-2" data-member-details></div></div></dialog>
@endsection
