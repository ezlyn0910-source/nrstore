@if ($paginator->hasPages())
    <nav class="nrit-pagination" role="navigation" aria-label="Pagination Navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="nrit-page nrit-pill is-disabled">Previous</span>
        @else
            <a class="nrit-page nrit-pill" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="nrit-page nrit-circle is-disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="nrit-page nrit-circle is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="nrit-page nrit-circle" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="nrit-page nrit-pill" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
        @else
            <span class="nrit-page nrit-pill is-disabled">Next</span>
        @endif
    </nav>
@endif
