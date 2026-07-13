<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ForkliftCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      $model2_details = null;
      if(isset($this->forklift2)){
        $model2_details = $this->forklift2;
      }
      return [
        'jcf_ref' => $this->job_request->jcf_ref,
        'forklift' => parent::toArray($request),
        'forklift2' => $model2_details,
        'report' => [
          'status' => $this->report->status,
          'publish' => $this->report->publish,
          'sync' => $this->report->sync,
          'updated' => $this->report->updated,    
          'user_id' => $this->report->user_id,
          'user_id_edit' => $this->report->user_id_edit,
          'user_id_approved' => $this->report->user_id_approved,
        ],
      ];
    }
}
