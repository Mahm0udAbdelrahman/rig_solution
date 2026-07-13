<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionReportCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $model = strtolower(str_replace("App\Models\\", "", $this->reportable_type));
        $model2 = $model."2";
        $model2_details = null;
        if (isset($this->reportable->$model2))
				{
          	$model2_details = $this->reportable->$model2;
        }
        return [
	          'id' => $this->id,
	          'report' => [
		            'job_request_id' => $this->job_request_id,
		            'code' => $this->code,
		            'status' => $this->status,
		            'publish' => $this->publish,
		            'user_id' => $this->user_id,
		            'user_id_edit' => $this->user_id_edit,
		            'user_id_approved' => $this->user_id_approved,
		            'reportable_id' => $this->reportable_id,
		            'reportable_type' => $this->reportable_type,
		            'sync' => $this->sync,
	          ],
	          $model => $this->reportable,
	          $model2 => $model2_details
        ];
    }
}
