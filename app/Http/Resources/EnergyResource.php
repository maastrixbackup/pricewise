<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Provider;
use App\Models\PostFeature;
use App\Models\EnergyRateChat;
use App\Models\FeedInCost;
use App\Http\Resources\PostFeatureResource;
class EnergyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $features = PostFeature::with(['postCategory:id,name', 'postFeature:id,features as name'])->where('post_id', $this->id)->where('category_id', $this->category)->get();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'product_type' => $this->product_type, 
            'delivery_cost_electric' => $this->delivery_cost_electric, 
            'delivery_cost_gas' => $this->delivery_cost_gas, 
            'normal_electric_price' => $this->normal_electric_price, 
            'peak_electric_price' => $this->peak_electric_price, 
            'gas_price' => $this->gas_price, 
            'image' => 'energy/'.$this->image, 
            'feed_in_normal' => $this->feed_in_normal, 
            'feed_in_peak' => $this->feed_in_peak, 
            'type_of_current' => $this->type_of_current, 
            'type_of_gas' => $this->type_of_gas, 
            'notice_period' => $this->notice_period, 
            'conditions' => $this->conditions, 
            'cashback' => $this->cashback, 
            'network_cost_gas' => $this->network_cost_gas, 
            'network_cost_electric' => $this->network_cost_electric,            
            'energy_label' => $this->energy_label, 
            'status' => $this->status, 
            'commission' => $this->commission, 
            'avg_delivery_time' => $this->avg_delivery_time, 
            'commission_type' => $this->commission_type, 
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
            'features' => PostFeatureResource::collection($features),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
