@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'Orders')

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
	  <li class="breadcrumb-item active">Orders</li>
	</ol>

	<div class="row">
	  <div class="col-md-12">
		<div class="card card-static-2 mb-30">
		  <div class="card-title-2">
			<h4>Orders</h4>
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
	</div>
  </div>
</main>


@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')

@push('scripts')
	<script>

		$(document).ready(function () {
			fetchOrders()
			fetchOrderViews()
		});

		function fetchOrders() {
			url = `{{ url('order/get') }}`
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
      url = `{{ url('order/get-view') }}`
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
@endpush

@endsection