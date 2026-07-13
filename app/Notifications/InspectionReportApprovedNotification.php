<?php

namespace App\Notifications;

use App\Models\Inspection\InspectionReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InspectionReportApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private InspectionReport $inspectionReport
    ) {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $reportCode = (string) ($this->inspectionReport->code ?? '');
        $jcfCode = (string) optional($this->inspectionReport->job_request)->code;
        $moduleName = class_basename((string) $this->inspectionReport->reportable_type);
        $moduleLabel = trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $moduleName));
        $displayCode = $jcfCode !== '' ? ($jcfCode.'/'.$reportCode) : $reportCode;

        return [
            'module' => 'inspection',
            'icon' => 'check-circle',
            'event_type' => 'inspection_approved',
            'title' => 'Inspection Approved',
            'message' => $displayCode.' was approved'.($moduleLabel !== '' ? (' ('.$moduleLabel.')') : '').'.',
            'status' => 'approved',
            'inspection_report_id' => $this->inspectionReport->id,
            'job_request_id' => $this->inspectionReport->job_request_id,
            'code' => $reportCode,
            'url' => route('inspection.all'),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
