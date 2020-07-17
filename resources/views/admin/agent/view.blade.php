@foreach ($agents as $agent)
<!-- Update product modal -->
<div class="modal fade" id="view{{ $agent->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">
          {{ strlen($agent->firstname) < 1 && strlen($agent->lastname) < 1 ? $agent->email : $agent->firstname.' '.$agent->lastname }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="img-style-view" style="background: url('{{ Storage::url('agents/'.$agent->photo) }}'); 
            width: 32% !important; height: 150px !important"></div>
          </div>
          <div class="col-md-12 mt-3">
            <table class="table">
              <tr>
                <td class="text-secondary" width="50%">First Name:</td>
                <td>{{ $agent->firstname }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Last Name:</td>
                <td>{{ $agent->lastname }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Email:</td>
                <td>{{ $agent->email }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Phone:</td>
                <td>{{ $agent->phone }}</td>
              </tr>
               <tr>
                <td class="text-secondary" width="50%">Gender:</td>
                <td>{{ $agent->gender }}</td>
              </tr>
               <tr>
                <td class="text-secondary" width="50%">DOB:</td>
                <td>{{ $agent->dob ? date('d/m/Y', strtotime($agent->dob)) : '' }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Address:</td>
                <td>{{ $agent->address }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">LGA, State:</td>
                <td>{{ $agent->lga ? $agent->lga->name .', '. $agent->lga->state->name : '' }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Level:</td>
                <td>{{ $agent->level->name }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">BVN:</td>
                <td>{{ $agent->bvn }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Wallet Balance:</td>
                <td>₦{{ number_format($agent->balance) }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Verified:</td>
                <td class="{{ $agent->verified ? 'text-success' : 'text-danger' }}">{{ $agent->verified ? 'Yes' : 'No' }}</td>
              </tr>
              <tr>
                <td class="text-secondary" width="50%">Joined:</td>
                <td>{{ date('d/m/Y', strtotime($agent->created_at)) }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach