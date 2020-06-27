<!-- Add product modal -->
  <div class="modal fade" id="add-product-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title ml-auto" id="exampleModalLabel">Add Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form class="row justify-content-center" method="POST">
            <div class="col-md-3 text-center">
              <span class="text-danger error-message" id="a-photo"></span>
              <div class="img-style" id="a-photo-fill" style="background: url('{{ Storage::url('products/placeholder.png') }}')"></div>
              <button class="btn btn-outline-secondary mt-2" type="button" id="a-select-img" onclick="pickImage('aphoto')">
                <i class="fas fa-camera"></i>
                Select Image
              </button>
              <input type="file" accept="image/*" id="aphoto" class="d-none" onchange="fillImage(this, 'a-photo-fill')" name="photo">
            </div>

            <div class="col-md-9 row">
              <div class="form-group col-lg-6 col-md-12">
                <label class="form-label">Product Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" placeholder="Product Title">
                <span class="text-danger error-message" id="a-title"></span>
              </div>
              <div class="form-group col-lg-3 col-md-6">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="quantity" placeholder="Quantity">
                <span class="text-danger error-message" id="a-quantity"></span>
              </div>
              <div class="form-group col-lg-3 col-md-6">
                <label class="form-label">Product Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="price" placeholder="Price">
                <span class="text-danger error-message" id="a-price"></span>
              </div>
              <div class="form-group col-lg-6 col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-control category" id="acategory" onchange="loadSubCategories(this.value)">
                  <option disabled selected>Select Category</option>
                </select>
                <span class="text-danger error-message" id="a-category"></span>
              </div>
              <div class="form-group col-lg-6 col-md-6">
                <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                <select name="sub_category" class="form-control" id="asub_category">
                </select>
                <span class="text-danger error-message" id="a-sub_category"></span>
              </div>
              <div class="form-group col-lg-12 col-md-12">
                <label class="form-label">Product Details</label>
                <textarea name="details" class="form-control" rows="5" placeholder="Details of the product"></textarea>
                <span class="text-danger error-message" id="a-details"></span>
              </div>
            </div>

            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-3 ml-auto mr-auto">
              <button class="btn btn-sign hover-btn" type="submit" id="add-product-btn" type="submit">
                <span id="btn-txt">Add Product</span>
                <div id="a-spinner" style="display: none;" class="spinner-border spinner-border-sm text-light"
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