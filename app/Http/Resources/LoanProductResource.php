<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Bank;
use App\Models\LoanType;

class LoanProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $bank = Bank::orderBy('id', 'asc')->get();
        $loanTypes = LoanType::orderBy('id', 'asc')->get();
        $providerIds = json_decode($this->provider, true);
        $loanTypeId = json_decode($this->loan_type_id, true);

        $loanData = $loanTypes->filter(function ($loanTypes) use ($loanTypeId){
            return in_array($loanTypes->id, $loanTypeId);
        })->map(function ($query) {
            return [
                'id' => $query->id,
                'name' => $query->loan_type,
                'description' => $query->description,
                'image' => asset('storage/images/loans/'.$query->image)
            ];
        })->values();

        $filterData = $bank->filter(function ($bank) use ($providerIds) {
            return in_array($bank->id, $providerIds);
        })->map(function ($filter) {
            return [
                'id' => $filter->id,
                'country_id' => $filter->country_id,
                'bank_name' => $filter->bank_name,
                'slug' => $filter->slug,
                'description' => $filter->description,
                'swift_code' => $filter->swift_code,
                'image' => asset('storage/images/bank_images/'. $filter->image) ,
            ];
        })->values();


        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->description,
            'spending_purpose' => $this->p_id,
            'image' => asset('storage/images/loans/' . $this->image),
            'provider' => $providerIds,
            'loan_type' => $loanTypeId,
            'max_borrow_amount' => $this->borrow_amount,
            'expected_amount' => $this->expected_amount,
            'interest' => $this->rate_of_interest,
            'purpose_details'=> $this->purposeDetails,
            'loan_type_details' => $loanData,
            'provider_details' => $filterData,
        ];
    }
}
