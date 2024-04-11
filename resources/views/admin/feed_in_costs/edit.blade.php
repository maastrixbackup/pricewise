@extends('admin.layouts.app')
@section('title','Energise- Feed In Cost Edit')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.feed-in-costs.index')}}">Feed In Cost</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<form id="productForm" method="post" action="{{route('admin.feed-in-costs.update',$objFeedInCost->id)}}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Feed In Cost</h5>
                </div>
                <div class="card-body p-4">
                    <div class="card-body p-4">
                        <div class=" mb-3">
                        <label for="provider" class=" col-form-label">Provider</label>
                        <select class="form-control selectpicker" data-live-search="true" name="provider" id="provider">
                                <option value="">Select</option>
                                @foreach($providers as $parent)
                                <option value="{{$parent->id}}" @if($objFeedInCost->provider == $parent->id)selected @endif>{{$parent->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <label for="provider" class=" col-form-label"><b>Feed In Tariff</b></label>
                    @php
                    $return_tariff = json_decode($objFeedInCost->return_tariff);
                    //dd($return_tariff);
                    @endphp
                    @if($return_tariff && count($return_tariff) > 0)
                    @foreach($return_tariff as $key => $tariff)
                    <div class="form-group row">
                        <label for="" class="col-md-2 col-form-label">From Range</label>
                        <div class="col-md-2">
                        <input type="number" class="col-md-2 form-control" id="" name="return_tariff[{{$key}}][from_range]" placeholder="From Range" value="{{$tariff->from_range}}">
                        </div>
                    
                        <label for="" class="col-md-2 col-form-label">To Range</label>
                        <div class="col-md-2">
                        <input type="number" class="col-md-2 form-control" id="" name="return_tariff[{{$key}}][to_range]" placeholder="To Range" value="{{$tariff->to_range}}">
                        </div>
                    
                        <label for="" class="col-md-2 col-form-label">Amount</label>
                        <div class="col-md-2">
                        <input type="number" class="col-md-2 form-control" id="" name="return_tariff[{{$key}}][amount]" placeholder="Amount" value="{{$tariff->amount}}">
                        </div>
                    </div>
                    @endforeach
                    @else
                    @for($i=0; $i<5; $i++)
                    <div class="form-group row">
                        <label for="" class="col-md-2 col-form-label">From Range</label>
                        <div class="col-md-2">
                        <input type="number" class="col-md-2 form-control" id="" name="return_tariff[{{$i}}][from_range]" placeholder="From Range" value="">
                        </div>
                    
                        <label for="" class="col-md-2 col-form-label">To Range</label>
                        <div class="col-md-2">
                        <input type="number" class="col-md-2 form-control" id="" name="return_tariff[{{$i}}][to_range]" placeholder="To Range" value="">
                        </div>
                    
                        <label for="" class="col-md-2 col-form-label">Amount</label>
                        <div class="col-md-2">
                        <input type="number" class="col-md-2 form-control" id="" name="return_tariff[{{$i}}][amount]" placeholder="Amount" value="">
                        </div>
                    </div>
                    @endfor
                    @endif
                    <div class=" mb-3">
                        <label for="normal_feed_in_cost" class=" col-form-label">Normal Feed In Cost</label>
                        <input type="number" class="form-control" id="normal_feed_in_cost" name="normal_feed_in_cost" placeholder="Normal Feed In Cost" value="{{$objFeedInCost->normal_feed_in_cost}}">
                    </div>
                    <div class=" mb-3">
                        <label for="off_peak_feed_in_cost" class=" col-form-label">Off Peak Feed In Cost</label>
                        <input type="number" class="form-control" id="off_peak_feed_in_cost" name="off_peak_feed_in_cost" placeholder="Off Peak Feed In Cost" value="{{$objFeedInCost->off_peak_feed_in_cost}}">
                    </div>   
                        

                        <div class="row mb-3">
                            <div class="">
                                <label class=" col-form-label"></label>

                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        
    </div>

</form>
  
@endsection
@push('scripts')

<script type="text/javascript">
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
            name: "required",
            

        },
        messages: {
            name: "Name is missing",
            
        },
        submitHandler: function(form) {

            var formData = new FormData(form);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success
                    $("#productForm").trigger("reset");
                    if (data.status) {
                        location.href = data.redirect_location;
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


</script>
@endpush