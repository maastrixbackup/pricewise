@extends('admin.layouts.app')
@section('title','Energise - Feed In Cost Create')
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
<form id="productForm" method="post" action="{{route('admin.feed-in-costs.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-9 col-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Add New Feed In Cost</h5>
                </div>
                <div class="card-body p-4">
                    <div class=" mb-3">
                        <label for="provider" class=" col-form-label">Provider</label>
                        <select class="form-control selectpicker" data-live-search="true" name="provider" id="provider">
                                <option value="">Select</option>
                                @foreach($providers as $parent)
                                <option value="{{$parent->id}}">{{$parent->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    
                    <div class=" mb-3">
                        <label for="from_range" class=" col-form-label">From Range</label>
                        <input type="number" class="form-control" id="from_range" name="from_range" placeholder="From Range">
                    </div>
                    <div class=" mb-3">
                        <label for="to_range" class=" col-form-label">To Range</label>
                        <input type="number" class="form-control" id="to_range" name="to_range" placeholder="To Range">
                    </div>

                    <div class=" mb-3">
                        <label for="amount" class=" col-form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount">
                    </div>
                    <div class=" mb-3">
                        <label for="normal_feed_in_cost" class=" col-form-label">Normal Feed In Cost</label>
                        <input type="number" class="form-control" id="normal_feed_in_cost" name="normal_feed_in_cost" placeholder="Normal Feed In Cost">
                    </div>
                    <div class=" mb-3">
                        <label for="off_peak_feed_in_cost" class=" col-form-label">Off Peak Feed In Cost</label>
                        <input type="number" class="form-control" id="off_peak_feed_in_cost" name="off_peak_feed_in_cost" placeholder="Off Peak Feed In Cost">
                    </div>
                <div class="row mb-3">
                    <div class="">
                        <label class=" col-form-label"></label>

                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
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
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

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
            // var value = CKEDITOR.instances['description'].getData()
            // $("#description").val(value);

            var formData = new FormData(this);
            $.ajax({

                url: form.action,
                method: "post",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //success
                    // alert(data);
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