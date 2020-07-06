@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'My Wallet')

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
      <li class="breadcrumb-item active">My Wallet</li>
    </ol>
    <div class="row">
      <div class="col-md-4 mt-2">
        <div class="card card-static-2">
          <div class="card-title-2">
            <h4>Wallet Balance</h4>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body text-center mt-2">
            <h1>₦{{ number_format(Auth::user()->balance) }}</h1>
            <button class="save-btn hover-btn" onclick="withdraw()">Withdraw</button>
          </div>
        </div>
      </div>
      <div class="col-md-8 mt-2">
        <div class="card card-static-2">
          <div class="card-title-2">
            <h4>Transaction History</h4>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body text-center">
            <div class="table-responsive table-striped">
              <table class="table ucp-table" id="products-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Transaction ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody id="products">
                  <tr class="text-center">
                    <td colspan="6">
                      <div id="spinner" class="spinner-border spinner-border-sm text-dark" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Withdraw Modal --}}
  <button id="pop-withdraw" class="d-none" data-toggle="modal" data-target="#withdraw-modal"></button>
   @section('withdraw')
      @include('vendor.account.withdraw')
   @show

  <script>
    function withdraw() {
      balance = parseFloat('{{ Auth::user()->balance }}');
      
      if(balance < 1000) {
        showAlert(false, "You must have atleast ₦1000 in order to withdraw")
      }
      else {
        $('#pop-withdraw').click()
      }
    }

    // Toggle button spinner
    function spin() {
      $('.btn-txt').toggle()
      $('.spin').toggle()
    }

    // Turn off errors
    function offError() {
      $('.error-message').html('')
    }
  </script>
</main>
@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')
@endsection