@foreach ($agents as $agent)

<div class="modal fade" id="assign{{ $agent->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
           
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach