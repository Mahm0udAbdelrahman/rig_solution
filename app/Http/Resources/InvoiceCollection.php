<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Invoice;

class InvoiceCollection extends JsonResource
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
          'invoice' => [
            'id' => $this->id,
            'cpo' => $this->cpo,
            'contract' => $this->contract,
            'items' => $this->items,
            'terms' => $this->terms,
            'sub_total' => (string)$this->sub_total,
            'withholding' => (string)$this->withholding,
            'tax' => (string)$this->tax,
            'total' => (string)$this->total,
            'type' => $this->type,
            'user_id' => (string)$this->user_id,
            'sync' => $this->sync,
            'updated' => $this->updated,
            'created_at' => $this->created_at,
          ],
        ];
    }
}
