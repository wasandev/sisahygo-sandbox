<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'order_header_id' => $this->order_customer->id,   
            'unit_id' => $this->unit_id,
            'price' => $this->price,
            'amount' => $this->amount,
            'remark' => $this->remark,         
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
}
