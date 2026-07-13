@extends('layouts.customer.app')

@section('content')
@php
    $formatMoney = function ($value) {
        return number_format((float) $value, 2, '.', ',');
    };
@endphp
<style>
    .customer-overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .customer-overview-card {
        border: 1px solid #dfe3eb;
        border-radius: 1rem;
        padding: 1.1rem 1.2rem;
        background: linear-gradient(180deg, #ffffff 0%, #f6f8fc 100%);
        box-shadow: 0 12px 28px rgba(31, 45, 61, 0.08);
    }
    .customer-overview-card-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #74839b;
        margin-bottom: 0.45rem;
    }
    .customer-overview-card-value {
        font-size: 2rem;
        line-height: 1;
        font-weight: 700;
        color: #23344d;
        margin-bottom: 0.55rem;
    }
    .customer-overview-card-note {
        color: #697a92;
        font-size: 0.92rem;
        line-height: 1.55;
    }
    .customer-overview-panel {
        border: 1px solid #dfe3eb;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 14px 32px rgba(31, 45, 61, 0.08);
        margin-bottom: 1.5rem;
    }
    .customer-overview-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #edf1f7;
    }
    .customer-overview-panel-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #23344d;
        margin: 0;
    }
    .customer-overview-panel-subtitle {
        color: #7a889d;
        font-size: 0.88rem;
    }
    .customer-overview-panel-body {
        padding: 1rem 1.25rem 1.2rem;
    }
    .customer-overview-inline-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.9rem;
    }
    .customer-inspection-chip {
        border-radius: 0.9rem;
        padding: 0.9rem 1rem;
        background: #f7f9fd;
        border: 1px solid #e2e8f2;
    }
    .customer-inspection-chip h5 {
        margin: 0 0 0.3rem;
        font-size: 1rem;
        color: #23344d;
    }
    .customer-inspection-chip .count {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3246d3;
    }
    .customer-overview-link {
        color: #3246d3;
        text-decoration: none;
        font-weight: 700;
    }
    .customer-overview-link:hover {
        color: #1f2fa2;
        text-decoration: underline;
    }
    .customer-badge-yes,
    .customer-badge-no {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 78px;
        border-radius: 999px;
        padding: 0.28rem 0.7rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .customer-badge-yes {
        background: #e6f7ef;
        color: #117a47;
    }
    .customer-badge-no {
        background: #fff2ec;
        color: #c15b2c;
    }
    .customer-table-compact td,
    .customer-table-compact th {
        vertical-align: middle;
        white-space: nowrap;
    }
</style>

<section class="users-list-wrapper">
    <div class="users-list">
        <div class="customer-overview-grid">
            @foreach($overview_cards as $card)
                <div class="customer-overview-card">
                    <div class="customer-overview-card-label">{{ $card['label'] }}</div>
                    <div class="customer-overview-card-value">{{ $card['value'] }}</div>
                    <div class="customer-overview-card-note">{{ $card['note'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="customer-overview-panel">
            <div class="customer-overview-panel-head">
                <div>
                    <h4 class="customer-overview-panel-title">Inspection Snapshot</h4>
                    <div class="customer-overview-panel-subtitle">Published inspection distribution across linked sections.</div>
                </div>
                <a href="{{ \Illuminate\Support\Facades\Auth::guard('clientDepartments')->check() ? route('department.reports') : route('customer.reports') }}" class="customer-portal-pill-btn is-active">Open Published Certificates</a>
            </div>
            <div class="customer-overview-panel-body">
                <div class="customer-overview-inline-grid">
                    @forelse($inspection_section_cards as $section)
                        <div class="customer-inspection-chip">
                            <h5>{{ $section['label'] }}</h5>
                            <div class="count">{{ $section['count'] }}</div>
                            <div class="text-muted small mt-25">
                                Latest:
                                @if(!empty($section['latest_pdf_url']))
                                    <a href="{{ $section['latest_pdf_url'] }}" target="_blank" rel="noopener" class="customer-overview-link">
                                        {{ $section['latest_code'] ?: '-' }}
                                    </a>
                                @else
                                    {{ $section['latest_code'] ?: '-' }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No published inspection certificates are currently available for this scope.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-7 col-lg-12">
                <div class="customer-overview-panel">
                    <div class="customer-overview-panel-head">
                        <div>
                            <h4 class="customer-overview-panel-title">Recent Published Certificates</h4>
                            <div class="customer-overview-panel-subtitle">Latest approved and published inspection outputs visible to this portal.</div>
                        </div>
                    </div>
                    <div class="customer-overview-panel-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped customer-table-compact mb-0">
                                <thead>
                                <tr>
                                    <th>Certificate</th>
                                    <th>Type</th>
                                    <th>Section</th>
                                    <th>Published State</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($recent_published_reports as $report)
                                    <tr>
                                        <td>
                                            @if(!empty($report['pdf_url']))
                                                <a href="{{ $report['pdf_url'] }}" target="_blank" rel="noopener" class="customer-overview-link">
                                                    {{ $report['report_no'] ?? '-' }}
                                                </a>
                                            @else
                                                {{ $report['report_no'] ?? '-' }}
                                            @endif
                                        </td>
                                        <td>{{ $report['type'] ?? '-' }}</td>
                                        <td>{{ $report['section'] ?? '-' }}</td>
                                        <td><span class="customer-badge-yes">Published</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">No published inspection reports found.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-5 col-lg-12">
            </div>
        </div>
    </div>
</section>
@endsection
