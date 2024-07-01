<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Provider;
use App\Models\Combo;
use App\Models\PostFeature;
use App\Http\Resources\PostFeatureResource;

class LiabilityInsuranceResource extends JsonResource
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

        $combos = Combo::where('category', $this->category)->get();
        $filteredCombos = $combos->filter(function ($combo) {
            return in_array($combo->id, json_decode($this->combos));
        });
        $other_cost = 0;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'product_type' => $this->product_type,
            'content' => $this->content,
            'avg_delivery_time' => $this->avg_delivery_time,
            'price' => $this->price,
            'discounted_till' => $this->discounted_till,
            'discount_percentage' => $this->discount_percentage,
            'commission' => $this->commission,
            'commission_type' => $this->commission_type,
            'image' => asset('storage/images/liability_insurance/'.$this->image),
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
            'sub_category'=>$this->sub_category,
            'provider' =>$this->providerDetails,
            'combos' => $this->combos,
            'combo_details' => $filteredCombos->toArray(),
            'manual_install' => $this->manual_install,
            'mechanic_install' => $this->mechanic_install,
            'mechanic_charge' => $this->mechanic_charge,
            'is_featured' => $this->is_featured,
            'coverages' => collect($this->coverages)->map(function ($coverage) {
                $coverage->image = asset('storage/images/insurance_coverages/' . $coverage->coverageDetails->image);
                return $coverage;
            }),
            'provider' => $this->providerDetails,
            'features' => PostFeatureResource::collection($features),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),

        ];
    }
}
