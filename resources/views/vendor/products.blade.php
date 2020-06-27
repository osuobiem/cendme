@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'Dashboard')

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
                    <th>Actions</th>
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
      tagFormListeners()
    });

    // Load Products
    function loadProducts() {
      let url = "{{ url('product/get') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#products').html(res)
          if (!DTInitialized) {
            $('#products-table').DataTable({
              "order": []
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
      let url = "{{ url('category/get') }}";

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
    function loadSubCategories(id) {
      let url = "{{ url('subcategory/get') }}/" + id;

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#asubcategory').html(res)
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
    function loadUpdateModals() {
      let url = "{{ url('product/get-update-modals') }}";

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

    // Add all event listeners
    function tagFormListeners() {
      addProduct();
    }

    // Pick Image
    function pickImage(inputId) {
      $('#' + inputId).click();
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