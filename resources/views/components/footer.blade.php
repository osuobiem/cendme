<footer class="footer pt-5 b-low">
    <div class="footer-first-row p-0">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-4 mt-2 text-center">
            <p class="m-0" style="color:#424361">Contact us</p>
            <ul class="call-email-alt">
              <li><a href="tel:+234 801 2345 678" class="callemail"><i class="uil uil-phone"></i>+234 801 2345 678</a>
              </li>
              <li><a href="mailto:info@cendme.com" class="callemail"><i
                    class="uil uil-envelope-alt"></i>info@cendme.com</a></li>
            </ul>
          </div>
          <div class="col-md-4 col-sm-4 mt-2">
            <div class="social-links-footer text-center">
              <p class="m-0" style="color:#424361">Follow us on social media</p>
              <ul>
                <li><a href="#"><i class="uil uil-facebook"></i></a></li>
                <li><a href="#"><i class="uil uil-twitter"></i></a></li>
                <li><a href="#"><i class="uil uil-instagram"></i></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4 col-sm-4 mt-2">
            <div class="social-links-footer text-center">
              <p class="m-0" style="color:#424361">Download CendMe Mobile App</p>
              <ul>
                <li><a href="#">
                  <img src="{{ url('assets/images/google.png') }}" alt="Google Play"></a></li>
                <li><a href="#">
                  <img src="{{ url('assets/images/apple.png') }}" alt="Google Play"></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-last-row" style="background: unset">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="footer-bottom-links">
              <ul>
                <li><a href="#">Become a Shopper</a></li>
                <li><a href="{{ url('vendor') }}">Start Selling</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Term & Conditions</a></li>
                <li><a href="#">Refund & Return Policy</a></li>
              </ul>
            </div>
            <div class="copyright-text">
              <i class="uil uil-copyright"></i>{{ date('Y') }} <b>Cendme</b>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      new Splide('#image-slider', {
        cover: true,
        focus: 'center',
        type: 'loop',
        rewind: true,
        autoplay: true,
      }).mount();
    });

    function showMore(status) {
      if (status) {
        $('#more').removeClass('d-none');
        $('#read-m-l').attr('onclick', "showMore(false)");
        $('#read-m-l').text('Less')
      }
      else {
        $('#more').addClass('d-none');
        $('#read-m-l').attr('onclick', "showMore(true)");
        $('#read-m-l').text('Read more')
      }
    }

    function openMenu(el) {
      $('#mobile-men').removeClass('d-none')
      $(el).html('<i class="uil uil-multiply"></i>')
      $(el).attr('onclick', 'closeMenu(this)')
    }

    function closeMenu(el) {
      $('#mobile-men').addClass('d-none')
      $(el).html('<i class="uil uil-bars"></i>')
      $(el).attr('onclick', 'openMenu(this)')
    }
  </script>

  <script src="{{ url('assets/vendor/splide-2.4.4/dist/js/splide.min.js')}}"></script>
  <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/vendor/DataTables/datatables.min.js') }}"></script>
  <script src="{{ url('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
  <script src="{{ url('assets/js/scripts.js') }}"></script>

  <script>
  AOS.init();
  </script>