@extends('admin.layouts.master')

{{-- Page Title --}}
@section('title', 'My Account')

{{-- Top Bar --}}
@section('topbar')
@include('admin.components.topbar')
@endsection

{{-- Side Bar --}}
@section('sidebar')
@include('admin.components.sidebar')
@endsection

{{-- Main Content --}}
@section('content')

<main>
	<div class="container-fluid">
		<ol class="breadcrumb mt-2 mb-1">
			<li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
			<li class="breadcrumb-item active">My Account</li>
		</ol>
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card card-static-2 mb-30">
					<div class="card-title-2">
						<h4>Update Profile</h4>
					</div>
					<div class="card-body-table">
						<div class="news-content-right pd-20">
							<form class="row" method="POST" id="update-form">
								@csrf
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Name <span class="text-danger">*</span></label>
										<input type="text" name="name" class="form-control" value="{{ $admin->name }}"
											placeholder="Name" required>
										<span class="text-danger error-message" id="name"></span>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Username</label>
										<input type="text" disabled class="form-control" value="{{ $admin->username }}" placeholder="Username">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Password <em>(Leave blank to maintain old password)</em></label>
										<input type="password" class="form-control" id="upass" onkeydown="showPass()"
											placeholder="New Password">
										<span class="text-danger error-message" id="password"></span>
									</div>
								</div>
								<div class="col-lg-12 text-center">
									<button class="save-btn hover-btn" type="submit">
										<span id="btn-txt">Save Changes</span>
										<div id="spinner" style="display: none;" class="spinner-border spinner-border-sm text-light"
											role="status">
											<span class="sr-only">Processing...</span>
										</div>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</main>

<script>

	$(document).ready(function () {
		// Submit update form
		$('#update-form').submit(el => {
			el.preventDefault();

			offError();

			let data = new FormData(el.target)
			let url = "{{ url('admin/update') }}"

			spin()

			$.ajax({
				type: "POST",
				url,
				data,
				processData: false,
				contentType: false,
			})
				.then(res => {
					spin()
					showAlert(true, 'Update Successful')
					setTimeout(() => {
						location.reload();
					}, 1000)
				})
				.catch(err => {
					spin()

					if (err.status === 400) {
						errors = err.responseJSON.message;

						if (typeof errors === "object") {
							for (const [key, value] of Object.entries(errors)) {
								$('#' + key).html('');
								[...value].forEach(m => {
									$('#' + key).append(`<p>${m}</p>`)
								})
							}
						}
						else {
							showAlert(false, errors)
						}
					}

					else {
						showAlert(false, "Oops! Something's not right. Try Again");
					}
				})
		})
	})

	function showPass() {
		if ($('#upass').val.length > 0) {
			$('#upass').attr('name', 'password')
		}
		else {
			$('#upass').removeAttr('name')
		}
	}

	function spin(id = false) {
		if (id) {
			$('#' + id).toggle()
			$('#' + id + 'spinner').toggle()
		}
		else {
			$('#btn-txt').toggle()
			$('#spinner').toggle()
		}
	}

	function offError() {
		$('.error-message').html('')
	}

</script>

@endsection

{{-- Footer --}}
@section('footer')
@include('admin.components.footer')
@endsection