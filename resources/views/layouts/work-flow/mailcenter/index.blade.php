@extends('layouts.app')

@section('header-bottom')
<style>
.mailcenter-shell{background:linear-gradient(180deg,#f7f9ff 0%,#ffffff 26%);border-radius:22px;padding:10px}
.mailcenter-hero{display:flex;justify-content:space-between;gap:18px;align-items:flex-start;padding:22px;border:1px solid #dde6ff;border-radius:18px;background:radial-gradient(circle at top right,rgba(79,70,229,.16),transparent 34%),linear-gradient(135deg,#1f3368 0%,#304a90 46%,#4f46e5 100%);color:#fff;margin-bottom:18px;box-shadow:0 24px 42px rgba(37,61,124,.16)}
.mailcenter-hero h3{color:#fff;margin-bottom:4px}
.mailcenter-hero p{margin-bottom:0;color:rgba(255,255,255,.82);max-width:720px}
.mailcenter-hero-metrics{display:grid;grid-template-columns:repeat(2,minmax(110px,1fr));gap:10px;min-width:260px}
.mailcenter-hero-metric{padding:12px 14px;border-radius:14px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);backdrop-filter:blur(8px)}
.mailcenter-hero-metric span{display:block;font-size:.76rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.68)}
.mailcenter-hero-metric strong{display:block;font-size:1.35rem;color:#fff}
.mailcenter-box{background:#fff;border:1px solid #e4eafc;border-radius:16px;padding:18px;box-shadow:0 12px 24px rgba(52,72,124,.05);margin-bottom:18px}
.mailcenter-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
.mailcenter-stat{background:#f8faff;border:1px solid #e3eafb;border-radius:12px;padding:16px}
.mailcenter-stat small{display:block;color:#7786b0}.mailcenter-stat strong{display:block;font-size:1.8rem;color:#2d3a67}
.mailcenter-status{display:inline-flex;padding:5px 10px;border-radius:999px;font-size:.78rem;font-weight:700;text-transform:uppercase}
.mailcenter-status.draft{background:#edf0f8;color:#5c6a90}.mailcenter-status.pending_approval{background:#fff1d9;color:#8f5b00}.mailcenter-status.approved{background:#e5f8ef;color:#0d7b47}.mailcenter-status.sent{background:#dff4ff;color:#13658f}.mailcenter-status.failed{background:#ffe2e2;color:#ba2525}
.mailcenter-actions{display:inline-flex;gap:6px;flex-wrap:wrap}.mailcenter-code{font-family:Consolas,monospace;font-weight:700;color:#5b43d4}.mailcenter-muted{color:#7b89b2;font-size:.85rem}
.mailcenter-notification-item{display:flex;justify-content:space-between;gap:14px;padding:14px 0;border-bottom:1px solid #edf1fb}
.mailcenter-notification-item:last-child{border-bottom:none;padding-bottom:0}
.mailcenter-notification-item.unread{background:#f7f9ff;border-radius:12px;padding:14px}
.mailcenter-compose-grid{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:18px}
.mailcenter-compose-panel{padding:18px;border:1px solid #e7ecfb;border-radius:16px;background:linear-gradient(180deg,#ffffff 0%,#fbfcff 100%);margin-bottom:14px}
.mailcenter-compose-panel:last-child{margin-bottom:0}
.mailcenter-panel-head{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:14px}
.mailcenter-panel-head h5{margin-bottom:3px}
.mailcenter-panel-head p{margin:0;color:#7b89b2;font-size:.84rem}
.mailcenter-panel-badge{display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;background:#eef2ff;color:#32477a;font-size:.75rem;font-weight:700}
.mailcenter-intro-note{border:1px solid #dbe6ff;background:#f6f9ff;color:#4d5f8d;border-radius:14px;padding:12px 14px;margin-bottom:14px}
.mailcenter-input-stack .form-group:last-child{margin-bottom:0}
.mailcenter-contact-select{min-height:190px}
.mailcenter-textarea-lg{min-height:220px}
.mailcenter-attachment-box{padding:16px;border:1px dashed #ccd7fb;border-radius:16px;background:radial-gradient(circle at top right,rgba(79,70,229,.08),transparent 38%),#f8faff}
.mailcenter-attachment-box h6{margin-bottom:6px;color:#2f3b63}
.mailcenter-attachment-box p{margin-bottom:10px;color:#7181aa;font-size:.84rem}
.mailcenter-compose-actions{display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
.mailcenter-sidebar-card{padding:16px;border:1px solid #e6ecfc;border-radius:16px;background:#fff;margin-bottom:14px;box-shadow:0 10px 22px rgba(64,83,138,.06)}
.mailcenter-sidebar-card:last-child{margin-bottom:0}
.mailcenter-sidebar-card h5{margin-bottom:8px}
.mailcenter-token-list{display:flex;flex-wrap:wrap;gap:8px}
.mailcenter-token-list code{padding:6px 10px;border-radius:999px;background:#f2f5ff;color:#3f4f80}
.mailcenter-message-summary{display:grid;grid-template-columns:repeat(4,minmax(120px,1fr));gap:12px;margin-bottom:18px}
.mailcenter-summary-card{padding:14px 16px;border:1px solid #e3eafb;border-radius:16px;background:linear-gradient(180deg,#fff 0%,#f9fbff 100%)}
.mailcenter-summary-card span{display:block;color:#7b89b2;font-size:.8rem}
.mailcenter-summary-card strong{display:block;color:#2d3a67;font-size:1.45rem}
.mailcenter-log-table tbody tr:hover{background:#fbfcff}
.mailcenter-log-subject{max-width:300px}
.mailcenter-log-subject strong{display:block;color:#22345f}
.mailcenter-log-meta{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.mailcenter-table-header{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:14px}
.mailcenter-table-header p{margin:0;color:#7786b0}
@media (max-width: 1199px){
  .mailcenter-compose-grid{grid-template-columns:1fr}
  .mailcenter-hero{flex-direction:column}
  .mailcenter-hero-metrics{width:100%;grid-template-columns:repeat(auto-fit,minmax(120px,1fr))}
}
@media (max-width: 767px){
  .mailcenter-shell{padding:0}
  .mailcenter-message-summary{grid-template-columns:repeat(2,minmax(120px,1fr))}
  .mailcenter-table-header{flex-direction:column}
}
</style>
@endsection

@section('content')
@php
    $editMessage = $editing_message ?? null;
    $composeAction = $editMessage ? route('mailCenter.update', $editMessage->id) : route('mailCenter.store');
    $composeDefaults = $compose_defaults ?? [];
    $selectedContactIds = old('contact_person_ids', optional($editMessage)->recipients?->pluck('contact_person_id')->filter()->all() ?: ($composeDefaults['contact_person_ids'] ?? []));
@endphp
<section class="users-list-wrapper">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    @php
        $activeTabTitle = [
            'dashboard' => 'Mail Operations Dashboard',
            'compose' => $editMessage ? 'Refine Draft Before Dispatch' : 'Compose A New Mail',
            'messages' => 'Message Log And Delivery Flow',
            'notifications' => 'Notification And Approval Control',
            'templates' => 'Reusable Mail Templates',
            'mailboxes' => 'SMTP Mailbox Control',
            'settings' => 'MailCenter Rules And Defaults',
        ][$active_tab] ?? 'Rig MailCenter';

        $activeTabDescription = [
            'dashboard' => 'Track delivery volume, approvals, unread notifications, and mailbox readiness from one place.',
            'compose' => 'Build a clean outbound message with linked JCF context, contacts, attachments, and approval-aware sending.',
            'messages' => 'Review drafts, pending approvals, sent items, and failed attempts without losing operational context.',
            'notifications' => 'Mix MailCenter alerts, action center items, and manager approval queues with per-user controls.',
            'templates' => 'Keep common client communication ready with reusable subjects and content blocks.',
            'mailboxes' => 'Control SMTP senders, access assignments, and default routing for each mailbox.',
            'settings' => 'Tune approval rules, polling, footer/signature defaults, and navbar visibility behavior.',
        ][$active_tab] ?? 'Centralized operational mail for workflow, approvals, and notifications.';
    @endphp

    <div class="mailcenter-shell">
    <div class="mailcenter-hero">
        <div>
            <h3>Rig MailCenter</h3>
            <div class="h5 mb-50">{{ $activeTabTitle }}</div>
            <p>{{ $activeTabDescription }}</p>
        </div>
        <div class="mailcenter-hero-metrics">
            <div class="mailcenter-hero-metric"><span>Messages</span><strong>{{ $dashboard_stats['total'] ?? ($messages->count() ?? 0) }}</strong></div>
            <div class="mailcenter-hero-metric"><span>Pending</span><strong>{{ $dashboard_stats['pending_approval'] ?? 0 }}</strong></div>
            <div class="mailcenter-hero-metric"><span>Unread Alerts</span><strong>{{ $dashboard_stats['unread_notifications'] ?? auth()->user()->unreadNotifications()->count() }}</strong></div>
            <div class="mailcenter-hero-metric"><span>Mailboxes</span><strong>{{ $dashboard_stats['mailboxes'] ?? ($mailboxes->count() ?? 0) }}</strong></div>
        </div>
    </div>

    <div class="card"><div class="card-body">
        <ul class="nav nav-tabs nav-top-border mb-2">
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'dashboard' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'dashboard']) }}">Dashboard</a></li>
            @can('create', App\Models\WorkFlow\MailCenter::class)
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'compose' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'compose']) }}">Compose</a></li>
            @endcan
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'messages' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'messages']) }}">Messages</a></li>
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'notifications' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'notifications']) }}">Notifications</a></li>
            @can('manageTemplates', App\Models\WorkFlow\MailCenter::class)
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'templates' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'templates']) }}">Templates</a></li>
            @endcan
            @can('manageSettings', App\Models\WorkFlow\MailCenter::class)
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'mailboxes' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'mailboxes']) }}">Mailboxes</a></li>
            <li class="nav-item"><a class="nav-link {{ $active_tab === 'settings' ? 'active' : '' }}" href="{{ route('mailCenter.index', ['tab' => 'settings']) }}">Settings</a></li>
            @endcan
        </ul>

        @if($active_tab === 'dashboard')
            <div class="mailcenter-box">
                <div class="mailcenter-stats">
                    <div class="mailcenter-stat"><small>Total Messages</small><strong>{{ $dashboard_stats['total'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Drafts</small><strong>{{ $dashboard_stats['draft'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Pending Approval</small><strong>{{ $dashboard_stats['pending_approval'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Sent</small><strong>{{ $dashboard_stats['sent'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Failed</small><strong>{{ $dashboard_stats['failed'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Action Queue</small><strong>{{ $dashboard_stats['actionable'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Unread Notifications</small><strong>{{ $dashboard_stats['unread_notifications'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Approval Queue</small><strong>{{ $dashboard_stats['approval_queue'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Mailboxes</small><strong>{{ $dashboard_stats['mailboxes'] ?? 0 }}</strong></div>
                    <div class="mailcenter-stat"><small>Templates</small><strong>{{ $dashboard_stats['templates'] ?? 0 }}</strong></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-7 col-12">
                    <div class="mailcenter-box">
                        <h4>Recent Messages</h4>
                        <div class="table-responsive"><table class="table table-striped table-bordered mb-0"><thead><tr><th>Code</th><th>Subject</th><th>Status</th><th>Updated</th><th></th></tr></thead><tbody>
                            @forelse($messages->take(8) as $message)
                                <tr>
                                    <td class="mailcenter-code">{{ $message->code }}</td>
                                    <td>{{ $message->subject }}<div class="mailcenter-muted">{{ optional($message->client)->name ?: optional(optional($message->jobRequest)->client)->name ?: '-' }}</div></td>
                                    <td><span class="mailcenter-status {{ $message->status }}">{{ str_replace('_', ' ', $message->status) }}</span></td>
                                    <td>{{ $message->updated_at?->format('d-m-Y H:i') }}</td>
                                    <td><a href="{{ route('mailCenter.show', $message->id) }}" class="btn btn-sm btn-outline-info">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No messages created yet.</td></tr>
                            @endforelse
                        </tbody></table></div>
                    </div>
                </div>
                <div class="col-xl-5 col-12">
                    <div class="mailcenter-box">
                        <h4>Quick Setup</h4>
                        <p class="mb-1"><strong>Default mailbox:</strong> {{ optional(optional($settings)->defaultMailbox)->name ?: 'Not configured' }}</p>
                        <p class="mb-1"><strong>Approval flow:</strong> {{ optional($settings)->require_approval ? 'Required before send' : 'Direct send allowed' }}</p>
                        <p class="mb-1"><strong>Attachment limit:</strong> {{ optional($settings)->attachment_limit_mb ?: 10 }} MB</p>
                        <p class="mb-1"><strong>Navbar mail dropdown:</strong> {{ optional($settings)->show_navbar_mail ? 'Enabled' : 'Disabled' }}</p>
                        <p class="mb-1"><strong>Navbar notifications:</strong> {{ optional($settings)->show_navbar_notifications ? 'Enabled' : 'Disabled' }}</p>
                        <p class="mb-1"><strong>Navbar polling:</strong> {{ (optional($settings)->navbar_polling_enabled ?? true) ? 'Enabled' : 'Disabled' }}</p>
                        <p class="mb-1"><strong>Polling interval:</strong> {{ optional($settings)->navbar_polling_interval_seconds ?: 60 }} sec</p>
                        <p class="mb-0"><strong>Event tracking:</strong> {{ optional($settings)->track_events ? 'Enabled' : 'Disabled' }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if($active_tab === 'compose')
            <div class="mailcenter-compose-grid">
                <div>
                    <form method="POST" action="{{ $composeAction }}" enctype="multipart/form-data" id="mailcenter-compose-form" class="mailcenter-box">
                        @csrf
                        @if($editMessage) @method('PUT') @endif

                        <div class="mailcenter-panel-head">
                            <div>
                                <h4 class="mb-50">{{ $editMessage ? 'Edit Draft' : 'Compose Email' }}</h4>
                                <p>Build the message once, keep the workflow context, and send with the correct mailbox and approval path.</p>
                            </div>
                            <span class="mailcenter-panel-badge">{{ $editMessage ? 'Draft Update' : 'New Dispatch' }}</span>
                        </div>

                        @if(!$editMessage && !empty($composeDefaults['source_label']))
                        <div class="mailcenter-intro-note">Prefilled from {{ $composeDefaults['source_label'] }}.</div>
                        @endif
                        <div class="mailcenter-intro-note">Only mailboxes assigned to your account are available for sending. Super admins keep full access.</div>

                        <div class="mailcenter-compose-panel">
                            <div class="mailcenter-panel-head">
                                <div>
                                    <h5>Delivery Setup</h5>
                                    <p>Choose the sender mailbox, template, and the operational record that this mail belongs to.</p>
                                </div>
                            </div>
                            <div class="row mailcenter-input-stack">
                                <div class="col-md-6"><div class="form-group"><label>Sender Mailbox</label><select class="form-control" name="mailbox_id" id="mailbox_id"><option value="">Use default mailbox</option>@foreach($mailboxes as $mailbox)<option value="{{ $mailbox->id }}" @selected(old('mailbox_id', optional($editMessage)->mailbox_id ?? ($composeDefaults['mailbox_id'] ?? null)) == $mailbox->id)>{{ $mailbox->name }} - {{ $mailbox->from_email }}</option>@endforeach</select></div></div>
                                <div class="col-md-6"><div class="form-group"><label>Template</label><select class="form-control" name="template_id" id="mailcenter-template-select"><option value="">Manual message</option>@foreach($templates as $template)<option value="{{ $template->id }}" @selected(old('template_id', optional($editMessage)->template_id ?? ($composeDefaults['template_id'] ?? null)) == $template->id)>{{ $template->name }}</option>@endforeach</select></div></div>
                                <div class="col-md-6"><div class="form-group"><label>Related JCF</label><select class="form-control" name="job_request_id" id="mailcenter-job-request"><option value="">No JCF link</option>@foreach($job_requests as $job)<option value="{{ $job->id }}" @selected(old('job_request_id', optional($editMessage)->job_request_id ?? ($composeDefaults['job_request_id'] ?? null)) == $job->id)>{{ $job->code }} - {{ optional($job->client)->name ?: optional($job->supplier)->name ?: '-' }}</option>@endforeach</select></div></div>
                                <div class="col-md-6"><div class="form-group"><label>Client</label><select class="form-control" name="client_id" id="mailcenter-client"><option value="">Select client</option>@foreach($clients as $client)<option value="{{ $client->id }}" @selected(old('client_id', optional($editMessage)->client_id ?? ($composeDefaults['client_id'] ?? null)) == $client->id)>{{ $client->code }} - {{ $client->name }}</option>@endforeach</select></div></div>
                            </div>
                        </div>

                        <div class="mailcenter-compose-panel">
                            <div class="mailcenter-panel-head">
                                <div>
                                    <h5>Recipients</h5>
                                    <p>Pick contact people first, then review direct recipients in To, CC, and BCC.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Contact Persons</label>
                                <select class="form-control mailcenter-contact-select" name="contact_person_ids[]" multiple size="6">@foreach($contacts as $contact)<option value="{{ $contact->id }}" @selected(in_array($contact->id, $selectedContactIds))>{{ $contact->name }} - {{ $contact->email }}</option>@endforeach</select>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><div class="form-group"><label>To</label><textarea class="form-control" rows="4" name="to_emails" id="mailcenter-to">{{ old('to_emails', optional($editMessage)->to_emails ?? ($composeDefaults['to_emails'] ?? '')) }}</textarea></div></div>
                                <div class="col-md-4"><div class="form-group"><label>CC</label><textarea class="form-control" rows="4" name="cc_emails">{{ old('cc_emails', optional($editMessage)->cc_emails) }}</textarea></div></div>
                                <div class="col-md-4"><div class="form-group"><label>BCC</label><textarea class="form-control" rows="4" name="bcc_emails">{{ old('bcc_emails', optional($editMessage)->bcc_emails) }}</textarea></div></div>
                            </div>
                        </div>

                        <div class="mailcenter-compose-panel">
                            <div class="mailcenter-panel-head">
                                <div>
                                    <h5>Content</h5>
                                    <p>Use the HTML body for the branded message and keep the plain-text body for fallback delivery.</p>
                                </div>
                            </div>
                            <div class="form-group"><label>Subject</label><input type="text" class="form-control" name="subject" id="mailcenter-subject" value="{{ old('subject', optional($editMessage)->subject ?? ($composeDefaults['subject'] ?? '')) }}"></div>
                            <div class="form-group"><label>HTML Body</label><textarea class="form-control mailcenter-textarea-lg" rows="10" name="body_html" id="mailcenter-body-html">{{ old('body_html', optional($editMessage)->body_html ?? ($composeDefaults['body_html'] ?? '')) }}</textarea></div>
                            <div class="form-group mb-0"><label>Plain Text Body</label><textarea class="form-control" rows="5" name="body_text">{{ old('body_text', optional($editMessage)->body_text ?? ($composeDefaults['body_text'] ?? '')) }}</textarea></div>
                        </div>

                        <div class="mailcenter-compose-panel">
                            <div class="mailcenter-panel-head">
                                <div>
                                    <h5>Attachments And Dispatch</h5>
                                    <p>Attach supporting files, keep them indexed in File Manager, then save or send immediately.</p>
                                </div>
                            </div>
                            <div class="mailcenter-attachment-box mb-2">
                                <h6>Attachments</h6>
                                <p>Files are indexed in File Manager under <strong>mailcenter / attachments</strong>.</p>
                                <input type="file" class="form-control system-file-input" name="attachments[]" multiple>
                                <small class="system-file-help">Use this for PDFs, signed letters, and any supporting attachments for the client or internal approval.</small>
                            </div>
                            <input type="hidden" name="related_type" value="{{ old('related_type', optional($editMessage)->related_type ?? ($composeDefaults['related_type'] ?? '')) }}">
                            <input type="hidden" name="related_id" value="{{ old('related_id', optional($editMessage)->related_id ?? ($composeDefaults['related_id'] ?? '')) }}">
                            <div class="mailcenter-compose-actions">
                                <div class="mailcenter-muted">Approval rules and mailbox permissions are applied automatically when you submit.</div>
                                <div class="mailcenter-actions"><button type="submit" name="submit_action" value="draft" class="btn btn-outline-primary">Save Draft</button><button type="submit" name="submit_action" value="send" class="btn btn-primary">Send Now</button></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="mailcenter-sidebar-card">
                        <h5>Compose Checklist</h5>
                        <div class="mailcenter-muted mb-1">Before sending, verify the sender mailbox, primary recipients, and whether this message should stay linked to a JCF.</div>
                        <div class="mailcenter-muted">If a template is selected, subject and HTML body are filled automatically.</div>
                    </div>
                    <div class="mailcenter-sidebar-card">
                        <h5>Placeholders</h5>
                        <div class="mailcenter-token-list">@foreach($placeholder_tokens as $token)<code>{{ $token }}</code>@endforeach</div>
                        <div class="mailcenter-muted mt-2">Selecting a JCF fills client and main contact email when available.</div>
                    </div>
                </div>
            </div>
        @endif

        @if($active_tab === 'messages')
            <div class="mailcenter-message-summary">
                <div class="mailcenter-summary-card"><span>Total</span><strong>{{ $messages->count() }}</strong></div>
                <div class="mailcenter-summary-card"><span>Draft + Failed</span><strong>{{ $messages->whereIn('status', ['draft', 'failed'])->count() }}</strong></div>
                <div class="mailcenter-summary-card"><span>Pending Approval</span><strong>{{ $messages->where('status', 'pending_approval')->count() }}</strong></div>
                <div class="mailcenter-summary-card"><span>Sent</span><strong>{{ $messages->where('status', 'sent')->count() }}</strong></div>
            </div>
            <div class="mailcenter-box">
                <div class="mailcenter-table-header">
                    <div>
                        <h4 class="mb-50">Messages Log</h4>
                        <p>Operational delivery history with edit, approve, resend, and delete actions kept on the same row.</p>
                    </div>
                </div>
                <div class="table-responsive"><table class="table table-striped table-bordered mb-0 mailcenter-log-table"><thead><tr><th>Code</th><th>Subject</th><th>Client</th><th>Mailbox</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
                    @forelse($messages as $message)
                    <tr>
                        <td class="mailcenter-code">{{ $message->code }}</td>
                        <td class="mailcenter-log-subject"><strong>{{ $message->subject }}</strong><div class="mailcenter-muted">{{ $message->recipients->take(2)->pluck('email')->implode(', ') }}</div></td>
                        <td>{{ optional($message->client)->name ?: optional(optional($message->jobRequest)->client)->name ?: '-' }}</td>
                        <td>{{ optional($message->mailbox)->name ?: '-' }}</td>
                        <td><span class="mailcenter-status {{ $message->status }}">{{ str_replace('_', ' ', $message->status) }}</span></td>
                        <td>{{ $message->updated_at?->format('d-m-Y H:i') }}</td>
                        <td><div class="mailcenter-actions">
                            <a href="{{ route('mailCenter.show', $message->id) }}" class="btn btn-sm btn-outline-info">View</a>
                            @if(in_array($message->status, ['draft', 'failed', 'pending_approval'], true) && auth()->user()->can('update', $message))
                                <a href="{{ route('mailCenter.edit', $message->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endif
                            @if($message->status === 'pending_approval' && auth()->user()->can('approve', App\Models\WorkFlow\MailCenter::class))
                                <form action="{{ route('mailCenter.approve', $message->id) }}" method="POST">@csrf<button class="btn btn-sm btn-warning">Approve</button></form>
                            @endif
                            @if(in_array($message->status, ['draft', 'approved', 'failed'], true) && auth()->user()->can('send', App\Models\WorkFlow\MailCenter::class))
                                <form action="{{ route('mailCenter.send', $message->id) }}" method="POST">@csrf<button class="btn btn-sm btn-success">Send</button></form>
                            @endif
                            @can('delete', $message)
                                <form action="{{ route('mailCenter.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                            @endcan
                        </div></td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No messages found.</td></tr>
                    @endforelse
                </tbody></table></div>
            </div>
        @endif
        @if($active_tab === 'notifications')
            <div class="row">
                <div class="col-xl-4 col-12">
                    <div class="mailcenter-box">
                        <h4>Notification Control Panel</h4>
                        <p class="mb-1"><strong>Unread:</strong> {{ auth()->user()->unreadNotifications()->count() }}</p>
                        <p class="mb-1"><strong>Approval queue:</strong> {{ $approval_queue_count ?? 0 }}</p>
                        <p class="mb-1"><strong>Mail approval alerts:</strong> {{ optional($settings)->notify_on_pending_approval ? 'On' : 'Off' }}</p>
                        <p class="mb-1"><strong>Approved alerts (Mail + Inspection):</strong> {{ optional($settings)->notify_on_approved ? 'On' : 'Off' }}</p>
                        <p class="mb-1"><strong>Sent alerts:</strong> {{ optional($settings)->notify_on_sent ? 'On' : 'Off' }}</p>
                        <p class="mb-1"><strong>Failed alerts:</strong> {{ optional($settings)->notify_on_failed ? 'On' : 'Off' }}</p>
                        <p class="mb-1"><strong>Action Center alerts:</strong> {{ $action_center_enabled ? 'On' : 'Off' }}</p>
                        <p class="mb-1"><strong>Live Action Center items:</strong> {{ $action_center_count ?? 0 }}</p>
                        <p class="mb-1"><strong>Navbar polling:</strong> {{ (optional($settings)->navbar_polling_enabled ?? true) ? 'On' : 'Off' }}</p>
                        <p class="mb-0"><strong>Polling interval:</strong> {{ optional($settings)->navbar_polling_interval_seconds ?: 60 }} sec</p>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                        <form method="POST" action="{{ route('mailCenter.notifications.readAll') }}" class="mt-2">
                            @csrf
                            <button class="btn btn-outline-primary btn-sm">Mark All Read</button>
                        </form>
                        @endif
                    </div>
                    <div class="mailcenter-box">
                        <h4>My Notification Preferences</h4>
                        <form method="POST" action="{{ route('mailCenter.preferences.update') }}">
                            @csrf
                            @can('approve', App\Models\WorkFlow\MailCenter::class)
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="user-notify-pending" name="notify_on_pending_approval" value="1" @checked(old('notify_on_pending_approval', optional($notification_preference)->notify_on_pending_approval ?? true))>
                                <label class="custom-control-label" for="user-notify-pending">Receive pending approval alerts</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="user-approval-queue" name="show_pending_approval_queue" value="1" @checked(old('show_pending_approval_queue', optional($notification_preference)->show_pending_approval_queue ?? true))>
                                <label class="custom-control-label" for="user-approval-queue">Show live manager/admin approval queue in notifications</label>
                            </div>
                            @else
                            <div class="alert alert-light">Approval queue controls appear only for admin/manager accounts that can approve MailCenter messages.</div>
                            @endcan
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="user-notify-approved" name="notify_on_approved" value="1" @checked(old('notify_on_approved', optional($notification_preference)->notify_on_approved ?? true))>
                                <label class="custom-control-label" for="user-notify-approved">Receive approved alerts (Mail + Inspection)</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="user-action-center-items" name="show_action_center_items" value="1" @checked(old('show_action_center_items', optional($notification_preference)->show_action_center_items ?? true))>
                                <label class="custom-control-label" for="user-action-center-items">Show live Action Center alerts in notifications</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input" id="user-notify-sent" name="notify_on_sent" value="1" @checked(old('notify_on_sent', optional($notification_preference)->notify_on_sent ?? true))>
                                <label class="custom-control-label" for="user-notify-sent">Receive sent alerts</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="user-notify-failed" name="notify_on_failed" value="1" @checked(old('notify_on_failed', optional($notification_preference)->notify_on_failed ?? true))>
                                <label class="custom-control-label" for="user-notify-failed">Receive failed alerts</label>
                            </div>
                            <button class="btn btn-primary btn-sm">Save My Preferences</button>
                        </form>
                    </div>
                </div>
                <div class="col-xl-8 col-12">
                    @if($action_center_enabled)
                    <div class="mailcenter-box">
                        <h4>Action Center Alerts</h4>
                        <div class="mailcenter-muted mb-1">Live workflow alerts using the same permission-aware logic as the dashboard Action Center.</div>
                        @forelse($action_center_items as $item)
                            <div class="mailcenter-notification-item unread">
                                <div>
                                    <div class="font-weight-bold">{{ $item['title'] ?? 'Action Center' }} <span class="badge badge-{{ $item['status'] ?? 'primary' }}">{{ $item['count'] ?? 0 }}</span></div>
                                    <div class="mailcenter-muted">{{ $item['message'] ?? '' }}</div>
                                    <div class="mailcenter-muted mt-50">{{ $item['subject'] ?? '' }}</div>
                                </div>
                                <div class="mailcenter-actions">
                                    <a href="{{ $item['url'] ?? '#' }}" class="btn btn-sm btn-{{ $item['status'] ?? 'primary' }}">{{ $item['cta'] ?? 'Open' }}</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">No Action Center alerts right now.</div>
                        @endforelse
                    </div>
                    @endif
                    @if($approval_queue_enabled)
                    <div class="mailcenter-box">
                        <h4>Pending Approval Queue</h4>
                        <div class="mailcenter-muted mb-1">Manager/admin-only view of messages still waiting for approval.</div>
                        @if(($approval_queue_count ?? 0) > $approval_queue_items->count())
                        <div class="mailcenter-muted mb-1">Showing the latest {{ $approval_queue_items->count() }} of {{ $approval_queue_count }} pending approvals.</div>
                        @endif
                        @forelse($approval_queue_items as $message)
                            <div class="mailcenter-notification-item unread">
                                <div>
                                    <div class="font-weight-bold">{{ $message['title'] ?? 'Approval required' }}</div>
                                    <div class="mailcenter-muted">{{ $message['message'] ?? '' }}</div>
                                    <div class="mailcenter-muted mt-50">{{ $message['subject'] ?? '' }}</div>
                                </div>
                                <div class="mailcenter-actions">
                                    <a href="{{ $message['url'] ?? '#' }}" class="btn btn-sm btn-outline-warning">Open Message</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">No pending approvals right now.</div>
                        @endforelse
                    </div>
                    @endif
                    <div class="mailcenter-box">
                        <h4>Recent Notifications</h4>
                        @forelse($notification_items as $notification)
                            <div class="mailcenter-notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                <div>
                                    <div class="font-weight-bold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                    <div class="mailcenter-muted">{{ $notification->data['message'] ?? '' }}</div>
                                    <div class="mailcenter-muted mt-50">{{ optional($notification->created_at)->format('d-m-Y H:i') }}</div>
                                </div>
                                <div class="mailcenter-actions">
                                    <a href="{{ route('mailCenter.notifications.open', $notification->id) }}" class="btn btn-sm btn-outline-info">{{ $notification->read_at ? 'Open' : 'Open + Read' }}</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">No notifications yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
        @if($active_tab === 'templates')
            <div class="row">
                <div class="col-xl-5 col-12"><div class="mailcenter-box"><h4>{{ $editing_template ? 'Edit Template' : 'Add Template' }}</h4>
                    <form method="POST" action="{{ $editing_template ? route('mailCenter.template.update', $editing_template->id) : route('mailCenter.template.store') }}">
                        @csrf @if($editing_template) @method('PUT') @endif
                        <div class="form-group"><label>Name</label><input class="form-control" name="name" value="{{ old('name', optional($editing_template)->name) }}"></div>
                        <div class="form-group"><label>Slug</label><input class="form-control" name="slug" value="{{ old('slug', optional($editing_template)->slug) }}"></div>
                        <div class="form-group"><label>Category</label><input class="form-control" name="category" value="{{ old('category', optional($editing_template)->category ?: 'general') }}"></div>
                        <div class="form-group"><label>Subject</label><input class="form-control" name="subject" value="{{ old('subject', optional($editing_template)->subject) }}"></div>
                        <div class="form-group"><label>HTML Body</label><textarea class="form-control" rows="8" name="body_html">{{ old('body_html', optional($editing_template)->body_html) }}</textarea></div>
                        <div class="form-group"><label>Plain Text</label><textarea class="form-control" rows="4" name="body_text">{{ old('body_text', optional($editing_template)->body_text) }}</textarea></div>
                        <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="template-active" name="is_active" value="1" @checked(old('is_active', optional($editing_template)->is_active ?? true))><label class="custom-control-label" for="template-active">Active</label></div>
                        <button class="btn btn-primary">{{ $editing_template ? 'Update Template' : 'Save Template' }}</button>
                    </form></div></div>
                <div class="col-xl-7 col-12"><div class="mailcenter-box"><h4>Existing Templates</h4><div class="table-responsive"><table class="table table-striped table-bordered mb-0"><thead><tr><th>Name</th><th>Category</th><th>Subject</th><th></th></tr></thead><tbody>@foreach($templates as $template)<tr><td>{{ $template->name }}</td><td>{{ $template->category }}</td><td>{{ $template->subject }}</td><td><div class="mailcenter-actions"><a href="{{ route('mailCenter.index', ['tab' => 'templates', 'edit_template' => $template->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a><form action="{{ route('mailCenter.template.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Delete this template?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></div></td></tr>@endforeach</tbody></table></div></div></div>
            </div>
        @endif

        @if($active_tab === 'mailboxes')
            <div class="row">
                <div class="col-xl-5 col-12"><div class="mailcenter-box"><h4>{{ $editing_mailbox ? 'Edit Mailbox' : 'Add SMTP Mailbox' }}</h4>
                    <form method="POST" action="{{ $editing_mailbox ? route('mailCenter.mailbox.update', $editing_mailbox->id) : route('mailCenter.mailbox.store') }}">
                        @csrf @if($editing_mailbox) @method('PUT') @endif
                        <div class="form-group"><label>Name</label><input class="form-control" name="name" value="{{ old('name', optional($editing_mailbox)->name) }}"></div>
                        <div class="row"><div class="col-md-4"><div class="form-group"><label>Driver</label><input class="form-control" name="driver" value="{{ old('driver', optional($editing_mailbox)->driver ?: 'smtp') }}"></div></div><div class="col-md-5"><div class="form-group"><label>Host</label><input class="form-control" name="host" value="{{ old('host', optional($editing_mailbox)->host) }}"></div></div><div class="col-md-3"><div class="form-group"><label>Port</label><input class="form-control" name="port" value="{{ old('port', optional($editing_mailbox)->port ?: 587) }}"></div></div></div>
                        <div class="row"><div class="col-md-6"><div class="form-group"><label>Encryption</label><input class="form-control" name="encryption" value="{{ old('encryption', optional($editing_mailbox)->encryption ?: 'tls') }}"></div></div><div class="col-md-6"><div class="form-group"><label>Username</label><input class="form-control" name="username" value="{{ old('username', optional($editing_mailbox)->username) }}"></div></div></div>
                        <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" value=""></div>
                        <div class="row"><div class="col-md-6"><div class="form-group"><label>From Email</label><input class="form-control" name="from_email" value="{{ old('from_email', optional($editing_mailbox)->from_email) }}"></div></div><div class="col-md-6"><div class="form-group"><label>From Name</label><input class="form-control" name="from_name" value="{{ old('from_name', optional($editing_mailbox)->from_name) }}"></div></div></div>
                        <div class="row"><div class="col-md-6"><div class="form-group"><label>Reply-To Email</label><input class="form-control" name="reply_to_email" value="{{ old('reply_to_email', optional($editing_mailbox)->reply_to_email) }}"></div></div><div class="col-md-6"><div class="form-group"><label>Reply-To Name</label><input class="form-control" name="reply_to_name" value="{{ old('reply_to_name', optional($editing_mailbox)->reply_to_name) }}"></div></div></div>
                        <div class="form-group"><label>Assigned Users</label><select class="form-control" name="user_ids[]" multiple size="6">@foreach($mailbox_users as $mailboxUser)<option value="{{ $mailboxUser->id }}" @selected(in_array($mailboxUser->id, old('user_ids', optional($editing_mailbox)->users?->pluck('id')->all() ?: [])))>{{ optional($mailboxUser->employee)->name ?: ('User #'.$mailboxUser->id) }}</option>@endforeach</select><small class="mailcenter-muted">If no assignments are saved anywhere, all authorized users keep access. Once assignments exist, access becomes mailbox-specific.</small></div>
                        <div class="form-group"><label>Note</label><textarea class="form-control" rows="3" name="note">{{ old('note', optional($editing_mailbox)->note) }}</textarea></div>
                        <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="mailbox-default" name="is_default" value="1" @checked(old('is_default', optional($editing_mailbox)->is_default))><label class="custom-control-label" for="mailbox-default">Default sender</label></div>
                        <div class="custom-control custom-checkbox mb-2"><input type="checkbox" class="custom-control-input" id="mailbox-active" name="is_active" value="1" @checked(old('is_active', optional($editing_mailbox)->is_active ?? true))><label class="custom-control-label" for="mailbox-active">Active</label></div>
                        <button class="btn btn-primary">{{ $editing_mailbox ? 'Update Mailbox' : 'Save Mailbox' }}</button>
                    </form></div></div>
                <div class="col-xl-7 col-12"><div class="mailcenter-box"><h4>Sender Mailboxes</h4><div class="table-responsive"><table class="table table-striped table-bordered mb-0"><thead><tr><th>Name</th><th>Address</th><th>SMTP</th><th>Assigned Users</th><th></th></tr></thead><tbody>@foreach($mailboxes as $mailbox)<tr><td>{{ $mailbox->name }} @if($mailbox->is_default)<span class="badge badge-info">Default</span>@endif @if(!$mailbox->is_active)<span class="badge badge-secondary">Inactive</span>@endif</td><td>{{ $mailbox->from_name }}<div class="mailcenter-muted">{{ $mailbox->from_email }}</div></td><td>{{ $mailbox->host }}:{{ $mailbox->port }}<div class="mailcenter-muted">{{ strtoupper($mailbox->driver) }} · {{ $mailbox->encryption ?: 'none' }}</div></td><td>{{ $mailbox->users->pluck('employee.name')->filter()->implode(', ') ?: 'All authorized users (fallback)' }}</td><td><div class="mailcenter-actions"><a href="{{ route('mailCenter.index', ['tab' => 'mailboxes', 'edit_mailbox' => $mailbox->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a><form action="{{ route('mailCenter.mailbox.destroy', $mailbox->id) }}" method="POST" onsubmit="return confirm('Delete this mailbox?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></div></td></tr>@endforeach</tbody></table></div></div></div>
            </div>
        @endif
        @if($active_tab === 'settings')
            <div class="mailcenter-box">
                <h4>MailCenter Settings</h4>
                <form method="POST" action="{{ route('mailCenter.settings.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Default Mailbox</label><select class="form-control" name="default_mailbox_id"><option value="">No default</option>@foreach($mailboxes as $mailbox)<option value="{{ $mailbox->id }}" @selected(old('default_mailbox_id', optional($settings)->default_mailbox_id) == $mailbox->id)>{{ $mailbox->name }} - {{ $mailbox->from_email }}</option>@endforeach</select></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Attachment Limit (MB)</label><input class="form-control" name="attachment_limit_mb" value="{{ old('attachment_limit_mb', optional($settings)->attachment_limit_mb ?: 10) }}"></div></div>
                        <div class="col-md-4"><div class="form-group"><label>Polling Interval (Seconds)</label><input class="form-control" name="navbar_polling_interval_seconds" value="{{ old('navbar_polling_interval_seconds', optional($settings)->navbar_polling_interval_seconds ?: 60) }}"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox mt-2"><input type="checkbox" class="custom-control-input" id="require-approval" name="require_approval" value="1" @checked(old('require_approval', optional($settings)->require_approval))><label class="custom-control-label" for="require-approval">Require approval before sending</label></div>
                            <div class="custom-control custom-checkbox mt-1"><input type="checkbox" class="custom-control-input" id="track-events" name="track_events" value="1" @checked(old('track_events', optional($settings)->track_events ?? true))><label class="custom-control-label" for="track-events">Track message events</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox mt-1"><input type="checkbox" class="custom-control-input" id="show-navbar-mail" name="show_navbar_mail" value="1" @checked(old('show_navbar_mail', optional($settings)->show_navbar_mail ?? true))><label class="custom-control-label" for="show-navbar-mail">Show mail dropdown in navbar</label></div>
                            <div class="custom-control custom-checkbox mt-1"><input type="checkbox" class="custom-control-input" id="show-navbar-notifications" name="show_navbar_notifications" value="1" @checked(old('show_navbar_notifications', optional($settings)->show_navbar_notifications ?? true))><label class="custom-control-label" for="show-navbar-notifications">Show notifications bell in navbar</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox mt-1"><input type="checkbox" class="custom-control-input" id="navbar-polling-enabled" name="navbar_polling_enabled" value="1" @checked(old('navbar_polling_enabled', optional($settings)->navbar_polling_enabled ?? true))><label class="custom-control-label" for="navbar-polling-enabled">Enable navbar polling</label></div>
                            <div class="mailcenter-muted mt-1">Polling refreshes the mail and notification counters without a full page reload.</div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-3"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="notify-pending" name="notify_on_pending_approval" value="1" @checked(old('notify_on_pending_approval', optional($settings)->notify_on_pending_approval ?? true))><label class="custom-control-label" for="notify-pending">Notify on pending approval</label></div></div>
                        <div class="col-md-3"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="notify-approved" name="notify_on_approved" value="1" @checked(old('notify_on_approved', optional($settings)->notify_on_approved ?? true))><label class="custom-control-label" for="notify-approved">Notify on approved (Mail + Inspection)</label></div></div>
                        <div class="col-md-3"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="notify-sent" name="notify_on_sent" value="1" @checked(old('notify_on_sent', optional($settings)->notify_on_sent ?? true))><label class="custom-control-label" for="notify-sent">Notify on sent</label></div></div>
                        <div class="col-md-3"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="notify-failed" name="notify_on_failed" value="1" @checked(old('notify_on_failed', optional($settings)->notify_on_failed ?? true))><label class="custom-control-label" for="notify-failed">Notify on failed</label></div></div>
                    </div>
                    <div class="form-group"><label>Default Signature HTML</label><textarea class="form-control" rows="5" name="default_signature_html">{{ old('default_signature_html', optional($settings)->default_signature_html) }}</textarea></div>
                    <div class="form-group"><label>Default Footer HTML</label><textarea class="form-control" rows="5" name="default_footer_html">{{ old('default_footer_html', optional($settings)->default_footer_html) }}</textarea></div>
                    <button class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        @endif
    </div></div>
    </div>
</section>
@endsection

@section('ajax')
<script>
const mailTemplates = @json($templates->map(function ($template) { return ['id' => $template->id, 'subject' => $template->subject, 'body_html' => $template->body_html]; })->values());
const jobRequests = @json($job_requests->mapWithKeys(function ($job) { return [$job->id => ['client_id' => $job->client_id, 'contact_email' => optional($job->contactPeopleShow)->email]]; }));
document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('mailcenter-template-select');
    const subjectInput = document.getElementById('mailcenter-subject');
    const bodyInput = document.getElementById('mailcenter-body-html');
    const jobRequestSelect = document.getElementById('mailcenter-job-request');
    const clientSelect = document.getElementById('mailcenter-client');
    const toInput = document.getElementById('mailcenter-to');
    if (templateSelect && subjectInput && bodyInput) {
        templateSelect.addEventListener('change', function () {
            const selected = mailTemplates.find(item => String(item.id) === String(this.value));
            if (!selected) { return; }
            subjectInput.value = selected.subject || '';
            bodyInput.value = selected.body_html || '';
        });
    }
    if (jobRequestSelect && clientSelect && toInput) {
        jobRequestSelect.addEventListener('change', function () {
            const job = jobRequests[this.value];
            if (!job) { return; }
            if (job.client_id) { clientSelect.value = job.client_id; }
            if (job.contact_email && !toInput.value.trim()) { toInput.value = job.contact_email; }
        });
    }
});
</script>
@endsection

