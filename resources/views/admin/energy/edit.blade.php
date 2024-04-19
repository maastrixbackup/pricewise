@extends('admin.layouts.app')
@section('title','PriceWise- Energy Products Edit')
@section('content')
<style type="text/css">
    .form-check-box {
    display: flex;
    align-items: center;
}

.form-check-pr label {
    position: relative;
    cursor: pointer;
}
.form-check-pr input {
    padding: 0;
    height: initial;
    width: initial;
    margin-bottom: 0;
    display: none;
    cursor: pointer;
}
.form-check-pr label:before {
    content: '';
    -webkit-appearance: none;
    background-color: transparent;
    border: 2px solid #fa9f1d;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
    padding: 10px;
    display: inline-block;
    position: relative;
    vertical-align: middle;
    cursor: pointer;
    margin-right: 5px;
}
.form-check-pr input:checked + label:after {content: '';display: block;position: absolute;top: 7px;left: 9px;width: 6px;height: 14px;border: solid #0079bf;border-width: 0 2px 2px 0;transform: rotate(45deg);}
</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.energy.index')}}">Energy</a></li>
            </ol>
        </nav>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-success" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#home" role="tab" aria-selected="true">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                        </div>
                        <div class="tab-title">About this package</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#pricing" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class='bx bx-badge-check font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Pricing</div>
                    </div>
                </a>
            </li>
         
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tv" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-badge-check font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Energy Features</div>
                    </div>
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-badge-check font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Documents</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#serviceInfo" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-badge-check font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Service Info</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                
                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Energy Details</h5>
                        </div>
                        <div class="col-md-12 col-lg-12 col-12">


                            <div class="card-body p-4">
                                <form id="productForm2" method="post" action="{{route('admin.energy.update', $objEnergy->id)}}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-9 col-lg-9 col-12">
                                            <div class="card">
                                                
                                                <div class="card-body p-4">
                                                    <div class=" mb-3">
                        <label for="input35" class=" col-form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Product Title" value="{{$objEnergy->title}}">
                    </div>

                    <div class=" mb-3">
                        <label for="input37" class="col-form-label">URL</label>

                        <!-- <div class="input-group mb-3"> <span class="input-group-text">/product/</span> -->
                        <input type="text" class="form-control" id="link" name="link" readonly value="{{$objEnergy->slug}}">
                        <!-- </div> -->

                    </div>

                    <div class="">
                        <label for="input35" class=" col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Description">{{$objEnergy->description}}</textarea>
                    </div>
                     <div class="mb-3">
                    <label for="provider" class="col-form-label"><b>Provider</b>
                    </label>

                    <select id="provider" name="provider" class="select2 form-select">
                        <option>Select</option>
                        @if($providers)
                        @foreach($providers as $provider)
                        <option value="{{$provider->id}}" @if($objEnergy->provider == $provider->id)selected @endif>{{$provider->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    </div>
                    
                        
                                
                                                    <div class="row">
                                                        <div class="col-md-6 col-12">
                                                            <div class=" mb-3">
                                                                <label for="pin_codes" class="col-form-label">Area PIN Codes</label>
                                                                <input type="text" class="form-control" id="pin_codes" name="pin_codes" placeholder="PIN codes with coma separated" value="{{implode(',',json_decode($objEnergy->pin_codes))}}">
                                                        </div>
                                                        </div>
                                    
                                                        <div class="col-md-6 col-12">
                                                            <div class=" mb-3">
                                                                <label for="valid_till" class="col-form-label">Offer Valid Till</label>
                                                                <input type="date" class="form-control" id="valid_till" name="valid_till" placeholder="Valid Till" value="{{$objEnergy->valid_till}}">
                                                        </div>
                                                        </div>
                                    
                                                    
                                                        <div class="col-md-6 col-12">
                                                            <div class=" mb-3">
                                                        <label for="avg_delivery_time" class=" col-form-label">Average delivery time</label>
                                                        <input type="number" class="form-control" id="avg_delivery_time" name="avg_delivery_time" placeholder="Average Delivery Time" value="{{$objEnergy->avg_delivery_time}}">
                                                            </div>
                                                        </div>
                                                    <div class="col-md-6 col-12">
                                                    <div class=" mb-3">
                                                        <label for="input35" class=" col-form-label">Transfer Service</label>
                                    
                                                        <div class="mb-3 add-scroll">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="transfer_service" value="1" @if($objEnergy->transfer_service == 1)checked @endif>
                                                                <label class="form-check-label" for="transfer_service">Available</label>
                                                            </div>
                                                        </div>
                                    
                                                    </div>
                                                    </div>
                                    
                                                    
                                                    
                                                    <div class="col-md-6 col-12">
                                                        <div class=" mb-3">
                                                            <label for="input40" class=" col-form-label">Contract Length
                                                            </label>
                                                            <input type="number" class="form-control" id="contract_length" name="contract_length" value="{{$objEnergy->contract_length}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="input40" class="col-form-label">Contract Type</label>
                                    
                                                        <select id="contract_length_id" name="contract_type" class="select2 form-select">
                                                            <option value="month" @if($objEnergy->contract_type == "month")selected @endif>Monthly</option>
                                                            <option value="year" @if($objEnergy->contract_type == "year")selected @endif>Yearly</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-12">
                                                            <div class=" mb-3">
                                                                <label for="input40" class=" col-form-label">Commission
                                                                </label>
                                                                <input type="number" class="form-control" id="commission" name="commission" value="{{$objEnergy->commission}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class=" mb-3">
                                                                <label for="input40" class=" col-form-label">Commission Type
                                                                </label>
                                                                <select id="commission_type" name="commission_type" class="select2 form-select">
                                                                    <option value="flat" @if($objEnergy->commission_type == "flat")selected @endif>Flat</option>
                                                                    <option value="prct" @if($objEnergy->commission_type == "prct")selected @endif>Percentage</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                    
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6 col-12">
                                                            <!-- <label for="input35" class="col-form-label"><b>Top 3 Product</b></label> -->
                                                            <div class="mb-3 add-scroll">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"  @if($objEnergy->is_featured == 1)checked @endif>
                                                                    <label class="form-check-label" for="flexCheckDefault">Feature Product</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                        
                                                    <div class="mb-3 add-scroll">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="no_gas" value="1" @if($objEnergy->no_gas == 1)checked @endif>
                                                            <label class="form-check-label" for="flexCheckDefault">No Gas</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        
                                                        <label for="input35" class=" col-form-label">Installation options</label>
                                                        <div class="mb-3 add-scroll">
                                                        <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="manual_install" value="1"  @if($objEnergy->manual_install == 1)checked @endif>
                                                        <label class="form-check-label" for="manual_install">Manual Installation</label>
                                                        </div>
                                                        </div>
                                                        <div class="mb-3 add-scroll">
                                                        <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="mechanic_install" value="1"  @if($objEnergy->mechanic_install == 1)checked @endif>
                                                        <label class="form-check-label" for="mechanic_charge">Mechanic Installation</label>
                                                        </div>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class=" mb-3">
                                                            <label for="mechanic_charge" class=" col-form-label">Mechanic Charge
                                                            </label>
                                                            <input type="number" class="form-control" id="mechanic_charge" name="mechanic_charge" value="{{$objEnergy->mechanic_charge}}">
                                                        </div>
                                                    </div>
                                                    </div>
                                
                                                
                                
                                                
                                
                                
                                                <div class="">
                                                    <label class=" col-form-label"></label>
                                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                                        <button type="submit" class="btn btn-primary px-4" name="submit23">Submit</button>
                                                        <button type="reset" class="btn btn-light px-4">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    
                                    <div class="col-md-3 col-12 col-lg-3">
                                        <div class="card">
                                            <div class="card-body p-4">
                                                <div class="mb-3 form-group">
                                                    <label for="input40" class="col-form-label"><b>Publish Status</b>
                                                    </label>
                                                    <select id="online_status" name="online_status" class="select2 form-select">
                                                        <option value="1"  @if($objEnergy->status == "1")selected @endif>Publish</option>
                                                        <option value="0" @if($objEnergy->status == "0")selected @endif>Draft</option>
                                                       
                                                    </select>
                                                </div>
                                                <div class="mb-3 form-group">
                                                    <label for="input40" class="col-form-label"><b>Product Type</b>
                                                    </label>
                                                    <select id="product_type" name="product_type" class="select2 form-select">
                                                        <option value="personal" @if($objEnergy->product_type == "personal")selected @endif>Personal</option>
                                                        <option value="business" @if($objEnergy->product_type == "business")selected @endif>Business</option>
                                                        <option value="large-business" @if($objEnergy->product_type == "large-business")selected @endif>Large Business</option>
                                                        
                                                    </select>
                                                </div>
                                                <div class="mb-3 form-group">
                                                    <label for="meter_type" class="col-form-label"><b>Meter Type</b>
                                                    </label>
                                                    <select id="meter_type" name="meter_type" class="select2 form-select">
                                                        <option value="">Select</option>
                                                        <option @if($objEnergy->meter_type == "Single Meter")selected @endif>Single Meter</option>
                                                        <option @if($objEnergy->meter_type == "Double Meter")selected @endif>Double Meter</option>
                                                        
                                                    </select>
                                                </div>
                                                <div class="mb-3 form-group">
                                                    <label for="no_of_person" class="col-form-label"><b>Number of Persons(Max)</b>
                                                    </label>
                                                    <input type="number" class="form-control" id="no_of_person" name="no_of_person" value="{{$objEnergy->no_of_person}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="input40" class="col-form-label"><b>Category</b>
                                                    </label>
                                
                                                    <select id="category" name="category" class="select2 form-select">
                                                        @if($objCategory)
                                                        @foreach($objCategory as $val)
                                                        <option value="{{$val->id}}" @if($objEnergy->category == $val->id)selected @endif>{{$val->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                @php
                                $energyLabel = $objEnergy->energy_label?json_decode($objEnergy->energy_label):[];
                                @endphp
                                                <label for="input40" class="col-sm-6 col-form-label"><b>Energy Label </b></label>
                <div class="col-lg-12"><div class="form-check-box"><div class="form-group form-check-pr"><input type="checkbox" id="a" name="energy_label[]" value="A" @if(in_array('A',$energyLabel))checked @endif><label for="a">A</label></div><div class="form-group form-check-pr"><input type="checkbox" id="b" name="energy_label[]" value="B" @if(in_array('B',$energyLabel))checked @endif><label for="b">B</label></div><div class="form-group form-check-pr"><input type="checkbox" id="c" name="energy_label[]" value="C" @if(in_array('C',$energyLabel))checked @endif><label for="c">C</label></div><div class="form-group form-check-pr"><input type="checkbox" id="d" name="energy_label[]" value="D" @if(in_array('D',$energyLabel))checked @endif><label for="d">D</label></div><div class="form-group form-check-pr"><input type="checkbox" id="e" name="energy_label[]" value="E" @if(in_array('E',$energyLabel))checked @endif><label for="e">E</label></div><div class="form-group form-check-pr"><input type="checkbox" id="f" name="energy_label[]" value="F" @if(in_array('F',$energyLabel))checked @endif><label for="f">F</label></div><div class="form-group form-check-pr"><input type="checkbox" id="g" name="energy_label[]" value="G" @if(in_array('G',$energyLabel))checked @endif><label for="g">G</label></div></div></div>
                                                <div class="mb-3">
                                                    <label for="upload_image">
                                <img src="{{asset('storage/images/energy/'. $objEnergy->image)}}" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />
                                <div class="overlay">
                                    <div>Click to Change Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                <input type="hidden" name="cropped_image" id="cropped_image">

                                </label>
                                                </div>
                                                
                                                
                                
                                                <label for="input35" class="col-form-label"><b>Combo Offers</b></label>
                                                <div class="mb-3 add-scroll">
                                                    @if($objRelatedProducts)
                                                    @foreach($objRelatedProducts as $val)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="related_products[]" value="{{$val->id}}" @if(in_array($val->id, json_decode($objEnergy->combos)))checked @endif>
                                                        <label class="form-check-label" for="flexCheckDefault">{{$val->title}}</label>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                
                                </form>
                                
                            </div>
                        </div>
                        
                    </div>
                
            </div>

            <div class="tab-pane fade" id="pricing" role="tabpanel">
                <form id="internetForm" method="post" action="{{route('admin.energy.pricing', $objEnergy->id)}}">
                    @csrf
                    <input type="hidden" name="category_id" value="{{$objEnergy->category}}">
                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Pricing</h5>
                        </div>
                        

                            	<div class="col-md-4 mb-3">
                            <label for="input37" class="col-form-label">Gas Price/m<sup>3</sup></label>
                            <input type="number" class="form-control" id="gas_price" name="gas_price" placeholder="Price" value="{{$objEnergy->prices?$objEnergy->prices->gas_rate:''}}" readonly="readonly">
                        </div>
                    
                    
                        <div class="col-md-4 mb-3">
                            <label for="input37" class="col-form-label">Normal Electric Price/kWh</label>
                            <input type="number" class="form-control" id="normal_electric_price" name="normal_electric_price" placeholder="Normal Electric Price" readonly="readonly" value="{{$objEnergy->prices?$objEnergy->prices->electric_rate:''}}"> </div>
                    
                        <div class="col-md-4 mb-3">
                            <label for="input37" class="col-form-label">Off Peak Electric Price/kWh</label>
                            <input type="number" class="form-control" id="peak_electric_price" name="peak_electric_price" placeholder="Off Peak Electric Price" readonly="readonly" value="{{$objEnergy->prices?$objEnergy->prices->off_peak_electric_rate:''}}">
                        </div>
                    
                    
                        <div class="col-md-4 mb-3">
                            <label for="input37" class="col-form-label">Normal Feed In Cost/kWh</label>
                            <input type="number" class="form-control" id="normal_feed_in_cost" name="normal_feed_in_cost" placeholder="Normal Feed In Cost" readonly="readonly" value="{{$objEnergy->feedInCost?$objEnergy->feedInCost->normal_feed_in_cost:''}}">
                        </div>
                   
                   
                        <div class="col-md-4 mb-3">
                            <label for="input37" class="col-form-label">Off Peak Feed In Cost/kWh</label>
                            <input type="number" class="form-control" id="peak_feed_in_cost" name="peak_feed_in_cost" placeholder="Off Peak Feed In Cost" readonly="readonly" value="{{$objEnergy->feedInCost?$objEnergy->feedInCost->off_peak_feed_in_cost:''}}">
                        </div>
                                        
                        <div class="col-md-4 mb-3">
                            <label for="network_cost_gas" class="col-form-label">Network Management Cost Gas</label>
                            <input type="number" class="form-control" id="network_cost_gas" name="network_cost_gas" placeholder="Network Management Cost Gas" value="{{$objEnergy->network_cost_gas}}">
                        </div>
                        <div class="col-md-4 mb-3">
                                <label for="input37" class="col-form-label">Network Management Cost Electric</label>
                                <input type="number" class="form-control" id="network_cost_electric" name="network_cost_electric" placeholder="Network Management Cost Gas" value="{{$objEnergy->network_cost_electric}}">
                        </div>
                        <div class="col-md-4 mb-3">
                                <label for="delivery_cost_electric" class="col-form-label">Fixed Delivery Cost Electric</label>
                                <input type="number" class="form-control" id="delivery_cost_electric" name="delivery_cost_electric" placeholder="Fixed Delivery Cost Electric" value="{{$objEnergy->delivery_cost_electric}}">
                        </div>
                        <div class="col-md-4 mb-3">
                                <label for="delivery_cost_gas" class="col-form-label">Fixed Delivery Cost Gas</label>
                                <input type="number" class="form-control" id="delivery_cost_gas" name="delivery_cost_gas" placeholder="Fixed Delivery Cost Gas" value="{{$objEnergy->delivery_cost_gas}}">
                        </div>

                        <div class="col-md-4 mb-3">
                                <label for="input37" class="col-form-label">Cashback</label>
                                <input type="number" class="form-control" id="cashback" name="cashback" placeholder="Cashback" value="{{$objEnergy->cashback}}">
                        </div>
                        
                            

                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-8">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" id="submitBtn2" class="btn btn-primary px-4" value="Submit">Save</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>  
            </div>

            <div class="tab-pane fade" id="tv" role="tabpanel">
                
                   <form id="tvForm" method="post" action="{{route('admin.energy_feature_update', $objEnergy->id)}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" value="{{$objEnergy->category}}">
                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Energy Features</h5>
                        </div>
                        <div class="col-md-7 col-12">
                            <div class="card-body p-4">

                            	{{--@if($objEnergyFeatures)
                                @foreach($objEnergyFeatures as $elm)
                                @php                                
                                $value = $postEnergyFeatures[trim($elm->id)] ?? '';                                
                                @endphp
                                @if($elm->input_type == "text")
                                <div class="mb-3">
                                    <label for="text_{{$elm->id}}" class="form-label">{{$elm->features}}</label>
                                    <input type="text" class="form-control" id="text_{{$elm->id}}" name="features[{{$elm->id}}]" placeholder="" value="{{ $value['feature_value']??'' }}">
                                    <textarea placeholder="Additional Info" name="details[{{$elm->id}}]" class="form-control">{{$value['details']??''}}</textarea>
                                </div>
                                @endif
                                @if($elm->input_type == "checkbox")
                                <div class="form-check">
                                <input type="hidden" name="features[{{$elm->id}}]" value="0">
                                <input class="form-check-input" type="checkbox"  name="features[{{$elm->id}}]" value="1" id="check_{{$elm->id}}" @if( isset($value['feature_value']) && $value['feature_value'] == 1 )checked @endif>
                                <label class="form-check-label" for="check_{{$elm->id}}">{{$elm->features}}</label>
                                <textarea placeholder="Additional Info" name="details[{{$elm->id}}]" class="form-control">{{$value['details']??''}}</textarea>
                                </div>
                                @endif
                                @endforeach
                                @endif--}}

@if($objEnergyFeatures)
    @foreach($objEnergyFeatures as $parentId => $features)
        {{-- Check if there are features for this parent --}}
        @if($parentId && $parentId != "No Parent")
        @php
        $displayedIds = [];
        @endphp
        @if($features->isNotEmpty())
        @php
            $displayedIds = array_merge($displayedIds, $features->pluck('id')->toArray());
        @endphp
            <h2>{{$parentId}}</h2>
            @foreach($features as $elm)
                @php
                    $value = $postEnergyFeatures[$elm->id] ?? ['feature_value' => '', 'details' => ''];
                @endphp
                @if($elm->input_type == "text")
                    <div class="mb-3">
                        <label for="text_{{$elm->id}}" class="form-label">{{$elm->features}}</label>
                        <input type="text" class="form-control" id="text_{{$elm->id}}" name="features[{{$elm->id}}]" placeholder="" value="{{ $value['feature_value'] }}">
                        <textarea placeholder="Additional Info" name="details[{{$elm->id}}]" class="form-control">{{$value['details']}}</textarea>
                    </div>
                @elseif($elm->input_type == "checkbox")
                    <div class="form-check">
                        <input type="hidden" name="features[{{$elm->id}}]" value="0">
                        <input class="form-check-input" type="checkbox"  name="features[{{$elm->id}}]" value="1" id="check_{{$elm->id}}" {{ isset($value['feature_value']) && $value['feature_value'] == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="check_{{$elm->id}}">{{$elm->features}}</label>
                        <textarea placeholder="Additional Info" name="details[{{$elm->id}}]" class="form-control">{{$value['details']}}</textarea>
                    </div>
                @endif
            @endforeach
        @else
            <p>No features found for Parent ID: {{$parentId}}</p>
        @endif
        @else
        @if($features->isNotEmpty())
        @foreach($features as $elm)
            @php
                $value = $postEnergyFeatures[$elm->id] ?? ['feature_value' => '', 'details' => ''];
            @endphp
            @if($elm->input_type == "text")
                <div class="mb-3">
                    <label for="text_{{$elm->id}}" class="form-label">{{$elm->features}}</label>
                    <input type="text" class="form-control" id="text_{{$elm->id}}" name="features[{{$elm->id}}]" placeholder="" value="{{ $value['feature_value'] }}">
                    <textarea placeholder="Additional Info" name="details[{{$elm->id}}]" class="form-control">{{$value['details']}}</textarea>
                </div>
            @elseif($elm->input_type == "checkbox")
                <div class="form-check">
                    <input type="hidden" name="features[{{$elm->id}}]" value="0">
                    <input class="form-check-input" type="checkbox"  name="features[{{$elm->id}}]" value="1" id="check_{{$elm->id}}" {{ isset($value['feature_value']) && $value['feature_value'] == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="check_{{$elm->id}}">{{$elm->features}}</label>
                    <textarea placeholder="Additional Info" name="details[{{$elm->id}}]" class="form-control">{{$value['details']}}</textarea>
                </div>
            @endif
        @endforeach
        @endif
        @endif
    @endforeach
@endif




                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-8">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" id="submitBtn3" class="btn btn-primary px-4" value="Submit">Save</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>         


            <div class="tab-pane fade" id="documents" role="tabpanel">                               

                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Documents</h5>
                        </div>
                        <div class="col-md-7 col-12">
                            <div class="card-body p-4">
                                @include('admin.partials.document_management_html', ['objPost' => $objEnergy, 'documents' => $documents])
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-8">                           
                        </div>
                    </div>
               
            </div>

            <div class="tab-pane fade" id="serviceInfo" role="tabpanel">
                <form id="infoForm" method="post" action="{{route('admin.service_info_update', $objEnergy->id)}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" value="{{$objEnergy->category}}">
                    <div class="row">
                        <div class="card-header px-4 py-3">
                            <h5 class="mb-0">Edit Service Info</h5>
                        </div>
                        <div class="col-md-7 col-12">
                            <div class="card-body p-4">

                                <div class=" mb-3">
                                    <label for="internet_guarantee" class=" col-form-label">Internet Guarantee</label>
                                    <textarea class="form-control" name="internet_guarantee" id="internet_guarantee" placeholder="Internet Guarantee">{{$serviceInfo[0]->feature_value??''}}</textarea>
                                </div>
                                
                                <div class=" mb-3">
                                    <label for="tr_info" class=" col-form-label">Transfer Information</label>
                                    <textarea class="form-control" name="transfer_info" id="tr_info" placeholder="Transfer Information">{{$serviceInfo[0]->feature_value??''}}</textarea>
                                </div>
                                <div class=" mb-3">
                                    <label for="active_service_info" class=" col-form-label">If Already have a Service</label>
                                    <textarea class="form-control" name="active_service_info" id="active_service_info" placeholder="Active Service Information">{{$serviceInfo[0]->feature_value??''}}</textarea>
                                </div>

                                <div class=" mb-3">
                                    <label for="mechanic_service_info" class=" col-form-label">Mechanic Service</label>
                                    <textarea class="form-control" name="mechanic_service_info" id="active_service_info" placeholder="Mechanic Service Information">{{$serviceInfo[0]->feature_value??''}}</textarea>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-8">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" id="submitBtn4" class="btn btn-primary px-4" value="Save">Save</button>
                                <button type="reset" class="btn btn-light px-4">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!--end breadcrumb-->

@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>

<script>
    tinymce.init({
        selector: '#description',
        plugins: 'codesample code advlist autolink lists link image charmap print preview hr anchor pagebreak',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | codesample code',
            codesample_languages: [
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'CSS', value: 'css' },
                { text: 'PHP', value: 'php' },
                { text: 'Ruby', value: 'ruby' },
                { text: 'Python', value: 'python' },
                { text: 'Java', value: 'java' },
                { text: 'C', value: 'c' },
                { text: 'C#', value: 'csharp' },
                { text: 'C++', value: 'cpp' }
            ],
       
         file_picker_callback (callback, value, meta) {
        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth
        let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight

        tinymce.activeEditor.windowManager.openUrl({
          url : '/file-manager/tinymce5',
          title : 'Laravel File manager',
          width : x * 0.8,
          height : y * 0.8,
          onMessage: (api, message) => {
            callback(message.content, { text: message.text })
          }
        })
      }
    });


    $("#productForm").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
            $(element).closest('.form-group').addClass("has-success");
        },

        rules: {
            title: "required",
            description: "required",

        },
        messages: {
            title: "Title is missing",
            description: "Description is missing",
        },
        submitHandler: function(form) {
            var data = CKEDITOR.instances.description.getData();
            $("#description").val(data);
            var formData = new FormData(form);
            alert(form.action)
            $.ajax({

                url: form.action,
                method: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success

                    if (data.status) {
                        location.href = data.redirect_location;
                    } else {
                        toastr.error(data.message.message, 'Already Exists!');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong . Please try again later!!', '');
                }
            });
            return false;
        }
    });
$("#internetForm").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
            $(element).closest('.form-group').addClass("has-success");
        },

        
        submitHandler: function(form) {
            // var data = CKEDITOR.instances.description.getData();
            // $("#description").val(data);
            //var formData = new FormData(form);
            //alert(form.action)
            $.ajax({

                url: form.action,
                method: "POST",
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                data: $(form).serialize(),
                // processData: false,
                // contentType: false,
                success: function(data) {
                    //success

                    if (data.status) {
                        //location.href = data.redirect_location;
                        toastr.success(data.message.message, '');
                    } else {
                        toastr.error(data.message.message, '');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong . Please try again later!!', '');
                }
            });
            return false;
        }
    });
$("#tvForm").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
            $(element).closest('.form-group').addClass("has-success");
        },

        
        submitHandler: function(form) {
            // var data = CKEDITOR.instances.description.getData();
            // $("#description").val(data);
            // var formData = new FormData(form);
            // alert(form.action)
            $.ajax({

                url: form.action,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(form).serialize(),
                // processData: false,
                // contentType: false,
                success: function(data) {
                    //success

                    if (data.status) {
                        //location.href = data.redirect_location;
                        toastr.success(data.message.message, '');
                    } else {
                        toastr.error(data.message.message, 'Something went wrong!');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong . Please try again later!!', '');
                }
            });
            return false;
        }
    });
$("#teleForm").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
            $(element).closest('.form-group').addClass("has-success");
        },

        
        submitHandler: function(form) {
            // var data = CKEDITOR.instances.description.getData();
            // $("#description").val(data);
            // var formData = new FormData(form);
            // alert(form.action)
            $.ajax({

                url: form.action,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(form).serialize(),
                // processData: false,
                // contentType: false,
                success: function(data) {
                    //success

                    if (data.status) {
                        //location.href = data.redirect_location;
                        toastr.success(data.message.message, '');
                    } else {
                        toastr.error(data.message.message, 'Something went wrong!');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong . Please try again later!!', '');
                }
            });
            return false;
        }
    });
