@foreach ($agents as $agent)

<div class="modal fade" id="assign{{ $agent->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">
          Assign Shopper to Supermarket
        </h5>
        <button type="button" class="close a-close-modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form id="assigned-shopper-form-{{ $agent->id }}" onsubmit="assignShopper(event)">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 text-center py-2">
              <h5>
                {{ strlen($agent->firstname) < 1 && strlen($agent->lastname) < 1 ? $agent->email : $agent->firstname.' '.$agent->lastname }}
              </h5>
            </div>
            <div class="col-md-12">
              <div class="img-style-view" style="background: url('{{ Storage::url('agents/'.$agent->photo) }}'); 
            width: 32% !important; height: 150px !important"></div>
            </div>
            <input type="hidden" name="agent"  value="{{$agent->id}}" />
            <div class="col-md-12 text-center mt-4">
              <p style="text-align: center;"> List of assigned Supermarket </p>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>S/N</th>
                  <th>Business Name</th>
                </tr>
              </thead>
              @foreach($agent->vendors as $sn => $shopper)
              <tr>
                <td width="30%">{{$sn+1 }}</td>
                <td>{{ ucwords($shopper->business_name) }}</td>
              </tr>
              @endforeach
            </table>
            <div class="col-lg-12 mt-3">
              <div class="form-group mb-3">
                <label class="form-label">Supermarket</label>
                <select class="form-control" name="supermarket">
                  @if($agent->vendors->count() >= 2)
                  <option disabled selected>Limit exceeded!, A shopper can only be assign to two supermarkets.</option>
                  @else
                  <option disabled selected>Select Supermarket</option>
                  @foreach ($agent->supermarkets() as $supermarket)
                  <option value="{{$supermarket->id}}">{{$supermarket->business_name}}</option>
                  @endforeach
                  @endif
                </select>
                <span class="text-danger error-message" id="a-supermarket"></span>
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
      </form>

    </div>
  </div>
</div>
@endforeach

<script>

    // submit assign shopper form
    function assignShopper(el) {
      el.preventDefault();

      // offError();

      let data = new FormData(el.target)
      let url = "{{ url('admin/assign-shopper') }}"

      // spin()

      $.ajax({
          type: "POST",
          url,
          data,
          processData: false,
          contentType: false,
        })
        .then(data => {

          $('.a-close-modal').click()

          setTimeout(() => {
            showAlert(true, data.message)
            loadAssignShopperModals()
          }, 1000)
        })

        .catch(err => {
          // spin()

          if (err.status === 400) {
            errors = err.responseJSON.message;

            if (typeof errors === "object") {
              for (const [key, value] of Object.entries(errors)) {
                $('#a-' + key).html('');
                [...value].forEach(m => {
                  $('#a-' + key).append(`<p>${m}</p>`)
                })
              }
            } else {
              showAlert(false, errors)
            }
          } else {
            showAlert(false, "Oops! Something's not right. Try Again")
          }
        })
    }
</script>