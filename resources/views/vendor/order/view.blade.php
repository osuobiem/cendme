@foreach ($orders as $order)

<div class="modal fade" id="view-order{{ $order['id'] }}" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title ml-auto" id="exampleModalLabel">Order Details</h5>
				<button type="button" class="close" id="vo-{{ $order['id'] }}" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<table class="table">
							<tr>
								<td class="text-secondary" style="border-top: none" width="40%"><small>Order Reference:</small></td>
								<td style="border-top: none" colspan="2"><strong><small>{{ $order['ref'] }}</small></strong></td>
							</tr>
							<tr>
								<td class="text-secondary" width="40%"><small>Status:</small></td>
								<td colspan="2"><strong><small
											class="{{ $order['status'] ? 'text-success' : 'text-danger' }}">{{ $order['status'] ? 'Paid' : 'Not Paid' }}</small></strong>
								</td>
							</tr>
							<tr>
								<td class="text-secondary" width="40%"><small>Date:</small></td>
								<td colspan="2"><strong><small>{{ date('d/m/Y, g:i:A', strtotime($order['date'])) }}</small></strong>
								</td>
							</tr>
							<tr>
								<td colspan="3" class="text-center">Products</td>
							</tr>
							<tr>
								<th><small><strong>Title</strong></small></th>
								<th><small><strong>Quantity Puchased</strong></small></th>
								<th><small><strong>Total Price</strong></small></th>
							</tr>

							@foreach ($order['products'] as $product)
							<tr>
								<td><small>{{ $product['title'] }}</small></td>
								<td><small>{{ $product['quantity'] }}</small></td>
								<td><small>₦{{ number_format($product['price']) }}</small></td>
							</tr>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td>
									<strong>₦{{ number_format($order['products_total']) }}</strong></td>
							</tr>

							<tr>
								<td class="text-center" colspan="3">
									<a class="view-btn hover-btn text-light" id="{{ $order['id'] }}-confirm"
										onclick="confirmPayment(`{{ $order['id'] }}`)">Confirm Payment</a>
								</td>
							</tr>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endforeach