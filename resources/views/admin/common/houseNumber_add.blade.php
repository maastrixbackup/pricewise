@extends('admin.layouts.app')
@section('title', 'Pricewise- House Number Create')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.house-numbers.index') }}">House Number</a></li>
                </ol>
            </nav>
        </div>

    </div>
    <!--end breadcrumb-->
    <div class="row">

        <form id="roleForm" method="post" action="{{ route('admin.house-numbers.store') }}">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Add House Number</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <label for="input35" class="col-sm-2 col-form-label">Post Code</label>
                            <div class="col-sm-6 mb-3">
                                <select name="postal_code" class="form-select" id="postal_code">
                                    <option value="" selected disabled>Select a Post Code...</option>
                                    @foreach ($postalCodes as $code => $val)
                                        <option
                                            value="{{ $val->id }}"{{ $val->id == old('postal_code') ? 'selected' : '' }}>
                                            {{ $val->post_code }}</option>
                                    @endforeach
                                </select>
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>House No.</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="appData">
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="house_no[]" value=""
                                                placeholder="" required>
                                        </td>
                                        <td>
                                            <textarea name="address[]" id="address" class="form-control" cols="30" rows="3" placeholder="" required></textarea>
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="btn  btn-success Add"><i
                                                    class="fa fa-plus-square-o" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Submit</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                new Choices(document.querySelector("#postal_code"), {
                    removeItemButton: true
                });

                $('.Add').on('click', function() {
                    var htmlData = `
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="house_no[]" value=""
                                        placeholder="" required>
                                </td>
                                <td>
                                    <textarea name="address[]" id="address" class="form-control" cols="30" rows="3"
                                        placeholder="" required></textarea>
                                </td>
                                <td>
                                    <a href="javascript:;" class="btn  btn-danger removeTr"><i
                                                            class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                                </td>
                            </tr>`;
                    $('#appData').append(htmlData);
                });

                $(document).on('click', '.removeTr', function() {
                    $(this).closest('tr').remove();
                });
            });
        </script>
        <script type="text/javascript">
            $("#userForm").validate({
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
                    user_name: "required",
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: "required",
                    password: {
                        required: true,
                        minlength: 5,
                    },
                    confirm_password: {
                        minlength: 5,
                        equalTo: "#password"
                    }
                },
                messages: {
                    name: "Name is missing",
                    user_name: "UserName is missing",
                    email: {
                        required: "Email is required",
                        email: "Enter Valid Email",
                    },
                    phone: "Phone no is missing",
                    password: {
                        required: "Password is required",
                        minlength: "Enter minimum 5 characters",
                    },
                    confirm_password: {
                        required: "Confirm Password is required",
                        minlength: "Enter minimum 5 characters",
                    },
                },
                submitHandler: function(form) {


                    $.ajax({
                        url: form.action,
                        method: "post",
                        data: $(form).serialize(),
                        success: function(data) {
                            //success
                            $("#userForm").trigger("reset");
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

        <script>
            $("#checkPermissionAll").click(function() {
                if ($(this).is(':checked')) {
                    $('input[type=checkbox]').prop('checked', true)
                } else {
                    $('input[type=checkbox]').prop('checked', false)
                }
            })
        </script>
    @endpush
