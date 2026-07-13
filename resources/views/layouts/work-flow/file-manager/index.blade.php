@extends('layouts.app')

@include('layouts.styles.datatables')

@section('header-bottom')
<style>
		#users-list thead tr.column-headings th { white-space: nowrap; }
		#users-list thead tr.column-headings th:first-child,
		#users-list thead tr.filter-row th:first-child {
				width: 42px;
				min-width: 42px;
				max-width: 42px;
				text-align: center;
		}
		#users-list thead tr.filter-row th {
				padding: 6px 6px !important;
				background: #f5f7fb;
				border-top: 0 !important;
		}
		#users-list thead tr.filter-row input,
		#users-list thead tr.filter-row select {
				width: 100%;
				min-width: 90px;
				height: 34px;
				padding: 4px 8px;
				border: 1px solid #d3dcf0;
				border-radius: 6px;
		}
		#users-list td:last-child { white-space: nowrap; }
		#users-list .wf-inline-actions {
				display: inline-flex;
				align-items: center;
				flex-wrap: nowrap;
				gap: 6px;
		}
		.file-manager-kpis .kpi-card {
				border: 1px solid #e4eafc;
				border-radius: 12px;
				background: #fdfdff;
				padding: 12px 14px;
				height: 100%;
		}
		.file-manager-kpis .kpi-label {
				font-size: 0.8rem;
				color: #7583ab;
				margin-bottom: 4px;
				text-transform: uppercase;
				letter-spacing: 0.03em;
		}
		.file-manager-kpis .kpi-value {
				font-size: 1.25rem;
				font-weight: 700;
				color: #2d3a64;
				line-height: 1.2;
		}
		.file-manager-preset .btn {
				border-radius: 999px;
				padding: 0.35rem 0.8rem;
		}
		.file-manager-preset .btn.active {
				background: #5e3ec9;
				border-color: #5e3ec9;
				color: #fff;
		}
		.file-view-toggle .btn {
				border-radius: 999px;
				padding: 0.35rem 0.9rem;
				font-weight: 600;
		}
		.file-view-toggle .btn.active {
				background: #2f6fed;
				border-color: #2f6fed;
				color: #fff;
		}
		.file-manager-filter-chip {
				display: inline-flex;
				align-items: center;
				padding: 0.2rem 0.55rem;
				border-radius: 999px;
				background: #eef2ff;
				border: 1px solid #d8e1ff;
				color: #465480;
				font-size: 0.76rem;
				font-weight: 600;
				margin: 0 0.35rem 0.35rem 0;
		}
		.file-preview-wrap {
				width: 100%;
				min-height: 64vh;
				background: #f7f9ff;
				border: 1px solid #e6ecff;
				border-radius: 8px;
				overflow: hidden;
		}
		.file-preview-wrap iframe,
		.file-preview-wrap img {
				width: 100%;
				height: 64vh;
				border: 0;
				object-fit: contain;
				background: #fff;
		}
		.folder-explorer-layout {
				display: grid;
				grid-template-columns: minmax(0, 1.75fr) minmax(320px, 0.9fr);
				gap: 16px;
				align-items: start;
		}
		.folder-explorer-panel,
		.preview-sidebar {
				border: 1px solid #dfe7ff;
				border-radius: 16px;
				background: #fff;
				box-shadow: 0 16px 40px rgba(23, 42, 98, 0.06);
		}
		.preview-sidebar {
				position: sticky;
				top: 90px;
				overflow: hidden;
		}
		.preview-sidebar-head {
				padding: 12px 14px;
				border-bottom: 1px solid #edf2ff;
				background: linear-gradient(135deg, #f7f9ff 0%, #ffffff 100%);
		}
		.preview-sidebar-body {
				padding: 14px;
		}
		.preview-sidebar-empty {
				min-height: 360px;
				border: 1px dashed #d7e2ff;
				border-radius: 14px;
				background: linear-gradient(180deg, #fbfcff 0%, #f4f7ff 100%);
				color: #60719d;
				display: flex;
				align-items: center;
				justify-content: center;
				text-align: center;
				padding: 20px;
		}
		.preview-sidebar-viewer {
				width: 100%;
				min-height: 420px;
				border: 1px solid #e6ecff;
				border-radius: 12px;
				background: #f9fbff;
				overflow: hidden;
		}
		.preview-sidebar-viewer iframe,
		.preview-sidebar-viewer img {
				width: 100%;
				height: 420px;
				border: 0;
				object-fit: contain;
				background: #fff;
		}
		.folder-tree {
				padding: 14px;
		}
		.folder-node {
				border: 1px solid #e7edff;
				border-radius: 14px;
				background: #fff;
				margin-bottom: 12px;
				overflow: hidden;
		}
		.folder-theme-inspection > .folder-summary { background: linear-gradient(135deg, #eef4ff 0%, #ffffff 100%); }
		.folder-theme-workflow > .folder-summary { background: linear-gradient(135deg, #f3f8ff 0%, #ffffff 100%); }
		.folder-theme-images > .folder-summary { background: linear-gradient(135deg, #fff6eb 0%, #ffffff 100%); }
		.folder-theme-pdf > .folder-summary { background: linear-gradient(135deg, #fff0f0 0%, #ffffff 100%); }
		.folder-theme-ndt > .folder-summary { background: linear-gradient(135deg, #eefcfa 0%, #ffffff 100%); }
		.folder-theme-tubular > .folder-summary { background: linear-gradient(135deg, #f4efff 0%, #ffffff 100%); }
		.folder-theme-lifting > .folder-summary { background: linear-gradient(135deg, #fef4ed 0%, #ffffff 100%); }
		.folder-theme-calibration > .folder-summary { background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); }
		.folder-theme-neutral > .folder-summary { background: linear-gradient(135deg, #f9fbff 0%, #ffffff 100%); }
		.folder-node .folder-node {
				margin: 10px 12px;
				border-radius: 12px;
		}
		.folder-node summary {
				list-style: none;
				cursor: pointer;
		}
		.folder-node summary::-webkit-details-marker {
				display: none;
		}
		.folder-summary {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 12px;
				padding: 12px 14px;
				color: #324271;
				background: linear-gradient(135deg, #f9fbff 0%, #ffffff 100%);
		}
		.folder-node[open] > .folder-summary {
				border-bottom: 1px solid #edf2ff;
		}
		.folder-summary-main {
				display: flex;
				align-items: center;
				gap: 10px;
				min-width: 0;
		}
		.folder-toggle-icon {
				font-size: 0.95rem;
				color: #5a6fae;
				transition: transform 0.18s ease;
		}
		.folder-node[open] > .folder-summary .folder-toggle-icon {
				transform: rotate(90deg);
		}
		.folder-node-title {
				font-weight: 700;
				letter-spacing: 0.01em;
		}
		.folder-summary-meta {
				display: flex;
				align-items: center;
				gap: 8px;
				flex-wrap: wrap;
		}
		.folder-node-body {
				padding: 2px 0 10px;
				background: #fcfdff;
		}
		.file-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
				gap: 10px;
				padding: 0 12px 0;
		}
		.report-family-stack {
				display: grid;
				gap: 12px;
				padding: 0 12px 0;
		}
		.report-family-card {
				border: 1px solid #dfe7ff;
				border-radius: 14px;
				background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
				box-shadow: 0 10px 28px rgba(39, 61, 125, 0.05);
				overflow: hidden;
		}
		.report-family-card.is-exportable {
				border-color: #cfe0ff;
				box-shadow: 0 12px 30px rgba(47, 111, 237, 0.08);
		}
		.report-family-head {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 12px;
				padding: 12px 14px;
				border-bottom: 1px solid #ebf1ff;
				background: linear-gradient(135deg, #f7f9ff 0%, #ffffff 100%);
		}
		.report-family-title {
				font-weight: 800;
				color: #24345f;
				letter-spacing: 0.01em;
		}
		.report-family-meta {
				font-size: 0.82rem;
				color: #6b7aa6;
				margin-top: 2px;
		}
		.report-family-actions {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
				align-items: center;
				justify-content: flex-end;
		}
		.file-card {
				border: 1px solid #e2e9ff;
				border-radius: 10px;
				background: #fff;
				padding: 10px;
				box-shadow: 0 8px 24px rgba(39, 61, 125, 0.05);
		}
		.file-card.is-inspection {
				border-color: #cfe0ff;
				box-shadow: 0 10px 28px rgba(47, 111, 237, 0.08);
		}
		.file-card-head {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 10px;
				margin-bottom: 8px;
		}
		.file-card-badges {
				display: flex;
				flex-wrap: wrap;
				gap: 6px;
		}
		.file-card-badges .badge {
				font-size: 0.72rem;
		}
		.file-card .file-name {
				font-weight: 700;
				color: #2f3c67;
				word-break: break-word;
		}
		.file-card .file-meta {
				font-size: 0.78rem;
				color: #7383ad;
		}
		.file-card .file-path {
				font-size: 0.75rem;
				color: #55618a;
				background: #f6f8ff;
				border: 1px solid #e7edff;
				border-radius: 6px;
				padding: 6px;
				word-break: break-all;
		}
		.file-upload-dropzone {
				border: 2px dashed #b9c9ff;
				border-radius: 14px;
				padding: 18px;
				text-align: center;
				background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);
				transition: all 0.18s ease;
				cursor: pointer;
		}
		.file-upload-dropzone.is-dragover {
				border-color: #3d7bfd;
				background: #eef4ff;
				box-shadow: 0 0 0 4px rgba(61, 123, 253, 0.08);
		}
		.file-upload-list {
				margin-top: 10px;
				padding: 0;
				list-style: none;
		}
		.file-upload-list li {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 8px 10px;
				border: 1px solid #e6ecff;
				border-radius: 8px;
				background: #fff;
				margin-bottom: 6px;
				font-size: 0.86rem;
				color: #33436e;
		}
		.bulk-action-bar {
				display: none;
				align-items: center;
				justify-content: space-between;
				gap: 12px;
				padding: 10px 12px;
				border: 1px solid #d9e4ff;
				border-radius: 12px;
				background: #f5f8ff;
				margin-bottom: 12px;
		}
		.bulk-action-bar.is-active {
				display: flex;
		}
		.bulk-action-bar .summary {
				font-weight: 700;
				color: #2f3c67;
		}
		.bulk-action-bar .summary small {
				display: block;
				font-weight: 500;
				color: #6a789d;
				margin-top: 2px;
		}
		.bulk-summary-stats {
				display: flex;
				flex-wrap: wrap;
				gap: 6px;
				margin-top: 6px;
		}
		.bulk-summary-stats .badge {
				font-size: 0.74rem;
				padding: 0.35rem 0.55rem;
		}
		.bulk-action-bar .actions {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
		}
		.bulk-move-panel {
				display: none;
				padding: 14px;
				border: 1px solid #dfe7ff;
				border-radius: 12px;
				background: #fbfcff;
				margin-bottom: 12px;
		}
		.bulk-move-panel.is-active {
				display: block;
		}
		.upload-panel-toggle .btn {
				border-radius: 999px;
				padding: 0.35rem 0.9rem;
				font-weight: 600;
		}
		.upload-panel-card .card-body {
				display: none;
		}
		.upload-panel-card.is-open .card-body {
				display: block;
		}
		@media (max-width: 1199.98px) {
				.folder-explorer-layout {
						grid-template-columns: 1fr;
				}
				.preview-sidebar {
						position: static;
				}
		}
</style>
@endsection

@section('content')
		<section class="users-list-wrapper">
				<div class="users-list">
						@if(session('success'))
								<div class="alert alert-success">{{ session('success') }}</div>
						@endif
						@if(session('error'))
								<div class="alert alert-danger">{{ session('error') }}</div>
						@endif

						<div class="card">
								<div class="card-body border-bottom">
										<div class="d-flex flex-wrap align-items-center justify-content-between">
												<div>
														<h4 class="mb-0">Inspection Files</h4>
														<small class="text-muted">{{ $files_count }} indexed inspection file(s) for current filter</small>
												</div>
												<div class="d-flex flex-wrap mt-1 mt-md-0">
														<div class="file-view-toggle mr-1 mb-1">
																<a href="{{ route('fileManager.index', array_merge($selected_filters, ['view_mode' => 'table'])) }}" class="btn btn-sm btn-outline-primary {{ ($view_mode ?? 'table') === 'table' ? 'active' : '' }}">
																		<i class="la la-table"></i> Table View
																</a>
																<a href="{{ route('fileManager.index', array_merge($selected_filters, ['view_mode' => 'folders'])) }}" class="btn btn-sm btn-outline-primary {{ ($view_mode ?? 'table') === 'folders' ? 'active' : '' }}">
																		<i class="la la-folder-open"></i> Folder View
																</a>
														</div>
														@can('create', App\Models\WorkFlow\FileManager::class)
																<form method="POST" action="{{ route('fileManager.sync') }}" class="mr-1 mb-1">
																		@csrf
																		<button type="submit" class="btn btn-primary btn-sm"><i class="la la-refresh"></i> Sync File Index</button>
																</form>
														@endcan
												</div>
										</div>
										<div class="row file-manager-kpis mt-2">
												<div class="col-xl-2 col-md-4 col-6 mb-1">
														<div class="kpi-card">
																<div class="kpi-label">Total Files</div>
																<div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
														</div>
												</div>
												<div class="col-xl-2 col-md-4 col-6 mb-1">
														<div class="kpi-card">
																<div class="kpi-label">Inspection Files</div>
																<div class="kpi-value">{{ $stats['inspection'] ?? 0 }}</div>
														</div>
												</div>
												<div class="col-xl-2 col-md-4 col-6 mb-1">
														<div class="kpi-card">
																<div class="kpi-label">PDF Reports</div>
																<div class="kpi-value">{{ $stats['pdf'] ?? 0 }}</div>
														</div>
												</div>
												<div class="col-xl-2 col-md-4 col-6 mb-1">
														<div class="kpi-card">
																<div class="kpi-label">Images</div>
																<div class="kpi-value">{{ $stats['images'] ?? 0 }}</div>
														</div>
												</div>
												<div class="col-xl-2 col-md-4 col-6 mb-1">
														<div class="kpi-card">
																<div class="kpi-label">JCF Linked</div>
																<div class="kpi-value">{{ $stats['jcf'] ?? 0 }}</div>
														</div>
												</div>
												<div class="col-xl-2 col-md-4 col-6 mb-1">
														<div class="kpi-card">
																<div class="kpi-label">Storage Size</div>
																<div class="kpi-value">{{ $stats['size_human'] ?? '0 B' }}</div>
														</div>
												</div>
										</div>
								</div>
								<div class="card-body">
										@php
												$activePreset = $selected_filters['preset'] ?? '';
												$activeFilters = [];
												foreach (['category' => 'Category', 'extension' => 'Extension', 'job_request_code' => 'JCF'] as $key => $label) {
														if (!empty($selected_filters[$key])) {
																$activeFilters[] = $label.': '.$selected_filters[$key];
														}
												}
												if (!empty($selected_filters['date_from'])) {
														$activeFilters[] = 'From: '.$selected_filters['date_from'];
												}
												if (!empty($selected_filters['date_to'])) {
														$activeFilters[] = 'To: '.$selected_filters['date_to'];
												}
										@endphp

										<div class="file-manager-preset d-flex flex-wrap align-items-center mb-1">
												<button type="button" data-preset="all" class="btn btn-sm btn-outline-primary mr-1 mb-1 js-file-preset {{ $activePreset === 'all' || $activePreset === '' ? 'active' : '' }}">All</button>
												<button type="button" data-preset="pdf" class="btn btn-sm btn-outline-primary mr-1 mb-1 js-file-preset {{ $activePreset === 'pdf' ? 'active' : '' }}">PDF</button>
												<button type="button" data-preset="images" class="btn btn-sm btn-outline-primary mr-1 mb-1 js-file-preset {{ $activePreset === 'images' ? 'active' : '' }}">Images</button>
												<button type="button" data-preset="recent30" class="btn btn-sm btn-outline-primary mr-1 mb-1 js-file-preset {{ $activePreset === 'recent30' ? 'active' : '' }}">Last 30 Days</button>
										</div>

										@if(!empty($activeFilters))
												<div class="mb-1">
														@foreach($activeFilters as $filterChip)
																<span class="file-manager-filter-chip">{{ $filterChip }}</span>
														@endforeach
												</div>
										@endif

										<form id="file-manager-filters" method="GET" action="{{ route('fileManager.index') }}" class="mb-2">
												<input type="hidden" name="preset" id="file-manager-preset" value="{{ $activePreset }}">
												<input type="hidden" name="view_mode" id="file-manager-view-mode" value="{{ $view_mode ?? 'table' }}">
												<div class="row">
														<div class="col-md-3 col-12">
																<div class="form-group">
																		<label>Category</label>
																		<select class="form-control" name="category">
																				<option value="">All</option>
																				@foreach(($category_options ?? collect()) as $categoryOption)
																						<option value="{{ $categoryOption }}" {{ ($selected_filters['category'] ?? '') === $categoryOption ? 'selected' : '' }}>{{ $categoryOption }}</option>
																				@endforeach
																		</select>
																</div>
														</div>
														<div class="col-md-3 col-12">
																<div class="form-group">
																		<label>Extension</label>
																		<select class="form-control" name="extension">
																				<option value="">All</option>
																				@foreach(($extension_options ?? collect()) as $extensionOption)
																						<option value="{{ $extensionOption }}" {{ ($selected_filters['extension'] ?? '') === $extensionOption ? 'selected' : '' }}>{{ $extensionOption }}</option>
																				@endforeach
																		</select>
																</div>
														</div>
														<div class="col-md-3 col-12">
																<div class="form-group">
																		<label>JCF Code</label>
																		<input type="text" class="form-control" name="job_request_code" list="file-manager-jcf-codes" value="{{ $selected_filters['job_request_code'] ?? '' }}" placeholder="26-001">
																		<datalist id="file-manager-jcf-codes">
																				@foreach(($job_request_code_options ?? collect()) as $jcfCodeOption)
																						<option value="{{ $jcfCodeOption }}"></option>
																				@endforeach
																		</datalist>
																</div>
														</div>
														<div class="col-md-1 col-12">
																<div class="form-group">
																		<label>Date From</label>
																		<input type="date" class="form-control" name="date_from" value="{{ $selected_filters['date_from'] ?? '' }}">
																</div>
														</div>
														<div class="col-md-2 col-12">
																<div class="form-group">
																		<label>Date To</label>
																		<input type="date" class="form-control" name="date_to" value="{{ $selected_filters['date_to'] ?? '' }}">
																</div>
														</div>
												</div>
												<div class="row">
														<div class="col-md-12 col-12 text-md-right mt-1 mt-md-0">
																<button type="submit" class="btn btn-primary btn-sm mr-1">Apply Filters</button>
																<a href="{{ route('fileManager.index', ['view_mode' => ($view_mode ?? 'table')]) }}" class="btn btn-light border btn-sm">Reset</a>
														</div>
												</div>
										</form>

										<div id="bulk-action-bar" class="bulk-action-bar">
												<div class="summary">
														<span id="bulk-selected-count">0</span> file(s) selected
														<small id="bulk-selection-hint">0 exportable files - 0 report families</small>
														<div class="bulk-summary-stats">
																<span class="badge badge-light" id="bulk-selected-badge">0 selected</span>
																<span class="badge badge-success" id="bulk-exportable-badge">0 exportable</span>
																<span class="badge badge-primary" id="bulk-family-badge">0 families</span>
														</div>
												</div>
												<div class="actions">
														<form id="bulk-download-form" method="POST" action="{{ route('fileManager.bulkDownload') }}" class="mb-0" data-submit-guard-mode="transient">
																@csrf
																<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																<div id="bulk-download-inputs"></div>
																<button type="submit" class="btn btn-primary btn-sm" id="bulk-download-btn" disabled>
																		<i class="la la-download"></i> Download ZIP
																</button>
														</form>
														<form id="bulk-export-workbooks-form" method="POST" action="{{ route('fileManager.bulkExportInspectionWorkbooks') }}" class="mb-0" data-submit-guard-mode="transient">
																@csrf
																<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																<input type="hidden" name="download_mode" value="zip">
																<div id="bulk-export-workbooks-inputs"></div>
																<button type="submit" class="btn btn-success btn-sm" id="bulk-export-workbooks-btn" disabled>
																		<i class="la la-file-archive-o"></i> Export Reports ZIP
																</button>
														</form>
														<form id="bulk-export-single-workbook-form" method="POST" action="{{ route('fileManager.bulkExportInspectionWorkbooks') }}" class="mb-0" data-submit-guard-mode="transient">
																@csrf
																<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																<input type="hidden" name="download_mode" value="single">
																<div id="bulk-export-single-workbook-inputs"></div>
																<button type="submit" class="btn btn-success btn-sm" id="bulk-export-single-workbook-btn" disabled>
																		<i class="la la-table"></i> Export One Report Excel
																</button>
														</form>
														@if($can_update_any ?? false)
																<button type="button" class="btn btn-info btn-sm" id="bulk-move-toggle-btn" disabled>
																		<i class="la la-random"></i> Bulk Move / Recategorize
																</button>
														@endif
														@if($can_delete_index_any ?? false)
																<form id="bulk-destroy-form" method="POST" action="{{ route('fileManager.bulkDestroy') }}" class="mb-0">
																		@csrf
																		<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																		<div id="bulk-destroy-inputs"></div>
																		<button type="submit" class="btn btn-danger btn-sm" id="bulk-destroy-btn" disabled>
																				<i class="la la-trash"></i> Remove Index
																		</button>
																</form>
														@endif
														@if($can_delete_physical_any ?? false)
																<form id="bulk-physical-destroy-form" method="POST" action="{{ route('fileManager.bulkDestroyPhysical') }}" class="mb-0">
																		@csrf
																		<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																		<div id="bulk-physical-destroy-inputs"></div>
																		<button type="submit" class="btn btn-outline-danger btn-sm" id="bulk-physical-destroy-btn" disabled>
																				<i class="la la-times-circle"></i> Delete Files
																		</button>
																</form>
														@endif
														<button type="button" class="btn btn-light border btn-sm" id="bulk-clear-btn">Clear Selection</button>
												</div>
										</div>

										@if($can_update_any ?? false)
												<div id="bulk-move-panel" class="bulk-move-panel">
														<form id="bulk-move-form" method="POST" action="{{ route('fileManager.bulkMove') }}">
																@csrf
																<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																<div id="bulk-move-inputs"></div>
																<div class="row">
																		<div class="col-md-3 col-12">
																				<div class="form-group mb-1">
																						<label>New Module</label>
																						<input type="text" class="form-control" name="move_module" list="file-manager-module-list" placeholder="Optional">
																				</div>
																		</div>
																		<div class="col-md-3 col-12">
																				<div class="form-group mb-1">
																						<label>New Category</label>
																						<input type="text" class="form-control" name="move_category" list="file-manager-category-list" placeholder="Optional">
																				</div>
																		</div>
																		<div class="col-md-3 col-12">
																				<div class="form-group mb-1">
																						<label>New JCF Code</label>
																						<input type="text" class="form-control" name="move_job_request_code" list="file-manager-jcf-codes" placeholder="Optional">
																				</div>
																		</div>
																		<div class="col-md-3 col-12">
																				<div class="form-group mb-1">
																						<label>Note</label>
																						<input type="text" class="form-control" name="move_note" placeholder="Optional note update">
																				</div>
																		</div>
																</div>
																<div class="d-flex flex-wrap align-items-center justify-content-between">
																		<small class="text-muted">Physical move is applied only to files inside `uploads/manual` or `uploads/managed`. System-generated files keep their storage path and are skipped.</small>
																		<div class="mt-1 mt-md-0">
																				<button type="button" class="btn btn-light border btn-sm mr-1" id="bulk-move-cancel-btn">Cancel</button>
																				<button type="submit" class="btn btn-info btn-sm" id="bulk-move-submit-btn" disabled>
																						<i class="la la-check"></i> Apply Bulk Update
																				</button>
																		</div>
																</div>
														</form>
												</div>
										@endif

										@if(($view_mode ?? 'table') === 'table')
												<div class="table-responsive">
														<table id="users-list" class="table table-striped table-bordered dataex-fixh-responsive row-grouping">
																<thead class="workflow-list-head">
																		<tr class="column-headings">
																				<th>
																						<div class="custom-control custom-checkbox text-center mb-0">
																								<input type="checkbox" class="custom-control-input" id="file-select-all">
																								<label class="custom-control-label" for="file-select-all"></label>
																						</div>
																				</th>
																				<th>File</th>
																				<th>Path</th>
																				<th>Category</th>
																				<th>Entity Type</th>
																				<th>JCF Code</th>
																				<th>Ext</th>
																				<th>Size</th>
																				<th>Generated At</th>
																				<th>Actions</th>
																		</tr>
																		<tr class="filter-row">
																				<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
																		</tr>
																</thead>
														</table>
												</div>
										@else
												<div class="folder-explorer-layout">
														<div class="folder-explorer-panel">
																<div class="folder-tree">
																		@forelse(($folder_tree ?? []) as $moduleName => $moduleData)
																				<details class="folder-node {{ $moduleData['theme']['class'] ?? 'folder-theme-neutral' }}">
																						<summary class="folder-summary">
																								<div class="folder-summary-main">
																										<i class="la la-angle-right folder-toggle-icon"></i>
																										<i class="la {{ $moduleData['theme']['icon'] ?? 'la-folder-open' }} text-primary"></i>
																										<span class="folder-node-title text-uppercase">{{ $moduleName }}</span>
																								</div>
																								<div class="folder-summary-meta">
																										<span class="badge badge-primary">{{ $moduleData['total_files'] ?? 0 }} files</span>
																								</div>
																						</summary>
																						<div class="folder-node-body">
																								@foreach(($moduleData['categories'] ?? []) as $categoryName => $categoryData)
																										<details class="folder-node {{ $categoryData['theme']['class'] ?? 'folder-theme-neutral' }}">
																												<summary class="folder-summary">
																														<div class="folder-summary-main">
																																<i class="la la-angle-right folder-toggle-icon"></i>
																																<i class="la {{ $categoryData['theme']['icon'] ?? 'la-folder' }} text-info"></i>
																																<span class="folder-node-title">{{ $categoryName }}</span>
																														</div>
																														<div class="folder-summary-meta">
																																<span class="badge badge-light">{{ $categoryData['total_files'] ?? 0 }}</span>
																														</div>
																												</summary>
																												<div class="folder-node-body">
																														@foreach(($categoryData['jcfs'] ?? []) as $jcfCode => $jcfData)
																																<details class="folder-node">
																																		<summary class="folder-summary">
																																				<div class="folder-summary-main">
																																						<i class="la la-angle-right folder-toggle-icon"></i>
																																						<i class="la la-file-text-o text-warning"></i>
																																						<span class="folder-node-title">{{ $jcfCode }}</span>
																																				</div>
																																				<div class="folder-summary-meta">
																																						<span class="badge badge-info">{{ $jcfData['total_files'] ?? 0 }} files</span>
																																						@if(($jcfData['inspection_files'] ?? 0) > 0)
																																								<span class="badge badge-primary">{{ $jcfData['inspection_files'] }} inspection</span>
																																						@endif
																																						@if(($jcfData['exportable_family_count'] ?? 0) > 0)
																																								<span class="badge badge-success">{{ $jcfData['exportable_family_count'] }} families</span>
																																								<form method="POST" action="{{ route('fileManager.bulkExportInspectionWorkbooks') }}" class="mb-0" data-submit-guard-mode="transient" onclick="event.stopPropagation();">
																																										@csrf
																																										<input type="hidden" name="view_mode" value="{{ $view_mode ?? 'table' }}">
																																										<input type="hidden" name="download_mode" value="combined">
																																										@foreach(($jcfData['exportable_file_ids'] ?? []) as $exportableFileId)
																																												<input type="hidden" name="selected_ids[]" value="{{ $exportableFileId }}">
																																										@endforeach
																																										<button type="submit" class="btn btn-success btn-sm" onclick="event.stopPropagation();">
																																												<i class="la la-table"></i> Export JCF Type Excel
																																										</button>
																																								</form>
																																						@endif
																																				</div>
																																		</summary>
																																		<div class="folder-node-body">
																																				<div class="report-family-stack">
																																						@foreach(($jcfData['family_groups'] ?? []) as $familyGroup)
																																								<div class="report-family-card {{ !empty($familyGroup['exportable']) ? 'is-exportable' : '' }}">
																																										<div class="report-family-head">
																																												<div>
																																														<div class="report-family-title">{{ $familyGroup['family_label'] }}</div>
																																														<div class="report-family-meta">{{ $familyGroup['type_label'] }} • {{ $familyGroup['revision_count'] }} revision(s) • {{ $familyGroup['file_count'] }} file(s)</div>
																																												</div>
																																												<div class="report-family-actions">
																																														@if(!empty($familyGroup['exportable']) && !empty($familyGroup['export_url']))
																																																<a href="{{ $familyGroup['export_url'] }}" class="btn btn-success btn-sm" title="Export this report family with all revisions"><i class="la la-table"></i> Export Family Excel</a>
																																														@endif
																																														@if(($familyGroup['file_count'] ?? 0) > 0)
																																																<button type="button" class="btn btn-outline-primary btn-sm js-select-family" data-file-ids="{{ implode(',', $familyGroup['file_ids'] ?? []) }}"><i class="la la-check-square-o"></i> Select Family</button>
																																														@endif
																																												</div>
																																										</div>
																																										<div class="file-grid">
																																												@foreach(($familyGroup['files'] ?? []) as $fileItem)
																																								<div class="file-card {{ !empty($fileItem['is_inspection']) ? 'is-inspection' : '' }}">
																																										<div class="file-card-head">
																																												<div class="file-card-badges">
																																														<span class="badge badge-primary">{{ $fileItem['badge_label'] ?? 'Inspection' }}</span>
																																												</div>
																																										</div>
																																										<div class="custom-control custom-checkbox mb-50">
																												<input type="checkbox" class="custom-control-input js-file-select" id="folder-file-select-{{ $fileItem['id'] }}" value="{{ $fileItem['id'] }}" data-exportable="{{ !empty($fileItem['workbook_exportable']) ? '1' : '0' }}" data-family-key="{{ $fileItem['workbook_family_key'] ?? '' }}">
																																												<label class="custom-control-label" for="folder-file-select-{{ $fileItem['id'] }}">Select</label>
																																										</div>
																																										<button type="button" class="btn btn-link p-0 text-left file-name js-preview-sidebar-file" data-open-url="{{ $fileItem['open_url'] ?? '' }}" data-file-name="{{ $fileItem['filename'] }}" data-mime-type="{{ $fileItem['mime_type'] }}" data-file-path="{{ $fileItem['path'] }}" data-file-meta="{{ trim(($fileItem['extension'] ?: '-') . ' | ' . $fileItem['size_human'] . ' | ' . $fileItem['generated_at']) }}" {{ empty($fileItem['previewable']) ? 'disabled' : '' }}>{{ $fileItem['filename'] }}</button>
																																										<div class="file-meta mt-25">{{ $fileItem['extension'] ?: '-' }} | {{ $fileItem['size_human'] }} | {{ $fileItem['generated_at'] }}</div>
																																										<div class="file-path mt-50">{{ $fileItem['path'] }}</div>
																																										<div class="d-flex flex-wrap mt-75">
																																												@if(!empty($fileItem['previewable']) && !empty($fileItem['open_url']))
																																														<button type="button" class="btn btn-warning btn-sm mr-50 mb-50 js-preview-sidebar-file" data-open-url="{{ $fileItem['open_url'] }}" data-file-name="{{ $fileItem['filename'] }}" data-mime-type="{{ $fileItem['mime_type'] }}" data-file-path="{{ $fileItem['path'] }}" data-file-meta="{{ trim(($fileItem['extension'] ?: '-') . ' | ' . $fileItem['size_human'] . ' | ' . $fileItem['generated_at']) }}"><i class="la la-eye"></i></button>
																																												@endif
																																												@if(!empty($fileItem['workbook_export_url']))
																																														<a href="{{ $fileItem['workbook_export_url'] }}" class="btn btn-success btn-sm mr-50 mb-50" title="Export Full Report Workbook (All Revisions)"><i class="la la-table"></i> Excel</a>
																																												@endif
																																												@if(!empty($fileItem['open_url']))
																																														<a target="_blank" href="{{ $fileItem['open_url'] }}" class="btn btn-info btn-sm mr-50 mb-50"><i class="la la-external-link"></i></a>
																																														<a href="{{ $fileItem['download_url'] }}" class="btn btn-primary btn-sm mr-50 mb-50"><i class="la la-download"></i></a>
																																												@endif
																																												<button type="button" class="btn btn-secondary btn-sm mr-50 mb-50 js-copy-path" data-path="{{ $fileItem['path'] }}"><i class="la la-copy"></i></button>
																																												@if(!empty($fileItem['can_delete_index']))
																																														<button type="button" data-id="{{ $fileItem['id'] }}" class="btn btn-danger btn-sm mr-50 mb-50 delete"><i class="la la-trash"></i></button>
																																												@endif
																																												@if(!empty($fileItem['can_delete_physical']))
																																														<button type="button" class="btn btn-outline-danger btn-sm mb-50 js-physical-delete" data-url="{{ $fileItem['physical_delete_url'] }}" data-file-name="{{ $fileItem['filename'] }}"><i class="la la-times-circle"></i></button>
																																												@endif
																																										</div>
																																								</div>
																																												@endforeach
																																										</div>
																																								</div>
																																						@endforeach
																																				</div>
																																		</div>
																																</details>
																														@endforeach
																												</div>
																										</details>
																								@endforeach
																						</div>
																				</details>
																		@empty
																				<div class="alert alert-light border mb-0">No files found for current filters.</div>
																		@endforelse
																</div>
														</div>
														<div class="preview-sidebar">
																<div class="preview-sidebar-head">
																		<div class="font-weight-bold">Folder Preview</div>
																		<small class="text-muted">Preview stays on the side while you browse nested folders.</small>
																</div>
																<div class="preview-sidebar-body">
																		<div id="folder-preview-empty" class="preview-sidebar-empty"><div><div class="h5 mb-50">No file selected</div><div>Select any previewable file from the folder tree to load it here.</div></div></div>
																		<div id="folder-preview-content" class="d-none">
																				<h5 id="folder-preview-title" class="mb-50">File Preview</h5>
																				<div id="folder-preview-meta" class="text-muted small mb-1"></div>
																				<div id="folder-preview-path" class="file-path mb-1"></div>
																				<div id="folder-preview-viewer" class="preview-sidebar-viewer mb-1"></div>
																				<div class="d-flex flex-wrap">
																						<a href="#" target="_blank" id="folder-preview-open-link" class="btn btn-primary btn-sm mr-1 mb-50">Open</a>
																						<button type="button" class="btn btn-light border btn-sm mb-50" id="folder-preview-clear-btn">Clear</button>
																				</div>
																		</div>
																</div>
														</div>
										@endif
								</div>
						</div>
				</div>
		</section>

		<div class="modal fade" id="file-preview-modal" tabindex="-1" role="dialog" aria-labelledby="file-preview-title" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
								<div class="modal-header">
										<h5 class="modal-title" id="file-preview-title">File Preview</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
										</button>
								</div>
								<div class="modal-body">
										<div class="file-preview-wrap" id="file-preview-container"></div>
								</div>
								<div class="modal-footer">
										<a href="#" id="file-preview-open" target="_blank" class="btn btn-primary btn-sm">Open In New Tab</a>
										<button type="button" class="btn btn-light border btn-sm" data-dismiss="modal">Close</button>
								</div>
						</div>
				</div>
		</div>
@endsection

@if(($view_mode ?? 'table') === 'table')
		@include('layouts.scripts.datatables', [
				'route' => 'fileManager',
				'route_param'=> $selected_filters,
				'columns' => ['select_row', 'filename', 'path', 'category', 'entity_type', 'job_request_code', 'extension', 'size_bytes', 'generated_at', 'action'],
				'datatable_options' => [
						'ordering' => true,
						'order' => [[8, 'desc']],
						'useFilterRow' => true,
						'fixedHeader' => false,
						'filterDebounceMs' => 250,
				],
				'non_orderable_columns' => ['select_row', 'action'],
				'non_searchable_columns' => ['select_row', 'action'],
				'disable_column_filters' => ['select_row', 'action'],
		])
@endif

@section('ajax')
<script>
		var selectedFileIds = {};
		var selectedFileMeta = {};

		function resetWorkflowListFilters() {
				if (typeof table === 'undefined') {
						return;
				}
				table.search('');
				table.columns().search('');
				$('#users-list thead tr.filter-row').find('input, select').val('');
		}

		function getSelectedFileIds() {
				return Object.keys(selectedFileIds)
						.filter(function (id) { return !!selectedFileIds[id]; })
						.map(function (id) { return parseInt(id, 10); })
						.filter(function (id) { return !isNaN(id) && id > 0; });
		}

		function getSelectedExportSummary() {
				var ids = getSelectedFileIds();
				var exportableFileCount = 0;
				var families = {};

				ids.forEach(function (id) {
						var meta = selectedFileMeta[String(id)] || {};
						if (!meta.exportable) {
								return;
						}

						exportableFileCount++;
						if (meta.familyKey) {
								families[meta.familyKey] = true;
						}
				});

				return {
						fileCount: ids.length,
						exportableFileCount: exportableFileCount,
						familyCount: Object.keys(families).length
				};
		}

		function refreshBulkSelectionState() {
				var ids = getSelectedFileIds();
				var count = ids.length;
				var exportSummary = getSelectedExportSummary();
				var $bar = $('#bulk-action-bar');
				$('#bulk-selected-count').text(count);
				$('#bulk-selection-hint').text(exportSummary.exportableFileCount + ' exportable files - ' + exportSummary.familyCount + ' report families');
				$('#bulk-selected-badge').text(count + ' selected');
				$('#bulk-exportable-badge').text(exportSummary.exportableFileCount + ' exportable');
				$('#bulk-family-badge').text(exportSummary.familyCount + ' families');
				$('#bulk-download-btn, #bulk-destroy-btn, #bulk-physical-destroy-btn, #bulk-move-toggle-btn, #bulk-move-submit-btn').prop('disabled', count === 0);
				$('#bulk-export-workbooks-btn').prop('disabled', exportSummary.familyCount === 0);
				$('#bulk-export-single-workbook-btn').prop('disabled', exportSummary.familyCount !== 1);

				var downloadInputs = ids.map(function (id) {
						return '<input type="hidden" name="selected_ids[]" value="' + id + '">';
				}).join('');
				$('#bulk-download-inputs').html(downloadInputs);
				$('#bulk-export-workbooks-inputs').html(downloadInputs);
				$('#bulk-export-single-workbook-inputs').html(downloadInputs);
				$('#bulk-destroy-inputs').html(downloadInputs);
				$('#bulk-physical-destroy-inputs').html(downloadInputs);
				$('#bulk-move-inputs').html(downloadInputs);

				if (count > 0) {
						$bar.addClass('is-active');
				} else {
						$bar.removeClass('is-active');
						$('#bulk-move-panel').removeClass('is-active');
				}
		}

		function syncVisibleSelections() {
				$('.js-file-select').each(function () {
						var id = String($(this).val() || '');
						$(this).prop('checked', !!selectedFileIds[id]);
				});
		}

		function resetFileManagerFilterForm() {
				var $form = $('#file-manager-filters');
				$form.find('select[name="category"]').val('');
				$form.find('select[name="extension"]').val('');
				$form.find('input[name="job_request_code"]').val('');
				$form.find('input[name="date_from"]').val('');
				$form.find('input[name="date_to"]').val('');
		}

		function formatDateISO(dateObj) {
				var month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
				var day = dateObj.getDate().toString().padStart(2, '0');
				return dateObj.getFullYear() + '-' + month + '-' + day;
		}

		$(document).on('click', '.js-file-preset', function () {
				var preset = String($(this).data('preset') || '');
				var $form = $('#file-manager-filters');
				resetFileManagerFilterForm();
				$('#file-manager-preset').val(preset);

				if (preset === 'pdf') {
						$form.find('select[name="extension"]').val('pdf');
				} else if (preset === 'images') {
						$form.find('select[name="extension"]').val('image');
				} else if (preset === 'recent30') {
						var now = new Date();
						var fromDate = new Date();
						fromDate.setDate(now.getDate() - 30);
						$form.find('input[name="date_from"]').val(formatDateISO(fromDate));
						$form.find('input[name="date_to"]').val(formatDateISO(now));
				}

				$form.trigger('submit');
		});

		$('#file-manager-filters').on('submit', function () {
				if (!$('#file-manager-preset').val()) {
						$('#file-manager-preset').val('');
				}
		});

		$('#file-manager-filters').on('change input', 'input, select', function () {
				var fieldName = String($(this).attr('name') || '');
				if (fieldName && fieldName !== 'preset') {
						$('#file-manager-preset').val('');
				}
		});

		$(document).on('click', '.js-copy-path', function () {
				var filePath = String($(this).data('path') || '');
				if (!filePath) {
						return;
				}

				if (navigator.clipboard && navigator.clipboard.writeText) {
						navigator.clipboard.writeText(filePath).then(function () {
								toastr.success('Path copied to clipboard', 'Success');
						});
						return;
				}

				var tempInput = $('<input>');
				$('body').append(tempInput);
				tempInput.val(filePath).select();
				document.execCommand('copy');
				tempInput.remove();
				toastr.success('Path copied to clipboard', 'Success');
		});

		$(document).on('click', '.js-preview-file', function () {
				var url = String($(this).data('open-url') || '');
				var fileName = String($(this).data('file-name') || 'File Preview');
				var mimeType = String($(this).data('mime-type') || '').toLowerCase();
				var html = '';

				if (!url) {
						return;
				}

				if (mimeType.indexOf('image/') === 0) {
						html = '<img src="' + url + '" alt="' + fileName + '">';
				} else {
						html = '<iframe src="' + url + '" title="' + fileName + '"></iframe>';
				}

				$('#file-preview-title').text(fileName);
				$('#file-preview-container').html(html);
				$('#file-preview-open').attr('href', url);
				$('#file-preview-modal').modal('show');
		});

		function clearFolderPreview() {
				$('#folder-preview-viewer').empty();
				$('#folder-preview-title').text('File Preview');
				$('#folder-preview-meta').text('');
				$('#folder-preview-path').text('');
				$('#folder-preview-open-link').attr('href', '#');
				$('#folder-preview-content').addClass('d-none');
				$('#folder-preview-empty').removeClass('d-none');
		}

		$(document).on('click', '.js-preview-sidebar-file', function () {
				if (!$('#folder-preview-viewer').length) {
						return;
				}

				var url = String($(this).data('open-url') || '');
				var fileName = String($(this).data('file-name') || 'File Preview');
				var mimeType = String($(this).data('mime-type') || '').toLowerCase();
				var filePath = String($(this).data('file-path') || '');
				var fileMeta = String($(this).data('file-meta') || '');
				var html = '';

				if (!url) {
						return;
				}

				if (mimeType.indexOf('image/') === 0) {
						html = '<img src="' + url + '" alt="' + fileName + '">';
				} else {
						html = '<iframe src="' + url + '" title="' + fileName + '"></iframe>';
				}

				$('#folder-preview-title').text(fileName);
				$('#folder-preview-meta').text(fileMeta);
				$('#folder-preview-path').text(filePath);
				$('#folder-preview-viewer').html(html);
				$('#folder-preview-open-link').attr('href', url);
				$('#folder-preview-empty').addClass('d-none');
				$('#folder-preview-content').removeClass('d-none');
		});

		$('#file-preview-modal').on('hidden.bs.modal', function () {
				$('#file-preview-container').empty();
				$('#file-preview-open').attr('href', '#');
		});

		$('#folder-preview-clear-btn').on('click', function () {
				clearFolderPreview();
		});

		$(document).on('change', '.js-file-select', function () {
				var id = String($(this).val() || '');
				if (!id) {
						return;
				}

				selectedFileIds[id] = $(this).is(':checked');
				selectedFileMeta[id] = {
						exportable: String($(this).data('exportable') || '0') === '1',
						familyKey: String($(this).data('family-key') || '')
				};
				refreshBulkSelectionState();
		});

		$(document).on('click', '.js-select-family', function () {
				var fileIds = String($(this).data('file-ids') || '')
						.split(',')
						.map(function (value) { return String(value || '').trim(); })
						.filter(function (value) { return value !== ''; });

				if (!fileIds.length) {
						return;
				}

				fileIds.forEach(function (id) {
						var $checkbox = $('.js-file-select[value="' + id + '"]').first();
						if (!$checkbox.length) {
								return;
						}

						selectedFileIds[id] = true;
						selectedFileMeta[id] = {
								exportable: String($checkbox.data('exportable') || '0') === '1',
								familyKey: String($checkbox.data('family-key') || '')
						};
						$checkbox.prop('checked', true);
				});

				refreshBulkSelectionState();
		});

		$('#bulk-clear-btn').on('click', function () {
				selectedFileIds = {};
				selectedFileMeta = {};
				syncVisibleSelections();
				$('#file-select-all').prop('checked', false);
				refreshBulkSelectionState();
		});

		$('#bulk-move-toggle-btn').on('click', function () {
				if (getSelectedFileIds().length === 0) {
						return;
				}
				$('#bulk-move-panel').toggleClass('is-active');
		});

		$('#bulk-move-cancel-btn').on('click', function () {
				$('#bulk-move-panel').removeClass('is-active');
		});

		$('#file-select-all').on('change', function () {
				var checked = $(this).is(':checked');
				$('#users-list .js-file-select').each(function () {
						var id = String($(this).val() || '');
						if (!id) {
								return;
						}
						selectedFileIds[id] = checked;
						selectedFileMeta[id] = {
								exportable: String($(this).data('exportable') || '0') === '1',
								familyKey: String($(this).data('family-key') || '')
						};
						$(this).prop('checked', checked);
				});
				refreshBulkSelectionState();
		});

		$('#bulk-export-workbooks-form').on('submit', function (e) {
				var exportSummary = getSelectedExportSummary();
				if (exportSummary.familyCount === 0) {
						e.preventDefault();
						return;
				}
		});

		$('#bulk-export-single-workbook-form').on('submit', function (e) {
				var exportSummary = getSelectedExportSummary();
				if (exportSummary.familyCount !== 1) {
						e.preventDefault();
						return;
				}
		});

		$('#bulk-destroy-form').on('submit', function (e) {
				if (getSelectedFileIds().length === 0) {
						e.preventDefault();
						return;
				}

				if (!window.confirm('Remove selected file index records?')) {
						e.preventDefault();
				}
		});

		$('#bulk-physical-destroy-form').on('submit', function (e) {
				if (getSelectedFileIds().length === 0) {
						e.preventDefault();
						return;
				}

				if (!window.confirm('Delete selected physical files and remove their index records?')) {
						e.preventDefault();
				}
		});

		$('#bulk-move-form').on('submit', function (e) {
				if (getSelectedFileIds().length === 0) {
						e.preventDefault();
						return;
				}
		});

		$(document).on('click', '.js-physical-delete', function () {
				var url = String($(this).data('url') || '');
				var fileName = String($(this).data('file-name') || 'this file');
				if (!url) {
						return;
				}

				if (!window.confirm('Delete physical file "' + fileName + '" and remove its index record?')) {
						return;
				}

				$.ajax({
						headers: {'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')},
						type: 'DELETE',
						url: url,
						success: function (data) {
								toastr.success(data.success || 'Physical file deleted successfully.', 'Success', {
										positionClass: 'toast-bottom-left',
										showMethod: 'slideDown',
										hideMethod: 'slideUp',
										progressBar: true,
										timeOut: 1200,
										fadeOut: 1200,
										onHidden: function () { window.location.reload(); }
								});
						}
				});
		});

		@if(($view_mode ?? 'table') === 'table')
		$(document).on('draw.dt', '#users-list', function () {
				syncVisibleSelections();
				refreshBulkSelectionState();
				$('#file-select-all').prop('checked', false);
		});
		@endif

		refreshBulkSelectionState();
</script>
@endsection
