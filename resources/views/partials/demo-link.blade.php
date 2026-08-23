@if (auth()->user()?->is_demo)
    <a class="{{ $class ?? 'button-secondary' }}" href="{{ route('journalists.page') }}">Demo workspace</a>
@else
    <form method="POST" action="{{ route('demo.login') }}" class="inline-flex">
        @csrf
        <button class="{{ $class ?? 'button-secondary' }}" type="submit">Explore the demo</button>
    </form>
@endif
