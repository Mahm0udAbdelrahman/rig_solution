<?php

namespace App\Support;

use App\Models\Inspection\InspectionReport;
use App\Models\User;
use App\Models\WorkFlow\Invoice;
use App\Models\WorkFlow\JobRequest;
use App\Models\WorkFlow\Qutation;
use App\Models\WorkFlow\ServiceTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MailCenterComposeDefaults
{
    public static function fromRelated(string $relatedType, int $relatedId, User $user): array
    {
        return match (Str::lower(trim($relatedType))) {
            'qutation', 'quotation' => self::fromQutation($relatedId, $user),
            'invoice' => self::fromInvoice($relatedId, $user),
            'service_ticket', 'serviceticket' => self::fromServiceTicket($relatedId, $user),
            'inspection_report', 'inspectionreport' => self::fromInspectionReport($relatedId, $user),
            default => abort(404),
        };
    }

    private static function fromQutation(int $id, User $user): array
    {
        $qutation = Qutation::query()
            ->with(['jobRequest.client', 'jobRequest.supplier', 'jobRequest.contactPeopleShow'])
            ->findOrFail($id);

        self::authorizeView($user, $qutation);

        $subject = trim(implode(' - ', array_filter([
            'Quotation '.$qutation->code,
            $qutation->subject ?: null,
        ])));

        return self::buildDefaults(
            'qutation',
            $qutation->id,
            'Quotation',
            (string) $qutation->code,
            $qutation->jobRequest,
            $subject
        );
    }

    private static function fromInvoice(int $id, User $user): array
    {
        $invoice = Invoice::query()
            ->with(['jobRequest.client', 'jobRequest.supplier', 'jobRequest.contactPeopleShow', 'jobRequest.qutation'])
            ->findOrFail($id);

        self::authorizeView($user, $invoice);

        $subject = 'Invoice '.$invoice->code;
        if ($invoice->jobRequest && $invoice->jobRequest->code) {
            $subject .= ' - '.$invoice->jobRequest->code;
        }

        return self::buildDefaults(
            'invoice',
            $invoice->id,
            'Invoice',
            (string) $invoice->code,
            $invoice->jobRequest,
            $subject
        );
    }

    private static function fromServiceTicket(int $id, User $user): array
    {
        $serviceTicket = ServiceTicket::query()
            ->with(['jobRequest.client', 'jobRequest.supplier', 'jobRequest.contactPeopleShow'])
            ->findOrFail($id);

        self::authorizeView($user, $serviceTicket);

        $subject = 'Service Ticket '.$serviceTicket->code;
        if ($serviceTicket->jobRequest && $serviceTicket->jobRequest->code) {
            $subject .= ' - '.$serviceTicket->jobRequest->code;
        }

        return self::buildDefaults(
            'service_ticket',
            $serviceTicket->id,
            'Service Ticket',
            (string) $serviceTicket->code,
            $serviceTicket->jobRequest,
            $subject
        );
    }

    private static function fromInspectionReport(int $id, User $user): array
    {
        $inspectionReport = InspectionReport::query()->with('reportable')->findOrFail($id);
        $reportable = $inspectionReport->reportable;

        abort_unless($reportable, 404);
        self::authorizeView($user, $reportable);

        $jobRequest = self::extractJobRequest($reportable);
        $documentLabel = Str::headline(class_basename($inspectionReport->reportable_type)).' Report';
        $documentCode = (string) ($reportable->code ?? $inspectionReport->code ?? $inspectionReport->id);
        $subject = $documentLabel.' '.$documentCode;
        if ($jobRequest && $jobRequest->code) {
            $subject .= ' - '.$jobRequest->code;
        }

        return self::buildDefaults(
            'inspection_report',
            $inspectionReport->id,
            $documentLabel,
            $documentCode,
            $jobRequest,
            $subject
        );
    }

    private static function buildDefaults(
        string $relatedType,
        int $relatedId,
        string $documentLabel,
        string $documentCode,
        ?JobRequest $jobRequest,
        string $subject
    ): array {
        $jobRequest?->loadMissing(['client', 'supplier', 'contactPeopleShow']);

        $ownerName = $jobRequest?->client?->name
            ?: $jobRequest?->supplier?->name
            ?: 'Client';
        $contactName = $jobRequest?->contactPeopleShow?->name ?: $ownerName;
        $jobCode = $jobRequest?->code ?: '-';

        $recipientEmails = array_values(array_unique(array_filter([
            $jobRequest?->contactPeopleShow?->email,
            $jobRequest?->client?->email,
            $jobRequest?->supplier?->email,
        ])));

        $bodyText = trim(
            "Dear {$contactName},\n\n"
            ."Please review {$documentLabel} {$documentCode} for JCF {$jobCode}.\n"
            ."Owner: {$ownerName}.\n\n"
            ."Regards,\nRig Solution Engineering"
        );

        $contactPersonIds = [];
        if ($jobRequest?->contactPeopleShow?->id && $jobRequest?->contactPeopleShow?->email) {
            $contactPersonIds[] = (int) $jobRequest->contactPeopleShow->id;
        }

        return [
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'job_request_id' => $jobRequest?->id,
            'client_id' => $jobRequest?->client_id,
            'contact_person_ids' => $contactPersonIds,
            'to_emails' => implode(', ', $recipientEmails),
            'subject' => $subject,
            'body_text' => $bodyText,
            'body_html' => nl2br(e($bodyText)),
            'source_label' => trim($documentLabel.' '.$documentCode),
        ];
    }

    private static function authorizeView(User $user, Model $model): void
    {
        abort_unless($user->can('view', $model), 403);
    }

    private static function extractJobRequest(Model $related): ?JobRequest
    {
        if (method_exists($related, 'jobRequest')) {
            $related->loadMissing(['jobRequest.client', 'jobRequest.supplier', 'jobRequest.contactPeopleShow']);

            return $related->jobRequest;
        }

        if (method_exists($related, 'job_request')) {
            $related->loadMissing(['job_request.client', 'job_request.supplier', 'job_request.contactPeopleShow']);

            return $related->job_request;
        }

        return null;
    }
}
