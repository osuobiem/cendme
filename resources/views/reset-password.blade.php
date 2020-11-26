<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Password Reset | Cendme</title>
  <link href="{{url('assets/css/styles.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/admin-style.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/custom.css')}}" rel="stylesheet">

  <!-- Vendor Stylesheets -->
  <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/animate.min.css')}}" rel="stylesheet">

  <script src="{{ url('assets/js/jquery-3.4.1.min.js') }}"></script>

</head>

<body>

  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-12 text-center mt-5">
                <img src="{{ url('assets/images/cendme-logo.png') }}" id="auth-logo" class="shadow-lg">
              </div>
            <div class="col-xl-5 col-lg-6">
              <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header card-sign-header">
                  <h3 class="text-center font-weight-light mt-2" style="font-size: 24px !important">Reset Password</h3>
                </div>
                <div class="card-body">
                  <div class="alert alert-danger text-center" style="display: none;" role="alert" id="form-error"></div>
                  <div class="alert alert-success text-center" style="display: none;" role="alert" id="form-success">
                  </div>

                  <form id="reset-form" method="POST">
                    @csrf
                    <div class="form-group">
                      <label class="form-label" for="inputEmailAddress">Email <span class="text-danger">*</span></label>
                      <input class="form-control py-3" id="inputEmailAddress" name="email" type="email" required
                        placeholder="Email address">
                        <span class="text-danger error-message" id="email"></span>
                      <small style="font-size: 11px">Please provide the email you registered with. We will send a password reset link to your email.</small>
                    </div>

                    <div class="form-group d-flex align-items-center col-md-7 ml-auto mr-auto justify-content-between mt-4 mb-0">
                      <button class="btn btn-sign hover-btn" type="submit" id="proceed">
                        <span id="btn-txt">Proceed</span>
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

    $('#reset-form').submit(e => {
      e.preventDefault();

      offError();

      let data = new FormData(e.target)
      let url = "{{ url('process-password-reset') }}"

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

          $('#reset-form').html(`
          <div style="padding: 20px; text-align: center">
                <p>A password reset link has been sent to your email. The link will expire {{ date('d/m/Y, g:i A', time()+86400) }}</p>
            </div>
          `)
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

  <div class="alert alert-success top-alert d-none" role="alert" id="success-alert">
  </div>

  <div class="alert alert-danger top-alert d-none" role="alert" id="error-alert">
  </div>

  <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
  <script src="{{ url('assets/js/scripts.js') }}"></script>
</body>

</html>