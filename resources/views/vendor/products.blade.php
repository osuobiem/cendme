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

  {{-- Add Product Modal --}}
  @section('add-product')
  @include('vendor.product.add')
  @show

  <script>
    $(document).ready(function () {
      loadProducts();
      loadCategories();
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
          $('#products-table').DataTable({
            "order": []
          });
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
          $('.categories').html(res)
        })
        .catch(err => {
          showAlert(false, 'An Error Occured!. Please relaod page')
        })
    }
  </script>

</main>
@endsection

{{-- Footer --}}
@section('footer')
@include('vendor.components.footer')
@endsection