<style>
    .listing-table-shell .table-responsive,
    .listing-table-shell .dataTables_wrapper,
    .listing-table-shell .dataTables_scroll,
    .listing-table-shell .dataTables_scrollBody,
    .listing-table-shell .dataTables_scrollHead {
        overflow: visible;
    }

    .listing-table-shell .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
    }

    .listing-table-shell #users-list {
        margin-bottom: 0 !important;
    }

    .listing-table-shell #users-list td,
    .listing-table-shell #users-list th {
        vertical-align: middle;
    }

    .listing-table-shell #users-list thead tr.column-headings th {
        white-space: nowrap;
    }

    .listing-table-shell #users-list thead tr.filter-row th {
        padding: 6px 6px !important;
        background: #f5f7fb;
        border-top: 0 !important;
    }

    .listing-table-shell #users-list thead tr.filter-row input,
    .listing-table-shell #users-list thead tr.filter-row select {
        width: 100%;
        min-width: 90px;
        height: 34px;
        padding: 4px 8px;
        border: 1px solid #d3dcf0;
        border-radius: 6px;
        background: #fff;
    }

    .listing-table-shell #users-list thead tr.column-headings th.sorting,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_asc,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_desc {
        cursor: pointer;
        transition: background-color .15s ease, color .15s ease;
        padding-right: 24px !important;
    }

    .listing-table-shell #users-list thead tr.column-headings th.sorting_asc,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_desc {
        background: #f2f5ff;
        color: #5a46d6;
    }

    .listing-table-shell #users-list thead tr.column-headings th.sorting:before,
    .listing-table-shell #users-list thead tr.column-headings th.sorting:after,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_asc:before,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_asc:after,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_desc:before,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_desc:after {
        opacity: .35 !important;
    }

    .listing-table-shell #users-list thead tr.column-headings th.sorting_asc:before,
    .listing-table-shell #users-list thead tr.column-headings th.sorting_desc:after {
        opacity: 1 !important;
        color: #5a46d6 !important;
    }

    .listing-table-toolbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
    }

    .listing-table-toolbar.is-sticky {
        position: sticky;
        top: 78px;
        z-index: 90;
        background: #fff;
        border-bottom: 1px solid #edf1fb;
        padding: 8px 0 6px;
        margin-bottom: 12px;
    }

    .listing-table-toolbar .listing-toolbar-actions,
    .listing-table-toolbar .listing-toolbar-presets {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
    }

    .listing-table-toolbar .btn {
        margin: 0;
    }

    .listing-table-toolbar .listing-toolbar-pill {
        border-radius: 999px;
    }

    .listing-table-toolbar .listing-toolbar-pill.is-active {
        color: #fff !important;
        background: #5a46d6 !important;
        border-color: #5a46d6 !important;
    }

    .listing-table-toolbar .listing-toolbar-clear {
        white-space: nowrap;
        border-radius: 10px;
    }

    .listing-table-shell .wf-inline-actions,
    .listing-table-shell .jcf-inline-actions,
    .listing-table-shell .jcf-inline-status {
        display: inline-flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 6px;
    }

    .listing-table-shell .wf-inline-actions .btn,
    .listing-table-shell .wf-inline-actions .btn-group,
    .listing-table-shell .jcf-inline-actions .btn,
    .listing-table-shell .jcf-inline-status .btn,
    .listing-table-shell .jcf-inline-status .btn-group,
    .listing-table-shell .jcf-inline-status p.btn {
        margin: 0 !important;
    }

    .listing-table-shell .wf-table-dropdown .dropdown-toggle,
    .listing-table-shell .wf-inline-actions .btn-group .dropdown-toggle,
    .listing-table-shell .jcf-inline-status .btn-group .dropdown-toggle {
        min-width: 74px;
    }

    .listing-table-shell .wf-table-dropdown-menu,
    .listing-table-shell .wf-inline-actions .dropdown-menu,
    .listing-table-shell .jcf-inline-status .dropdown-menu,
    .wf-floating-dropdown-menu {
        min-width: 250px;
        max-width: 320px;
        max-height: min(65vh, 420px);
        overflow: auto;
        border: 1px solid #dde4f3;
        box-shadow: 0 16px 36px rgba(20, 30, 70, 0.16);
        border-radius: 12px;
        padding: 8px 0;
        z-index: 1080;
        overflow-x: hidden;
        background: #fff;
    }

    .wf-floating-dropdown-menu {
        position: absolute !important;
        display: block !important;
        margin: 0 !important;
    }

    .listing-table-shell .wf-inline-actions .dropdown-menu.show,
    .listing-table-shell .jcf-inline-status .dropdown-menu.show,
    .listing-table-shell .wf-table-dropdown-menu.show,
    .wf-floating-dropdown-menu.show {
        overflow: hidden auto;
    }

    .listing-table-shell .wf-table-dropdown-menu .dropdown-header,
    .listing-table-shell .wf-inline-actions .dropdown-menu .dropdown-header,
    .listing-table-shell .jcf-inline-status .dropdown-menu .dropdown-header,
    .wf-floating-dropdown-menu .dropdown-header {
        padding: 8px 14px 4px;
        font-size: .72rem;
        letter-spacing: .04em;
        text-transform: uppercase;
        font-weight: 700;
        color: #6b7794;
    }

    .listing-table-shell .wf-table-dropdown-menu .dropdown-item,
    .listing-table-shell .wf-inline-actions .dropdown-menu .dropdown-item,
    .listing-table-shell .jcf-inline-status .dropdown-menu .dropdown-item,
    .wf-floating-dropdown-menu .dropdown-item {
        white-space: normal;
        padding: 9px 14px;
        line-height: 1.35;
        position: relative;
        z-index: 1;
    }

    .listing-table-shell .wf-table-dropdown-menu .dropdown-item:hover,
    .listing-table-shell .wf-table-dropdown-menu .dropdown-item:focus,
    .listing-table-shell .wf-inline-actions .dropdown-menu .dropdown-item:hover,
    .listing-table-shell .wf-inline-actions .dropdown-menu .dropdown-item:focus,
    .listing-table-shell .jcf-inline-status .dropdown-menu .dropdown-item:hover,
    .listing-table-shell .jcf-inline-status .dropdown-menu .dropdown-item:focus,
    .wf-floating-dropdown-menu .dropdown-item:hover,
    .wf-floating-dropdown-menu .dropdown-item:focus {
        background: #f4f6ff;
        color: #4f40c7;
    }

    .listing-table-shell .wf-table-dropdown-menu .dropdown-divider,
    .listing-table-shell .wf-inline-actions .dropdown-menu .dropdown-divider,
    .listing-table-shell .jcf-inline-status .dropdown-menu .dropdown-divider,
    .wf-floating-dropdown-menu .dropdown-divider {
        margin: 6px 0;
    }

    @media (max-width: 767.98px) {
        .listing-table-toolbar,
        .listing-table-toolbar .listing-toolbar-actions,
        .listing-table-toolbar .listing-toolbar-presets {
            width: 100%;
        }
    }
</style>
