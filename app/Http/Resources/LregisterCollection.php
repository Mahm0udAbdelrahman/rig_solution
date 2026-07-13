<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class LregisterCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      return [
        'jcf_ref' => $this->job_request->jcf_ref,
        'lregister' => parent::toArray($request),
        'lregister2' => null,
        'report' => [
          'status' => $this->report->status,
          'publish' => $this->report->publish,
          'user_id' => $this->report->user_id,
          'sync' => $this->report->sync,
          'updated' => $this->report->updated,
          'user_id_edit' => $this->report->user_id_edit,
          'user_id_approved' => $this->report->user_id_approved,
        ],
      ];
    }
}
