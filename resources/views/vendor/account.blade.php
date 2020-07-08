@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'My Account')

{{-- Top Bar --}}
@section('topbar')
@include('vendor.components.topbar')
@endsection

{{-- Side Bar --}}
@section('sidebar')
@include('vendor.components.sidebar')
@endsection

{{-- Main Content --}}
@section('content')

<main>
	<div class="container-fluid">
		<ol class="breadcrumb mt-2 mb-1">
			<li class="breadcrumb-item"><a href="{{ url('vendor') }}">Dashboard</a></li>
			<li class="breadcrumb-item active">My Account</li>
		</ol>
		<div class="row">

			<div class="col-md-5">
				<div class="col-md-12 p-0">
					<div class="card card-static-2 mb-30">
						<div class="card-body-table">
							<div class="shopowner-content-left text-center pd-20">
								<div class="shop_img mb-3">
									<div class="vendor-img-md" id="u-photo-fill"
										style="background: url('{{ Storage::url('vendors/'.$vendor->photo) }}')">
										<button class="btn btn-success btn-sm vendor-img-btn" onclick="pickImage('photo')"><i
												class="fas fa-pen"></i></button>
									</div>
								</div>
								<form method="POST" id="photo-form">
									@csrf
									<input type="file" accept="image/*" name="photo" class="d-none" id="photo"
										onchange="fillImage(this, 'u-photo-fill')">
								</form>
								<div class="shopowner-dt-left">
									<h4>{{ $vendor->business_name }}</h4>
								</div>
								<div class="shopowner-dts">
									<div class="shopowner-dt-list">
										<span class="left-dt">Email</span>
										<span class="right-dt">{{ $vendor->email }}</span>
									</div>
									<div class="shopowner-dt-list">
										<span class="left-dt">Phone</span>
										<span class="right-dt">{{ $vendor->phone }}</span>
									</div>
									<div class="shopowner-dt-list">
										<span class="left-dt">Address</span>
										<span class="right-dt">{{ $vendor->address }}<br>{{ $vendor->lga->name }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 p-0">
					<div class="card card-static-2 mb-30">
						<div class="card-title-2">
							<h4>Bank Details</h4>
						</div>
						<div class="card-body-table">
							<div class="news-content-right pd-20">
								<form class="row" method="POST" id="bank-form">
									@csrf
									<div class="col-lg-12">
										<div class="form-group mb-3">
											<label class="form-label">Account Name <span class="text-danger">*</span></label>
											<input type="text" name="account_name" class="form-control"
												value="{{ Auth::user()->account->account_name }}" placeholder="Account Name" required>
											<span class="text-danger error-message" id="account_name"></span>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group mb-3">
											<label class="form-label">Account Number <span class="text-danger">*</span></label>
											<input type="text" name="account_number" class="form-control"
												value="{{ Auth::user()->account->account_number }}" placeholder="Account Number" required>
											<span class="text-danger error-message" id="account_number"></span>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group mb-3">
											<label class="form-label">Bank <span class="text-danger">*</span></label>
											<select class="form-control" name="bank" required>
												@foreach($banks as $bank)
												<option value="{{ $bank->id }}"
													{{ Auth::user()->account->bank_id == $bank->id ? 'selected' : ''}}>{{ $bank->name }}</option>
												@endforeach
											</select>
											<span class="text-danger error-message" id="bank"></span>
										</div>
									</div>
									<div class="col-lg-12">
										<button class="save-btn hover-btn" type="submit">
											<span id="bank-btn">Save Changes</span>
											<div id="bank-btnspinner" style="display: none;"
												class="spinner-border spinner-border-sm text-light" role="status">
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
			<div class="col-md-7">
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
										<label class="form-label">Business Name <span class="text-danger">*</span></label>
										<input type="text" name="business_name" class="form-control" value="{{ $vendor->business_name }}"
											placeholder="Business Name" required>
										<span class="text-danger error-message" id="business_name"></span>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label">Email</label>
										<input type="email" disabled class="form-control" value="{{ $vendor->email }}" placeholder="Email">
										<span class="text-danger error-message" id="email"></span>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label">Phone Number <span class="text-danger">*</span></label>
										<input type="phone" name="phone" class="form-control" value="{{ $vendor->phone }}"
											placeholder="Phone Number" required>
										<span class="text-danger error-message" id="phone"></span>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label">State <span class="text-danger">*</span></label>
										<select class="form-control" id="ustate" required onchange="loadLgas(this.value)">
											@foreach($states as $state)
											<option value="{{ base64_encode($state->id) }}"
												{{ $state->id == $vendor->lga->state_id ? 'selected' : '' }}>{{ $state->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group mb-3">
										<label class="form-label">LGA <span class="text-danger">*</span></label>
										<select class="form-control" id="ulga" name="lga" required>
											@foreach($lgas as $lga)
											<option value="{{ $lga->id }}" {{ $lga->id == $vendor->lga->id ? 'selected' : '' }}>
												{{ $lga->name }}
											</option>
											@endforeach
										</select>
										<span class="text-danger error-message" id="lga"></span>
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
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Address <span class="text-danger">*</span></label>
										<textarea class="form-control" rows="4" placeholder="Address" name="address"
											required>{{ $vendor->address }}</textarea>
										<span class="text-danger error-message" id="address"></span>
									</div>
								</div>
								<div class="col-lg-12">
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
			let url = "{{ url('vendor/update/'.base64_encode($vendor->id)) }}"

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

		// Submit Bank Form
		$('#bank-form').submit(el => {
			el.preventDefault();

			offError();

			let data = new FormData(el.target)
			let url = "{{ url('vendor/update-bank-details/'.base64_encode($vendor->id)) }}"

			spin('bank-btn')

			$.ajax({
				type: "POST",
				url,
				data,
				processData: false,
				contentType: false,
			})
				.then(res => {
					spin('bank-btn')
					showAlert(true, 'Update Successful')
				})
				.catch(err => {
					spin('bank-btn')

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

		// Submit photo form
		$('#photo-form').submit(el => {
			el.preventDefault()

			let data = new FormData(el.target)
			let url = "{{ url('vendor/update-photo/'.base64_encode($vendor->id)) }}"

			$.ajax({
				type: "POST",
				url,
				data,
				processData: false,
				contentType: false,
			})
				.then(res => {
					showAlert(true, 'Photo Updated Successfully')
				})
				.catch(err => {
					showAlert(false, "Oops! Something's not right. Try Again");
				})
		})
	})

	// Pick Image
	function pickImage(inputId) {
		$('#' + inputId).click();
	}

	// Fill Picked Image in Div
	function fillImage(input, fillId) {
		let img = document.getElementById(fillId)

		if (input.files && input.files[0]) {
			if (input.files[0].size > 5120000) {
				showAlert(false, 'Image size must not be more than 5MB')
			} else if (input.files[0].type.split('/')[0] != 'image') {
				showAlert(false, 'The file is not an image')
			} else {
				var reader = new FileReader();

				reader.onload = e => {
					img.setAttribute('style', "background: url(\"" + e.target.result + "\")")
				}

				reader.readAsDataURL(input.files[0]);
				$('#photo-form').submit();
			}
		}
	}

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

	// Load Lgas
	function loadLgas(id) {
		let url = "{{ url('vendor/lgas') }}/" + id;

		$.ajax({
			type: "GET",
			url
		})
			.then(res => {
				$('#ulga').html(res)
			})
			.catch(err => {
				showAlert(false, 'An Error Occured!. Please relaod page')
			})
	}
</script>

@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')
@endsection