$("#infoForm").validate({
        errorElement: 'span',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
            $(element).closest('.form-group').addClass("has-success");
        },

        
        submitHandler: function(form) {
            // var data = CKEDITOR.instances.description.getData();
            // $("#description").val(data);
            // var formData = new FormData(form);
            // alert(form.action)
            $.ajax({

                url: form.action,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(form).serialize(),
                // processData: false,
                // contentType: false,
                success: function(data) {
                    //success

                    if (data.status) {
                        //location.href = data.redirect_location;
                        toastr.success(data.message.message, '');
                    } else {
                        toastr.error(data.message.message, 'Something went wrong!');
                    }
                },
                error: function(e) {
                    toastr.error('Something went wrong . Please try again later!!', '');
                }
            });
            return false;
        }
    });
    $("#title").keyup(function() {
        var title_val = $("#title").val();
        $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
    $("#title").keydown(function() {
        var title_val = $("#title").val();
        $("#link").val(title_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
    $(document).ready(function(){
        $("#provider").on('change', function() {
            var provider_id = $(this).val(); 
            $.ajax({
                url: "http://192.168.1.44/pricewise/public/api/login", // Replace this with the actual URL to fetch subcategories
                type: 'POST',
                data: {
                   "email" : "customer@customer.com",
                   "password" : "password"    
                },
                // headers: {
                // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(response) {
                    console.log(response.data)
                    
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle errors here
                }
            });
        });
    });

    $(document).ready(function() {
    $('#addDocument').click(function() {
        $('<input type="file" name="documents[]" class="documentInput">').appendTo('#documents');
    });

    $('#save').click(function() {
        var formData = new FormData();
        $('.documentInput').each(function(index, element) {
            formData.append('documents[]', $(element)[0].files[0]);
        });

        $.ajax({
            url: '/insurance/store',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                // Handle success
            }
        });
    });

    $(document).on('click', '.delete', function() {
        var documentId = $(this).data('id');

        $.ajax({
            url: '/insurance/' + documentId,
            method: 'DELETE',
            success: function(data) {
                // Handle success
            }
        });
    });
});
</script>
@include('admin.partials.document_management', ['objPost' => $objEnergy])
@endpush