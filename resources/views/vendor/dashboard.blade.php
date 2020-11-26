@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'Dashboard')

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

<style type="text/css">
#pay-qr-code-1 {
  display: inline-block
}
  @media print
  {
  #qr-container { display: block !important }
  }
  </style>

<main>
  <div class="container-fluid">

    <div class="alert alert-danger mt-3 text-center {{ !$ofs_product ? 'd-none' : '' }}" role="alert">
      <span>Some products will soon be <span class="alert-link">out of stock</span>!</span>
      &nbsp;&nbsp;&nbsp;
      <button class="btn btn-outline-dark btn-sm" onclick="ofsProducts()">
        View Now
      </button>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="row mt-3">
      <div class="col-md-8">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Recent Orders</h4>
            <a href="{{ url('vendor/orders') }}" class="view-btn hover-btn">View All</a>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive">
              <table class="table ucp-table table-hover" id="order-table">
                <thead>
                  <tr>
                    <th>Order Reference</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody id="orders-table">
                  <tr class="text-center">
                    <td colspan="4">
                      <div id="order-spinner" class="spinner-border spinner-border-sm text-dark" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div id="order-views"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Payment QR Code</h4>
            <a href="#" onclick="printQR()" class="view-btn hover-btn"> <i class="fas fa-print"></i> Print</a>
          </div>
          <hr style="margin: 0 !important;">
          <div class="card-body">
            <div id="qr-txt"><strong>Scan to pay</strong></div>
            <div class="text-center pr-3"><img src="{{ url('assets/images/cendme-logo-l.png') }}" alt="Cendme.com" style="height: 70px"></div>
            <div id="pay-qr-code"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center d-none mt-5" id="qr-container">
    <div id="qr-txt"><h3><strong>Scan to pay</strong></h3></div>
    <div class="text-center pr-3"><img src="{{ url('assets/images/cendme-logo-l.png') }}" alt="Cendme.com" style="height: 140px"></div>
    <div id="pay-qr-code-1"></div>
  </div>

  <script src="{{ url('assets/vendor/qrcode/qrcode.min.js') }}"></script>

  <script>
    $(document).ready(function () {
      let qrcode = new QRCode("pay-qr-code", {
        text: "{{ Auth::user()->qr_token }}",
        width: 300,
        height: 300,
        colorDark: "#2b2f4c",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });

      new QRCode("pay-qr-code-1", {
        text: "{{ Auth::user()->qr_token }}",
        width: 600,
        height: 600,
        colorDark: "#2b2f4c",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });

      fetchOrders()
      fetchOrderViews()
    });

    function ofsProducts() {
      location.href = "{{ url('vendor/products?sort=true') }}"
    }

    // Print QR Code
    function printQR() {
      $('#qr-container').print()
    }

    // Fetch Orders
    function fetchOrders() {
      url = `{{ url('order/get/10') }}`
      goGet(url)
        .then(res => {
          $('#orders-table').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load orders. Please relaod page')
        })
    }

    // Fetch Order Views
    function fetchOrderViews() {
      url = `{{ url('order/get-view/10') }}`
      goGet(url)
        .then(res => {
          $('#order-views').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load orders. Please relaod page')
        })
    }

    function confirmPayment(order_id) {
		let url = `{{ url('order/confirm-payment') }}/${order_id}`
		goGet(url)
			.then(res => {
				showAlert(true, 'Payment Confirmed')
				$(`#vo-${order_id}`).click()
				fetchOrders()
				fetchOrderViews()
			})
			.catch(err => {
				showAlert(false, 'Could not confirm payment. Please try again')
			})
	}
  </script>
</main>
@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')
@endsection