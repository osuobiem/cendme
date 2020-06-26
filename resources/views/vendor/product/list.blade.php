@foreach ($products as $product)

<tr>
  <td></td>
  <td>{{ $product->title }}</td>
  <td>₦{{ $product->price }}</td>
  <td>{{ $product->quantity }}</td>
  <td>{{ date('d/m/Y, g:i A', strtotime($product->created_at)) }}</td>
  <td></td>
</tr>
    
@endforeach