@extends('admin.layouts.app')
@section('title','PriceWise- Energy Products Edit')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.requests.index')}}">Requests</a></li>
            </ol>
        </nav>
    </div>
</div>

<!-- added by satyajit for white background toastr message -->
<style>
        /* Custom style for success message */
        .toast-success {
            background-color: #4CAF50;
        }

        /* Custom style for error message */
        .toast-error {
            background-color: #FF5722;
        }
</style>
<!-- end -->
                 
                    <div class="row">                      
                        <div class="col-12">
                            <!-- Right column content -->
                            <div class="card">
                                <div class="card-header">
                                    <h2>Subscription and One-Time Costs</h2>                                    
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                    <div class="row">  
                                        <div class="col-sm-4">
                                        <!-- Customer Details aligned to the left -->
                                        <h5>Subscriber Details</h5>                                
                                        <strong>Name: </strong>{{$userRequest->userDetails?$userRequest->userDetails->name:''}}<br>
                                        <strong>Address: </strong>{{$userRequest->userDetails?$userRequest->userDetails->address:''}}<br>
                                        <strong>Postal Code: </strong>
                                        </div>
                                        <div class="col-sm-4">
                                            <!-- Supplier Details aligned to the right -->
                                            <h5>Supplier Details    </h5>                                
                                            <strong>Name: </strong>{{$userRequest->providerDetails?$userRequest->providerDetails->name:''}}<br>
                                            <strong>Address: </strong>{{$userRequest->providerDetails?$userRequest->providerDetails->address:''}}<br>                                        
                                        </div>
                                        <div class="col-sm-4">
                                        <strong>    Order Date: </strong>{{$userRequest->created_at->format('d/m/Y')}}
                                        <strong>    Order No: </strong>{{$userRequest->order_no}}
                                        <form action="{{route('admin.request.update_status', [$userRequest->id])}}" id="update_status">
                                            @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label font-weight-bold">Status:</label>
                                            <div class="col-sm-9">
                                                <select id="request_status" name="request_status" class="select2 form-select">
                                                    <option value="">Select</option>
                                                    <option value="Under Process" @if($userRequest->request_status == 'Under Process')selected @endif>Under Process</option>
                                                    <option value="Completed" @if($userRequest->request_status == 'Completed')selected @endif>Completed</option>
                                                    <option value="Cancelled" @if($userRequest->request_status == 'Cancelled')selected @endif>Cancelled</option>
                                                </select>
                                            </div>
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" id="submitBtn4" class="btn btn-sm btn-primary px-2" value="Update">Update</button>
                                            
                                        </div>
                                        </div>
                                        </form>
                                        </div>
                                                                           
                                    </div>
                                    <hr/>                                  
                                    
                                        <h5>Subscription Details</h5>
                                      <div class="row">
                                        <div class="col">
                                          <div class="card">
                                            <div class="card-body">
                                              <h5 class="card-title d-flex justify-content-between">
                                                Your Subscription
                                                <a class="collapsed" data-toggle="collapse" href="#priceBreakdown" aria-expanded="false" aria-controls="priceBreakdown">
                                                  <i class="fas fa-chevron-down"></i>
                                                </a>
                                              </h5>
                                              <div class="row">
                                                  <div class="col-6">
                                                    <p class="mb-0">{{$userRequest->service?$userRequest->service->title:''}}</p>
                                                  </div>
                                                  <div class="col-6 text-right">
                                                    <p class="mb-0">€ {{$userRequest->total_price}}</p>
                                                  </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-6">
                                                    <small class="text-muted">12 months cost € {{$userRequest->total_price}}</small>
                                                  </div>
                                                </div>
                                              <div class="collapse" id="priceBreakdown">
                                                <hr>
                                                
                
@if($userRequest->service_type == 'App\Models\EnergyProduct')
@include('admin.partials.energy_request',['advantages'=>json_decode($userRequest->advantages, true) ])
@endif
@if($userRequest->service_type == 'App\Models\TvInternetProduct')
@include('admin.partials.tvinternet_request',['advantages'=>json_decode($userRequest->advantages, true) ])
@endif
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- <div class="row mt-4">
                                        <div class="col">
                                          <div class="card">
                                            <div class="card-body">
                                              <h5 class="card-title">One-off costs</h5>
                                              <p class="mb-2">The one-off costs are stated on the first invoice</p>
                                              <div class="row">
                                                <div class="col-6">
                                                  <p class="mb-1">+ Connection costs</p>
                                                  <small class="text-muted">One-time € 25.00 Discount</small>
                                                </div>
                                                <div class="col-6 text-right">
                                                  <p class="mb-1">Free</p>
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-6">
                                                  <p class="mb-1">Shipping and handling charges</p>
                                                </div>
                                                <div class="col-6 text-right">
                                                  <p class="mb-1">Free</p>
                                                </div>
                                              </div>
                                              <div class="row">
                                                <div class="col-6">
                                                  <p class="mb-0">+ Order a technician directly</p>
                                                </div>
                                                <div class="col-6 text-right">
                                                  <p class="mb-0">€ 75.00</p>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div> -->
                                    </div>                              
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                            
                        </div>
                    </div>
                
            </div>

        </div>
    </div>
</div>
<!--end breadcrumb-->

@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

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

    $("#update_status").validate({
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
</script>

@endpush