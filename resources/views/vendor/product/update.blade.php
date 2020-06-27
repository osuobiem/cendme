@foreach ($products as $product)

<!-- Update product modal -->
<div class="modal fade" id="update{{ $product->id }}-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">Update Product</h5>
        <button type="button" class="close" id="e{{ $product->id }}-close-modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form class="row justify-content-center" method="POST" id="update{{ $product->id }}-product-form">
          @csrf
          <div class="col-lg-3 col-md-5 text-center">
            <span class="text-danger error-message" id="e{{ $product->id }}-photo"></span>
            <div class="img-style" id="e{{ $product->id }}-photo-fill"
              style="background: url('{{ Storage::url('products/'.$product->photo) }}')"></div>
            <button class="btn btn-outline-secondary mt-2" type="button" id="e{{ $product->id }}-select-img"
              onclick="pickImage('e{{ $product->id }}photo')">
              <i class="fas fa-camera"></i>
              Select Image
            </button>
            <input type="file" accept="image/*" id="e{{ $product->id }}photo" class="d-none" onchange="fillImage(this, 'e{{ $product->id }}-photo-fill')"
              name="photo">
          </div>

          <div class="col-lg-9 col-md-7 row">
            <div class="form-group col-lg-6 col-md-12">
              <label class="form-label">Product Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="title" value="{{ $product->title }}" placeholder="Product Title">
              <span class="text-danger error-message" id="e{{ $product->id }}-title"></span>
            </div>
            <div class="form-group col-lg-3 col-md-6">
              <label class="form-label">Quantity <span class="text-danger">*</span></label>
              <input type="number" class="form-control" value="{{ $product->quantity }}" required name="quantity" placeholder="Quantity">
              <span class="text-danger error-message" id="e{{ $product->id }}-quantity"></span>
            </div>

            <div class="form-group col-lg-3 col-md-6">
              <label class="form-label">Product Price <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text p-1" id="basic-addon1">₦</span>
                </div>
                <input type="number" class="form-control" required value="{{ $product->price }}" name="price" placeholder="Price" aria-label="Price"
                  aria-describedby="basic-addon1">
              </div>
              <span class="text-danger error-message" id="e{{ $product->id }}-price"></span>
            </div>

            <div class="form-group col-lg-6 col-md-6">
              <label class="form-label">Category <span class="text-danger">*</span></label>
              <select class="form-control category" id="e{{ $product->id }}category" required onchange="loadSubCategories(this.value)">
                @foreach ($categories as $category)
                    <option value="{{ base64_encode($category->id) }}" 
                      {{ $category->id == $product->subcategory->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              <span class="text-danger error-message" id="e{{ $product->id }}-category"></span>
            </div>
            <div class="form-group col-lg-6 col-md-6">
              <label class="form-label">Subcategory <span class="text-danger">*</span></label>
              <select name="sub_category" required class="form-control" id="e{{ $product->id }}sub_category">
                @foreach ($subcategories as $subcategory)
                    <option value="{{ base64_encode($subcategory->id) }}" 
                      {{ $subcategory->id == $product->sub_category_id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                @endforeach
              </select>
              <span class="text-danger error-message" id="e{{ $product->id }}-sub_category"></span>
            </div>
            <div class="form-group col-lg-12 col-md-12">
              <label class="form-label">Product Details</label>
              <textarea name="details" class="form-control" rows="5" placeholder="Details of the product">{{ $product->details }}</textarea>
              <span class="text-danger error-message" id="e{{ $product->id }}-details"></span>
            </div>
          </div>

          <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-3 ml-auto mr-auto">
            <button class="btn btn-sign hover-btn" type="submit" id="update-product-btn" type="submit">
              <span class="btn-txt">Update Product</span>
              <div style="display: none;" class="spinner-border spinner-border-sm text-light spin"
                role="status">
                <span class="sr-only">Processing...</span>
              </div>
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endforeach

<script>

  // Submit add product form
  function updateProduct(id) {
    $(`#update${id}-product-form`).submit(el => {
      el.preventDefault();

      offError();

      let data = new FormData(el.target)
      let url = "{{ url('product/update') }}/"+id

      spin()

      $.ajax({
        type: "POST",
        url,
        data,
        processData: false,
        contentType: false,
      })
        .then(res => {
          spin()
          showAlert(true, res.message)

          setTimeout(() => {
            $(`#e${id}-close-modal`).click()
            loadProducts()
          }, 1800)
        })
        .catch(err => {
          spin()

          if (err.status === 400) {
            errors = err.responseJSON.message;

            if (typeof errors === "object") {
              for (const [key, value] of Object.entries(errors)) {
                $(`#e${id}-` + key).html('');
                [...value].forEach(m => {
                  $(`#e${id}-` + key).append(`<p>${m}</p>`)
                })
              }
            }
            else {
              error()
              showAlert(false, errors)
            }
          }

          else {
            error()
            showAlert(false, "Oops! Something's not right. Try Again")
          }
        })
    })
  }

</script>