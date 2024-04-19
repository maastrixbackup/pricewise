<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Provider;
use App\Models\PostFeature;
use App\Http\Resources\PostFeatureResource;
class InternetTvResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $features = PostFeature::with(['postCategory:id,name', 'postFeature:id,features as name,is_preferred'])->where('post_id', $this->id)->where('category_id', $this->category)->get();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'product_type' => $this->product_type, 
            'content' => $this->content, 
            'avg_delivery_time' => $this->avg_delivery_time, 
            'price' => $this->price, 
            'commission' => $this->commission, 
            'commission_type' => $this->commission_type, 
            'image' => 'energy/'.$this->image, 
            'feed_in_normal' => $this->feed_in_normal, 
            'feed_in_peak' => $this->feed_in_peak, 
            'type_of_current' => $this->type_of_current, 
            'type_of_gas' => $this->type_of_gas, 
            'notice_period' => $this->notice_period, 
            'conditions' => $this->conditions, 
            'cashback' => $this->cashback,            
            'status' => $this->status,            
            'contract_length' => $this->contract_length, 
            'contract_type' => $this->contract_type, 
            'transfer_service' => $this->transfer_service, 
            'pin_codes' => $this->pin_codes, 
            'valid_till' => $this->valid_till, 
            'no_of_person' => $this->no_of_person, 
            'category' => $this->category, 
            'provider' => $this->provider, 
            'combos' => $this->combos, 
            'manual_install' => $this->manual_install, 
            'mechanic_install' => $this->mechanic_install, 
            'mechanic_charge' => $this->mechanic_charge, 
            'is_featured' => $this->is_featured,
            'documents' => $this->whenLoaded('documents', function () {
                return $this->documents->filter(function ($document) {
                    return $this->category == $document->category;
                })->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'filename' => $document->filename,
                        'path' => $document->path,
                    ];
                });
            }),
            'features' => PostFeatureResource::collection($features),            
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            
        ];
    }
}
