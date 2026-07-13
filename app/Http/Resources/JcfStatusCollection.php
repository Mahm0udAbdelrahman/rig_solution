<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;


class JcfStatusCollection extends JsonResource
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
        return [
          'job_request_id' => $this->job_request_id,
          'status' => $this->status,
          'comment' => $this->comment,
          'start_at' => $this->start_at,
          'end_at' => $this->end_at,
        ];
    }
}
