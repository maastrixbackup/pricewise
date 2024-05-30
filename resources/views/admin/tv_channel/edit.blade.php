@extends('admin.layouts.app')
@section('title', 'Pricewise- Tv Channel Edit')
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
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.tv-channel.index') }}">Tv Channels</a>
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
                    <h5 class="mb-0">Add New Tv Channel</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.tv-channel.update',$channel->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Channel Name<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="text" class="form-control" name="channel_name" value="{{$channel->channel_name}}" placeholder="Channel Name">
                                @error('channel_name')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Description</label>
                            <div class="">
                                <textarea name="description" class="form-control" cols="30" rows="5">{{$channel->description}}</textarea>
                                @error('description')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Price<sup class="text-danger">*</sup></label>
                            <div class="">
                                <input type="number" class="form-control" name="price" value="{{$channel->price}}" placeholder="Price">
                                @error('price')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="input_type" class=" col-form-label">Type<sup class="text-danger">*</sup></label>
                            <div class="">
                                <select class="form-control" name="type">
                                    <option value="">Select</option>
                                    <option value="HD" {{$channel->type == "HD" ? "selected" : ""}}>HD</option>
                                    <option value="NORMAL" {{$channel->type == "NORMAL" ? "selected" : ""}}>NORMAL</option>
                                </select>
                                @error('type')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="">
                                <label for="input_type" class=" col-form-label">Features</label>
                            </div>
                           @if (isset($channel->features))
                               @php
                                $features  = json_decode($channel->features);
                               @endphp
                             
                                   <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{in_array("At home via TV App", $features) ? 'checked' : ''}} name="features[]" value="At home via TV App">
                                    <label class="form-check-label">At home via TV App</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{in_array("Everywhere via TV App", $features) ? 'checked' : ''}} name="features[]" value="Everywhere via TV App">
                                    <label class="form-check-label" >Everywhere via TV App</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{in_array("Missed start", $features) ? 'checked' : ''}} name="features[]"  value="Missed start">
                                    <label class="form-check-label">Missed start</label>
                                  </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="features[]" {{in_array("Missed programme", $features) ? 'checked' : ''}} value="Missed programme">
                                    <label class="form-check-label">Missed programme</label>
                                  </div>
                            @else
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"  name="features[]" value="At home via TV App">
                                    <label class="form-check-label">At home via TV App</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="Everywhere via TV App">
                                    <label class="form-check-label" >Everywhere via TV App</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="features[]"  value="Missed start">
                                    <label class="form-check-label">Missed start</label>
                                  </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="Missed programme">
                                    <label class="form-check-label">Missed programme</label>
                                  </div>
                           @endif
                        
                            
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
