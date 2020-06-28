<!-- Add product modal -->
<div id="add-form-h">
  <div class="modal fade" id="add-product-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" id="a-close-modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form class="row justify-content-center" method="POST" id="add-product-form">
          @csrf
          <div class="col-lg-3 col-md-5 text-center">
            <span class="text-danger error-message" id="a-photo"></span>
            <div class="img-style" id="a-photo-fill"
              style="background: url('{{ Storage::url('products/placeholder.png') }}')"></div>
            <button class="btn btn-outline-secondary mt-2" type="button" id="a-select-img"
              onclick="pickImage('aphoto')">
              <i class="fas fa-camera"></i>
              Select Image
            </button>
            <input type="file" accept="image/*" id="aphoto" class="d-none" onchange="fillImage(this, 'a-photo-fill')"
              name="photo">
          </div>

          <div class="col-lg-9 col-md-7 row">
            <div class="form-group col-lg-6 col-md-12">
              <label class="form-label">Product Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="title" placeholder="Product Title">
              <span class="text-danger error-message" id="a-title"></span>
            </div>
            <div class="form-group col-lg-3 col-md-6">
              <label class="form-label">Quantity <span class="text-danger">*</span></label>
              <input type="number" class="form-control" required name="quantity" placeholder="Quantity">
              <span class="text-danger error-message" id="a-quantity"></span>
            </div>

            <div class="form-group col-lg-3 col-md-6">
              <label class="form-label">Product Price <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text p-1" id="basic-addon1">₦</span>
                </div>
                <input type="number" class="form-control" required name="price" placeholder="Price" aria-label="Price"
                  aria-describedby="basic-addon1">
              </div>
              <span class="text-danger error-message" id="a-price"></span>
            </div>

            <div class="form-group col-lg-6 col-md-6">
              <label class="form-label">Category <span class="text-danger">*</span></label>
              <select class="form-control category" id="acategory" required
                onchange="loadSubCategories(this.value, 'asubcategory')">
                <option disabled selected>Select Category</option>
              </select>
              <span class="text-danger error-message" id="a-category"></span>
            </div>
            <div class="form-group col-lg-6 col-md-6">
              <label class="form-label">Subcategory <span class="text-danger">*</span></label>
              <select name="subcategory" required class="form-control" id="asubcategory">
              </select>
              <span class="text-danger error-message" id="a-subcategory"></span>
            </div>
            <div class="form-group col-lg-12 col-md-12">
              <label class="form-label">Product Details</label>
              <textarea name="details" class="form-control" rows="5" placeholder="Details of the product"></textarea>
              <span class="text-danger error-message" id="a-details"></span>
            </div>
          </div>

          <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-3 ml-auto mr-auto">
            <button class="btn btn-sign hover-btn" type="submit" id="add-product-btn" type="submit">
              <span class="btn-txt">Add Product</span>
              <div style="display: none;" class="spinner-border spinner-border-sm text-light spin" role="status">
                <span class="sr-only">Processing...</span>
              </div>
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
</div>

<script>

  // Submit add product form
  function addProduct() {
    $('#add-product-form').submit(el => {
      el.preventDefault();

      offError();

      let data = new FormData(el.target)
      let url = "{{ url('product/create') }}"

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
            $('#a-close-modal').click()
            loadProducts()
          }, 1800)
          
          setTimeout(() => {
            loadUpdateModals()
            loadViewModals()
            loadAddForm()
          }, 2000)
        })
        .catch(err => {
          spin()

          if (err.status === 400) {
            errors = err.responseJSON.message;

            if (typeof errors === "object") {
              for (const [key, value] of Object.entries(errors)) {
                $('#a-' + key).html('');
                [...value].forEach(m => {
                  $('#a-' + key).append(`<p>${m}</p>`)
                })
              }
            }
            else {
              showAlert(false, errors)
            }
          }

          else {
            showAlert(false, "Oops! Something's not right. Try Again")
          }
        })
    })
  }

</script>