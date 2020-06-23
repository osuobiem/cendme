@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'Vendor Login')

@section('content')

<body>

  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header card-sign-header">
                  <h3 class="text-center font-weight-light my-4">Login</h3>
                </div>
                <div class="card-body">
                  <form>

                    <div class="form-group">
                      <label class="form-label" for="inputEmailAddress">Email <span class="text-danger">*</span></label>
                      <input class="form-control py-3" id="inputEmailAddress" type="email"
                        placeholder="Enter email address">
                      <span class="text-danger" id="email"></span>
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="inputPassword">Password <span class="text-danger">*</span></label>
                      <input class="form-control py-3" id="inputPassword" type="password" placeholder="Enter password">
                      <span class="text-danger" id="password"></span>
                    </div>

                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" checked />
                        <label class="custom-control-label" for="rememberPasswordCheck">Remember me</label>
                      </div>
                    </div>

                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                      <button class="btn btn-sign hover-btn" id="login">Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  @endsection