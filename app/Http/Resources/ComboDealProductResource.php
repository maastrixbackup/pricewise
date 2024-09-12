<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComboDealProductResource extends JsonResource
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
            'combo_id' => $this->id,
            'product_id' => $this->product_id,
            'deal_id' => $this->deal_id,
            'deal_name' => $this->comboProductDetails->title,
            'deal_url' => $this->comboProductDetails->slug,
            'price' => $this->comboProductDetails->sell_price,
            'deal_price' => $this->deal_price,
            'deal_rating' => $this->comboProductDetails->ratings,
            'deal_image' => asset('storage/images/shops/' . $this->comboProductDetails->banner_image),
        ];
    }
}
