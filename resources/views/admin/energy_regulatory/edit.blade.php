@extends('admin.layouts.app')
@section('title', 'Pricewise- Regulatory Edit')
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
                            href="{{ route('admin.energy-regulatory') }}">Energy Regulatory</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header px-4 py-3">
                    <h5 class="mb-0">Upadte Regulatory FAQ</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.update-regulatory') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $regEdit->id }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="appData">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ $regEdit->title }}" placeholder="Title" required>
                                    </td>
                                    <td>
                                        <textarea name="description" id="description" class="form-control" cols="30" rows="5"
                                            placeholder="Description" required>{{ $regEdit->description }}</textarea>
                                    </td>
                                    <td>

                                        <button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <label class=" col-form-label"></label>
                            <div class="">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
