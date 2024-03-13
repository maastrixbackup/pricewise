@extends('admin.layouts.app')
@section('title','NoPlan- Roles Edit')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.roles.index')}}">Roles</a></li>
			</ol>
		</nav>
	</div>

</div>
<!--end breadcrumb-->
<div class="row">

	<form id="roleForm" method="post" action="{{ route('admin.roles.update', $role->id) }}">
		@csrf
		<div class="col-12 col-lg-8">
			<div class="card">
				<div class="card-header px-4 py-3">
					<h5 class="mb-0">Edit Role</h5>
				</div>
				<div class="card-body p-4">
					<div class="row mb-3">
						<label for="input35" class="col-sm-3 col-form-label">Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" id="name" class="form-control @error('name') form-control-error @enderror" placeholder="Enter role name" value="{{ $role->name }}" required>

							@error('name')
							<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
					</div>

					<div class="row mb-3">
						<label for="input35" class="col-sm-3 col-form-label">Code</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="code" id="code" class="form-control @error('code') form-control-error @enderror" placeholder="Enter code" value="{{ $role->code }}" required>

							@error('code')
							<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="col-xl-12 check-list-edit">
			<div class="card">
				<div class="card-header px-4 py-3">
					<h5 class="mb-0">Permissions</h5>
				</div>
				<div class="card-body p-4">
					<div class="form-group mb-3">
						@error('permission')
						<span class="text-danger">{{ $message }}</span>
						@enderror

						<div class="form-check mt-2">
							<input class="form-check-input" type="checkbox" id="checkPermissionAll" value="1"> All
							<label class="form-check-label" for="flexCheckDefault" style="margin-left:20px;">
							</label>
						</div>
						<hr>
						<div class="col-md-12  form-check mt-2">
							<ul  style="list-style:none">
								@foreach ($permissions as $permission)
								<li>


									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="permission[]" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}  value="{{ $permission->id }}">
										<label class="form-check-label" for="flexCheckDefault" style="margin-left:25px;">{{ $permission->name }}
										</label>
									</div>
								</li>
								@endforeach
							</ul>
						</div>

						<div class="row">
							<label class="col-sm-3 col-form-label"></label>
							<div class="col-sm-9">
								<div class="d-md-flex d-grid align-items-center gap-3">
									<button type="submit" class="btn btn-primary px-4" name="submit2">Update</button>
									
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</form>
	@endsection
	@push('scripts')
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