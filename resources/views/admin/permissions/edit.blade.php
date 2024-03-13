@extends('admin.layouts.app')
@section('title','NoPlan- Permission Edit')
@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.permissions.index')}}">Permissions</a></li>
			</ol>
		</nav>
	</div>

</div>
<!--end breadcrumb-->
<div class="row">

	<form id="permissionForm" method="post" action="{{ route('admin.permissions.update', $permissions->id) }}">
		@csrf
		<div class="col-12 col-lg-8">
			<div class="card">
				<div class="card-header px-4 py-3">
					<h5 class="mb-0">Edit New Permission</h5>
				</div>
				<div class="card-body p-4">
					<div class="row mb-3">
						<label for="input35" class="col-sm-3 col-form-label">Name</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" id="name" class="form-control @error('name') form-control-error @enderror" placeholder="Permission name" value="{{$permissions->name}}" required>

							@error('name')
							<span class="text-danger">{{ $message }}</span>
							@enderror
						</div>
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