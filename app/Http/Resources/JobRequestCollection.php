<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EngineersResource;
use App\Models\WorkFlow\JcfStatus;


class JobRequestCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $departments = [];
        $employees = [];
        foreach($this->departments as $key => $value) {
          $departments[$key] = $value->id;
        }

        foreach($this->employees as $key => $value) {
          $employees[$key] = $value->id;
        }
        return [
          'jcf' => [
            'id' => $this->id,
            'jcf_ref' => $this->jcf_ref,
            'client_id' => (string)$this->client_id,
            'supplier_id' => (string)$this->supplier_id,
            'contact_people_id' => (string)$this->contact_people_id,
            'subject' => $this->subject,
            'work_location' => $this->work_location,
            'contactway' => $this->contactway,
            'user_id' => (string)$this->user_id,
            'job_requierd_details' => $this->job_requierd_details,
            'contact_date' => $this->contact_date,
            'managers' => $this->managers,
            'tools' => $this->tools,
            'scope_of_work' => $this->scope_of_work,
            'specification' => $this->specification,
            'deploc' => $this->deploc,
            'sync' => $this->sync,
            'updated' => $this->updated,
          ],
          'departments' => $departments,
          'engineers' => $employees,
          'status' => JcfStatus::where('job_request_id', $this->id)->first(),
        ];
    }
}
