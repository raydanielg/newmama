@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display:flex; justify-content:center;">
        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            @if ($paginator->onFirstPage())
                <span style="padding:8px 12px; border-radius:999px; border:1px solid #e5e7eb; background:#f9fafb; color:#9ca3af; font-weight:800;">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="padding:8px 12px; border-radius:999px; border:1px solid #e5e7eb; background:#fff; color:#111827; font-weight:900; text-decoration:none;">Prev</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="padding:8px 12px; border-radius:999px; border:1px dashed #e5e7eb; background:#fff; color:#6b7280; font-weight:800;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" style="padding:8px 12px; border-radius:999px; border:1px solid #2563eb; background:#2563eb; color:#fff; font-weight:900;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="padding:8px 12px; border-radius:999px; border:1px solid #e5e7eb; background:#fff; color:#111827; font-weight:900; text-decoration:none;">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="padding:8px 12px; border-radius:999px; border:1px solid #e5e7eb; background:#fff; color:#111827; font-weight:900; text-decoration:none;">Next</a>
            @else
                <span style="padding:8px 12px; border-radius:999px; border:1px solid #e5e7eb; background:#f9fafb; color:#9ca3af; font-weight:800;">Next</span>
            @endif
        </div>
    </nav>
@endif
