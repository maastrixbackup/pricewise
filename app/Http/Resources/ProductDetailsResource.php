<?php

namespace App\Http\Resources;

use App\Models\ProductRating;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews = ProductRating::where('product_id', $this->id)->get();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'actual_price' => $this->actual_price,
            'sell_price' => $this->sell_price,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'color_id' => $this->color_id,
            'model' => $this->model,
            'size' => $this->size,
            'highlights' => json_decode($this->heighlights, true),
            'rating' => $this->ratings,
            'reviews' => $this->review_count,
            'banner_image' => asset('storage/images/shops/' . $this->banner_image),
            'no_image' => asset('storage/images/no_image/no-image.png'),
            'about' => $this->about,
            'description' => $this->description,
            'specification' => json_decode($this->specification, true),
            'reviews_details' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_id' => $review->user_id,
                    'product_id' => $review->product_id,
                    'reviews' => $review->review,
                    'rating' => $review->rating
                ];
            }),
            'brand' => $this->whenLoaded('brandDetails', function () {
                return [
                    'id' => $this->brandDetails->id,
                    'title' => $this->brandDetails->title,
                    'slug' => $this->brandDetails->slug,
                ];
            }),
            'category' => $this->whenLoaded('categoryDetails', function () {
                return [
                    'id' => $this->categoryDetails->id,
                    'title' => $this->categoryDetails->title,
                    'slug' => $this->categoryDetails->slug,
                    'image' => asset('storage/images/shops/' . $this->categoryDetails->image)
                ];
            }),
            'color' => $this->whenLoaded('colorDetails', function () {
                return [
                    'id' => $this->colorDetails->id,
                    'title' => $this->colorDetails->title,
                    'slug' => $this->colorDetails->slug,
                ];
            }),
            'available_status' => $this->p_status,
            'product_type' => $this->product_type,
            'images' => $this->images ? $this->images->map(function ($image) {
                return [
                    'product_id' => $image->product_id,
                    'image' => asset('storage/images/shops/' . $image->image),
                ];
            })->all() : [],
        ];
    }
}
