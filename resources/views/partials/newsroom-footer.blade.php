<footer class="mt-auto border-t-4 border-double border-stone-800 bg-[#ebe2cf] py-8 dark:border-stone-300 dark:bg-stone-900">
    <div class="mx-auto flex max-w-5xl flex-col gap-3 px-4 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <p><strong>{{ $user->name }}</strong><br><span class="text-stone-600 dark:text-stone-300">Official press newsroom</span></p>
        <a class="button-quiet" href="{{ route('home') }}">Published with LetraPress</a>
    </div>
</footer>
