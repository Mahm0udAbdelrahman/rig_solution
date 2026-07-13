@php
    $themeClass = 'inspection-theme-' . ($section['theme'] ?? 'lifting');
    $isAllMode = ($catalog_mode ?? 'all') === 'all';
@endphp

<div class="{{ $isAllMode ? 'col-xl-6 col-lg-6 col-12' : 'col-12' }}">
    <div class="card inspection-section-card {{ $themeClass }}">
        <div class="card-body">
            <div class="inspection-section-head">
                <div>
                    <div class="inspection-section-kicker">Inspection Section</div>
                    <h4 class="inspection-section-title mb-25">{{ $section['title'] }}</h4>
                    <p class="inspection-section-description mb-0">{{ $section['description'] }}</p>
                </div>
                <div class="inspection-section-badges">
                    <span class="inspection-pill">{{ $section['visible_item_count'] }} types</span>
                    @if(($section['pending_approve_count'] ?? 0) > 0)
                        <span class="inspection-pill inspection-pill-soft">{{ $section['pending_approve_count'] }} need approve</span>
                    @endif
                    @if(($section['pending_publish_count'] ?? 0) > 0)
                        <span class="inspection-pill inspection-pill-soft">{{ $section['pending_publish_count'] }} need publish</span>
                    @endif
                </div>
            </div>

            <div class="inspection-section-actions">
                <a href="{{ $section['index_url'] }}" class="btn btn-sm inspection-btn-primary">Open Section</a>
                @if($isAllMode)
                    <a href="{{ $section['index_url'] }}#inspection-report-list" class="btn btn-sm inspection-btn-secondary">Jump To Reports</a>
                @endif
            </div>

            <div class="inspection-report-list" id="inspection-report-list">
                @foreach($section['items'] as $item)
                    <div class="inspection-report-row">
                        <div class="inspection-report-meta">
                            <span class="inspection-report-order">{{ str_pad((string) $item['order'], 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                @if($item['index_url'])
                                    <a href="{{ $item['index_url'] }}" class="inspection-report-title">{{ $item['display_title'] ?? $item['title'] }}</a>
                                @else
                                    <div class="inspection-report-title">{{ $item['display_title'] ?? $item['title'] }}</div>
                                @endif
                                @if(($item['display_title'] ?? $item['title']) !== ($item['full_title'] ?? $item['title']))
                                    <div class="inspection-report-subtitle">{{ $item['full_title'] }}</div>
                                @endif
                                @if(($item['total_count'] ?? 0) > 0 || ($item['need_approve_count'] ?? 0) > 0 || ($item['need_publish_count'] ?? 0) > 0)
                                    <div class="inspection-report-badges">
                                        @if(($item['total_count'] ?? 0) > 0)
                                            <span class="inspection-row-badge">{{ $item['total_count'] }} total</span>
                                        @endif
                                        @if(($item['need_approve_count'] ?? 0) > 0)
                                            <span class="inspection-row-badge is-warning">{{ $item['need_approve_count'] }} need approve</span>
                                        @endif
                                        @if(($item['need_publish_count'] ?? 0) > 0)
                                            <span class="inspection-row-badge is-info">{{ $item['need_publish_count'] }} need publish</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="inspection-report-actions">
                            @if($item['index_url'])
                                <a href="{{ $item['index_url'] }}" class="btn btn-sm btn-outline-primary">Open</a>
                            @endif
                            @if($item['create_url'])
                                <a href="{{ $item['create_url'] }}" class="btn btn-sm btn-success">New</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
