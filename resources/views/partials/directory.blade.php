<div id="directory-app" data-kind="{{ $kind }}" data-endpoint="{{ $endpoint }}" data-add-url="{{ route('ajax.directory.add') }}" data-human-status-url="{{ route('ajax.directory.human-status') }}" data-human-verify-url="{{ route('ajax.directory.human-verify') }}" data-detail-base="{{ url('/ajax/directory') }}">
    <div class="mb-9 grid gap-6 lg:grid-cols-[1fr_auto] lg:items-end">
        <div><p class="eyebrow">Media directory</p><h1 class="page-title mt-2">{{ $heading }}</h1><p class="lede mt-3 max-w-3xl">{{ $description }}</p></div>
        <p class="border-y border-stone-500 py-2 text-sm italic text-stone-600 dark:text-stone-300"><span id="directory-count">—</span> records found<br><span class="text-xs not-italic">Browse the first 990 matches</span></p>
    </div>
    <form id="directory-filters" class="panel-flat mb-7" aria-label="Directory filters">
        <div class="grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-6">
            <div class="sm:col-span-2"><label class="label" for="search">Search by name</label><input class="field" id="search" name="search" placeholder="Type a name…"></div>
            <div><label class="label" for="country_id">Country</label><select class="field" id="country_id" name="country_id"><option value="">All countries</option>@foreach ($countries as $country)<option value="{{ $country->id }}">{{ $country->country }} ({{ number_format($country->record_count) }})</option>@endforeach</select></div>
            <div><label class="label" for="media_type_id">Media</label><select class="field" id="media_type_id" name="media_type_id"><option value="">All media</option>@foreach ($mediaTypes as $type)<option value="{{ $type->id }}">{{ $type->outlet_type }} ({{ number_format($type->record_count) }})</option>@endforeach</select></div>
            <div><label class="label" for="topic_id">Topic</label><select class="field" id="topic_id" name="topic_id"><option value="">All topics</option>@foreach ($topics as $topic)<option value="{{ $topic->id }}">{{ $topic->topic }} ({{ number_format($topic->record_count) }})</option>@endforeach</select></div>
            <div><label class="label" for="language_id">Language</label><select class="field" id="language_id" name="language_id"><option value="">All languages</option>@foreach ($languages as $language)<option value="{{ $language->id }}">{{ $language->language }} ({{ number_format($language->record_count) }})</option>@endforeach</select></div>
        </div>
        <div class="grid gap-3 border-t border-stone-400 bg-stone-100/50 p-4 sm:grid-cols-2 lg:grid-cols-[14rem_18rem_1fr] dark:border-stone-700 dark:bg-stone-950/30">
            <div><label class="label" for="per_page">Results per page</label><select class="field" id="per_page" name="per_page"><option value="12">12</option><option value="24" selected>24</option><option value="48">48</option><option value="99">99</option></select></div>
            <div><label class="label" for="sort">Sort order</label><select class="field" id="sort" name="sort"><option value="score_desc">Score: highest first</option><option value="score_asc">Score: lowest first</option><option value="name_asc">Name: A–Z</option><option value="name_desc">Name: Z–A</option></select></div>
            <p class="self-end pb-2 text-sm italic text-stone-600 dark:text-stone-300">Up to 990 matches are available for every search.</p>
        </div>
    </form>
    <div id="directory-results" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3" aria-live="polite"></div>
    <div id="directory-pagination" class="mt-8 flex items-center justify-between border-t border-stone-500 pt-5"></div>
    <template id="contact-options">@foreach($contactLists as $list)<option value="{{ $list->id }}">{{ $list->name }}</option>@endforeach</template>
</div>

<dialog id="human-check-dialog" class="modal">
    <div class="modal-body">
        <p class="eyebrow">A quick privacy check</p>
        <h2 class="mt-2 text-3xl font-black">Are you human?</h2>
        <p class="mt-4 leading-7 text-stone-600 dark:text-stone-300">Contact details are kept behind an active signed-in session. Use your mouse or pointer to slide the marker fully to the right. You will only be asked once during this session.</p>
        <label class="label mt-7" for="human-slider">Slide to confirm</label>
        <input id="human-slider" class="w-full accent-red-900" type="range" min="0" max="100" value="0" aria-describedby="human-check-progress">
        <div class="mt-2 flex justify-between text-xs font-bold uppercase tracking-wider"><span id="human-check-progress">0%</span><span>Human</span></div>
        <p id="human-check-error" class="field-error min-h-6" role="alert"></p>
        <div class="mt-6 flex justify-end gap-3"><button class="button-secondary" type="button" data-human-cancel>Cancel</button><button class="button-primary" type="button" data-human-submit disabled>Open contact</button></div>
    </div>
</dialog>

<dialog id="directory-detail-dialog" class="modal w-[min(94vw,56rem)]">
    <div class="modal-body">
        <div class="flex items-start justify-between gap-4"><div><p class="eyebrow" data-detail-kind>Media contact</p><h2 class="mt-2 text-3xl font-black" data-detail-name></h2></div><button class="button-secondary shrink-0" type="button" data-detail-close>Close</button></div>
        <div class="mt-7" data-detail-content></div>
    </div>
</dialog>
