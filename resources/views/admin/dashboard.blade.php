@extends('admin.layouts.master')

{{-- Page Title --}}
@section('title', 'Dashboard')

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

    <div class="row mt-3">

      <!-- Vendors -->
      <div class="col-md-4">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Vendors</h4>
            <a href="{{ url('admin/vendors') }}" class="view-btn hover-btn">View All</a>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive">
              <table class="table ucp-table table-striped" id="vendor-table">
                <thead>
                  <tr>
                    <th>Business Name</th>
                    <th>Joined</th>
                  </tr>
                </thead>
                <tbody id="vendors">
                  <tr class="text-center">
                    <td colspan="2">
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

      <!-- Agents -->
      <div class="col-md-4">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Shoppers</h4>
            <a href="{{ url('admin/shoppers') }}" class="view-btn hover-btn">View All</a>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive">
              <table class="table ucp-table table-hover" id="agent-table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Joined</th>
                  </tr>
                </thead>
                <tbody id="agents">
                  <tr class="text-center">
                    <td colspan="2">
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

      <!-- Users -->
      <div class="col-md-4">
        <div class="card card-static-2 mb-30">
          <div class="card-title-2">
            <h4>Users</h4>
            <a href="{{ url('admin/users') }}" class="view-btn hover-btn">View All</a>
          </div>

          <hr style="margin: 0 !important;">

          <div class="card-body">
            <div class="table-responsive">
              <table class="table ucp-table table-hover" id="user-table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Joined</th>
                  </tr>
                </thead>
                <tbody id="users">
                  <tr class="text-center">
                    <td colspan="2">
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

  <script>
    $(document).ready(function () {
      loadVendors()
      loadAgents()
      loadUsers()
    });

    // Load Vendors
    function loadVendors() {
      let url = "{{ url('admin/vendors/get/6') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#vendors').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load vendors. Please relaod page')
        })
    }

    // Load Agents
    function loadAgents() {
      let url = "{{ url('admin/agents/get/6') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#agents').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load shoppers. Please relaod page')
        })
    }

    // Load Users
    function loadUsers() {
      let url = "{{ url('admin/users/get/6') }}";

      $.ajax({
        type: "GET",
        url
      })
        .then(res => {
          $('#users').html(res)
        })
        .catch(err => {
          showAlert(false, 'Could not load users. Please relaod page')
        })
    }
  </script>
</main>

@endsection

{{-- Footer --}}
@section('footer')
@include('admin.components.footer')
@endsection