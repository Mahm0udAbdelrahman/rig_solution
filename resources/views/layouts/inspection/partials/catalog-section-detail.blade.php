@php
    $detailSection = $section;
    $detailThemeClass = 'inspection-theme-' . (($detailSection['theme'] ?? 'lifting'));
    $showLocalSearch = $showLocalSearch ?? true;
@endphp

<div class="inspection-section-detail {{ $detailThemeClass }}" data-inspection-detail-root data-section-key="{{ $detailSection['key'] ?? 'inspection-section' }}">
    <div class="inspection-section-detail-body">
        <div class="inspection-detail-toolbar">
            @if($showLocalSearch)
                <div class="inspection-detail-search">
                    <label for="inspection-section-filter-{{ $detailSection['key'] ?? 'section' }}">Find Report Type</label>
                    <input id="inspection-section-filter-{{ $detailSection['key'] ?? 'section' }}" type="text" placeholder="Type a report name, keyword, or group" data-inspection-filter-input>
                    <small>Useful for longer sections like NDT and Tubular.</small>
                </div>
            @endif
            <div class="inspection-detail-toolbar-right">
                <div class="inspection-view-toggle" role="group" aria-label="Section view mode">
                    <button type="button" class="inspection-view-btn is-active" data-inspection-view-trigger data-view-mode="list">List</button>
                    <button type="button" class="inspection-view-btn" data-inspection-view-trigger data-view-mode="cards">Cards</button>
                </div>
                <div class="inspection-detail-stats">
                    <span class="inspection-pill">{{ $detailSection['visible_item_count'] ?? 0 }} types</span>
                    <span class="inspection-pill inspection-pill-soft">{{ $detailSection['total_report_count'] ?? 0 }} reports</span>
                </div>
            </div>
        </div>

        @if(!empty($detailSection['focus_panels']))
            <div class="inspection-focus-header">
                <div>
                    <div class="inspection-section-kicker">Focus Reports</div>
                    <div class="inspection-focus-copy">Control what appears here: curated pinned reports, approval queue, publish queue, or the most active report types.</div>
                </div>
                <div class="inspection-focus-controls">
                    @foreach(($detailSection['focus_panels'] ?? []) as $panel)
                        <button type="button" class="inspection-focus-btn {{ $loop->first ? 'is-active' : '' }}" data-inspection-focus-trigger data-focus-key="{{ $panel['key'] }}">{{ $panel['label'] }}</button>
                    @endforeach
                </div>
            </div>

            @foreach(($detailSection['focus_panels'] ?? []) as $panel)
            <div class="inspection-pinned-strip" data-inspection-focus-panel data-focus-key="{{ $panel['key'] }}" @if(!$loop->first) style="display: none;" @endif>
                @forelse(($panel['items'] ?? []) as $item)
                    @php
                        $pinnedTag = !empty($item['primary_url']) ? 'a' : 'div';
                    @endphp
                    <{{ $pinnedTag }} @if(!empty($item['primary_url'])) href="{{ $item['primary_url'] }}" @endif class="inspection-pinned-card">
                        <div class="inspection-pinned-kicker">
                            <i class="la la-thumb-tack"></i>
                            <span>{{ $panel['label'] }}</span>
                        </div>
                        <div class="inspection-pinned-number">{{ str_pad((string) ($item['order'] ?? 0), 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="inspection-pinned-title">{{ $item['display_title'] ?? $item['title'] }}</div>
                        <div class="inspection-pinned-copy">{{ $item['group'] ?? 'Inspection Report' }}</div>
                        <div class="inspection-pinned-stats">
                            <span class="inspection-row-badge">{{ $item['total_count'] ?? 0 }} total</span>
                            @if(($item['need_approve_count'] ?? 0) > 0)
                                <span class="inspection-row-badge is-warning">{{ $item['need_approve_count'] }} approve</span>
                            @endif
                            @if(($item['need_publish_count'] ?? 0) > 0)
                                <span class="inspection-row-badge is-info">{{ $item['need_publish_count'] }} publish</span>
                            @endif
                        </div>
                        <div class="inspection-pinned-actions">
                            @if(!empty($item['need_approve_url']) && ($item['need_approve_count'] ?? 0) > 0)
                                <span class="inspection-queue-action is-approve">Approve Queue</span>
                            @elseif(!empty($item['need_publish_url']) && ($item['need_publish_count'] ?? 0) > 0)
                                <span class="inspection-queue-action is-publish">Publish Queue</span>
                            @else
                                <span class="inspection-queue-action is-open">Open</span>
                            @endif
                        </div>
                    </{{ $pinnedTag }}>
                @empty
                    <div class="inspection-empty-state">{{ $panel['description'] ?? 'No items available for this focus mode.' }}</div>
                @endforelse
            </div>
            @endforeach
        @endif

        <div class="inspection-section-filters">
            <button type="button" class="inspection-filter-btn is-active" data-inspection-filter-trigger data-filter-tag="all">All <strong>{{ $detailSection['filter_counts']['all'] ?? 0 }}</strong></button>
            <button type="button" class="inspection-filter-btn" data-inspection-filter-trigger data-filter-tag="need_approve">Need Approve <strong>{{ $detailSection['filter_counts']['need_approve'] ?? 0 }}</strong></button>
            <button type="button" class="inspection-filter-btn" data-inspection-filter-trigger data-filter-tag="need_publish">Need Publish <strong>{{ $detailSection['filter_counts']['need_publish'] ?? 0 }}</strong></button>
            <button type="button" class="inspection-filter-btn" data-inspection-filter-trigger data-filter-tag="queues">Only Queues <strong>{{ $detailSection['filter_counts']['queues'] ?? 0 }}</strong></button>
            <button type="button" class="inspection-filter-btn" data-inspection-filter-trigger data-filter-tag="published_heavy">Published Heavy <strong>{{ $detailSection['filter_counts']['published_heavy'] ?? 0 }}</strong></button>
            <button type="button" class="inspection-filter-btn" data-inspection-filter-trigger data-filter-tag="empty_new">Empty / New <strong>{{ $detailSection['filter_counts']['empty_new'] ?? 0 }}</strong></button>
        </div>

        @foreach(($detailSection['grouped_items'] ?? []) as $group)
            <div class="inspection-group has-accent accent-{{ $group['accent_key'] ?? 'other' }}" data-inspection-group>
                <div class="inspection-group-head">
                    <h3 class="inspection-group-title">{{ $group['title'] }}</h3>
                    <div class="inspection-group-meta">{{ $group['item_count'] ?? count($group['items'] ?? []) }} report types</div>
                </div>
                <div class="inspection-report-list">
                    @foreach(($group['items'] ?? []) as $item)
                        <div
                            class="inspection-report-row {{ !empty($item['index_url']) ? 'is-clickable' : '' }}"
                            data-inspection-item
                            data-filter-text="{{ strtolower(trim(($item['keywords'] ?? '') . ' ' . ($group['title'] ?? '') . ' ' . ($detailSection['title'] ?? ''))) }}"
                            data-filter-tags="{{ implode(' ', $item['filter_tags'] ?? ['all']) }}"
                            @if(!empty($item['index_url'])) data-primary-url="{{ $item['index_url'] }}" tabindex="0" role="link" aria-label="Open {{ $item['display_title'] ?? $item['title'] }} listing" @endif
                        >
                            <div class="inspection-report-meta">
                                <span class="inspection-report-order">{{ str_pad((string) $item['order'], 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="inspection-report-visual"><i class="{{ $item['icon_class'] ?? 'la la-file-text-o' }}"></i></span>
                                <div>
                                    @if($item['index_url'])
                                        <a href="{{ $item['index_url'] }}" class="inspection-report-title">{{ $item['display_title'] ?? $item['title'] }}</a>
                                    @else
                                        <div class="inspection-report-title">{{ $item['display_title'] ?? $item['title'] }}</div>
                                    @endif
                                    @if(($item['display_title'] ?? $item['title']) !== ($item['full_title'] ?? $item['title']))
                                        <div class="inspection-report-subtitle">{{ $item['full_title'] }}</div>
                                    @endif
                                    <div class="inspection-report-badges">
                                        <span class="inspection-row-badge">{{ $item['total_count'] ?? 0 }} total</span>
                                        @if(($item['need_approve_count'] ?? 0) > 0)
                                            <span class="inspection-row-badge is-warning">{{ $item['need_approve_count'] }} need approve</span>
                                        @endif
                                        @if(($item['need_publish_count'] ?? 0) > 0)
                                            <span class="inspection-row-badge is-info">{{ $item['need_publish_count'] }} need publish</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="inspection-report-actions">
                                @if(!empty($item['need_approve_url']) && ($item['need_approve_count'] ?? 0) > 0)
                                    <a href="{{ $item['need_approve_url'] }}" class="btn btn-sm inspection-queue-action is-approve">Approve Queue</a>
                                @endif
                                @if(!empty($item['need_publish_url']) && ($item['need_publish_count'] ?? 0) > 0)
                                    <a href="{{ $item['need_publish_url'] }}" class="btn btn-sm inspection-queue-action is-publish">Publish Queue</a>
                                @endif
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
        @endforeach

        <div class="inspection-empty-state mt-1" data-inspection-empty-state style="display: none;">
            No report types match the current filter.
        </div>
    </div>
</div>
