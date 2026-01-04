<!-- Breadcrumb Partial -->
<div class="admin-breadcrumb">
    <ul class="admin-breadcrumb-list">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->last)
                <li class="admin-breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                </li>
                <li class="admin-breadcrumb-separator">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </li>
            @else
                <li class="admin-breadcrumb-item active">
                    {{ $breadcrumb['label'] }}
                </li>
            @endif
        @endforeach
    </ul>
</div>
