@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.85rem; margin-top: 1.5rem; padding: 0.85rem 1.15rem; background: rgba(255, 255, 255, 0.88); border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 16px; box-shadow: 0 2px 10px rgba(10, 57, 74, 0.04);">
        <div style="font-size: 0.82rem; color: #64748b; font-weight: 600;">
            Showing <strong style="color: #0f172a;">{{ $paginator->firstItem() ?? 0 }}</strong> to <strong style="color: #0f172a;">{{ $paginator->lastItem() ?? 0 }}</strong> of <strong style="color: #0f172a;">{{ $paginator->total() }}</strong> results
        </div>

        <div style="display: inline-flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.42rem 0.8rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; cursor: not-allowed;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Prev</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.42rem 0.8rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; color: #0a394a; background: #ffffff; border: 1px solid rgba(10, 57, 74, 0.2); text-decoration: none;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Prev</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @if (isset($elements) && is_array($elements))
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span style="padding: 0.4rem 0.55rem; font-size: 0.82rem; color: #94a3b8; font-weight: 700;">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" style="display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 0.55rem; border-radius: 10px; font-size: 0.82rem; font-weight: 800; color: #ffffff; background: #0a394a; border: 1px solid #0a394a;">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 0.55rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; color: #334155; background: #ffffff; border: 1px solid #e2e8f0; text-decoration: none;">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.42rem 0.8rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; color: #0a394a; background: #ffffff; border: 1px solid rgba(10, 57, 74, 0.2); text-decoration: none;">
                    <span>Next</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <span aria-disabled="true" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.42rem 0.8rem; border-radius: 10px; font-size: 0.82rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; cursor: not-allowed;">
                    <span>Next</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
