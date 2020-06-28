@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'Products')

{{-- Top Bar --}}
@section('topbar')
@include('vendor.components.topbar')
@endsection

{{-- Side Bar --}}
@section('sidebar')
@include('vendor.components.sidebar')
@endsection

{{-- Main Content --}}
@section('content')
<main>
  <div class="container-fluid">
    <ol class="breadcrumb mt-2 mb-1">
      <li class="breadcrumb-item"><a href="{{ url('vendor') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Products</li>
    </ol>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Products</h4>
            <a href="#add-product-modal" data-toggle="modal" class="view-btn hover-btn"> <i class="fas fa-plus"></i> Add
              Product</a>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive table-striped">
              <table class="table ucp-table" id="products-table">
                <thead>
                  <tr>
                    <th>Photo</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Added</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products">
                  <tr class="text-center">
                    <td colspan="6">
                      <div id="spinner" class="spinner-border spinner-border-sm text-dark" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Update Modals Container -->
  <div id="update-modals-h"></div>

  <!-- View Modals Container -->
  <div id="view-modals-h"></div>

  {{-- Add Product Modal --}}
  @section('add-product')
  @include('vendor.product.add')
  @show

  <script>
    DTInitialized = false;

    $(document).ready(function () {
      loadProducts();
      loadCategories();
      loadUpdateModals();
      loadViewModals();
      tagFormListeners()
    });

    // Load Products
    function loadProducts() {
      let url = "{{ url('vendor/products/get') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#products').html(res)
          if (!DTInitialized) {
            order = '{{ $sort }}' == true ? [[3, 'asc']] : [];
            $('#products-table').DataTable({
              "order": order
            });
            DTInitialized = true;
          }
        })
        .catch(err => {
          showAlert(false, 'Could not load products. Please relaod page')
        })
    }

    // Load Product Categories
    function loadCategories() {
      let url = "{{ url('vendor/categories') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#acategory').append(res)
        })
        .catch(err => {
          showAlert(false, 'An Error Occured!. Please relaod page')
        })
    }

    // Load Product SubCategories
    function loadSubCategories(id, container) {
      let url = "{{ url('vendor/subcategories') }}/" + id;

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#' + container).html(res)
        })
        .catch(err => {
          showAlert(false, 'An Error Occured!. Please relaod page')
        })
    }

    // Fill Picked Image in Div
    function fillImage(input, fillId) {
      let img = document.getElementById(fillId)

      if (input.files && input.files[0]) {
        if (input.files[0].size > 5120000) {
          showAlert(false, 'Image size must not be more than 5MB')
        } else if (input.files[0].type.split('/')[0] != 'image') {
          showAlert(false, 'The file is not an image')
        } else {
          var reader = new FileReader();

          reader.onload = e => {
            img.setAttribute('style', "background: url(\"" + e.target.result + "\")")
          }

          reader.readAsDataURL(input.files[0]);
        }
      }
    }

    // Load Update Modals
    function loadAddForm() {
      let url = "{{ url('vendor/products/add-form') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#add-form-h').html(res)
        })
        .catch(err => {
        })
    }

    // Load Update Modals
    function loadUpdateModals() {
      let url = "{{ url('vendor/products/update-modals') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#update-modals-h').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load product edits. Please relaod page')
        })
    }

    // Load View Modals
    function loadViewModals() {
      let url = "{{ url('vendor/products/view-modals') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#view-modals-h').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load product views. Please relaod page')
        })
    }

    // Add all event listeners
    function tagFormListeners() {
      addProduct();
    }

    // Pick Image
    function pickImage(inputId) {
      $('#' + inputId).click();
    }

    // Delete Product Warning
    function deleteWarn(id) {
      swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: [true, "Delete"],
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            deleteProduct(id)
          }
        });
    }

    // Delete Product
    function deleteProduct(id) {
      let url = "{{ url('product/delete') }}/" + id;

      $.ajax({
        type: "DELETE",
        url
      })
        .then(res => {
          showAlert(true, res.message)
          loadProducts()
        })
        .catch(err => {
          showAlert(false, "Oops! Something's not right. Try Again")
        })
    }

    // Toggle button spinner
    function spin() {
      $('.btn-txt').toggle()
      $('.spin').toggle()
    }

    // Turn off errors
    function offError() {
      $('.error-message').html('')
    }


  </script>

</main>
@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')
@endsection