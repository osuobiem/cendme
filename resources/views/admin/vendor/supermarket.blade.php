@foreach($vendors as $vendor)
<div class="modal fade" id="supermarket{{ $vendor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ml-auto" id="exampleModalLabel">
          {{strtoupper($vendor->business_name)}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 mt-3">
            <table class="table">

              @foreach($vendor->shoppers as $shopper)
                <tr>
                  <td width="80%">{{ucwords($shopper->firstname)}} {{ucwords($shopper->lastname)}}</td>
                  <td> <button class="btn btn-sm btn-outline-danger" title="Remove Shopper" onclick="deleteWarning({{ $shopper->pivot->vendor_id }},{{ $shopper->pivot->shopper_id }})"><i class="fas fa-times"></i></button></td>
                </tr>
              @endforeach  

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

<script>
// Delete Assigned Shopper Warning
    function deleteWarning(vendor_id, shopper_id) {
      swal({
        title: "Are you sure?",
        icon: "warning",
        buttons: [true, "Delete"],
        dangerMode: true,
      })
        .then((willDelete) => {
          if (willDelete) {
            deleteAssigned(vendor_id, shopper_id)
          }
        });
    }

    // Delete Assigned Shopper
    function deleteAssigned(vendor_id, shopper_id) {
      let url = "{{ url('admin/assigned/delete') }}/" + vendor_id + "/" + shopper_id;

      $.ajax({
        type: "DELETE",
        url
      })
        .then(res => {
          showAlert(true, res.message)
          location.reload()
          loadVendors()
        })
        .catch(err => {
          showAlert(false, "Oops! Something's not right. Try Again")
        })
    }
</script>