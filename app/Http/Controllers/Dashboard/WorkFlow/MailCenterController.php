<?php

namespace App\Http\Controllers\Dashboard\WorkFlow;

use App\Http\Controllers\Controller;
use App\Mail\RigMailCenterMessage;
use App\Models\Persons\Client;
use App\Models\Persons\ContactPerson;
use App\Models\User;
use App\Models\WorkFlow\FileManager;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\MailCenter;
use App\Models\WorkFlow\MailCenterEvent;
use App\Models\WorkFlow\MailCenterMailbox;
use App\Models\WorkFlow\MailCenterSetting;
use App\Models\WorkFlow\MailCenterTemplate;
use App\Support\MailCenterComposeDefaults;
use App\Notifications\MailCenterEventNotification;
use App\Support\MailCenterNavbarData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MailCenterController extends Controller
{
    public $page_name = 'Rig MailCenter';

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(MailCenter::class, 'mailCenter');
    }

    public function index(Request $request)
    {
        $settings = $this->getSettings();
        $activeTab = $request->get('tab', 'dashboard');
        if ($activeTab === 'compose' && !Auth::user()->can('create', MailCenter::class)) {
            $activeTab = 'dashboard';
        }
        if ($activeTab === 'templates' && !Auth::user()->can('manageTemplates', MailCenter::class)) {
            $activeTab = 'dashboard';
        }
        if (in_array($activeTab, ['mailboxes', 'settings'], true) && !Auth::user()->can('manageSettings', MailCenter::class)) {
            $activeTab = 'dashboard';
        }
        if ($activeTab === 'notifications' && !Auth::user()->can('viewAny', MailCenter::class)) {
            $activeTab = 'dashboard';
        }
        $editingTemplateId = (int) $request->get('edit_template');
        $editingMailboxId = (int) $request->get('edit_mailbox');
        $editingMessageId = (int) $request->get('edit_message');
        $composeDefaults = (array) session('mailcenter_compose_defaults', []);

        $user = Auth::user();
        $visibleMessagesQuery = MailCenter::query()->visibleToUser($user);

        $messages = MailCenter::query()
            ->visibleToUser($user)
            ->with([
                'mailbox:id,name,from_email,from_name',
                'template:id,name',
                'jobRequest:id,code,client_id,supplier_id',
                'jobRequest.client:id,name',
                'client:id,name,code,email',
                'creator.employee:id,name',
                'recipients:id,mail_center_id,recipient_type,name,email',
                'attachments:id,mail_center_id,filename,path,disk',
                'events:id,mail_center_id,event_type,description,created_at,created_by',
            ])
            ->latest()
            ->limit(30)
            ->get();

        $dashboardStats = [
            'total' => (clone $visibleMessagesQuery)->count(),
            'draft' => (clone $visibleMessagesQuery)->where('status', 'draft')->count(),
            'pending_approval' => (clone $visibleMessagesQuery)->where('status', 'pending_approval')->count(),
            'sent' => (clone $visibleMessagesQuery)->where('status', 'sent')->count(),
            'failed' => (clone $visibleMessagesQuery)->where('status', 'failed')->count(),
            'templates' => MailCenterTemplate::query()->count(),
            'mailboxes' => MailCenterMailbox::query()->visibleToUser($user)->count(),
            'actionable' => MailCenter::actionableCountForUser($user),
            'unread_notifications' => $user->unreadNotifications()->count(),
            'approval_queue' => MailCenterNavbarData::approvalQueueCountForUser($user),
        ];

        $jobRequests = JobRequest::query()
            ->with(['client:id,name,code,email', 'supplier:id,name,code,email', 'contactPeopleShow:id,name,email'])
            ->orderByDesc('id')
            ->limit(250)
            ->get();

        $clients = Client::query()
            ->select('id', 'code', 'name', 'email')
            ->orderBy('name')
            ->get();

        $contacts = ContactPerson::query()
            ->select('id', 'name', 'email')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name')
            ->get();

        $mailboxes = Auth::user()->can('manageSettings', MailCenter::class)
            ? MailCenterMailbox::query()->with(['users.employee'])->latest()->get()
            : MailCenterMailbox::query()->visibleToUser($user, true)->latest()->get();

        $editingMailbox = null;
        if ($editingMailboxId && Auth::user()->can('manageSettings', MailCenter::class)) {
            $editingMailbox = MailCenterMailbox::query()->with(['users'])->find($editingMailboxId);
        }

        $editingMessage = null;
        if ($editingMessageId) {
            $editingMessage = MailCenter::query()
                ->visibleToUser($user)
                ->with(['recipients', 'attachments'])
                ->find($editingMessageId);
        }

        $notificationPreference = MailCenterNavbarData::userPreference($user);
        $notificationItems = $user->notifications()->latest()->limit(50)->get();
        $approvalQueueCount = MailCenterNavbarData::approvalQueueCountForUser($user, $notificationPreference);
        $approvalQueueItems = MailCenterNavbarData::approvalQueueItemsForUser($user, 20, $notificationPreference);
        $actionCenterCount = MailCenterNavbarData::actionCenterCountForUser($user, $notificationPreference);
        $actionCenterItems = MailCenterNavbarData::actionCenterItemsForUser($user, 20, $notificationPreference);

        return view('layouts.work-flow.mailcenter.index', [
            'page_name' => $this->page_name('All', $this->page_name),
            'active_tab' => $activeTab,
            'settings' => $settings,
            'dashboard_stats' => $dashboardStats,
            'messages' => $messages,
            'mailboxes' => $mailboxes,
            'templates' => MailCenterTemplate::query()->latest()->get(),
            'job_requests' => $jobRequests,
            'clients' => $clients,
            'contacts' => $contacts,
            'editing_template' => $editingTemplateId ? MailCenterTemplate::query()->find($editingTemplateId) : null,
            'editing_mailbox' => $editingMailbox,
            'editing_message' => $editingMessage,
            'compose_defaults' => $composeDefaults,
            'placeholder_tokens' => $this->placeholderTokens(),
            'notification_items' => $notificationItems,
            'notification_preference' => $notificationPreference,
            'approval_queue_count' => $approvalQueueCount,
            'approval_queue_items' => $approvalQueueItems,
            'approval_queue_enabled' => MailCenterNavbarData::approvalQueueEnabledForUser($user, $notificationPreference),
            'action_center_count' => $actionCenterCount,
            'action_center_items' => $actionCenterItems,
            'action_center_enabled' => MailCenterNavbarData::actionCenterEnabledForUser($user, $notificationPreference),
            'mailbox_users' => User::query()->with('employee:id,name')->where('is_active', 1)->orderBy('employee_id')->get(),
        ]);
    }

    public function create()
    {
        return redirect()->route('mailCenter.index', ['tab' => 'compose']);
    }

    public function composeRelated(string $relatedType, int $relatedId)
    {
        $this->authorize('create', MailCenter::class);

        $defaults = MailCenterComposeDefaults::fromRelated($relatedType, $relatedId, Auth::user());

        return redirect()
            ->route('mailCenter.index', ['tab' => 'compose'])
            ->with('mailcenter_compose_defaults', $defaults);
    }

    public function store(Request $request)
    {
        $payload = $this->validateMessage($request);
        $recipients = $this->buildRecipientsPayload($request);
        if (count($recipients) === 0) {
            return back()->withInput()->with('error', 'At least one valid recipient email is required.');
        }

        $settings = $this->getSettings();
        $mailbox = $this->resolveMailbox($payload['mailbox_id'] ?? null, $settings);
        if (!$mailbox) {
            return back()->withInput()->with('error', 'Please configure at least one active sender mailbox.');
        }
        if (!$this->userCanUseMailbox($mailbox)) {
            return back()->withInput()->with('error', 'You do not have access to use the selected mailbox.');
        }

        $jobRequest = $payload['job_request_id'] ? JobRequest::query()->with(['client', 'contactPeopleShow'])->find($payload['job_request_id']) : null;
        if ($jobRequest && empty($payload['client_id']) && $jobRequest->client_id) {
            $payload['client_id'] = $jobRequest->client_id;
        }

        $message = MailCenter::query()->create([
            'code' => $this->generateCode(),
            'mailbox_id' => $mailbox->id,
            'template_id' => $payload['template_id'] ?? null,
            'job_request_id' => $payload['job_request_id'] ?? null,
            'client_id' => $payload['client_id'] ?? null,
            'related_type' => $payload['related_type'] ?? null,
            'related_id' => $payload['related_id'] ?? null,
            'subject' => $payload['subject'],
            'body_html' => $payload['body_html'] ?? null,
            'body_text' => $payload['body_text'] ?? null,
            'to_emails' => implode(', ', array_column(array_filter($recipients, fn ($item) => $item['recipient_type'] === 'to'), 'email')),
            'cc_emails' => implode(', ', array_column(array_filter($recipients, fn ($item) => $item['recipient_type'] === 'cc'), 'email')),
            'bcc_emails' => implode(', ', array_column(array_filter($recipients, fn ($item) => $item['recipient_type'] === 'bcc'), 'email')),
            'status' => 'draft',
            'approval_required' => (bool) ($settings->require_approval ?? false),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        foreach ($recipients as $recipient) {
            $message->recipients()->create($recipient);
        }

        $this->storeAttachments($request, $message, $settings);
        $this->logEvent($message, 'created', 'Mail draft created.');

        if (($payload['submit_action'] ?? 'draft') === 'send') {
            return $this->submitForSending($message, $settings);
        }

        return redirect()
            ->route('mailCenter.index', ['tab' => 'messages'])
            ->with('success', 'Mail draft created successfully.');
    }

    public function show(MailCenter $mailCenter)
    {
        $mailCenter->load([
            'mailbox',
            'template',
            'jobRequest.client',
            'client',
            'recipients.contactPerson',
            'attachments.fileManager',
            'events.creator.employee',
            'creator.employee',
            'approver.employee',
        ]);

        $settings = $this->getSettings();
        $rendered = $this->renderMessageContent($mailCenter, $settings);

        return view('layouts.work-flow.mailcenter.show', [
            'page_name' => 'Mail Message Overview',
            'mail_message' => $mailCenter,
            'rendered_html' => $rendered['html'],
            'rendered_text' => $rendered['text'],
        ]);
    }

    public function edit(MailCenter $mailCenter)
    {
        return redirect()->route('mailCenter.index', ['tab' => 'compose', 'edit_message' => $mailCenter->id]);
    }

    public function update(Request $request, MailCenter $mailCenter)
    {
        if (!in_array($mailCenter->status, ['draft', 'failed', 'pending_approval'], true)) {
            return back()->with('error', 'Only draft, failed, or pending approval messages can be edited.');
        }

        $payload = $this->validateMessage($request);
        $recipients = $this->buildRecipientsPayload($request);
        if (count($recipients) === 0) {
            return back()->withInput()->with('error', 'At least one valid recipient email is required.');
        }

        $settings = $this->getSettings();
        $mailbox = $this->resolveMailbox($payload['mailbox_id'] ?? null, $settings);
        if (!$mailbox) {
            return back()->withInput()->with('error', 'Please configure at least one active sender mailbox.');
        }
        if (!$this->userCanUseMailbox($mailbox)) {
            return back()->withInput()->with('error', 'You do not have access to use the selected mailbox.');
        }

        $jobRequest = $payload['job_request_id'] ? JobRequest::query()->find($payload['job_request_id']) : null;
        if ($jobRequest && empty($payload['client_id']) && $jobRequest->client_id) {
            $payload['client_id'] = $jobRequest->client_id;
        }

        $mailCenter->update([
            'mailbox_id' => $mailbox->id,
            'template_id' => $payload['template_id'] ?? null,
            'job_request_id' => $payload['job_request_id'] ?? null,
            'client_id' => $payload['client_id'] ?? null,
            'related_type' => $payload['related_type'] ?? null,
            'related_id' => $payload['related_id'] ?? null,
            'subject' => $payload['subject'],
            'body_html' => $payload['body_html'] ?? null,
            'body_text' => $payload['body_text'] ?? null,
            'to_emails' => implode(', ', array_column(array_filter($recipients, fn ($item) => $item['recipient_type'] === 'to'), 'email')),
            'cc_emails' => implode(', ', array_column(array_filter($recipients, fn ($item) => $item['recipient_type'] === 'cc'), 'email')),
            'bcc_emails' => implode(', ', array_column(array_filter($recipients, fn ($item) => $item['recipient_type'] === 'bcc'), 'email')),
            'updated_by' => Auth::id(),
        ]);

        $mailCenter->recipients()->delete();
        foreach ($recipients as $recipient) {
            $mailCenter->recipients()->create($recipient);
        }

        $this->storeAttachments($request, $mailCenter, $settings);
        $this->logEvent($mailCenter, 'updated', 'Mail draft updated.');

        if (($payload['submit_action'] ?? 'draft') === 'send') {
            return $this->submitForSending($mailCenter, $settings);
        }

        return redirect()
            ->route('mailCenter.index', ['tab' => 'messages'])
            ->with('success', 'Mail draft updated successfully.');
    }

    public function destroy(MailCenter $mailCenter)
    {
        abort_unless($mailCenter->isVisibleToUser(Auth::user()), 403);

        foreach ($mailCenter->attachments as $attachment) {
            if (Storage::disk($attachment->disk ?: 'public')->exists($attachment->path)) {
                Storage::disk($attachment->disk ?: 'public')->delete($attachment->path);
            }

            FileManager::query()->where('disk', $attachment->disk ?: 'public')->where('path', ltrim($attachment->path, '/'))->delete();
        }

        $mailCenter->delete();

        return back()->with('success', 'Mail message deleted successfully.');
    }

    public function approve(MailCenter $mailCenter)
    {
        $this->authorize('approve', MailCenter::class);
        abort_unless($mailCenter->isVisibleToUser(Auth::user()), 403);

        $mailCenter->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->logEvent($mailCenter, 'approved', 'Mail message approved.');
        $this->notifyUsersForEvent($mailCenter->fresh(['mailbox', 'client', 'jobRequest.client']), 'approved');

        return back()->with('success', 'Mail message approved successfully.');
    }

    public function send(MailCenter $mailCenter)
    {
        $this->authorize('send', MailCenter::class);
        abort_unless($mailCenter->isVisibleToUser(Auth::user()), 403);

        return $this->sendStoredMessage($mailCenter, $this->getSettings());
    }

    public function openNotification(string $notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->firstOrFail();
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url'] ?? route('mailCenter.index', ['tab' => 'notifications']));
    }

    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function navbarSnapshot()
    {
        return response()->json(MailCenterNavbarData::compose(Auth::user()));
    }

    public function updateNotificationPreferences(Request $request)
    {
        $payload = $request->validate([
            'notify_on_pending_approval' => ['nullable', 'boolean'],
            'notify_on_approved' => ['nullable', 'boolean'],
            'notify_on_sent' => ['nullable', 'boolean'],
            'notify_on_failed' => ['nullable', 'boolean'],
            'show_pending_approval_queue' => ['nullable', 'boolean'],
            'show_action_center_items' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        $preference = MailCenterNavbarData::userPreference($user);
        if (!$preference->exists) {
            $preference->user_id = $user->id;
        }

        $preference->notify_on_pending_approval = (bool) ($payload['notify_on_pending_approval'] ?? false);
        $preference->notify_on_approved = (bool) ($payload['notify_on_approved'] ?? false);
        $preference->notify_on_sent = (bool) ($payload['notify_on_sent'] ?? false);
        $preference->notify_on_failed = (bool) ($payload['notify_on_failed'] ?? false);
        $preference->show_action_center_items = (bool) ($payload['show_action_center_items'] ?? false);

        if ($user->can('approve', MailCenter::class)) {
            $preference->show_pending_approval_queue = (bool) ($payload['show_pending_approval_queue'] ?? false);
        }

        $preference->save();

        return back()->with('success', 'Notification preferences updated successfully.');
    }

    public function updateSettings(Request $request)
    {
        $this->authorize('manageSettings', MailCenter::class);

        $payload = $request->validate([
            'default_mailbox_id' => ['nullable', 'exists:mail_center_mailboxes,id'],
            'default_signature_html' => ['nullable', 'string'],
            'default_footer_html' => ['nullable', 'string'],
            'require_approval' => ['nullable', 'boolean'],
            'track_events' => ['nullable', 'boolean'],
            'show_navbar_mail' => ['nullable', 'boolean'],
            'show_navbar_notifications' => ['nullable', 'boolean'],
            'navbar_polling_enabled' => ['nullable', 'boolean'],
            'navbar_polling_interval_seconds' => ['required', 'integer', 'min:15', 'max:900'],
            'notify_on_pending_approval' => ['nullable', 'boolean'],
            'notify_on_approved' => ['nullable', 'boolean'],
            'notify_on_sent' => ['nullable', 'boolean'],
            'notify_on_failed' => ['nullable', 'boolean'],
            'attachment_limit_mb' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $settings = $this->getSettings();
        $settings->fill([
            'default_mailbox_id' => $payload['default_mailbox_id'] ?? null,
            'default_signature_html' => $payload['default_signature_html'] ?? null,
            'default_footer_html' => $payload['default_footer_html'] ?? null,
            'require_approval' => (bool) ($payload['require_approval'] ?? false),
            'track_events' => (bool) ($payload['track_events'] ?? false),
            'show_navbar_mail' => (bool) ($payload['show_navbar_mail'] ?? false),
            'show_navbar_notifications' => (bool) ($payload['show_navbar_notifications'] ?? false),
            'navbar_polling_enabled' => (bool) ($payload['navbar_polling_enabled'] ?? false),
            'navbar_polling_interval_seconds' => (int) ($payload['navbar_polling_interval_seconds'] ?? 60),
            'notify_on_pending_approval' => (bool) ($payload['notify_on_pending_approval'] ?? false),
            'notify_on_approved' => (bool) ($payload['notify_on_approved'] ?? false),
            'notify_on_sent' => (bool) ($payload['notify_on_sent'] ?? false),
            'notify_on_failed' => (bool) ($payload['notify_on_failed'] ?? false),
            'attachment_limit_mb' => $payload['attachment_limit_mb'],
            'updated_by' => Auth::id(),
        ]);

        if (!$settings->exists) {
            $settings->created_by = Auth::id();
        }

        $settings->save();

        return back()->with('success', 'MailCenter settings updated successfully.');
    }

    public function storeMailbox(Request $request)
    {
        $this->authorize('manageSettings', MailCenter::class);

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'driver' => ['required', 'string', 'max:40'],
            'host' => ['required', 'string', 'max:191'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string'],
            'from_email' => ['required', 'email', 'max:191'],
            'from_name' => ['required', 'string', 'max:191'],
            'reply_to_email' => ['nullable', 'email', 'max:191'],
            'reply_to_name' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        if (!empty($payload['is_default'])) {
            MailCenterMailbox::query()->update(['is_default' => 0]);
        }

        $mailbox = MailCenterMailbox::query()->create([
            'name' => $payload['name'],
            'driver' => $payload['driver'],
            'host' => $payload['host'],
            'port' => $payload['port'],
            'encryption' => $payload['encryption'] ?? null,
            'username' => $payload['username'],
            'password' => $payload['password'],
            'from_email' => $payload['from_email'],
            'from_name' => $payload['from_name'],
            'reply_to_email' => $payload['reply_to_email'] ?? null,
            'reply_to_name' => $payload['reply_to_name'] ?? null,
            'is_default' => (bool) ($payload['is_default'] ?? false),
            'is_active' => (bool) ($payload['is_active'] ?? false),
            'note' => $payload['note'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $mailbox->users()->sync(array_map('intval', (array) ($payload['user_ids'] ?? [])));

        return redirect()->route('mailCenter.index', ['tab' => 'mailboxes'])->with('success', 'Sender mailbox added successfully.');
    }

    public function updateMailbox(Request $request, MailCenterMailbox $mailbox)
    {
        $this->authorize('manageSettings', MailCenter::class);

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'driver' => ['required', 'string', 'max:40'],
            'host' => ['required', 'string', 'max:191'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:191'],
            'password' => ['nullable', 'string'],
            'from_email' => ['required', 'email', 'max:191'],
            'from_name' => ['required', 'string', 'max:191'],
            'reply_to_email' => ['nullable', 'email', 'max:191'],
            'reply_to_name' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        if (!empty($payload['is_default'])) {
            MailCenterMailbox::query()->where('id', '!=', $mailbox->id)->update(['is_default' => 0]);
        }

        $update = [
            'name' => $payload['name'],
            'driver' => $payload['driver'],
            'host' => $payload['host'],
            'port' => $payload['port'],
            'encryption' => $payload['encryption'] ?? null,
            'username' => $payload['username'],
            'from_email' => $payload['from_email'],
            'from_name' => $payload['from_name'],
            'reply_to_email' => $payload['reply_to_email'] ?? null,
            'reply_to_name' => $payload['reply_to_name'] ?? null,
            'is_default' => (bool) ($payload['is_default'] ?? false),
            'is_active' => (bool) ($payload['is_active'] ?? false),
            'note' => $payload['note'] ?? null,
            'updated_by' => Auth::id(),
        ];

        if (!empty($payload['password'])) {
            $update['password'] = $payload['password'];
        }

        $mailbox->update($update);
        $mailbox->users()->sync(array_map('intval', (array) ($payload['user_ids'] ?? [])));

        return redirect()->route('mailCenter.index', ['tab' => 'mailboxes'])->with('success', 'Sender mailbox updated successfully.');
    }

    public function destroyMailbox(MailCenterMailbox $mailbox)
    {
        $this->authorize('manageSettings', MailCenter::class);

        if ($mailbox->messages()->exists()) {
            return back()->with('error', 'This mailbox is already linked to sent or draft messages.');
        }

        $mailbox->delete();

        return back()->with('success', 'Sender mailbox deleted successfully.');
    }

    public function storeTemplate(Request $request)
    {
        $this->authorize('manageTemplates', MailCenter::class);

        $payload = $this->validateTemplate($request);
        MailCenterTemplate::query()->create($payload + [
            'slug' => Str::slug($payload['slug']),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('mailCenter.index', ['tab' => 'templates'])->with('success', 'Mail template added successfully.');
    }

    public function updateTemplate(Request $request, MailCenterTemplate $template)
    {
        $this->authorize('manageTemplates', MailCenter::class);

        $payload = $this->validateTemplate($request, $template->id);
        $template->update($payload + [
            'slug' => Str::slug($payload['slug']),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('mailCenter.index', ['tab' => 'templates'])->with('success', 'Mail template updated successfully.');
    }

    public function destroyTemplate(MailCenterTemplate $template)
    {
        $this->authorize('manageTemplates', MailCenter::class);

        if ($template->messages()->exists()) {
            return back()->with('error', 'This template is already used by messages. Disable it instead of deleting.');
        }

        $template->delete();

        return back()->with('success', 'Mail template deleted successfully.');
    }

    private function validateMessage(Request $request): array
    {
        return $request->validate([
            'mailbox_id' => ['nullable', 'exists:mail_center_mailboxes,id'],
            'template_id' => ['nullable', 'exists:mail_center_templates,id'],
            'job_request_id' => ['nullable', 'exists:job_requests,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'related_type' => ['nullable', 'string', 'max:191'],
            'related_id' => ['nullable', 'integer'],
            'subject' => ['required', 'string', 'max:191'],
            'body_html' => ['nullable', 'string'],
            'body_text' => ['nullable', 'string'],
            'to_emails' => ['nullable', 'string'],
            'cc_emails' => ['nullable', 'string'],
            'bcc_emails' => ['nullable', 'string'],
            'contact_person_ids' => ['nullable', 'array'],
            'contact_person_ids.*' => ['exists:contact_people,id'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file'],
            'submit_action' => ['nullable', 'in:draft,send'],
        ]);
    }

    private function validateTemplate(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = 'unique:mail_center_templates,slug';
        if ($ignoreId) {
            $slugRule .= ','.$ignoreId;
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['required', 'string', 'max:191', $slugRule],
            'category' => ['required', 'string', 'max:191'],
            'subject' => ['required', 'string', 'max:191'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_active' => (bool) $request->boolean('is_active'),
        ];
    }

    private function buildRecipientsPayload(Request $request): array
    {
        $rows = [];

        foreach (['to' => 'to_emails', 'cc' => 'cc_emails', 'bcc' => 'bcc_emails'] as $type => $field) {
            foreach ($this->splitEmails($request->input($field)) as $email) {
                $rows[$type.'-'.$email] = [
                    'recipient_type' => $type,
                    'name' => null,
                    'email' => $email,
                    'contact_person_id' => null,
                ];
            }
        }

        $contactIds = array_filter((array) $request->input('contact_person_ids', []));
        if (count($contactIds) > 0) {
            $contacts = ContactPerson::query()->select('id', 'name', 'email')->whereIn('id', $contactIds)->get();
            foreach ($contacts as $contact) {
                if (!$contact->email) {
                    continue;
                }

                $rows['to-'.$contact->email] = [
                    'recipient_type' => 'to',
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'contact_person_id' => $contact->id,
                ];
            }
        }

        return array_values($rows);
    }

    private function splitEmails(?string $value): array
    {
        if ($value === null) {
            return [];
        }

        $parts = preg_split('/[;,\\r\\n]+/', $value) ?: [];
        $parts = array_map(static fn ($item) => strtolower(trim((string) $item)), $parts);
        $parts = array_filter($parts, static fn ($item) => filter_var($item, FILTER_VALIDATE_EMAIL));

        return array_values(array_unique($parts));
    }

    private function storeAttachments(Request $request, MailCenter $message, MailCenterSetting $settings): void
    {
        $files = $request->file('attachments', []);
        if (!is_array($files) || count($files) === 0) {
            return;
        }

        $limitBytes = ((int) ($settings->attachment_limit_mb ?? 10)) * 1024 * 1024;
        $jobCode = optional($message->jobRequest)->code ?: 'general';
        $basePath = 'uploads/manual/mailcenter/attachments/'.$jobCode;

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            if ($limitBytes > 0 && (int) $file->getSize() > $limitBytes) {
                continue;
            }

            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $extension = $file->getClientOriginalExtension();
            $storedPath = $file->storeAs($basePath, $filename.($extension ? '.'.$extension : ''), 'public');
            $indexedFile = FileManager::upsertPublicFile($storedPath, now());

            $message->attachments()->create([
                'file_manager_id' => optional($indexedFile)->id,
                'disk' => 'public',
                'path' => $storedPath,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
        }
    }

    private function submitForSending(MailCenter $message, MailCenterSetting $settings)
    {
        if (($settings->require_approval ?? false) && !Auth::user()->can('approve', MailCenter::class)) {
            $message->update([
                'status' => 'pending_approval',
                'approval_required' => 1,
                'updated_by' => Auth::id(),
            ]);
            $this->logEvent($message, 'pending_approval', 'Mail message is waiting for approval before sending.');
            $this->notifyUsersForEvent($message->fresh(['mailbox', 'client', 'jobRequest.client']), 'pending_approval');

            return redirect()
                ->route('mailCenter.index', ['tab' => 'messages'])
                ->with('success', 'Mail message saved and submitted for approval.');
        }

        if (($settings->require_approval ?? false) && Auth::user()->can('approve', MailCenter::class) && !$message->approved_at) {
            $message->update([
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'status' => 'approved',
                'updated_by' => Auth::id(),
            ]);
            $this->logEvent($message, 'approved', 'Mail message auto-approved by sender.');
        }

        return $this->sendStoredMessage($message, $settings);
    }

    private function sendStoredMessage(MailCenter $message, MailCenterSetting $settings)
    {
        $message->loadMissing(['mailbox', 'jobRequest.client', 'client', 'recipients', 'attachments']);

        if ($message->approval_required && !$message->approved_at && !Auth::user()->can('approve', MailCenter::class)) {
            return back()->with('error', 'This message must be approved before sending.');
        }

        $mailbox = $this->resolveMailbox($message->mailbox_id, $settings);
        if (!$mailbox || !$mailbox->is_active) {
            return back()->with('error', 'The selected sender mailbox is not active.');
        }

        $to = $message->recipients->where('recipient_type', 'to')->pluck('email')->values()->all();
        $cc = $message->recipients->where('recipient_type', 'cc')->pluck('email')->values()->all();
        $bcc = $message->recipients->where('recipient_type', 'bcc')->pluck('email')->values()->all();

        if (count($to) === 0) {
            return back()->with('error', 'At least one TO recipient is required before sending.');
        }

        $rendered = $this->renderMessageContent($message, $settings);
        $this->configureRuntimeMailer($mailbox);

        try {
            $mailable = new RigMailCenterMessage($message, $rendered['html'], $rendered['text']);
            $mailer = Mail::mailer('rig_mailcenter_runtime')->to($to);
            if (count($cc) > 0) {
                $mailer->cc($cc);
            }
            if (count($bcc) > 0) {
                $mailer->bcc($bcc);
            }

            $mailer->send($mailable);

            $message->update([
                'mailbox_id' => $mailbox->id,
                'status' => 'sent',
                'sent_at' => now(),
                'last_error' => null,
                'updated_by' => Auth::id(),
            ]);

            $message->recipients()->update(['delivery_status' => 'sent']);
            $this->logEvent($message, 'sent', 'Mail message sent successfully.', [
                'mailbox' => $mailbox->from_email,
                'to' => $to,
                'cc' => $cc,
                'bcc' => $bcc,
            ]);
            $this->notifyUsersForEvent($message->fresh(['mailbox', 'client', 'jobRequest.client']), 'sent');

            return redirect()->route('mailCenter.index', ['tab' => 'messages'])->with('success', 'Mail sent successfully.');
        } catch (\Throwable $e) {
            $message->update([
                'status' => 'failed',
                'last_error' => $e->getMessage(),
                'updated_by' => Auth::id(),
            ]);

            $message->recipients()->update(['delivery_status' => 'failed']);
            $this->logEvent($message, 'failed', 'Mail sending failed.', [
                'error' => $e->getMessage(),
            ]);
            $this->notifyUsersForEvent($message->fresh(['mailbox', 'client', 'jobRequest.client']), 'failed', $e->getMessage());

            return back()->with('error', 'Mail sending failed: '.$e->getMessage());
        }
    }

    private function configureRuntimeMailer(MailCenterMailbox $mailbox): void
    {
        config([
            'mail.mailers.rig_mailcenter_runtime' => [
                'transport' => $mailbox->driver ?: 'smtp',
                'host' => $mailbox->host,
                'port' => (int) $mailbox->port,
                'encryption' => $mailbox->encryption ?: null,
                'username' => $mailbox->username,
                'password' => $mailbox->password,
                'timeout' => null,
                'auth_mode' => null,
            ],
        ]);
    }

    private function resolveMailbox(?int $mailboxId, MailCenterSetting $settings): ?MailCenterMailbox
    {
        $user = Auth::user();
        $visibleQuery = MailCenterMailbox::query()->visibleToUser($user, true);

        if ($mailboxId) {
            return (clone $visibleQuery)->find($mailboxId);
        }

        if ($settings->default_mailbox_id) {
            $default = (clone $visibleQuery)->find($settings->default_mailbox_id);
            if ($default) {
                return $default;
            }
        }

        return $visibleQuery->orderByDesc('is_default')->orderBy('name')->first();
    }

    private function getSettings(): MailCenterSetting
    {
        return MailCenterSetting::query()->first() ?: new MailCenterSetting([
            'attachment_limit_mb' => 10,
            'track_events' => 1,
            'require_approval' => 0,
            'show_navbar_mail' => 1,
            'show_navbar_notifications' => 1,
            'navbar_polling_enabled' => 1,
            'navbar_polling_interval_seconds' => 60,
            'notify_on_pending_approval' => 1,
            'notify_on_approved' => 1,
            'notify_on_sent' => 1,
            'notify_on_failed' => 1,
        ]);
    }

    private function renderMessageContent(MailCenter $message, MailCenterSetting $settings): array
    {
        $client = $message->client ?: optional($message->jobRequest)->client;
        $contact = $message->recipients->first();
        $tokens = [
            '{{client_name}}' => optional($client)->name ?: 'Client',
            '{{contact_name}}' => optional($contact)->name ?: 'Client',
            '{{jcf_code}}' => optional($message->jobRequest)->code ?: '-',
            '{{sender_name}}' => optional($message->mailbox)->from_name ?: optional(Auth::user()->employee)->name ?: 'Rig Solution',
            '{{sender_email}}' => optional($message->mailbox)->from_email ?: '',
            '{{company_name}}' => 'Rig Solution Engineering',
            '{{current_date}}' => now()->format('d-m-Y'),
        ];

        $bodyHtml = (string) ($message->body_html ?? '');
        $bodyText = (string) ($message->body_text ?? strip_tags($bodyHtml));
        $subject = (string) $message->subject;

        foreach ($tokens as $key => $value) {
            $subject = str_replace($key, $value, $subject);
            $bodyHtml = str_replace($key, $value, $bodyHtml);
            $bodyText = str_replace($key, $value, $bodyText);
        }

        $appendParts = array_filter([
            $settings->default_signature_html ?? null,
            $settings->default_footer_html ?? null,
        ]);
        if (count($appendParts) > 0) {
            $bodyHtml .= '<hr>'.implode('', $appendParts);
        }

        $message->subject = $subject;

        return [
            'html' => $bodyHtml,
            'text' => trim(strip_tags($bodyText)),
        ];
    }

    private function userCanUseMailbox(MailCenterMailbox $mailbox): bool
    {
        return $mailbox->isVisibleToUser(Auth::user());
    }

    private function notifyUsersForEvent(MailCenter $message, string $eventType, ?string $error = null): void
    {
        $settings = $this->getSettings();
        $toggleMap = [
            'pending_approval' => 'notify_on_pending_approval',
            'approved' => 'notify_on_approved',
            'sent' => 'notify_on_sent',
            'failed' => 'notify_on_failed',
        ];

        $toggleField = $toggleMap[$eventType] ?? null;
        if ($toggleField && !$settings->{$toggleField}) {
            return;
        }

        $recipients = $this->resolveNotificationRecipients($message, $eventType);
        if ($recipients->isEmpty()) {
            return;
        }

        [$title, $body] = $this->notificationMessageContent($message, $eventType, $error);
        Notification::send($recipients, new MailCenterEventNotification($message, $eventType, $title, $body));
    }

    private function resolveNotificationRecipients(MailCenter $message, string $eventType)
    {
        $message->loadMissing(['mailbox.users', 'creator.employee', 'jobRequest.client', 'client']);
        $users = collect();

        if ($eventType === 'pending_approval') {
            $approvers = User::query()
                ->with(['employee:id,name', 'mailCenterNotificationPreference'])
                ->where('is_active', 1)
                ->get()
                ->filter(function (User $user) use ($message) {
                    return $user->can('approve', MailCenter::class)
                        && ($message->mailbox ? $message->mailbox->isVisibleToUser($user) : true);
                });

            $users = $users->merge($approvers);
        }

        if (in_array($eventType, ['approved', 'sent', 'failed'], true)) {
            if ($message->creator && $message->creator->is_active) {
                $message->creator->loadMissing('mailCenterNotificationPreference');
                $users->push($message->creator);
            }
        }

        return $users
            ->unique('id')
            ->filter(function (User $user) use ($eventType) {
                return MailCenterNavbarData::userPreference($user)->allowsEvent($eventType);
            })
            ->reject(function ($user) {
                return (int) $user->id === (int) Auth::id();
            })
            ->values();
    }

    private function notificationMessageContent(MailCenter $message, string $eventType, ?string $error = null): array
    {
        $clientName = optional($message->client)->name ?: optional(optional($message->jobRequest)->client)->name ?: 'Client';
        $subject = $message->subject ?: 'Mail message';
        $mailboxName = optional($message->mailbox)->name ?: 'mailbox';

        return match ($eventType) {
            'pending_approval' => [
                'Mail approval required',
                "{$message->code} for {$clientName} is waiting for approval via {$mailboxName}.",
            ],
            'approved' => [
                'Mail approved',
                "{$message->code} was approved and is ready to send. Subject: {$subject}.",
            ],
            'sent' => [
                'Mail sent successfully',
                "{$message->code} was sent from {$mailboxName}. Subject: {$subject}.",
            ],
            'failed' => [
                'Mail delivery failed',
                "{$message->code} failed to send. ".trim((string) $error),
            ],
            default => [
                'Mail update',
                "{$message->code} has a new event: {$eventType}.",
            ],
        };
    }

    private function logEvent(MailCenter $message, string $eventType, string $description, array $payload = []): void
    {
        $settings = $this->getSettings();
        if (!$settings->track_events && $eventType !== 'failed') {
            return;
        }

        MailCenterEvent::query()->create([
            'mail_center_id' => $message->id,
            'event_type' => $eventType,
            'description' => $description,
            'payload_json' => count($payload) > 0 ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'created_by' => Auth::id(),
        ]);
    }

    private function generateCode(): string
    {
        $prefix = 'MC-'.now()->format('y');
        $lastCode = MailCenter::query()
            ->where('code', 'like', $prefix.'-%')
            ->orderByDesc('id')
            ->value('code');

        $next = 1;
        if ($lastCode && preg_match('/(\d+)$/', $lastCode, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return $prefix.'-'.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function placeholderTokens(): array
    {
        return [
            '{{client_name}}',
            '{{contact_name}}',
            '{{jcf_code}}',
            '{{sender_name}}',
            '{{sender_email}}',
            '{{company_name}}',
            '{{current_date}}',
        ];
    }
}
