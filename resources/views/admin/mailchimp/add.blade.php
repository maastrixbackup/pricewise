@extends('admin.layouts.app')
@section('title','POPTelecom- MailChimp Create')
@section('content')
  <!-- Custom CSS -->
  <link href="{{ asset('assets/css/template.css')}}" rel="stylesheet">

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.subscribers-list')}}">MailChimp</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Send Campaign</h5>
            </div>
            <div class="card-body p-4">
                <form id="CampaignForm" method="post" action="{{route('admin.send-campaign')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Template</label>
                        <select id="template" name="template" class="select2 form-select">
                            <option value="">Select</option>
                            @if($newsletter)
                            @foreach($newsletter as $val)
                            <option data-id="{{$val->id}}" value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Your Subject">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">To</label>
                        <input type="text" class="form-control" id="to" name="to" placeholder="To">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">From</label>
                        <input type="text" class="form-control" id="from" name="from" placeholder="From">
                    </div>

                    <div class="mb-3">
                        <label for="input35" class="col-form-label">Your message</label>
                        <textarea class="form-control" id="message" name="message" placeholder="Please enter your message here.." rows="3"></textarea>

                    </div>

                    <label class="col-form-label"></label>

                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4" name="submit2">Send Campaign</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <form id="newsletterForm" method="post" action="{{route('admin.store-newsletter')}}">
            @csrf
            <div class="card" id="newsletter" name="desc_html">
            </div>
            <label class="col-form-label"></label>
            <div class="d-md-flex d-grid align-items-center gap-3">
                <button type="submit" class="btn btn-primary px-4" name="submitBtn" id="submitBtn" style="display:none;">Update</button>

            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>

<script type="text/javascript">
    $("#CampaignForm").validate({
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
            subject: "required",
            to: "required",
            from: "required",
            message: "required"
        },
        messages: {
            subject: "Subject is missing",
            to: "To is missing",
            from: "From is missing",
            message: "Message is missing",
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#CampaignForm").trigger("reset");
                    if (data.status) {
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
</script>
<!-- <script type="text/javascript">
    $("#newsletterForm").validate({
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
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#newsletterForm").trigger("reset");
                    if (data.status) {
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
</script> -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#template').on('change', function() {
            $('#newsletter').val($('#template option:selected').data('id'));
            console.log($('#template option:selected').data('id'));
            $.ajax({
                type: "get",
                url: "{{ route('admin.get-template') }}",
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    template: $('select[name=template]').val()
                },
                success: function(data) {
                    //alert(data.response);

                    $('#demo').remove();
                    $('#submitBtn').css("display", "block");
                    $('#submitBtn').val(data.id);
                    $('#newsletter').append(
                        '<div id="demo">' + data.response + '</div>');
                }
            });

        })

        $(document).on("dblclick", "#demo", function() {

            var current = $(this).html();
            $("#demo").html('<textarea class="form-control ckeditor" id="newcont" name="newcont" rows="8">' + current + '</textarea>');
            CKEDITOR.replace('newcont');
            $("#newcont").focus();

            $("#newcont").focus(function() {
                console.log('in');
            }).blur(function() {
                var newcont = $("#newcont").val();
                var desc_html = $("#demo").text(newcont);
                //alert(desc_html);
                $("#newsletterForm").validate({
       
        submitHandler: function(form) {
            var newcont = $("#newcont").val();
                var desc_html = $("#demo").text(newcont);
              var  template=$('select[name=template]').val();
            $.ajax({
                url: form.action,
                method: "post",
                data: $(form).serialize(),
                success: function(data) {
                    //success
                    $("#newsletterForm").trigger("reset");
                    if (data.status) {
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
            });

        })
    });
</script>
@endpush