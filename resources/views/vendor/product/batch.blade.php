<!-- Add batch product modal -->
<div id="batch-form-b">
  <div class="modal fade" id="batch-products-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title ml-auto" id="exampleModalLabel">Add Batch Products</h5>
          <button type="button" class="close" id="b-close-modal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                   <form class="row justify-content-center" id="batch-products-form">
            @csrf
            <div class="col-md-12 row">
              <div class="form-group col-md-4">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-control category" id="bcategory" required onchange="loadSubCategories(this.value, 'bsubcategory')">
                  <option disabled selected>Select Category</option>
                </select>
                <span class="text-danger error-message" id="b-category"></span>
              </div>
              <div class="form-group col-md-4">
                <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                <select name="subcategory" required class="form-control" id="bsubcategory">
                </select>
                <span class="text-danger error-message" id="b-subcategory"></span>
              </div>
              <div class="form-group col-md-4">
                <label class="form-label">Upload Excel File<span class="text-danger">*</span></label>
                <input class="form-control" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file">
                 <span class="text-danger error-message" id="b-file"></span>
              </div>
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-3 ml-auto mr-auto">
              <button class="btn btn-sign hover-btn" type="submit" type="submit">
                <span class="btn-txt">Upload Products</span>
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
  function batchProduct() {
    $('#batch-products-form').submit(el => {
      el.preventDefault();
      offError();
      let data = new FormData(el.target)
      let url = "{{ url('/product/batch_create') }}"
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
            $('#b-close-modal').click()
            loadProducts()
            loadUpdateModals()
            loadViewModals()
            loadBatchForm()
          }, 1800)
        })
        .catch(err => {
          spin()
          if (err.status === 400) {
            errors = err.responseJSON.message;
            if (typeof errors === "object") {
              for (const [key, value] of Object.entries(errors)) {
                $('#b-' + key).html('');
                [...value].forEach(m => {
                  $('#b-' + key).append(`<p>${m}</p>`)
                })
              }
            } else {
              showAlert(false, errors)
            }
          } else {
            showAlert(false, "Oops! Something's not right. Try Again")
          }
        })
    });
  }
</script>