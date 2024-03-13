@extends('admin.layouts.app')
@section('title','POPTelecom- Newsletter')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            </ol>
        </nav>
    </div>

</div>
<!--end breadcrumb-->
<div class="row">
    <div class="col-12 col-lg-12">
        <h6 class="mb-0 text-uppercase">Newsletter</h6>
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cmspageTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($newsletter)
                            @foreach($newsletter as $val)

                            <tr id="status_{{$val->id}}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$val->name ? $val->name : "NA"}}</td>
                                <td>
                                    <div class="col">
                                        <a title="Preview" href="{{route('admin.newsletter-template-view',$val->id)}}" class="btn1 btn-outline-secondary" target="_blank"><i class="lni lni-eye"></i></a>
                                        <!-- <a title="Edit" href="{{--route('admin.newsletter-template-edit',$val->id)--}}" class="btn1 btn-outline-primary" target="_blank"><i class="lni lni-pencil"></i></a> -->
                                        <!-- <a title="Delete" href="javascript:void(0)" data-id="{{-- $val->id --}}" data-action="{{--route('admin.cmsPages.destroy',$val->id)--}}" class="btn1 btn-outline-danger trash"><i class="bx bx-trash me-0"></i></a> -->
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        var table = $('#cmspageTable').DataTable({
            // lengthChange: false,
            // buttons: ['excel', 'pdf', 'print']
        });

        $("body").on("click", ".trash", function() {
            var current_object = $(this);
            swal({
                title: "Are you sure?",
                text: "It will be Deleted Permanently!",
                type: "success",
                showCancelButton: true,
                dangerMode: true,
                cancelButtonClass: '#DD6B55',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ok!',
            }, function(result) {
                if (result) {
                    var action = current_object.attr('data-action');
                    var token = jQuery('meta[name="csrf-token"]').attr('content');
                    var id = current_object.attr('data-id');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: action,
                        type: 'DELETE',
                        data: {
                            'id': id
                        },
                        success: function(result) {
                            if (result.status) {
                                $("#status_" + id).hide();
                                swal("Nice!", "Deleted Successfully", "success");
                            } else {
                                swal("Error!", "Something went wrong !! Please Try again later", "error");
                            }
                        },
                        error: function(e) {
                            swal("Error!", "Something went wrong !! Please Try again later", "error");
                        }
                    });
                }
            });
        });
    });
</script>
@endpush