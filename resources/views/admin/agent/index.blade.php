@extends('admin.layouts.master')

{{-- Page Title --}}
@section('title', 'Shoppers')

{{-- Top Bar --}}
@section('topbar')
@include('admin.components.topbar')
@endsection

{{-- Side Bar --}}
@section('sidebar')
@include('admin.components.sidebar')
@endsection

{{-- Main Content --}}
@section('content')
<main>
  <div class="container-fluid">
    <ol class="breadcrumb mt-2 mb-1">
      <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Shoppers</li>
    </ol>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Shoppers</h4>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive table-striped">
              <table class="table ucp-table" id="agents-table">
                <thead>
                  <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Verified</th>
                    <th>Joined</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="agents">
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

<div id="agent-modals-h"></div>

  <!-- Update Modals Container -->
  <div id="update-modals-h"></div>

  <!-- View Modals Container -->
  <div id="view-modals-h"></div>

  
  <script>
    DTInitialized = false;

    $(document).ready(function () {
      loadAgents();
      loadViewModals();
      loadAssignShopperModals();
    });

    // Load Agents
    function loadAgents() {
      let url = "{{ url('admin/agents/get') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#agents').html(res)
          if (!DTInitialized) {
            $('#agents-table').DataTable({
              "order": [[5, 'desc']]
            });
            DTInitialized = true;
          }
        })
        .catch(err => {
          showAlert(false, 'Could not load shoppers. Please relaod page')
        })
    }

    // Load View Modals
    function  loadAssignShopperModals() {
      let url = "{{ url('admin/agents/assign-modals') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#agent-modals-h').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load shopper views. Please relaod page')
        })
    }

       // Delete Agent Warning
    function deleteWarn(id) {
      swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: [true, "Delete"],
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            deleteAgent(id)
          }
        });
    }

      // Load View Modals
    function loadViewModals() {
      let url = "{{ url('admin/agents/view-modals') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#view-modals-h').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load shopper views. Please relaod page')
        })
    }

       // Delete Agent Warning
    function deleteWarn(id) {
      swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: [true, "Delete"],
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            deleteAgent(id)
          }
        });
    }

    // Delete Agent
    function deleteAgent(id) {
      let url = "{{ url('admin/agents/delete') }}/" + id;

      $.ajax({
        type: "DELETE",
        url
      })
        .then(res => {
          showAlert(true, res.message)
          loadAgents()
        })
        .catch(err => {
          showAlert(false, "Oops! Something's not right. Try Again")
        })
    }

  </script>

</main>
@endsection

{{-- Footer --}}
@section('footer')
@include('admin.components.footer')
@endsection