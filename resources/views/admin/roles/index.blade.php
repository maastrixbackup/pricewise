@extends('admin.layouts.app')
@section('title','NoPlan- Roles')
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

			@if (Gate::check('role-create'))
			<a type="button" href="{{route('admin.roles.create')}}" class="btn btn-primary">
				Create a New Role
			</a>
			@endif

		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-12 col-lg-12">
		<h6 class="mb-0 text-uppercase">Roles</h6>
		<hr />
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="roleTable" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Sl</th>
								<th>Name</th>
								<th>Code</th>

								@if(Gate::check('role-edit') || Gate::check('role-delete'))
								<th>Action</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@if($roles)
							@foreach($roles as $role)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{$role->name}}</td>
								<td>{{$role->code}}</td>
								<td>
									<div class="col">
										@if(Gate::check('role-edit'))
										<a title="Edit" href="{{route('admin.roles.edit', $role->id)}}" class="btn btn-outline-primary"><i class="bx bx-pencil me-0"></i></a>
										@endif

										@if(Gate::check('role-delete'))
										<a title="Delete" class="btn btn-outline-danger trash remove-role" data-id="{{ $role->id }}" data-action="{{route('admin.roles.destroy')}}"><i class="bx bx-trash me-0"></i></a>
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
		var table = $('#roleTable').DataTable({
			lengthChange: false,
			buttons: ['excel', 'pdf', 'print']
		});

		table.buttons().container()
			.appendTo('#roleTable_wrapper .col-md-6:eq(0)');
	});
</script>

<script type="text/javascript">
	$("body").on("click", ".remove-role", function() {
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