@extends('layouts.app')

@section('content')
<section class="users-list-wrapper">
    <div class="row">
        <div class="col-xl-4 col-12">
            <div class="card"><div class="card-body">
                <h4>{{ $mail_message->code }}</h4>
                <p><strong>Status:</strong> {{ str_replace('_', ' ', $mail_message->status) }}</p>
                <p><strong>Subject:</strong> {{ $mail_message->subject }}</p>
                <p><strong>Mailbox:</strong> {{ optional($mail_message->mailbox)->name ?: '-' }}</p>
                <p><strong>Client:</strong> {{ optional($mail_message->client)->name ?: optional(optional($mail_message->jobRequest)->client)->name ?: '-' }}</p>
                <p><strong>JCF:</strong> {{ optional($mail_message->jobRequest)->code ?: '-' }}</p>
                <p><strong>Created By:</strong> {{ optional(optional($mail_message->creator)->employee)->name ?: '-' }}</p>
                <p><strong>Approved By:</strong> {{ optional(optional($mail_message->approver)->employee)->name ?: '-' }}</p>
                <hr>
                <h5>Recipients</h5>
                @forelse($mail_message->recipients as $recipient)
                    <div>{{ strtoupper($recipient->recipient_type) }} - {{ $recipient->email }}</div>
                @empty
                    <div>No recipients</div>
                @endforelse
                <hr>
                <h5>Attachments</h5>
                @forelse($mail_message->attachments as $attachment)
                    <div><a href="{{ Storage::disk($attachment->disk ?: 'public')->url($attachment->path) }}" target="_blank">{{ $attachment->filename }}</a></div>
                @empty
                    <div>No attachments</div>
                @endforelse
            </div></div>
        </div>
        <div class="col-xl-8 col-12">
            <div class="card"><div class="card-body">
                <h4>Rendered Message</h4>
                <div style="border:1px solid #e1e8fb;border-radius:12px;padding:18px;background:#fff">{!! $rendered_html !!}</div>
            </div></div>
            <div class="card"><div class="card-body">
                <h4>Event Timeline</h4>
                @forelse($mail_message->events as $event)
                    <div class="mb-2 pb-2" style="border-bottom:1px solid #eef2fb">
                        <strong>{{ $event->event_type }}</strong> - {{ $event->description }}
                        <div class="text-muted">{{ $event->created_at?->format('d-m-Y H:i') }}</div>
                        @if($event->payload_json)<pre class="mt-1 mb-0" style="white-space:pre-wrap">{{ $event->payload_json }}</pre>@endif
                    </div>
                @empty
                    <div>No events found.</div>
                @endforelse
            </div></div>
        </div>
    </div>
</section>
@endsection
