<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\EngineersResource;
use App\Models\PackingSlip;

class PackingSlipCollection extends JsonResource
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
          'packing_slip' => [
            'id' => $this->id,
            'user_id' => (string)$this->user_id,
            'dlocation' => $this->dlocation,
            'po' => $this->po,
            'shppingmethods' => $this->shppingmethods,
            'orderdate' => $this->orderdate,
            'items' => $this->items,
            'notice' => $this->notice,
            'transportation' => $this->transportation,
            'received' => $this->received,
            'delivered' => $this->delivered,
            'sync' => $this->sync,
            'updated' => $this->updated,
          ],
        ];
    }
}
