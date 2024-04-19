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
        $features = PostFeature::with(['postCategory:id,name', 'postFeature:id,features as name,is_preferred'])->where('post_id', $this->id)->where('category_id', $this->category)->get();
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
            'no_gas' => $this->no_gas,  
            'valid_till' => $this->valid_till, 
            'no_of_person' => $this->no_of_person, 
            'category' => $this->category, 
            'provider' => $this->provider,
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
            'prices' => $this->whenLoaded('prices', function ()  use ($request){
                
                    return [
                        'normal_electric_rate' => $this->prices->electric_rate,
                        'off_peak_electric_rate' => $this->prices->off_peak_electric_rate,
                        'gas_rate' => $this->prices->gas_rate,
                        'total' => $request->normal_electric_consume * $this->prices->electric_rate
                        + $request->peak_electric_consume * $this->prices->off_peak_electric_rate
                        + $request->gas_consume * $this->prices->gas_rate
                        + $this->delivery_cost_electric
                        + $this->delivery_cost_gas
                        + $this->feedInCost->normal_feed_in_cost
                        + $this->feedInCost->off_peak_feed_in_cost
                        - $this->cashback
                    ];
                
            }),
            
            // Include feedInCost relationship if loaded
            'feed_in_cost' => $this->whenLoaded('feedInCost', function () {
                return [
                    'return_tariff' => json_decode($this->feedInCost->return_tariff),
                    'normal_feed_in_cost' => $this->feedInCost->normal_feed_in_cost,
                    'off_peak_feed_in_cost' => $this->feedInCost->off_peak_feed_in_cost,
                ];
            }),

// Normal rate per kWh              €0.26475
// Off-peak rate per kWh            €0.26475
// Fixed delivery costs per year   €83.88
// Feed-in costs per year *       €120.45
// Gas
// Rate per m³                      €1.25902
// Fixed delivery costs per year   €83.88
// Total
// Total amount per year          €421.79
// Cashback                     € -170.00
// Your costs per month            €20.98
// Your costs per year            €251.79

        ];
    }
}
