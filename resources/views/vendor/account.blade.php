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
								<div class="card card-static-2 mb-30">
									<div class="card-body-table">
										<div class="shopowner-content-left text-center pd-20">
											<div class="shop_img mb-3">
                        <div class="vendor-img-md" style="background: url('{{ Storage::url('vendor/'.Auth::user()->photo) }}')">
                          <button class="btn btn-success btn-sm vendor-img-btn"><i class="fas fa-pen"></i></button>
                        </div>
											</div>
											<div class="shopowner-dt-left">
												<h4>{{ Auth::user()->business_name }}</h4>
											</div>
											<div class="shopowner-dts">
												<div class="shopowner-dt-list">
													<span class="left-dt">Email</span>
													<span class="right-dt">{{ Auth::user()->email }}</span>
												</div>
												<div class="shopowner-dt-list">
													<span class="left-dt">Phone</span>
													<span class="right-dt">{{ Auth::user()->phone }}</span>
												</div>
												<div class="shopowner-dt-list">
													<span class="left-dt">Address</span>
													<span class="right-dt">{{ Auth::user()->address }}<br>{{ Auth::user()->lga->name }}</span>
												</div>
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
											<div class="row">
												<div class="col-lg-12">
													<div class="form-group mb-3">
														<label class="form-label">Business Name <span class="text-danger">*</span></label>
														<input type="text" class="form-control" value="{{ Auth::user()->business_name }}" placeholder="Enter First Name">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group mb-3">
														<label class="form-label">Email <span class="text-danger">*</span></label>
														<input type="text" class="form-control" value="Supermarket" placeholder="Enter Last Name">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group mb-3">
														<label class="form-label">Phone Number <span class="text-danger">*</span></label>
														<input type="email" class="form-control" value="gambol943@gmail.com" placeholder="Enter Email Address">
													</div>
                        </div>
                        <div class="col-lg-12">
												<div class="form-group mb-3">
														<label class="form-label">Password <em>(Leave blank to maintain old password)</em></label>
														<input type="text" class="form-control" value="{{ Auth::user()->business_name }}" placeholder="Enter First Name">
                          </div>
                        </div>
												<div class="col-lg-12">
													<div class="form-group mb-3">
														<label class="form-label">Address <em>(Optional)</em></label>
														<textarea class="text-control" placeholder="">Ludhiana, Punjab</textarea>														
													</div>
												</div>
												<div class="col-lg-12">
													<button class="save-btn hover-btn" type="submit">Save Changes</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

    </div>
  </div>
</main>

@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')
@endsection