@foreach ($products as $product)

<tr>
  <td><div class="prod-img-sm" style="background: url('{{ Storage::url('products/'.$product->photo) }}')"></td>
  <td>{{ $product->title }}</td>
  <td>₦{{ $product->price }}</td>
  <td>{{ $product->quantity }}</td>
  <td>{{ date('d/m/Y, g:i A', strtotime($product->created_at)) }}</td>
  <td>
    <button class="btn btn-sm btn-outline-success" title="View Product"><i class="fas fa-eye"></i></button>
    <button class="btn btn-sm btn-outline-primary" title="Edit Product"><i class="fas fa-edit"></i></button>
    <button class="btn btn-sm btn-outline-danger" title="Delete Product"><i class="fas fa-trash"></i></button>
  </td>
</tr>
    
@endforeach