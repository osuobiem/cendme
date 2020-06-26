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
<main>
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-md-8">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Recent Orders</h4>
            <a href="orders.html" class="view-btn hover-btn">View All</a>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive">
              <table class="table ucp-table table-hover" id="order-table">
                <thead>
                  <tr>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Payment QR Code</h4>
            <a href="orders.html" class="view-btn hover-btn"> <i class="fas fa-print"></i> Print</a>
          </div>
          <hr style="margin: 0 !important;">
          <div class="card-body">
            <div id="qr-txt"><span>Scan to pay</span></div>
            <div id="pay-qr-code"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ url('assets/vendor/qrcode/qrcode.min.js') }}"></script>

  <script>
    $(document).ready(function () {
      $('#order-table').DataTable({
        "order": [[2, "desc"]]
      });

      let qrcode = new QRCode("pay-qr-code", {
        text: "This code cannot work without Cendme mobile app",
        width: 300,
        height: 300,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });
    });
  </script>
</main>
@endsection