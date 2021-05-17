@foreach ($products as $product)
<!-- Update product modal -->
<div class="modal fade" id="view{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">{{ $product->title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="img-style-view" style="background: url('{{ Storage::url('products/'.$product->photo) }}')"></div>
          </div>
          <div class="col-md-12 mt-3">
            <table class="table">
              <tr>
                <td class="text-secondary" width="50%">Title:</td>
                <td>{{ $product->title }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Price:</td>
                <td>₦{{ number_format($product->price) }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Quantity in stock:</td>
                <td class="{{ $product->quantity < 10 ? 'text-danger':'' }}">{{ $product->quantity }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Subcategory:</td>
                <td>{{ $product->subcategory->name }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Added:</td>
                <td>{{ date('d/m/Y, g:i A', strtotime($product->created_at)) }}</td>
              </tr>
              @if(strlen($product->details) > 0)
              <tr>
                <td colspan="2">{{ $product->details }}</td>
              </tr>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach