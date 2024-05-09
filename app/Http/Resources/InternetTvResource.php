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
        $other_cost = 0;
        return [
            'id' => $this->id,
            'title' => $this->title,            
            'product_type' => $this->product_type, 
            'content' => $this->content, 
            'avg_delivery_time' => $this->avg_delivery_time, 
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'discounted_till' => $this->discounted_till,            
            'discount' => $this->discount,
            'commission' => $this->commission, 
            'commission_type' => $this->commission_type, 
            'image' => 'tvtnternet/'.$this->image,            
            'connection_cost' => $this->connection_cost, 
            'shipping_cost' => $this->shipping_cost,
            'other_cost' => $other_cost,           
            'status' => $this->status,            
            'contract_length' => $this->contract_length, 
            'contract_type' => $this->contract_type, 
            'transfer_service' => $this->transfer_service, 
            'pin_codes' => $this->pin_codes, 
            'valid_till' => $this->valid_till, 
            'no_of_person' => $this->no_of_person,
            'no_of_receivers' => $this->no_of_receivers,
            'telephone_extensions' => $this->telephone_extensions,
            'tv_packages' => $this->tv_packages,
            'network_type' => $this->network_type,
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
            'provider_details' => $this->whenLoaded('providerDetails', function () {
                return [
                        'about' => $this->providerDetails->about,
                        'payment_options' => $this->providerDetails->payment_options,
                        'annual_accounts' => $this->providerDetails->annual_accounts,
                        'meter_readings' => $this->providerDetails->meter_readings,
                        'adjust_installments' => $this->providerDetails->adjust_installments,
                        'view_consumption' => $this->providerDetails->view_consumption,
                        'rose_scheme' => $this->providerDetails->rose_scheme,
                    ];
                
            }),
            'features' => PostFeatureResource::collection($features),            
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            
        ];
    }
}
