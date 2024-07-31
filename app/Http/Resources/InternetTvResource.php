<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Provider;
use App\Models\Combo;
use App\Models\PostFeature;
use App\Http\Resources\PostFeatureResource;
use App\Models\FAQ;
use App\Models\TvChannel;
use App\Models\TvPackage;

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
        $combos = Combo::where('category', $this->category)->get();
        $tvPackages = TvPackage::where('provider_id', $this->provider)->get();
        $filteredPackages = $tvPackages->map(function ($package) {
            // Decode the TV channels JSON and get the array of channel IDs
            $channelIds = json_decode($package->tv_channels, true);
            $channelCount = is_array($channelIds) ? count($channelIds) : 0;

            // Retrieve all TV channels and filter those in the package
            $tvChannels = TvChannel::latest()->get();
            $filteredChannels = $tvChannels->filter(function ($channel) use ($channelIds) {
                return in_array($channel->id, $channelIds);
            })->map(function ($ch) {
                return [
                    'id' => $ch->id,
                    'name' => $ch->channel_name,
                    'image' => asset('storage/images/tvChannels' . $ch->image),
                    'features' => json_decode($ch->features, true),
                    'description' => $ch->description,
                    'price' => $ch->price,
                    'type' => $ch->type
                ];
            })->values(); // Use `values()->all()` to reset keys and convert to array

            return [
                'id' => $package->id,
                'name' => $package->package_name,
                'image' => asset('storage/images/tvPackages/' . $package->image),
                'features' => $package->features,
                'channel_ids' => $package->tv_channels,
                'channels' => $filteredChannels, // Changed key to 'channels' for clarity
                'channel_count' => $channelCount,
            ];
        });


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
            'discounted_price' => $this->discounted_price,
            'discounted_till' => $this->discounted_till,
            'discount' => $this->discount,
            'commission' => $this->commission,
            'commission_type' => $this->commission_type,
            'image' => asset('storage/images/tvinternet/' . $this->image),
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
            'combo_details' => $filteredCombos->toArray(),
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
                    'id' => $this->providerDetails->id,
                    'name' => $this->providerDetails->name,
                    'about' => $this->providerDetails->about,
                    'payment_options' => $this->providerDetails->payment_options,
                    'annual_accounts' => $this->providerDetails->annual_accounts,
                    'meter_readings' => $this->providerDetails->meter_readings,
                    'adjust_installments' => $this->providerDetails->adjust_installments,
                    'view_consumption' => $this->providerDetails->view_consumption,
                    'rose_scheme' => $this->providerDetails->rose_scheme,
                ];
            }),
            'package_details' => $filteredPackages,
            'faqs' => FAQ::where('category_id', $this->category)->get(),
            'features' => PostFeatureResource::collection($features),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),

        ];
    }
}
