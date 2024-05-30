@extends('admin.layouts.app')
@section('title', 'Pricewise- Combo Edit')

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
                            href="{{ route('admin.combos.index') }}">Combos</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Combo Edit</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.combos.update',$combo->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Title<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="title" value="{{$combo->title}}"
                                    placeholder="Title">
                                @error('title')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Image<sup class="text-danger">*</sup></label>
                            <div class="">
                                <label for="upload_image">
                                    <img src="{{asset('combo_images/'.$combo->image)}}" id="uploaded_image" class="img img-responsive img-circle" width="100" alt="Select image" />
        
                                    <div class="overlay">
                                        <div>Click to Change Image</div>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                    <input type="hidden" name="cropped_image" id="cropped_image">
        
                                </label>
                                @error('image')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Category<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="category">
                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $combo->category == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                      
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Price<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="number" class="form-control" name="price" value="{{ $combo->price }}"
                                    placeholder="Price">
                                @error('price')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

