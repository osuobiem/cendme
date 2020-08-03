<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Vendor Sign Up | Cendme</title>
  <link href="{{url('assets/css/styles.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/admin-style.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/custom.css')}}" rel="stylesheet">

  <!-- Vendor Stylesheets -->
  <link href="{{url('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/vendor/DataTables/datatables.min.css')}}" rel="stylesheet">
  <link href="{{url('assets/css/animate.min.css')}}" rel="stylesheet">

  <script src="{{ url('assets/js/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ url('assets/js/custom.js') }}"></script>

</head>

<body>

  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-12 mt-3 text-center">
              <a href="{{ url('') }}" class="btn btn-sign hover-btn" style="width: fit-content !important;">
                <span><i class="fa fa-arrow-left"></i> Home</span>
              </a>
            </div>
            <div class="col-lg-6">
              <div class="card shadow-lg border-0 rounded-lg mt-3">
                <div class="card-header card-sign-header">
                  <h3 class="text-center font-weight-light mt-4">Start selling today</h3>
                </div>
                <div class="card-body">
                  <div class="alert alert-danger text-center" style="display: none;" role="alert" id="form-error"></div>
                  <div class="alert alert-success text-center" style="display: none;" role="alert" id="form-success">
                  </div>

                  <form id="sign-up-form" method="POST">
                    @csrf
                    <div class="row">
                      <div class="form-group col-lg-12">
                        <label class="form-label" for="inputEmailAddress">Business Name <span
                            class="text-danger">*</span></label>
                        <input class="form-control py-3" name="business_name" type="text" required
                          placeholder="Business name">
                        <span class="text-danger error-message" id="business_name"></span>
                      </div>
                      <div class="form-group col-lg-6">
                        <label class="form-label" for="inputEmailAddress">Email <span
                            class="text-danger">*</span></label>
                        <input class="form-control py-3" name="email" type="email" required placeholder="Email address">
                        <span class="text-danger error-message" id="email"></span>
                      </div>

                      <div class="form-group col-lg-6">
                        <label class="form-label" for="inputEmailAddress">Phone Number <span
                            class="text-danger">*</span></label>
                        <input class="form-control py-3" name="phone" type="text" required placeholder="Phone number">
                        <span class="text-danger error-message" id="phone"></span>
                      </div>
                      <div class="form-group col-lg-6">
                        <label class="form-label" for="inputEmailAddress">State <span
                            class="text-danger">*</span></label>
                        <select id="state" class="form-control" onchange="loadAreas(this.value)">
                          <option disabled selected>Select State</option>
                          @foreach($states as $state)
                          <option value="{{ base64_encode($state->id) }}">{{ $state->name }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group col-lg-6">
                        <label class="form-label" for="inputEmailAddress">Area <span class="text-danger">*</span></label>
                        <select id="sarea" class="form-control" name="area">
                        </select>
                        <span class="text-danger error-message" id="area"></span>
                      </div>

                      <div class="form-group col-lg-12">
                        <label class="form-label" for="inputPassword">Password <span
                            class="text-danger">*</span></label>
                        <input class="form-control py-3" name="password" id="inputPassword" required type="password"
                          placeholder="Password">
                        <span class="text-danger error-message" id="password"></span>
                      </div>

                      <div class="form-group col-lg-12">
                        <label class="form-label" for="inputPassword">Address <span class="text-danger">*</span></label>
                        <textarea name="address" placeholder="Address" class="form-control" rows="4"
                          required></textarea>
                        <span class="text-danger error-message" id="address"></span>
                      </div>

                      <div
                        class="form-group col-md-6 ml-auto mr-auto d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button class="btn btn-sign hover-btn" type="submit" id="sign-up">
                          <span id="btn-txt">Sign up</span>
                          <div id="spinner" style="display: none;" class="spinner-border spinner-border-sm text-light"
                            role="status">
                            <span class="sr-only">Processing...</span>
                          </div>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="text-center mt-3 mb-4">
                <span style="font-size: 14px">Do you have an account? <a class="breadcrumb-item active"
                    href="{{ url('vendor/login') }}">Login</a> here</span>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>

    $('#sign-up-form').submit(e => {
      e.preventDefault();

      offError();

      let data = new FormData(e.target)
      let url = "{{ url('vendor/p-signup') }}"

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

    // Load Areas
    function loadAreas(id) {
      let url = "{{ url('vendor/areas') }}/" + id + '/true';

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#sarea').html(res)
        })
        .catch(err => {
          showAlert(false, 'An Error Occured!. Please relaod page')
        })
    }
  </script>

  <div class="alert alert-success top-alert d-none" role="alert" id="success-alert">
  </div>

  <div class="alert alert-danger top-alert d-none" role="alert" id="error-alert">
  </div>

  <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/vendor/DataTables/datatables.min.js') }}"></script>
  <script src="{{ url('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
  <script src="{{ url('assets/js/scripts.js') }}"></script>
</body>

</html>