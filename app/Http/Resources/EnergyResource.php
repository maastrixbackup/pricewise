<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Provider;
use App\Models\PostFeature;
use App\Models\EnergyRateChat;
use App\Models\FeedInCost;
use App\Http\Resources\PostFeatureResource;
use App\Models\Document;
use App\Models\Setting;

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
        $features = PostFeature::with([
            'postCategory:id,name',
            'postFeature:id,features as name,is_preferred'
        ])->where('post_id', $this->id)
            ->where('category_id', $this->category)->get();

        $docs = Document::where([
            'post_id' => $this->provider_id,
            'category' => config('constant.category.energy')
        ])->get();


        return [
            'id' => $this->id,
            'target_group' => $this->target_group,
            'fix_delivery' => $this->fixed_delivery,
            'grid_management' => $this->grid_management,
            'power_cost_per_unit' => $this->power_cost_per_unit,
            'gas_cost_per_unit' => $this->gas_cost_per_unit,
            'tax_on_electric' => $this->tax_on_electric,
            'tax_on_gas' => $this->tax_on_gas,
            'ode_on_electric' => $this->ode_on_electric,
            'ode_on_gas' => $this->ode_on_gas,
            'vat' => $this->vat,
            'discount' => $this->discount,
            'feed_in_tariff' => $this->feed_in_tariff,
            'energy_tax_reduction' => $this->energy_tax_reduction,
            'image' => asset('storage/images/providers/' . $this->providerDetails->image),
            'power_origin' => $this->power_origin,
            'type_of_current' => $this->type_of_current,
            'type_of_gas' => $this->type_of_gas,

            'notice_period' => $this->notice_period ?? 1,
            'energy_label' => $this->energy_label,
            'status' => $this->status,
            'contract_length' => $this->contract_length,
            'category' => config('constant.category.energy'),
            'provider' => $this->provider_id,
            'provider_details' => $this->whenLoaded('providerDetails', function () {
                return [
                    'id' => $this->providerDetails->id,
                    'name' => $this->providerDetails->name,
                    'about' => $this->providerDetails->about,
                    'rating' => $this->providerDetails->rating,
                    'payment_options' => $this->providerDetails->payment_options,
                    'annual_accounts' => $this->providerDetails->annual_accounts,
                    'meter_readings' => $this->providerDetails->meter_readings,
                    'adjust_installments' => $this->providerDetails->adjust_installments,
                    'view_consumption' => $this->providerDetails->view_consumption,
                    'rose_scheme' => $this->providerDetails->rose_scheme,
                ];
            }),

            'documents' => $docs->map(function ($document) {
                return [
                    'id' => $document->id,
                    'filename' => asset('storage/documents/' .  $document->filename),
                    'name' => $document->type,
                ];
            }),
            // 'documents' => $this->whenLoaded('documents', function () {
            //     return $this->documents->filter(function ($document) {
            //         return $this->category == $document->category;
            //     })->map(function ($document) {
            //         return [
            //             'id' => $document->id,
            //             'filename' => $document->filename,
            //             'path' => $document->path,
            //             'name' => $document->type,
            //         ];
            //     });
            // }),

            'features' => PostFeatureResource::collection($features),
            // 'created_at' => $this->created_at->format('d/m/Y'),
            // 'updated_at' => $this->updated_at->format('d/m/Y'),
            // 'prices' => $this->whenLoaded('prices', function ()  use ($request) {
            //     return [
            //         'normal_electric_rate' => $this->prices->electric_rate,
            //         'off_peak_electric_rate' => $this->prices->off_peak_electric_rate,
            //         'gas_rate' => $this->prices->gas_rate,
            //         'total' => $request->normal_electric_consume * $this->prices->electric_rate
            //             + $request->peak_electric_consume * $this->prices->off_peak_electric_rate
            //             + $request->gas_consume * $this->prices->gas_rate
            //             + $this->delivery_cost_electric
            //             + $this->delivery_cost_gas
            //             + $this->feedInCost->normal_feed_in_cost
            //             + $this->feedInCost->off_peak_feed_in_cost
            //             - $this->cashback
            //     ];
            // }),

            // Include feedInCost relationship if loaded
            // 'feed_in_cost' => $this->whenLoaded('feedInCost', function () {
            //     return [
            //         'feed_ranges' => json_decode($this->feedInCost->feed_in_cost),
            //         'normal_return_delivery' => $this->feedInCost->normal_return_delivery,
            //         'off_peak_return_delivery' => $this->feedInCost->off_peak_return_delivery,
            //     ];
            // }),
            // 'govt_taxes' => Setting::select('key', 'value')->where('type', 'business_general')->whereIn('sub_type', ['gas', 'current'])->get(),



        ];
    }
}
