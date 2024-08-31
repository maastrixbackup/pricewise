@extends('admin.layouts.app')
@section('title', 'Price Compare- Payment Setting')
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Fees Setting</h5>
                </div>
                <div class="card-body p-4">
                    <form id="feesSetForm" method="post" action="{{ route('admin.nominal-fees-store') }}">
                        @csrf
                        @foreach ($categories as $cat)
                            @php
                                $found = false;
                            @endphp
                            @foreach ($settingFees as $sFee)
                                @if ($cat->id == $sFee->category_id)
                                    @php
                                        $found = true;
                                    @endphp
                                    <div class="mb-3">
                                        <label for="host" class="col-form-label">{{ strtoupper($cat->name) }}</label>
                                        <input type="hidden" name="cat_id[{{ $cat->id }}]"
                                            value="{{ $cat->id }}">
                                        <input class="form-control decimal" type="number" id="fees_{{ $cat->id }}"
                                            placeholder="Nominal fees" name="fees[{{ $cat->id }}]"
                                            value="{{ $sFee->amount }}" step=".01"
                                            onkeyup="enforceTwoDecimalPlaces('{{ $cat->id }}')">
                                    </div>
                                @endif
                            @endforeach

                            @if (!$found)
                                <div class="mb-3">
                                    <label for="host" class="col-form-label">{{ strtoupper($cat->name) }}</label>
                                    <input type="hidden" name="cat_id[{{ $cat->id }}]" value="{{ $cat->id }}">
                                    <input class="form-control decimal" type="number" id="fees_{{ $cat->id }}"
                                        placeholder="Nominal fees" name="fees[{{ $cat->id }}]" value=""
                                        step=".01" onkeyup="enforceTwoDecimalPlaces('{{ $cat->id }}')">
                                </div>
                            @endif
                        @endforeach



                        <label class="col-form-label"></label>
                        <hr>
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4" name="submit2"
                                value="Update">Update</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $("#mailSetForm").validate({
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
                mail_host: "required",
                mail_port: "required",
            },
            messages: {
                mail_host: "Host is missing",
                mail_port: "Port is missing",
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    method: "post",
                    data: $(form).serialize(),
                    success: function(data) {
                        //success
                        //$("#mailchimpForm").trigger("reset");
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

        // function chkAmLth(e, ct_id) {
        //     // Get the occurrence of the decimal operator
        //     var match = $('#fees_' + ct_id).val().match(/\./g);
        //     if (match != null) {
        //         // Allow: backspace, delete, tab, escape, enter, decimal key
        //         if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
        //             // Allow: Ctrl+A
        //             (e.keyCode == 65 && e.ctrlKey === true) ||
        //             // Allow: home, end, left, right
        //             (e.keyCode >= 35 && e.keyCode <= 39)) {
        //             // let it happen, don't do anything
        //             return;
        //         } else if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        //             e.preventDefault();
        //         }
        //     } else {
        //         // Allow: backspace, delete, tab, escape, enter, and decimal key
        //         if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        //             // Allow: Ctrl+A
        //             (e.keyCode == 65 && e.ctrlKey === true) ||
        //             // Allow: home, end, left, right
        //             (e.keyCode >= 35 && e.keyCode <= 39)) {
        //             // let it happen, don't do anything
        //             return;
        //         }
        //         if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        //             e.preventDefault();
        //         }
        //     }
        // }

        function enforceTwoDecimalPlaces(ct_id) {
            var inputField = $('#fees_' + ct_id);
            var value = inputField.val();
            console.log(value);

            // Check if there's a decimal point in the value
            if (value.indexOf('.') !== -1) {
                var parts = value.split('.');

                // If the length of the decimal part is greater than 2, trim it
                if (parts[1].length > 2) {
                    parts[1] = parts[1].substring(0, 2);
                    inputField.val(parts[0] + '.' + parts[1]);
                }
            }
        }
    </script>
@endpush
