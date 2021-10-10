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
                            <div class="w-100 text-right">
                                <a href="#batch-products-modal" data-toggle="modal" class="view-btn hover-btn "> <i
                                        class="fas fa-plus"></i> Add Batch
                                    Products</a>
                                <a href="#add-product-modal" data-toggle="modal" class="view-btn hover-btn "> <i
                                        class="fas fa-plus"></i> Add Single
                                    Product</a>
                                <a href="export-excell" class="view-btn hover-btn "> <i class="fas fa-download"></i>
                                    Download Excel Sheet</a>
                            </div>
                        </div>

                        <hr style="margin: 0 !important;">

                        <div class="card-body">
                          
                          <div class="mb-2" style="justify-content: right; display: flex; position: relative; align-items: center">
                            <input class="form-control" type="text" placeholder="Search Products" id="search-input">
                            <i id="cancel-search" onclick="cancelSearch()" class="fas fa-times"></i>
                            <button class="view-btn hover-btn" onclick="searchProducts()">Search</button>
                          </div>

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
                                                <div id="spinner" class="spinner-border spinner-border-sm text-dark"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="w-100 paginate-div">
                              <div class="paginate-item inactive item-prev">
                                <a><i class="fas fa-angle-left"></i> Prev</a>
                              </div>

                              <div class="paginate-list">

                              </div>

                              <div class="paginate-item item-next" onclick="loadMoreProducts()">
                                <a>Next <i class="fas fa-angle-right"></i></a>
                              </div>
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
        @include('vendor.product.batch')
        @include('vendor.product.add')
    @show

    <script>
        lastId = 0;
        fillID = 0;
        page = 1;
        search = false;
        keyword = '';
        forward = true;

        $(document).ready(function() {
            loadProducts();
            loadCategories();
            loadUpdateModals();
            loadViewModals();
            tagFormListeners();
        });

        function searchProducts() {
          $('.paginate-page-item').remove();
          $('.item-number').remove();

          page = 1;

          search = true;
          lastId = 0;
          keyword = $('#search-input').val();
          forward = true;
          
          loadMoreProducts();
        }

        function cancelSearch() {
          $('.paginate-page-item').remove();
          $('.item-number').remove();
          page = 1;
          search = false;
          lastId = 0;
          loadMoreProducts();
        }

        function showPage(p) {
          $('.paginate-page-item').addClass('d-none');
          $('.paginate-page-'+p).removeClass('d-none');
          $('.paginate-item').removeClass('active');
          $('[data-page="'+p+'"]').addClass('active');

          if(p > 1) {
            $('.item-prev').removeClass('inactive').attr('onclick', `showPage(${p-1})`);
          }
          else {
            $('.item-prev').addClass('inactive').removeAttr('onclick');
          }
        }

        function loadMoreProducts() {
            loadProducts();
            loadUpdateModals();
            loadViewModals();
        }

        // Load Products
        function loadProducts() {
            let url = search 
            ? "{{ url('vendor/products/search') }}/" + keyword + '/' + page + '/' + lastId
            : "{{ url('vendor/products/get') }}/" + page + '/' + lastId

            $.ajax({
                    type: "GET",
                    url
                })
                .then(res => {
                    if(res.length > 10) {
                      $('.paginate-page-item').addClass('d-none');
                      (lastId == 0) ? $('#products').html(res): $('#products').append(res);
                      $('.paginate-item').removeClass('active');
                      $('.paginate-list').append(`
                        <div class="paginate-item item-number active" onclick="showPage(${page})" data-page="${page}">
                          <a>${page}</a>
                        </div>
                      `).scrollLeft('10000');

                      if(page > 1) {
                        $('.item-prev').removeClass('inactive').attr('onclick', `showPage(${page-1})`);
                      }
                      page += 1;
                      $('.item-next').removeClass('inactive').attr('onclick', `loadMoreProducts()`)
                    }
                    else {
                      forward = false;
                      $('.item-next').addClass('inactive').removeAttr('onclick');
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
                    $('#bcategory').append(res)
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
            fillID = fillId

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
                .catch(err => {})
        }

        //load 
        function loadBatchForm() {
            let url = "{{ url('vendor/products/batch-form') }}";

            $.ajax({
                    type: "GET",
                    url
                })
                .then(res => {
                    $('#add-form-b').html(res)
                })
                .catch(err => {})
        }

        // Load Update Modals
        function loadUpdateModals() {
            let url = search 
            ? "{{ url('vendor/products/search-modals') }}/" + keyword + '/' + lastId
            : "{{ url('vendor/products/update-modals') }}/" + lastId;

            $.ajax({
                    type: "GET",
                    url
                })
                .then(res => {
                    (lastId == 0) ? $('#update-modals-h').html(res): $('#update-modals-h').append(res)
                })
                .catch(err => {
                    showAlert(false, 'Could not load product edits. Please relaod page')
                })
        }

        // Load View Modals
        function loadViewModals() {
          let url = search 
            ? "{{ url('vendor/products/search-views') }}/" + keyword + '/' + lastId
            : "{{ url('vendor/products/view-modals') }}/" + lastId;

            $.ajax({
                    type: "GET",
                    url
                })
                .then(res => {
                    (lastId == 0) ? $('#view-modals-h').html(res): $('#view-modals-h').append(res)
                })
                .catch(err => {
                    showAlert(false, 'Could not load product views. Please relaod page')
                })
        }

        // Add all event listeners
        function tagFormListeners() {
            addProduct();
            batchProduct();

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
