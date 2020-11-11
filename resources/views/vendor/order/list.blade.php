@if(count($orders))

  @foreach ($orders as $order)
  <tr>
	<td>{{ $order['ref'] }}</td>
	<td><strong class="{{ $order['status'] ? 'text-success' : 'text-danger' }}">{{ $order['status'] ? 'Paid' : 'Not Paid' }}</strong></td>
	<td>{{ date('d/m/Y, g:i:A', strtotime($order['date'])) }}</td>
	<td><a class="view-btn hover-btn text-light">View</a></td>
  </tr>
  @endforeach

@else
<tr class="text-center">
	<td colspan="4">
		No Orders Yet!
	</td>
@endif