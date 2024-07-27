<?php

namespace App\Http\Resources;

use App\Models\SecurityFeature;
use App\Models\SecurityProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class SecurityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $features = SecurityFeature::latest()->get();
        $provider = SecurityProvider::latest()->get();

        $filteredP = $provider->filter(function ($provider) {
            return in_array($provider->id, json_decode($this->provider_id));
        })->map(function ($query) {
            return [
                'id' => $query->id,
                'title' => $query->title,
                'image' => asset('storage/images/cyber_security/'. $query->image),
                'description' => $query->description,
                'address' => $query->address,
            ];
        })->values();

        $filteredCombos = $features->filter(function ($feature) {
            return in_array($feature->id, json_decode($this->features));
        })->map(function ($query) {
            return [
                'id' => $query->id,
                'title' => $query->title,
                'description' => $query->description,
            ];
        })->values();


        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => asset('storage/images/cyber_security/'. $this->image) ,
            'duration' => $this->license_duration,
            'backup' => $this->cloud_backup,
            'no_of_pc' => $this->no_of_pc,
            'price' => $this->price,
            'product_type' => $this->product_type,
            'features' => $filteredCombos,
            'providers' => $filteredP
        ];
    }
}
