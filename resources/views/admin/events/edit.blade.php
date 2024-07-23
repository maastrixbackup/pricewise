@extends('admin.layouts.app')
@section('title', 'Pricewise - Event Edit')
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
                            href="{{ route('admin.events.index') }}">Events</a></li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <form id="" method="post" action="{{ route('admin.events.update', $objEvent->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-header px-4 py-3">
                        <h5 class="mb-0">Edit Event</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="card-body p-4">
                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Event Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                    value="{{ $objEvent->name }}">
                                <input type="hidden" class="form-control" id="url" name="url" placeholder="Url"
                                    value="{{ $objEvent->slug }}">
                            </div>

                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Event Type</label>
                                <select name="event_type" id="event_type" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach (App\Models\EventType::all() as $ev)
                                        <option value="{{ $ev->id }}"
                                            {{ $objEvent->event_type == $ev->id ? 'selected' : '' }}>{{ $ev->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Caterer</label>
                                <select name="caterer_id" id="caterer_id" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach (App\Models\Caterer::all() as $caterer)
                                        <option value="{{ $caterer->id }}"
                                            {{ $caterer->id == $objEvent->caterer_id ? 'selected' : '' }}>
                                            {{ $caterer->caterer_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Description</label>
                                <textarea type="text" class="form-control" id="description" name="description" placeholder="Description.."
                                    rows="3">{{ $objEvent->description }}</textarea>
                            </div>
                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="Location" value="{{ $objEvent->location }}">
                            </div>
                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Post Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                    placeholder="PIN codes with coma (,) separated"
                                    value="{{ implode(',', json_decode($objEvent->postal_code, true)) }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="input35" class=" col-form-label">House No.</label>
                                    <input type="text" class="form-control" id="house_no" name="house_no"
                                        placeholder="House No." value="{{ $objEvent->house_no }}">
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label for="input35" class=" col-form-label">Room</label>
                                    <select name="room_type" id="room_type" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach (App\Models\EventRoom::all() as $rooms)
                                            <option value="{{ $rooms->id }}"
                                                {{ $rooms->id == $objEvent->room_type ? 'selected' : '' }}>
                                                {{ $rooms->room }}</option>
                                        @endforeach
                                        {{-- <option value="1" {{ $objEvent->room_type == '1' ? 'selected' : '' }}>1 Room</option>
                                        <option value="2" {{ $objEvent->room_type == '2' ? 'selected' : '' }}>2 Room</option>
                                        <option value="3" {{ $objEvent->room_type == '3' ? 'selected' : '' }}>3 Room</option>
                                        <option value="4" {{ $objEvent->room_type == '4' ? 'selected' : '' }}>4 Room</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="input35" class=" col-form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        placeholder="select" value="{{ $objEvent->start_date }}" />
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label for="input35" class=" col-form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        placeholder="End Date" value="{{ $objEvent->end_date }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="input35" class=" col-form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time"
                                        placeholder="Start Time" value="{{ $objEvent->start_time }}">
                                </div>

                                <div class="col-md-6 col-12 mb-3">
                                    <label for="input35" class=" col-form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time"
                                        placeholder="End Time" value="{{ $objEvent->end_time }}">
                                </div>
                            </div>

                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Catering Price</label>
                                <input type="number" class="form-control" id="cateringprice" name="cateringprice"
                                    placeholder="Catering price" value="{{ $objEvent->catering_price }}">
                            </div>

                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Decoration Price</label>
                                <input type="number" class="form-control" id="decorationprice" name="decorationprice"
                                    placeholder="Decoration price" value="{{ $objEvent->decoration_price }}">
                            </div>

                            <div class=" mb-3">
                                <label for="input35" class=" col-form-label">Photoshop Price</label>
                                <input type="number" class="form-control" id="photoshopprice" name="photoshopprice"
                                    placeholder="Photoshop price" value="{{ $objEvent->photoshop_price }}">
                            </div>

                            <div class="row mb-3">
                                <div class="">
                                    <label class=" col-form-label"></label>

                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary px-4"
                                            name="submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="mb-3 form-group">
                            <label for="input40" class="col-form-label"><b>Publish Status</b>
                            </label>
                            <select id="online_status" name="status" class="select2 form-select">

                                <option value="1" {{ $objEvent->status == 1 ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ $objEvent->status == 0 ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="input40" class="col-form-label"><b>State</b>
                            </label>
                            <select id="country_id" name="country_id" class="select2 form-select">
                                <option value="">--Select--</option>
                                <option value=""></option>
                            </select>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            allowedContent: true
        });
    </script>
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
                email: "required",
                phone: "required",
                address: "required",
                passport: "required",
                driving_license: "required"

            },
            messages: {
                name: "Name is missing",
                email: "Email is missing",
                phone: "Phone No. is missing",
                address: "Address is missing",
                passport: "Passport is missing",
                driving_license: "Driving License is missing"
            },
            submitHandler: function(form) {

                var formData = new FormData(form);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: form.action,
                    method: "post",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        //success
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
    <script>
        $("#name").keyup(function() {
            var name_val = $("#name").val();
            $("#url").val(name_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
        $("#name").keydown(function() {
            var name_val = $("#name").val();
            $("#url").val(name_val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        });
    </script>
@endpush
