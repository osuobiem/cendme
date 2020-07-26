
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
										<input type="text" name="secret_key" class="form-control" value="{{ $cred->paystack_secret_key }}"
											placeholder="Secret Key" required>
										<span class="text-danger error-message" id="secret_key"></span>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group mb-3">
										<label class="form-label">Public Key <span class="text-danger">*</span></label>
										<input type="text" name="public_key" class="form-control" value="{{ $cred->paystack_public_key }}"
											placeholder="Public Key" required>
										<span class="text-danger error-message" id="public_key"></span>
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