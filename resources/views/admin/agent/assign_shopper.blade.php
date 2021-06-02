@foreach ($agents as $agent)

<div class="modal fade" id="assign{{ $agent->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <h6 style="font-weight: bold; font-size:18px; text-align:center; margin-top:5px">Assign Shopper to Supermarket</h6>
      <div class="modal-header mt-5">
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
          <div class="col-lg-12 mt-3">
            <div class="form-group mb-3">
              <label class="form-label">Supermarket</label>
              <select id="state" class="form-control">
                <option disabled selected>select Supermarket</option>
                @foreach($supermarkets as $supermarket)
                <option value="{{$supermarket->id }}">{{ ucwords($supermarket->business_name) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-12 text-center mt-5">
              <button class="save-btn hover-btn" type="submit">
                <span id="btn-txt">Assign Shopper</span>
                <div id="spinner" style="display: none;" class="spinner-border spinner-border-sm text-light" role="status">
                  <span class="sr-only">Processing...</span>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach