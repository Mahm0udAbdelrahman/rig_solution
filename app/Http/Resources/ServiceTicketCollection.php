<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\ServiceTicket;


class ServiceTicketCollection extends JsonResource
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
          'jcf_ref' => $this->jobRequest->jcf_ref,
          'service_ticket' => [
            'id' => $this->id,
            'location' => $this->location,
            'start' => $this->start,
            'end' => $this->end,
            'services' => $this->services,
            'user_id' => (string)$this->user_id,
            'notice' => $this->notice,
            'approval_date' => $this->approval_date,
            'sync' => $this->sync,
            'updated' => $this->updated,
          ],
        ];
    }
}
