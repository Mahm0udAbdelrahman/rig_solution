<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Qutation;

class QutationCollection extends JsonResource
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
          'qutation' => [
            'id' => $this->id,
            'delivery' => $this->delivery,
            'location' => $this->location,
            'payment_method' => $this->payment_method,
            'terms' => $this->terms,
            'items' => $this->items,
            'type' => $this->type,
            'user_id' => (string)$this->user_id,
            'subject' => $this->subject,
            'user_id_approved' => (string)$this->user_id_approved,
            'sync' => $this->sync,
            'updated' => $this->updated,
            'created_at' => $this->created_at,
          ],
        ];
    }
}
