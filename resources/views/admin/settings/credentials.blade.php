
	<div class="container-fluid">
		<ol class="breadcrumb mt-2 mb-1">
			<li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
			<li class="breadcrumb-item active">Settings</li>
			<li class="breadcrumb-item active">Credentials</li>
		</ol>
		<div class="row">
			<div class="col-md-6">
				<div class="card card-static-2 mb-30">
					<div class="card-title-2">
						<h4>Paystack Credentials</h4>
					</div>
					<div class="card-body-table">
						<div class="news-content-right pd-20">
							<form class="row" method="POST" id="paystack-form">
								@csrf
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Secret Key <span class="text-danger">*</span></label>
										<input type="text" name="secret_key" class="form-control" value="{{ $data[0]->value }}"
											placeholder="Secret Key" redw>
										<span class="text-danger error-message" id="psecret_key"></span>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Public Key <span class="text-danger">*</span></label>
										<input type="text" name="public_key" class="form-control" value="{{ $data[1]->value }}"
											placeholder="Public Key" redw>
										<span class="text-danger error-message" id="ppublic_key"></span>
									</div>
								</div>
								<div class="col-lg-12 text-center">
									<button class="save-btn hover-btn" type="submit">
										<span id="pbtn">Save Changes</span>
										<div id="pbtnspinner" style="display: none;" class="spinner-border spinner-border-sm text-light"
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
      
      <div class="col-md-6">
				<div class="card card-static-2 mb-30">
					<div class="card-title-2">
						<h4>Google Credentials</h4>
					</div>
					<div class="card-body-table">
						<div class="news-content-right pd-20">
							<form class="row" method="POST" id="google-form">
								@csrf
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">API Key <span class="text-danger">*</span></label>
										<input type="text" name="api_key" class="form-control" value="{{ $data[2]->value }}"
											placeholder="API Key" redw>
										<span class="text-danger error-message" id="gapi_key"></span>
									</div>
								</div>
								<div class="col-lg-12 text-center">
									<button class="save-btn hover-btn" type="submit">
										<span id="gbtn">Save Changes</span>
										<div id="gbtnspinner" style="display: none;" class="spinner-border spinner-border-sm text-light"
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
  
  <script>
    // Update Paystack Credentials
    $('#paystack-form').submit(el => {
			el.preventDefault();

			offError();

			let data = new FormData(el.target)

			let url = "{{ url('settings/credentials/paystack') }}"

			spin('pbtn')

			$.ajax({
				type: "POST",
				url,
				data,
				processData: false,
				contentType: false,
			})
				.then(res => {
					spin('pbtn')
					showAlert(true, 'Update Successful')
				})
				.catch(err => {
					spin('pbtn')

					if (err.status === 400) {
						errors = err.responseJSON.message;

						if (typeof errors === "object") {
							for (const [key, value] of Object.entries(errors)) {
								$('#p' + key).html('');
								[...value].forEach(m => {
									$('#p' + key).append(`<p>${m}</p>`)
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
		});

// Update Google Credentials
    $('#google-form').submit(el => {
			el.preventDefault();

			offError();

			let data = new FormData(el.target)

			let url = "{{ url('settings/credentials/google') }}"

			spin('gbtn')

			$.ajax({
				type: "POST",
				url,
				data,
				processData: false,
				contentType: false,
			})
				.then(res => {
					spin('gbtn')
					showAlert(true, 'Update Successful')
				})
				.catch(err => {
					spin('gbtn')

					if (err.status === 400) {
						errors = err.responseJSON.message;

						if (typeof errors === "object") {
							for (const [key, value] of Object.entries(errors)) {
								$('#g' + key).html('');
								[...value].forEach(m => {
									$('#g' + key).append(`<p>${m}</p>`)
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
		});


  </script>