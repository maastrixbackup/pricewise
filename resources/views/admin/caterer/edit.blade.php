@extends('admin.layouts.app')
@section('title','Pricewise- Caterer Create')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <!-- <div class="breadcrumb-title pe-3">Add New User</div> -->
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.list.caterer')}}">Caterers</a></li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header px-4 py-3">
                <h5 class="mb-0">Edit Caterer</h5>
            </div>
            <div class="card-body p-4">
                <form id="featureForm" method="post" action="{{route('admin.update.caterer',$editcaterer->id)}}">
                    @csrf
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Name</label>
                        <div class="">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Caterer's Name" value="{{$editcaterer->caterer_name}}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Description</label>
                        <div class="">
                            <textarea name="description" class="form-control" id="description" placeholder="Description" rows="5">{{ $editcaterer->description }}</textarea>
                        </div>
                        @error('description')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <label for="input35" class=" col-form-label">Is Enable</label>
                        <div class="">
                            <select name="isenable" id="isenable" class="form-control">
                                <option value="active" {{$editcaterer->status=='active'?'selected':''}}>Yes</option>
                                <option value="inactive" {{$editcaterer->status=='inactive'?'selected':''}}>No</option>
                            </select>
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
