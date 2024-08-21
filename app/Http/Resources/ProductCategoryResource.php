<?php

namespace App\Http\Resources;

use App\Models\ProductBrand;
use App\Models\ShopProduct;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = ShopProduct::where('category_id', $this->id)->get();
        $brands = ProductBrand::where('category', $this->id)->get();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => asset('storage/images/shops/' . $this->image),
            'no_image' => asset('storage/images/no_image/no-image.png'),
            'brands' => $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'title' => $brand->title,
                    'slug' => $brand->slug,
                ];
            }),
            'product_details' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'color' => $product->colorDetails->title,
                    'banner_image' => asset('storage/images/shops/' . $this->banner_image),
                    'images' => $this->images ? $this->images->map(function ($image) {
                        return [
                            'product_id' => $image->product_id,
                            'image' => asset('storage/images/shops/' . $image->image),
                        ];
                    })->all() : [],
                ];
            })->values(),
        ];
    }
}
