@foreach ($users as $user)

<div class="modal fade" id="view{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">
          {{ strlen($user->firstname) < 1 && strlen($user->lastname) < 1 ? $user->email : $user->firstname.' '.$user->lastname }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="img-style-view" style="background: url('{{ Storage::url('users/'.$user->photo) }}'); 
            width: 32% !important; height: 150px !important"></div>
          </div>
          <div class="col-md-12 mt-3">
            <table class="table">
              <tr>
                <td class="text-secondary" width="50%">First Name:</td>
                <td>{{ $user->firstname }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Last Name:</td>
                <td>{{ $user->lastname }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Email:</td>
                <td>{{ $user->email }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Phone:</td>
                <td>{{ $user->phone }}</td>
              </tr>
               <tr>
                <td class="text-secondary" width="50%">Gender:</td>
                <td>{{ $user->gender }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Address:</td>
                <td>{{ $user->address }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">LGA, State:</td>
                <td>{{ $user->lga ? $user->lga->name .', '. $user->lga->state->name : '' }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Wallet Balance:</td>
                <td>₦{{ number_format($user->balance) }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Joined:</td>
                <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach