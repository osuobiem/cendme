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
            <div class="col-md-12 text-center mt-4">
              <img src="{{ url('assets/images/cendme-logo.png') }}" id="auth-logo" class="shadow-lg">
            </div>
            <div class="col-xl-5 col-lg-6">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header card-sign-header">
                  <h3 class="text-center font-weight-light mt-4">Login</h3>
                </div>
                <div class="card-body">
                  <div class="alert alert-danger text-center" style="display: none;" role="alert" id="form-error"></div>
                  <div class="alert alert-success text-center" style="display: none;" role="alert" id="form-success">
                  </div>

                  <form id="login-form" method="POST">
                    @csrf
                    <div class="form-group">
                      <label class="form-label" for="inputEmailAddress">Email <span class="text-danger">*</span></label>
                      <input class="form-control py-3" id="inputEmailAddress" name="email" type="email" required
                        placeholder="Enter email address">
                      <span class="text-danger error-message" id="email"></span>
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="inputPassword">Password <span class="text-danger">*</span></label>
                      <input class="form-control py-3" name="password" id="inputPassword" required type="password"
                        placeholder="Enter password">
                      <span class="text-danger error-message" id="password"></span>
                    </div>

                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="rememberPasswordCheck" name="remember_me"
                          type="checkbox" checked />
                        <label class="custom-control-label" for="rememberPasswordCheck">Remember me</label>
                      </div>
                    </div>

                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                      <button class="btn btn-sign hover-btn" type="submit" id="login">
                        <span id="btn-txt">Login</span>
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
      </main>
    </div>
  </div>

  <script>

    $('#login-form').submit(e => {
      e.preventDefault();

      offError();

      let data = new FormData(e.target)
      let url = "{{ url('vendor/p-login') }}"

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
          success()
          $('#form-success').text(res.message)

          setTimeout(() => {
            location.href = "{{ url('vendor') }}";
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
              error()
              $('#form-error').text(errors)
            }
          }

          else {
            error()
            $('#form-error').text("Oops! Something's not right. Try Again")
          }
        })
    })

    function spin() {
      $('#btn-txt').toggle()
      $('#spinner').toggle()
    }

    function error() {
      $('#form-error').addClass('animate__animated animate__headShake')
      $('#form-error').removeAttr('style');
    }

    function success() {
      $('#form-success').addClass('animate__animated animate__fadeIn')
      $('#form-success').removeAttr('style');
    }

    function offError() {
      $('#form-error').attr('style', 'display: none');
      $('.error-message').html('')
    }
  </script>

  @endsection