@props(['rating' => 5, 'count' => null, 'showNumber' => false])

<div class="star-rating" style="display: inline-flex; align-items: center; gap: 0.15rem;" title="{{ $rating }} out of 5 stars">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= round($rating))
            <svg width="15" height="15" viewBox="0 0 24 24" fill="#f59e0b">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        @else
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#cbd5e1">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        @endif
    @endfor
    @if ($showNumber)
        <span style="font-weight: 700; font-size: 0.88rem; color: #1e293b; margin-left: 0.35rem;">{{ number_format($rating, 1) }}</span>
    @endif
    @if ($count !== null)
        <span style="font-size: 0.8rem; color: #64748b; margin-left: 0.2rem;">({{ $count }})</span>
    @endif
</div>
