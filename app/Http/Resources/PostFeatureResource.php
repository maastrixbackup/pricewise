<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostFeatureResource extends JsonResource
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
            'id' => $this->id,
            'post_id' => $this->post_id,
            'category_details' => $this->whenLoaded('postCategory'),
            'feature_details' => $this->whenLoaded('postFeature'),
            'feature_value' => $this->feature_value,
            'more_info' => $this->details
        ];
    }
}
