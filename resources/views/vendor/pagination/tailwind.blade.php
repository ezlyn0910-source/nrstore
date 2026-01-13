@if ($paginator->hasPages())
    <div class="pagination-wrap">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">‹ Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Prev</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" --}}
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            {{-- Page Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Next ›</a>
        @else
            <span class="disabled">Next ›</span>
        @endif
    </div>
@endif
