<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Provider;
use App\Models\ProviderFaq;
use App\Models\PostFeature;
use App\Models\EnergyRateChat;
use App\Models\FeedInCost;
use App\Http\Resources\PostFeatureResource;
use App\Models\Document;
use App\Models\Setting;
use App\Models\SwitchingPlanFaq;

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

        $pFaqs = ProviderFaq::where([
            'provider_id' => $this->provider_id,
            'cat_id' => config('constant.category.energy')
        ])->get();
        $pFaqs = $pFaqs->map(function ($pf) {
            return [
                'id' => $pf->id,
                'title' => $pf->title,
                'description' => $pf->description
            ];
        });

        $planFaq = SwitchingPlanFaq::where([
            'provider_id' => $this->provider_id,
            'cat_id' => config('constant.category.energy')
        ])->get();
        $planFaq = $planFaq->map(function ($pf) {
            return [
                'id' => $pf->id,
                'title' => $pf->title,
                'description' => $pf->description,
                'question' => $pf->question,
                'answer' => $pf->answer,
            ];
        });

        return [
            'id' => $this->id,
            'target_group' => $this->target_group,
            'fix_delivery' => number_format($this->fixed_delivery, 2),
            'grid_management' => number_format($this->grid_management, 2),
            'power_cost_per_unit' => number_format($this->power_cost_per_unit, 2),
            'gas_cost_per_unit' => number_format($this->gas_cost_per_unit, 2),
            'tax_on_electric' => number_format($this->tax_on_electric, 2),
            'tax_on_gas' => number_format($this->tax_on_gas, 2),
            'ode_on_electric' => number_format($this->ode_on_electric, 2),
            'ode_on_gas' => number_format($this->ode_on_gas, 2),
            'vat' => $this->vat,
            'discount' => $this->discount,
            'ratings' => $this->ratings,
            'insight_app' => $this->insight_app,
            'boiler' => $this->boiler,
            'feed_in_tariff' => number_format($this->feed_in_tariff, 2),
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
                    'ratings' => $this->providerDetails->ratings,
                    'insight_app' => $this->providerDetails->insight_app,
                    'discount_term ' => $this->providerDetails->discount,
                    'payment_options' => $this->providerDetails->payment_options,
                    'annual_accounts' => $this->providerDetails->annual_accounts,
                    'meter_readings' => $this->providerDetails->meter_readings,
                    'adjust_installments' => $this->providerDetails->adjust_installments,
                    'view_consumption' => $this->providerDetails->view_consumption,
                    'rose_scheme' => $this->providerDetails->rose_scheme,
                ];
            }),

            'pFeatures' => [
                'contact_duration' => $this->contract_length,
                'rate' => 'Fixed',
                'type_of_current' => json_decode($this->power_origin, true),
                'type_of_gas' => json_decode($this->type_of_gas, true),
            ],

            'documents' => $docs->map(function ($document) {
                return [
                    'id' => $document->id,
                    'filename' => asset('storage/documents/' .  $document->filename),
                    'name' => $document->type,
                ];
            }),
            'pFaqs' => $pFaqs,
            'valid_till' => $this->valid_till,
            'switching_plan' => $planFaq,
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
