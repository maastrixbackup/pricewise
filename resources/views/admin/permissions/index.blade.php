@extends('admin.layouts.app')
@section('title','NoPlan- Permissions')
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
	<div class="ms-auto">
		<div class="btn-group">

			@if (Gate::check('permission-create'))
			<a type="button" href="{{route('admin.permissions.create')}}" class="btn btn-primary">
				Create a New Permission
			</a>
			@endif

		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-12 col-lg-12">
		<h6 class="mb-0 text-uppercase">Permissions</h6>
		<hr />
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="permissionTable" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Sl</th>
								<th>Name</th>

								@if(Gate::check('permission-edit') || Gate::check('permission-delete'))
								<th>Action</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@if($permissions)
							@foreach($permissions as $permission)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $permission->name }}</td>
								<td>
									<div class="col">
										@if(Gate::check('permission-edit'))
										<a title="Edit" href="{{route('admin.permissions.edit', $permission->id)}}" class="btn btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
										@endif

										@if(Gate::check('permission-delete'))
										<a title="Delete" class="btn btn-outline-danger trash remove-permission" data-id="{{ $permission->id }}" data-action="{{route('admin.permissions.destroy')}}"><i class="bx bx-trash me-0"></i></a>
										@endif
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
		var table = $('#permissionTable').DataTable({
            lengthChange: false,
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="far fa-file-excel"></i>',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fal fa-file-pdf"></i>',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="far fa-print"></i>',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
            ],
            'columnDefs': [{
                'targets': [2], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        });

		table.buttons().container()
			.appendTo('#permissionTable_wrapper .col-md-6:eq(0)');
	});
</script>

<script type="text/javascript">
	$("body").on("click", ".remove-permission", function() {
		var current_object = $(this);
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this data!",
			type: "error",
			showCancelButton: true,
			dangerMode: true,
			cancelButtonClass: '#DD6B55',
			confirmButtonColor: '#dc3545',
			confirmButtonText: 'Delete!',
		}, function(result) {
			if (result) {
				var action = current_object.attr('data-action');
				var token = jQuery('meta[name="csrf-token"]').attr('content');
				var id = current_object.attr('data-id');

				$('body').html("<form class='form-inline remove-form' method='POST' action='" + action + "'></form>");
				$('body').find('.remove-form').append('<input name="_method" type="hidden" value="post">');
				$('body').find('.remove-form').append('<input name="_token" type="hidden" value="' + token + '">');
				$('body').find('.remove-form').append('<input name="id" type="hidden" value="' + id + '">');
				$('body').find('.remove-form').submit();
			}
		});
	});
</script>
@endpush