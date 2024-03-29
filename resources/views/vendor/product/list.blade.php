@foreach ($products as $key => $product)
<tr class="paginate-page-item paginate-page-{{ $page }}">
  <td><div class="prod-img-sm" id="prod-list-img-{{ $product->id }}" style="background: url('{{ Storage::url('products/'.$product->photo) }}')"></td>
  <td>{{ $product->title }}</td>
  <td>₦{{ number_format($product->price) }}</td>
  <td class="{{ $product->quantity < 10 ? 'text-danger':'' }}">{{ $product->quantity }}</td>
  <td>{{ date('d/m/Y, g:i A', strtotime($product->created_at)) }}</td>
  <td>
    <button onclick="markProduct(this, '{{ $product->id }}')" class="btn btn-sm btn-outline-warning mark-product" data-id="{{ $product->id }}"><i class="fas fa-check-square"></i></button>
    <button class="btn btn-sm btn-outline-success" data-target="#view{{ $product->id }}" data-toggle="modal" title="View Product"><i class="fas fa-eye"></i></button>
    <button class="btn btn-sm btn-outline-primary" title="Update Product" data-toggle="modal" data-target="#update{{ $product->id }}-modal"><i class="fas fa-edit"></i></button>
    <button class="btn btn-sm btn-outline-danger" title="Delete Product" onclick="deleteWarn({{ $product->id }})"><i class="fas fa-trash"></i></button>
  </td>
</tr>

@if($key+1 == count($products))
  <script>
    lastId = `{{ $product->id }}`;
  </script>
@endif

@endforeach