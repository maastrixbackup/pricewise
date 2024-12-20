<?php

namespace App\Http\Resources;

use App\Models\ComboProduct;
use Illuminate\Http\Resources\Json\JsonResource;


class ShopProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $comboDeals = ComboProduct::where('product_id', $this->id)->with('comboProductDetails')->get();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'actual_price' => $this->actual_price,
            'sell_price' => $this->sell_price,
            'model' => $this->model,
            'size' => $this->size,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'color_id' => $this->color_id,
            'highlights' => json_decode($this->heighlights, true),
            'rating' => $this->ratings,
            'reviews' => $this->review_count,
            'available_status' => $this->p_status,
            'product_type' => $this->product_type,
            'banner_image' => asset('storage/images/shops/' . $this->banner_image),
            'no_image' => asset('storage/images/no_image/no-image.png'),
            'images' => $this->images ? $this->images->map(function ($image) {
                return [
                    'product_id' => $image->product_id,
                    'image' => asset('storage/images/shops/' . $image->image),
                ];
            })->all() : [],
            'combo_deals' => ComboDealProductResource::collection($comboDeals),
        ];
    }
}
