@extends('layouts.app')

@section('header-bottom')
    <style>
        .inspection-catalog-shell {
            display: grid;
            gap: 1.5rem;
        }

        .inspection-hero {
            background: linear-gradient(135deg, #f5f7fb 0%, #eef3ff 100%);
            border: 1px solid #d9e2f2;
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 18px 40px rgba(31, 45, 61, 0.08);
        }

        .inspection-hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .inspection-hero-kicker {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #5f6f86;
            margin-bottom: 0.5rem;
        }

        .inspection-hero-title {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #203047;
        }

        .inspection-hero-copy {
            margin: 0.6rem 0 0;
            max-width: 760px;
            color: #5d6c81;
            line-height: 1.7;
        }

        .inspection-overview-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .inspection-overview-tabs .nav-link {
            border: 1px solid #d7ddec;
            border-radius: 999px;
            padding: 0.58rem 0.95rem;
            background: #fff;
            color: #5b6d87;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(31, 45, 61, 0.06);
        }

        .inspection-overview-tabs .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, #3146d3 0%, #5a6cf0 100%);
            border-color: #3146d3;
            box-shadow: 0 12px 26px rgba(49, 70, 211, 0.22);
        }

        .inspection-overview-tab-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .inspection-overview-tab-stats {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .inspection-overview-tab-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.18rem 0.48rem;
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 1;
            background: #edf2fb;
            color: #42556d;
        }

        .inspection-overview-tab-badge.is-approve {
            background: rgba(210, 137, 32, 0.14);
            color: #9c630c;
        }

        .inspection-overview-tab-badge.is-publish {
            background: rgba(45, 115, 213, 0.14);
            color: #1f5fb8;
        }

        .inspection-overview-shell {
            display: grid;
            gap: 1rem;
        }

        .inspection-overview-command {
            display: grid;
            gap: 0.85rem;
            background: #fff;
            border: 1px solid #dde6f3;
            border-radius: 18px;
            padding: 1rem 1.1rem;
            box-shadow: 0 14px 28px rgba(17, 24, 39, 0.06);
        }

        .inspection-overview-command-top {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .inspection-global-search {
            display: grid;
            gap: 0.45rem;
            flex: 1 1 520px;
            max-width: 760px;
        }

        .inspection-global-search label {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64758b;
        }

        .inspection-global-search input {
            border: 1px solid #d6dfec;
            border-radius: 14px;
            background: #fff;
            color: #203047;
            padding: 0.88rem 1rem;
            box-shadow: 0 10px 22px rgba(17, 24, 39, 0.05);
        }

        .inspection-global-search small {
            color: #738198;
        }

        .inspection-overview-category {
            display: grid;
            gap: 0.45rem;
            min-width: 250px;
        }

        .inspection-overview-category label {
            margin: 0;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64758b;
        }

        .inspection-overview-category-tools {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .inspection-overview-category select {
            border: 1px solid #d6dfec;
            border-radius: 14px;
            background: #fff;
            color: #203047;
            padding: 0.82rem 0.95rem;
            min-width: 230px;
            box-shadow: 0 10px 22px rgba(17, 24, 39, 0.05);
        }

        .inspection-overview-pin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            border: 1px solid #d6dfec;
            border-radius: 999px;
            background: #fff;
            color: #52657d;
            padding: 0.78rem 0.95rem;
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 10px 22px rgba(17, 24, 39, 0.05);
            transition: background 0.18s ease, color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .inspection-overview-pin.is-active {
            background: linear-gradient(135deg, #203047 0%, #38537b 100%);
            border-color: #203047;
            color: #fff;
            box-shadow: 0 14px 24px rgba(32, 48, 71, 0.18);
        }

        .inspection-overview-command-note {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
            color: #738198;
            font-size: 0.84rem;
        }

        .inspection-overview-command-note strong {
            color: #29415f;
        }

        .inspection-recent-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.75rem;
        }

        .inspection-recent-card {
            border: 1px solid #dce5f1;
            border-radius: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
            padding: 0.95rem 1rem;
            box-shadow: 0 12px 24px rgba(17, 24, 39, 0.05);
        }

        a.inspection-recent-card {
            display: block;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        a.inspection-recent-card:hover {
            text-decoration: none;
            transform: translateY(-2px);
            border-color: #cad7ea;
            box-shadow: 0 16px 28px rgba(17, 24, 39, 0.08);
        }

        .inspection-recent-head {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 0.75rem;
        }

        .inspection-recent-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #23395d;
            color: #fff;
            box-shadow: 0 10px 18px rgba(17, 24, 39, 0.14);
            flex: 0 0 auto;
        }

        .inspection-recent-kicker {
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #78859a;
            margin-bottom: 0.15rem;
        }

        .inspection-recent-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: #203047;
            line-height: 1.35;
        }

        .inspection-recent-code {
            color: #3b4f67;
            font-size: 0.88rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .inspection-recent-meta {
            color: #738198;
            font-size: 0.82rem;
        }

        .inspection-overview-tab-pane {
            margin-top: 1.1rem;
        }

        .inspection-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
            margin-top: 1.25rem;
        }

        .inspection-summary-card {
            background: #fff;
            border: 1px solid #e1e8f3;
            border-radius: 14px;
            padding: 1rem 1.1rem;
        }

        a.inspection-summary-card {
            display: block;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        a.inspection-summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(31, 45, 61, 0.08);
            border-color: #ccd7ea;
            text-decoration: none;
        }

        .inspection-summary-label {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #7a8798;
            margin-bottom: 0.35rem;
        }

        .inspection-summary-value {
            font-size: 1.7rem;
            font-weight: 700;
            color: #1f2d3d;
            line-height: 1;
        }

        .inspection-summary-head {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.7rem;
        }

        .inspection-summary-index {
            width: 2.3rem;
            height: 2.3rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            background: #4f6df5;
            flex: 0 0 auto;
        }

        .inspection-summary-title {
            color: #24364d;
            font-size: 1.02rem;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 0.35rem;
        }

        .inspection-summary-copy {
            color: #67768a;
            font-size: 0.9rem;
            line-height: 1.55;
            margin-bottom: 0.75rem;
        }

        .inspection-summary-link {
            color: #3550c7;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .inspection-summary-support {
            color: #7a8798;
            font-size: 0.84rem;
            margin-top: 0.35rem;
        }

        .inspection-section-grid {
            row-gap: 1.25rem;
        }

        .inspection-section-card {
            height: 100%;
            border: 1px solid #e3e8f1;
            border-radius: 18px;
            box-shadow: 0 16px 34px rgba(17, 24, 39, 0.08);
            overflow: hidden;
        }

        .inspection-section-card .card-body {
            padding: 1.35rem;
        }

        .inspection-section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .inspection-section-kicker {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #7a8798;
        }

        .inspection-section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #203047;
        }

        .inspection-section-description {
            color: #5e6b80;
            line-height: 1.6;
            max-width: 700px;
        }

        .inspection-section-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .inspection-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            background: #4f6df5;
        }

        .inspection-pill-soft {
            background: rgba(79, 109, 245, 0.12);
            color: #3550c7;
        }

        .inspection-theme-lifting .inspection-pill-soft {
            background: rgba(77, 103, 123, 0.14);
            color: #31475a;
        }

        .inspection-theme-ndt .inspection-pill-soft {
            background: rgba(210, 137, 32, 0.14);
            color: #9c630c;
        }

        .inspection-theme-tubular .inspection-pill-soft {
            background: rgba(45, 115, 213, 0.14);
            color: #1f5fb8;
        }

        .inspection-theme-drop-object .inspection-pill-soft {
            background: rgba(138, 92, 246, 0.14);
            color: #6f3de0;
        }

        .inspection-theme-calibration .inspection-pill-soft {
            background: rgba(15, 157, 143, 0.14);
            color: #0b7b71;
        }

        .inspection-section-actions {
            display: flex;
            gap: 0.65rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .inspection-btn-primary,
        .inspection-btn-secondary {
            border-radius: 999px;
            padding-left: 1rem;
            padding-right: 1rem;
            font-weight: 600;
        }

        .inspection-btn-primary {
            background: #23395d;
            border-color: #23395d;
            color: #fff;
        }

        .inspection-btn-primary:hover {
            color: #fff;
            background: #182a45;
            border-color: #182a45;
        }

        .inspection-btn-secondary {
            background: #fff;
            border: 1px solid #d8e1ee;
            color: #324255;
        }

        .inspection-report-list {
            display: grid;
            gap: 0.75rem;
        }

        .inspection-report-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.9rem;
            padding: 0.95rem 1rem;
            border: 1px solid #ebeff5;
            border-radius: 14px;
            background: #fff;
            flex-wrap: wrap;
        }

        .inspection-report-row.is-clickable {
            cursor: pointer;
            position: relative;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        }

        .inspection-report-row.is-clickable::after {
            content: 'Open listing';
            position: absolute;
            top: 0.7rem;
            right: 0.85rem;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4d63d4;
            opacity: 0;
            transform: translateY(-2px);
            transition: opacity 0.18s ease, transform 0.18s ease;
            pointer-events: none;
        }

        .inspection-report-row.is-clickable:hover,
        .inspection-report-row.is-clickable:focus {
            border-color: #cfd9ee;
            box-shadow: 0 14px 28px rgba(17, 24, 39, 0.08);
            transform: translateY(-2px);
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .inspection-report-row.is-clickable:hover::after,
        .inspection-report-row.is-clickable:focus::after {
            opacity: 1;
            transform: translateY(0);
        }

        .inspection-report-meta {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            min-width: 0;
        }

        .inspection-report-visual {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: #5c6f87;
            box-shadow: 0 10px 20px rgba(17, 24, 39, 0.12);
            flex: 0 0 auto;
        }

        .inspection-report-visual i {
            font-size: 1rem;
            line-height: 1;
        }

        .inspection-report-order {
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            background: #5c6f87;
            flex: 0 0 auto;
        }

        .inspection-report-title {
            font-weight: 600;
            color: #203047;
        }

        a.inspection-report-title {
            display: inline-block;
            text-decoration: none;
            transition: color 0.18s ease;
        }

        a.inspection-report-title:hover {
            color: #3550c7;
            text-decoration: none;
        }

        .inspection-report-state {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
            color: #6a788d;
            font-size: 0.85rem;
            margin-top: 0.2rem;
        }

        .inspection-report-subtitle {
            color: #7a8798;
            font-size: 0.82rem;
            margin-top: 0.15rem;
        }

        .inspection-report-badges {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
            margin-top: 0.55rem;
        }

        .inspection-row-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            background: #edf2fb;
            color: #43556b;
            padding: 0.2rem 0.55rem;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .inspection-row-badge.is-warning {
            background: rgba(210, 137, 32, 0.14);
            color: #9c630c;
        }

        .inspection-row-badge.is-info {
            background: rgba(45, 115, 213, 0.14);
            color: #1f5fb8;
        }

        .inspection-dot {
            width: 0.25rem;
            height: 0.25rem;
            border-radius: 999px;
            background: currentColor;
            align-self: center;
        }

        .inspection-report-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .inspection-section-detail {
            background: #fff;
            border: 1px solid #e3e8f1;
            border-radius: 18px;
            box-shadow: 0 16px 34px rgba(17, 24, 39, 0.08);
            overflow: hidden;
        }

        .inspection-section-detail-body {
            padding: 1.35rem;
        }

        .inspection-detail-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .inspection-detail-toolbar-right {
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .inspection-detail-search {
            width: min(100%, 420px);
        }

        .inspection-detail-search label {
            display: block;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #7a8798;
            margin-bottom: 0.45rem;
            font-weight: 700;
        }

        .inspection-detail-search input {
            border-radius: 999px;
            border: 1px solid #d8e1ee;
            padding: 0.75rem 1rem;
            width: 100%;
            color: #21354c;
            background: #f9fbff;
        }

        .inspection-detail-search small {
            display: block;
            color: #7a8798;
            margin-top: 0.45rem;
        }

        .inspection-section-filters {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .inspection-filter-btn {
            border: 1px solid #d8e1ee;
            border-radius: 999px;
            background: #fff;
            color: #4d5e74;
            padding: 0.5rem 0.85rem;
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1;
            transition: background 0.18s ease, color 0.18s ease, border-color 0.18s ease;
        }

        .inspection-filter-btn strong {
            font-weight: 800;
            margin-left: 0.25rem;
        }

        .inspection-filter-btn.is-active {
            background: #23395d;
            border-color: #23395d;
            color: #fff;
        }

        .inspection-focus-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.9rem;
            flex-wrap: wrap;
        }

        .inspection-focus-copy {
            color: #6f7d90;
            font-size: 0.9rem;
        }

        .inspection-focus-controls {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .inspection-focus-btn {
            border: 1px solid #d8e1ee;
            border-radius: 999px;
            background: #fff;
            color: #4d5e74;
            padding: 0.45rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 700;
            line-height: 1;
            transition: background 0.18s ease, color 0.18s ease, border-color 0.18s ease;
        }

        .inspection-focus-btn.is-active {
            background: #23395d;
            border-color: #23395d;
            color: #fff;
        }

        .inspection-detail-stats {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .inspection-view-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #eef3fb;
            border: 1px solid #d8e1ee;
            border-radius: 999px;
            padding: 0.25rem;
        }

        .inspection-view-btn {
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: #53657b;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.45rem 0.8rem;
            line-height: 1;
            transition: background 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
        }

        .inspection-view-btn.is-active {
            background: #23395d;
            color: #fff;
            box-shadow: 0 6px 16px rgba(31, 45, 61, 0.16);
        }

        .inspection-detail-stats .inspection-pill {
            color: #fff;
        }

        .inspection-detail-stats .inspection-pill-soft {
            color: #fff;
            background: #5c748c;
        }

        .inspection-theme-ndt .inspection-detail-stats .inspection-pill-soft {
            background: #a66a10;
        }

        .inspection-theme-tubular .inspection-detail-stats .inspection-pill-soft {
            background: #2d73d5;
        }

        .inspection-theme-drop-object .inspection-detail-stats .inspection-pill-soft {
            background: #7a4fe0;
        }

        .inspection-theme-calibration .inspection-detail-stats .inspection-pill-soft {
            background: #0f8d80;
        }

        .inspection-group {
            border-top: 1px solid #edf2f8;
            padding-top: 1.1rem;
            margin-top: 1.1rem;
            position: relative;
        }

        .inspection-group:first-of-type {
            border-top: 0;
            padding-top: 0;
            margin-top: 0;
        }

        .inspection-group-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.8rem;
            flex-wrap: wrap;
        }

        .inspection-group-title {
            margin: 0;
            color: #203047;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .inspection-group-meta {
            color: #7a8798;
            font-size: 0.83rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .inspection-group::before {
            content: '';
            position: absolute;
            left: -1.35rem;
            top: 1.1rem;
            bottom: 0;
            width: 4px;
            border-radius: 999px;
            background: #d8e1ee;
            opacity: 0;
        }

        .inspection-group:first-of-type::before {
            top: 0;
        }

        .inspection-group.has-accent::before {
            opacity: 1;
        }

        .inspection-group.accent-core-equipment::before,
        .inspection-group.accent-surface-and-visual::before,
        .inspection-group.accent-overview::before,
        .inspection-group.accent-survey::before,
        .inspection-group.accent-certificates::before {
            background: #4f6df5;
        }

        .inspection-group.accent-assurance-and-follow-up::before,
        .inspection-group.accent-hydro-and-support::before,
        .inspection-group.accent-connections-and-tools::before {
            background: #d28920;
        }

        .inspection-group.accent-ultrasonic::before,
        .inspection-group.accent-pipe-body::before {
            background: #0f9d8f;
        }

        .inspection-section-detail[data-view-mode="cards"] .inspection-report-list {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }

        .inspection-section-detail[data-view-mode="cards"] .inspection-report-row {
            min-height: 100%;
            align-items: stretch;
            justify-content: flex-start;
            flex-direction: column;
            padding: 1rem;
            border-color: #dfe6f2;
            box-shadow: 0 12px 24px rgba(17, 24, 39, 0.05);
        }

        .inspection-section-detail[data-view-mode="cards"] .inspection-report-meta {
            width: 100%;
        }

        .inspection-section-detail[data-view-mode="cards"] .inspection-report-meta > div:last-child {
            width: 100%;
        }

        .inspection-section-detail[data-view-mode="cards"] .inspection-report-actions {
            width: 100%;
            margin-top: auto;
            padding-top: 0.9rem;
            border-top: 1px solid #eef3fb;
        }

        .inspection-empty-state {
            border: 1px dashed #d8e1ee;
            border-radius: 14px;
            padding: 1rem;
            color: #7a8798;
            background: #f9fbff;
            text-align: center;
        }

        .inspection-pinned-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .inspection-pinned-card {
            background: linear-gradient(135deg, #fff 0%, #f7faff 100%);
            border: 1px solid #dfe7f3;
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 14px 28px rgba(17, 24, 39, 0.06);
        }

        a.inspection-pinned-card {
            display: block;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        a.inspection-pinned-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 32px rgba(17, 24, 39, 0.1);
            text-decoration: none;
        }

        .inspection-pinned-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #7a8798;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.65rem;
        }

        .inspection-pinned-title {
            color: #203047;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .inspection-pinned-number {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: #fff;
            background: #4f6df5;
            box-shadow: 0 10px 20px rgba(17, 24, 39, 0.12);
            margin-bottom: 0.7rem;
        }

        .inspection-pinned-copy {
            color: #627287;
            font-size: 0.86rem;
            margin-bottom: 0.7rem;
        }

        .inspection-pinned-stats {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
            margin-bottom: 0.8rem;
        }

        .inspection-pinned-actions {
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .inspection-queue-action {
            border-radius: 999px;
            padding: 0.48rem 0.82rem;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 8px 16px rgba(17, 24, 39, 0.08);
        }

        .inspection-queue-action.is-approve {
            background: #d28920;
            color: #fff;
            border: 1px solid #c07a16;
        }

        .inspection-queue-action.is-publish {
            background: #2d73d5;
            color: #fff;
            border: 1px solid #2365c1;
        }

        .inspection-queue-action.is-open {
            background: #0f9d8f;
            color: #fff;
            border: 1px solid #0d8b7e;
        }

        .inspection-theme-lifting {
            border-top: 4px solid #4d677b;
        }

        .inspection-theme-lifting .inspection-pill,
        .inspection-theme-lifting .inspection-report-order,
        .inspection-theme-lifting .inspection-report-visual {
            background: #4d677b;
        }

        .inspection-theme-ndt {
            border-top: 4px solid #d28920;
        }

        .inspection-theme-ndt .inspection-pill,
        .inspection-theme-ndt .inspection-report-order,
        .inspection-theme-ndt .inspection-report-visual {
            background: #d28920;
        }

        .inspection-theme-tubular {
            border-top: 4px solid #2d73d5;
        }

        .inspection-theme-tubular .inspection-pill,
        .inspection-theme-tubular .inspection-report-order,
        .inspection-theme-tubular .inspection-report-visual {
            background: #2d73d5;
        }

        .inspection-theme-drop-object {
            border-top: 4px solid #8a5cf6;
        }

        .inspection-theme-drop-object .inspection-pill,
        .inspection-theme-drop-object .inspection-report-order,
        .inspection-theme-drop-object .inspection-report-visual {
            background: #8a5cf6;
        }

        .inspection-theme-calibration {
            border-top: 4px solid #0f9d8f;
        }

        .inspection-theme-calibration .inspection-pill,
        .inspection-theme-calibration .inspection-report-order,
        .inspection-theme-calibration .inspection-report-visual {
            background: #0f9d8f;
        }

        @media (max-width: 991.98px) {
            .inspection-summary-grid {
                grid-template-columns: 1fr;
            }

            .inspection-hero-title {
                font-size: 1.65rem;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $isAllMode = ($catalog_mode ?? 'all') === 'all';
        $heroTitle = $isAllMode ? 'Inspection Hub' : ($catalog_active_section['title'] ?? 'Inspection Section');
        $heroCopy = $isAllMode
            ? 'Jump into the right inspection section faster, review operational queues, and avoid scanning one long static list.'
            : (($catalog_active_section['description'] ?? '') . ' Focus here on report types, current queues, and the fastest path to the listing you need.');
        $activeSectionThemeClass = 'inspection-theme-' . (($catalog_active_section['theme'] ?? 'lifting'));
    @endphp

    <section class="inspection-catalog-shell">
        <div class="inspection-hero">
            <div class="inspection-hero-top">
                <div>
                    <span class="inspection-hero-kicker">{{ $isAllMode ? 'All Inspections' : 'Inspection Section' }}</span>
                    <h2 class="inspection-hero-title">{{ $heroTitle }}</h2>
                    <p class="inspection-hero-copy">{{ $heroCopy }}</p>
                </div>
                @if(!$isAllMode)
                    <div>
                        <a href="{{ route('inspection.all') }}" class="btn btn-outline-primary">Back To All Inspections</a>
                    </div>
                @endif
            </div>

            <div class="inspection-summary-grid">
                @if($isAllMode)
                    @foreach(($catalog_spotlights ?? []) as $spotlight)
                        @php
                            $spotlightTag = !empty($spotlight['url']) ? 'a' : 'div';
                        @endphp
                        <{{ $spotlightTag }} @if(!empty($spotlight['url'])) href="{{ $spotlight['url'] }}" @endif class="inspection-summary-card">
                            <div class="inspection-summary-label">{{ $spotlight['label'] }}</div>
                            <div class="inspection-summary-value">{{ $spotlight['value'] ?? 0 }}</div>
                            <div class="inspection-summary-title mt-50">{{ $spotlight['title'] }}</div>
                            <div class="inspection-summary-copy">{{ $spotlight['description'] }}</div>
                            <div class="inspection-summary-link">{{ $spotlight['action_label'] }}</div>
                        </{{ $spotlightTag }}>
                    @endforeach
                @else
                    @foreach(($catalog_spotlights ?? []) as $spotlight)
                        @php
                            $spotlightTag = !empty($spotlight['url']) ? 'a' : 'div';
                        @endphp
                        <{{ $spotlightTag }} @if(!empty($spotlight['url'])) href="{{ $spotlight['url'] }}" @endif class="inspection-summary-card">
                            <div class="inspection-summary-label">{{ $spotlight['label'] }}</div>
                            <div class="inspection-summary-value">{{ $spotlight['value'] ?? 0 }}</div>
                            <div class="inspection-summary-title mt-50">{{ $spotlight['title'] }}</div>
                            <div class="inspection-summary-copy">{{ $spotlight['description'] }}</div>
                            <div class="inspection-summary-link">{{ $spotlight['action_label'] }}</div>
                        </{{ $spotlightTag }}>
                    @endforeach
                @endif
            </div>
        </div>

        @if($isAllMode)
            <div class="inspection-overview-shell">
                <div class="inspection-overview-command">
                    <div class="inspection-overview-command-top">
                        <div class="inspection-global-search">
                            <label for="inspection-global-search">Inspection Search</label>
                            <input id="inspection-global-search" type="text" placeholder="Search report names, groups, or keywords" data-inspection-global-search>
                            <small>Use one search only, then limit it to a single category or keep it across all tabs.</small>
                        </div>
                        <div class="inspection-overview-category">
                            <label for="inspection-overview-category-select">Category</label>
                            <div class="inspection-overview-category-tools">
                                <select id="inspection-overview-category-select" data-inspection-overview-category>
                                    <option value="all">All categories</option>
                                    @foreach($catalog_sections as $section)
                                        <option value="{{ $section['key'] }}">{{ $section['title'] }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="inspection-overview-pin" data-inspection-overview-pin>
                                    <i class="la la-thumb-tack"></i>
                                    <span>Pin Category</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="inspection-overview-command-note">
                        <span data-inspection-overview-note>Tabs remain available for browsing, but the search is now unified across the overview.</span>
                        <span data-inspection-overview-pin-state></span>
                    </div>
                </div>

                @if(!empty($catalog_recent_activity))
                    <div class="inspection-recent-strip">
                        @foreach(($catalog_recent_activity ?? []) as $activity)
                            @php($activityTag = !empty($activity['url']) ? 'a' : 'div')
                            <{{ $activityTag }} @if(!empty($activity['url'])) href="{{ $activity['url'] }}" @endif class="inspection-recent-card">
                                <div class="inspection-recent-head">
                                    <span class="inspection-recent-icon"><i class="{{ $activity['icon_class'] ?? 'la la-file-text-o' }}"></i></span>
                                    <div>
                                        <div class="inspection-recent-kicker">{{ $activity['activity_label'] }} · {{ $activity['section_title'] }}</div>
                                        <div class="inspection-recent-title">{{ $activity['title'] }}</div>
                                    </div>
                                </div>
                                <div class="inspection-recent-code">{{ $activity['code'] }}</div>
                                <div class="inspection-recent-meta">{{ $activity['activity_at'] }}</div>
                            </{{ $activityTag }}>
                        @endforeach
                    </div>
                @endif

                <ul class="nav nav-pills inspection-overview-tabs" role="tablist">
                    @foreach($catalog_sections as $section)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="inspection-overview-tab-{{ $section['key'] }}" data-toggle="tab" href="#inspection-overview-pane-{{ $section['key'] }}" role="tab" aria-controls="inspection-overview-pane-{{ $section['key'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" data-section-key="{{ $section['key'] }}">
                                <span class="inspection-overview-tab-label">
                                    <span>{{ $section['title'] }}</span>
                                    <span class="inspection-overview-tab-stats">
                                        <span class="inspection-overview-tab-badge">{{ $section['total_report_count'] ?? 0 }} total</span>
                                        <span class="inspection-overview-tab-badge is-approve">{{ $section['pending_approve_count'] ?? 0 }} approve</span>
                                        <span class="inspection-overview-tab-badge is-publish">{{ $section['pending_publish_count'] ?? 0 }} publish</span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($catalog_sections as $section)
                        <div class="tab-pane fade inspection-overview-tab-pane {{ $loop->first ? 'show active' : '' }}" id="inspection-overview-pane-{{ $section['key'] }}" role="tabpanel" aria-labelledby="inspection-overview-tab-{{ $section['key'] }}">
                            @include('layouts.inspection.partials.catalog-section-detail', ['section' => $section, 'showLocalSearch' => false])
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            @include('layouts.inspection.partials.catalog-section-detail', ['section' => $catalog_active_section])
        @endif
    </section>
@endsection

@section('footer')
    @parent
    <script>
        (function () {
            var detailRoots = Array.prototype.slice.call(document.querySelectorAll('[data-inspection-detail-root]'));
            if (!detailRoots.length) {
                return;
            }
            var globalSearchInput = document.querySelector('[data-inspection-global-search]');
            var overviewCategorySelect = document.querySelector('[data-inspection-overview-category]');
            var overviewPinButton = document.querySelector('[data-inspection-overview-pin]');
            var overviewPinState = document.querySelector('[data-inspection-overview-pin-state]');
            var overviewNote = document.querySelector('[data-inspection-overview-note]');
            var pinnedCategoryStorageKey = 'inspection-overview-pinned-tab';

            var getSectionKeyFromHash = function (hashValue) {
                if (!hashValue || hashValue.charAt(0) !== '#') {
                    return '';
                }

                return hashValue.replace('#inspection-overview-pane-', '');
            };

            var activateOverviewTab = function (hashValue) {
                if (!hashValue || hashValue.charAt(0) !== '#') {
                    return false;
                }

                var tabTrigger = document.querySelector('.inspection-overview-tabs .nav-link[href="' + hashValue + '"]');
                if (!tabTrigger) {
                    tabTrigger = document.querySelector(hashValue);
                    if (tabTrigger && !tabTrigger.classList.contains('nav-link')) {
                        var paneId = tabTrigger.getAttribute('id');
                        if (paneId) {
                            tabTrigger = document.querySelector('.inspection-overview-tabs .nav-link[href="#' + paneId + '"]');
                        }
                    }
                }

                if (!tabTrigger) {
                    return false;
                }

                if (window.jQuery && window.jQuery.fn && typeof window.jQuery(tabTrigger).tab === 'function') {
                    window.jQuery(tabTrigger).tab('show');
                } else {
                    tabTrigger.click();
                }

                return true;
            };

            var syncOverviewCategorySelect = function (hashValue) {
                if (!overviewCategorySelect) {
                    return;
                }

                var sectionKey = getSectionKeyFromHash(hashValue);
                if (!sectionKey) {
                    return;
                }

                overviewCategorySelect.value = sectionKey;
            };

            var refreshPinnedCategoryUi = function () {
                if (!overviewPinButton) {
                    return;
                }

                var pinnedCategory = '';
                try {
                    pinnedCategory = window.localStorage.getItem(pinnedCategoryStorageKey) || '';
                } catch (error) {
                }

                var selectedCategory = overviewCategorySelect ? (overviewCategorySelect.value || 'all') : 'all';
                var isPinned = pinnedCategory !== '' && selectedCategory === pinnedCategory;

                overviewPinButton.classList.toggle('is-active', isPinned);

                if (overviewPinState) {
                    if (!pinnedCategory) {
                        overviewPinState.textContent = 'No pinned category.';
                    } else {
                        var selectedOption = overviewCategorySelect
                            ? overviewCategorySelect.querySelector('option[value="' + pinnedCategory + '"]')
                            : null;
                        overviewPinState.textContent = 'Pinned category: ' + (selectedOption ? selectedOption.textContent : pinnedCategory);
                    }
                }

                if (overviewNote) {
                    overviewNote.textContent = selectedCategory === 'all'
                        ? 'The unified search scans all tabs and opens the first category with matches.'
                        : 'The unified search is currently limited to the selected category only.';
                }
            };

            if (!activateOverviewTab(window.location.hash)) {
                try {
                    var savedOverviewTab = window.localStorage.getItem(pinnedCategoryStorageKey)
                        ? '#inspection-overview-pane-' + window.localStorage.getItem(pinnedCategoryStorageKey)
                        : window.localStorage.getItem('inspection-overview-active-tab');
                    activateOverviewTab(savedOverviewTab || '');
                } catch (error) {
                }
            }

            var activeOverviewTrigger = document.querySelector('.inspection-overview-tabs .nav-link.active');
            syncOverviewCategorySelect(window.location.hash || (activeOverviewTrigger ? activeOverviewTrigger.getAttribute('href') : '') || '');
            refreshPinnedCategoryUi();

            if (window.jQuery) {
                window.jQuery('.inspection-overview-tabs .nav-link').on('shown.bs.tab', function (event) {
                    var targetHash = event.target.getAttribute('href') || '';
                    if (!targetHash) {
                        return;
                    }

                    try {
                        window.localStorage.setItem('inspection-overview-active-tab', targetHash);
                    } catch (error) {
                    }

                    if (window.history && typeof window.history.replaceState === 'function') {
                        window.history.replaceState(null, '', targetHash);
                    }

                    syncOverviewCategorySelect(targetHash);
                    refreshPinnedCategoryUi();
                });
            }

            if (overviewCategorySelect) {
                overviewCategorySelect.addEventListener('change', function () {
                    var selectedCategory = overviewCategorySelect.value || 'all';
                    if (selectedCategory !== 'all') {
                        activateOverviewTab('#inspection-overview-pane-' + selectedCategory);
                    }

                    refreshPinnedCategoryUi();

                    detailRoots.forEach(function (detailRoot) {
                        if (typeof detailRoot.__inspectionApplyFilter === 'function') {
                            detailRoot.__inspectionApplyFilter();
                        }
                    });
                });
            }

            if (overviewPinButton) {
                overviewPinButton.addEventListener('click', function () {
                    if (!overviewCategorySelect) {
                        return;
                    }

                    var selectedCategory = overviewCategorySelect.value || 'all';
                    try {
                        var currentPinned = window.localStorage.getItem(pinnedCategoryStorageKey) || '';
                        if (selectedCategory === 'all' || currentPinned === selectedCategory) {
                            window.localStorage.removeItem(pinnedCategoryStorageKey);
                        } else {
                            window.localStorage.setItem(pinnedCategoryStorageKey, selectedCategory);
                        }
                    } catch (error) {
                    }

                    refreshPinnedCategoryUi();
                });
            }

            detailRoots.forEach(function (detailRoot) {
                var input = detailRoot.querySelector('[data-inspection-filter-input]');
                var items = Array.prototype.slice.call(detailRoot.querySelectorAll('[data-inspection-item]'));
                var groups = Array.prototype.slice.call(detailRoot.querySelectorAll('[data-inspection-group]'));
                var emptyState = detailRoot.querySelector('[data-inspection-empty-state]');
                var viewButtons = Array.prototype.slice.call(detailRoot.querySelectorAll('[data-inspection-view-trigger]'));
                var filterButtons = Array.prototype.slice.call(detailRoot.querySelectorAll('[data-inspection-filter-trigger]'));
                var focusButtons = Array.prototype.slice.call(detailRoot.querySelectorAll('[data-inspection-focus-trigger]'));
                var focusPanels = Array.prototype.slice.call(detailRoot.querySelectorAll('[data-inspection-focus-panel]'));
                var clickRows = Array.prototype.slice.call(detailRoot.querySelectorAll('.inspection-report-row[data-primary-url]'));
                var sectionKey = detailRoot.getAttribute('data-section-key') || 'inspection-section';
                var storageKey = 'inspection-section-view-mode:' + sectionKey;
                var activeFilterTag = 'all';

                function applyViewMode(mode) {
                    var normalizedMode = mode === 'cards' ? 'cards' : 'list';
                    detailRoot.setAttribute('data-view-mode', normalizedMode);
                    viewButtons.forEach(function (button) {
                        var isActive = button.getAttribute('data-view-mode') === normalizedMode;
                        button.classList.toggle('is-active', isActive);
                    });

                    try {
                        window.localStorage.setItem(storageKey, normalizedMode);
                    } catch (error) {
                    }
                }

                function applyFilter() {
                    var term = input ? (input.value || '').toLowerCase().trim() : '';
                    var globalTerm = globalSearchInput ? (globalSearchInput.value || '').toLowerCase().trim() : '';
                    var selectedCategory = overviewCategorySelect ? (overviewCategorySelect.value || 'all') : 'all';
                    var currentSectionKey = detailRoot.getAttribute('data-section-key') || '';
                    var categoryAllowsRoot = selectedCategory === 'all' || selectedCategory === currentSectionKey;
                    var visibleCount = 0;

                    items.forEach(function (item) {
                        var haystack = (item.getAttribute('data-filter-text') || '').toLowerCase();
                        var tags = (item.getAttribute('data-filter-tags') || '').split(/\s+/);
                        var matchesText = term === '' || haystack.indexOf(term) !== -1;
                        var matchesGlobalText = globalTerm === '' || haystack.indexOf(globalTerm) !== -1;
                        var matchesTag = activeFilterTag === 'all' || tags.indexOf(activeFilterTag) !== -1;
                        var matches = categoryAllowsRoot && matchesText && matchesGlobalText && matchesTag;
                        item.style.display = matches ? '' : 'none';
                        if (matches) {
                            visibleCount++;
                        }
                    });

                    groups.forEach(function (group) {
                        var visibleRows = group.querySelectorAll('[data-inspection-item]:not([style*="display: none"])');
                        group.style.display = visibleRows.length ? '' : 'none';
                    });

                    if (emptyState) {
                        emptyState.style.display = visibleCount ? 'none' : '';
                    }
                }

                if (input) {
                    input.addEventListener('input', applyFilter);
                }

                detailRoot.__inspectionApplyFilter = applyFilter;

                filterButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        activeFilterTag = button.getAttribute('data-filter-tag') || 'all';
                        filterButtons.forEach(function (candidate) {
                            candidate.classList.toggle('is-active', candidate === button);
                        });
                        applyFilter();
                    });
                });

                focusButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var focusKey = button.getAttribute('data-focus-key') || '';
                        focusButtons.forEach(function (candidate) {
                            candidate.classList.toggle('is-active', candidate === button);
                        });
                        focusPanels.forEach(function (panel) {
                            panel.style.display = panel.getAttribute('data-focus-key') === focusKey ? '' : 'none';
                        });
                    });
                });

                viewButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        applyViewMode(button.getAttribute('data-view-mode') || 'list');
                    });
                });

                clickRows.forEach(function (row) {
                    row.addEventListener('click', function (event) {
                        if (event.target.closest('a, button, input, select, textarea, label')) {
                            return;
                        }

                        var targetUrl = row.getAttribute('data-primary-url');
                        if (targetUrl) {
                            window.location.href = targetUrl;
                        }
                    });

                    row.addEventListener('keydown', function (event) {
                        if (event.key !== 'Enter' && event.key !== ' ') {
                            return;
                        }

                        if (event.target.closest('a, button, input, select, textarea, label')) {
                            return;
                        }

                        event.preventDefault();
                        var targetUrl = row.getAttribute('data-primary-url');
                        if (targetUrl) {
                            window.location.href = targetUrl;
                        }
                    });
                });

                var savedMode = 'list';
                try {
                    savedMode = window.localStorage.getItem(storageKey) || 'list';
                } catch (error) {
                }

                applyViewMode(savedMode);
                applyFilter();
            });

            if (globalSearchInput) {
                globalSearchInput.addEventListener('input', function () {
                    detailRoots.forEach(function (detailRoot) {
                        if (typeof detailRoot.__inspectionApplyFilter === 'function') {
                            detailRoot.__inspectionApplyFilter();
                        }
                    });

                    var firstMatchingRoot = detailRoots.find(function (detailRoot) {
                        return !!detailRoot.querySelector('[data-inspection-item]:not([style*="display: none"])');
                    });

                    if (!firstMatchingRoot) {
                        return;
                    }

                    var selectedCategory = overviewCategorySelect ? (overviewCategorySelect.value || 'all') : 'all';
                    if (selectedCategory === 'all') {
                        var pane = firstMatchingRoot.closest('.tab-pane');
                        if (!pane || !pane.id) {
                            return;
                        }

                        activateOverviewTab('#' + pane.id);
                    }
                });
            }
        })();
    </script>
@endsection
