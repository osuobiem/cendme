@foreach ($vendors as $vendor)

<div class="modal fade" id="view{{ $vendor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">{{ $vendor->business_name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="img-style-view" style="background: url('{{ Storage::url('vendors/'.$vendor->photo) }}'); 
            width: 32% !important; height: 150px !important"></div>
          </div>
          <div class="col-md-12 mt-3">
            <table class="table">
              <tr>
                <td class="text-secondary" width="50%">Business Name:</td>
                <td>{{ $vendor->business_name }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Email:</td>
                <td>{{ $vendor->email }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Phone:</td>
                <td>{{ $vendor->phone }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Address:</td>
                <td>{{ $vendor->address }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">LGA, State:</td>
                <td>{{ $vendor->lga->name.', '.$vendor->lga->state->name }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Wallet Balance:</td>
                <td>₦{{ number_format($vendor->balance) }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Joined:</td>
                <td>{{ date('d/m/Y', strtotime($vendor->created_at)) }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach