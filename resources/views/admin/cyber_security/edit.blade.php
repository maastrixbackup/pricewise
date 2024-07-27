@extends('admin.layouts.app')
@section('title', 'Pricewise- Cyber Security Edit')
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
                    <li class="breadcrumb-item active" aria-current="page"><a
                            href="{{ route('admin.cyber-security.index') }}">Cyber Security</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Edit Product</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.cyber-security.update', $product->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input35" class=" col-form-label">Title</label>
                            <div class="">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                    value="{{ $product->title }}">
                                @error('title')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea class="form-control" name="description" placeholder="Description" id="" cols="30"
                                    rows="5">{!! $product->description !!}</textarea>
                                @error('description')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-md-6 col-12 mb-3">
                                <label for="input35" class=" col-form-label">Price</label>
                                <input type="text" name="price" id="price" placeholder="Price" class="form-control"
                                    value="{{ $product->price }}">
                                @error('price')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="input35" class=" col-form-label">Pin Codes</label>
                                <input type="text" name="pin_codes" id="pin_codes"
                                    placeholder="PIN codes with coma (,) separated" class="form-control"
                                    value="{{ implode(',', json_decode($product->pin_codes, true)) }}">
                                @error('pin_codes')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="input35" class=" col-form-label">Provider</label>
                                <select name="provider_id[]" id="provider_id" class="form-control selectpicker" multiple>
                                    <option value="">--Select--</option>
                                    @foreach (App\Models\SecurityProvider::all() as $p)
                                        @if ($p->status == 'active')
                                            <option value="{{ $p->id }}"
                                                {{ in_array($p->id, json_decode($product->provider_id)) ? 'selected' : '' }}>
                                                {{ $p->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6 col-12 mb-3">
                                <label for="input35" class=" col-form-label">License Duration (<b>Year</b>) </label>
                                <input type="number" class="form-control" placeholder="max-2years" name="license_duration"
                                    id="license_duration" value="{{ $product->license_duration }}" onkeyup="checkYear()">
                                @error('license_duration')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 col-12 mb-3">
                                <label for="input35" class=" col-form-label">Features</label>
                                <select name="features[]" id="features" class="form-control selectpicker2" multiple>
                                    <option value="">--Select--</option>
                                    @foreach (App\Models\SecurityFeature::all() as $f)
                                        @if ($f->status == 'active')
                                            <option
                                                value="{{ $f->id }}"{{ in_array($f->id, json_decode($product->features)) ? 'selected' : '' }}>
                                                {{ $f->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="input35" class=" col-form-label">Cloud Backup</label>
                                <input type="number" class="form-control" placeholder="max-100GB" name="cloud_backup"
                                    id="cloud_backup" value="{{ $product->cloud_backup }}" onchange="chkBackup()">
                                @error('cloud_backup')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label for="input35" class=" col-form-label">Number of PCs</label>
                                <input type="number" class="form-control" placeholder="max-20PCs" name="no_of_pc"
                                    id="no_of_pc" value="{{ $product->no_of_pc }}" onkeyup="chkPcs()">
                                @error('no_of_pc')
                                    <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 col-12  mb-3">
                                <label for="input35" class=" col-form-label">Country</label>
                                <div class="">
                                    <select name="country_id" id="country_id" class="form-control selectpicker">
                                        <option value="">-- Select --</option>
                                        @foreach (App\Models\Country::all() as $country)
                                            <option
                                                value="{{ $country->id }}"{{ $country->id == $product->country_id ? 'selected' : '' }}>
                                                {{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="alert alert-danger py-1 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12  mb-3">
                                <label for="input35" class=" col-form-label">Is Enable</label>
                                <div class="">
                                    <select name="status" id="isenable" class="form-control ">
                                        <option value="active"{{ $product->status == 'active' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="inactive"{{ $product->status == 'inactive' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6 col-12 ">
                                <label for="product_type" class="col-form-label">Product Type
                                </label>
                                <select id="product_type" name="product_type" class="form-select">
                                    <option value="personal" {{ $product->product_type == 'personal' ? 'selected' : '' }}>
                                        Personal</option>
                                    <option value="business"{{ $product->product_type == 'business' ? 'selected' : '' }}>
                                        Business</option>
                                    <option
                                        value="large-business"{{ $product->product_type == 'large-business' ? 'selected' : '' }}>
                                        Large Business</option>

                                </select>
                            </div>

                            <div class="col-md-6 col-12  pt-3 mt-3">
                                <label for="upload_image" class="mb-3">

                                    <img src="{{ asset('storage/images/cyber_security/' . $product->image) }}"
                                        id="uploaded_image" class="img img-responsive img-circle" width="100"
                                        alt="Select image" />

                                    <div class="overlay" style="cursor: pointer">
                                        <div>Click to Change Image</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image"
                                        style="display:none" />
                                    <input type="hidden" name="cropped_image" id="cropped_image">

                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            allowedContent: true
        });

        function checkYear() {
            var year = $('#license_duration').val();
            if (year != '') {
                if (year > 2) {
                    alert("Please add minimum 1 year or maximum 2 years");
                    $('#license_duration').val('');
                } else if (year < 1) {
                    alert("Please add minimum 1 or maximum 2 years");
                    $('#license_duration').val('');

                }
            }
        }

        function chkBackup() {
            var bkp = $('#cloud_backup').val();
            if (bkp !== '') {
                if (bkp > 100) {
                    alert("Please enter a value between 10GB and 100GB.");
                    $('#cloud_backup').val('');
                } else if (bkp < 10) {
                    alert("Please enter a value between 10GB and 100GB.");
                    $('#cloud_backup').val('');

                }
            }
        }


        function chkPcs() {
            var pcs = $('#no_of_pc').val();
            if (pcs != '') {
                if (pcs > 20) {
                    alert("Please add minimum 1PC or maximum 20PCs");
                    $('#no_of_pc').val('');
                } else if (pcs < 1) {
                    alert("Please add minimum 1PC or maximum 20PCs");
                    $('#no_of_pc').val('');
                }

            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".selectpicker"), {
                removeItemButton: true
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            new Choices(document.querySelector(".selectpicker2"), {
                removeItemButton: true
            });
        });
    </script>
@endpush
