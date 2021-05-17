<div class="modal fade" id="withdraw-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Withdraw</h5>
        <button type="button" class="close" id="w-close-modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form class="row justify-content-center" method="POST" id="withdraw-form">
          @csrf

          <div class="col-md-12">
            <label for="">Amount</label>
            <input type="text" required name="amount" placeholder="1000" class="form-control" />
            <span class="text-danger error-message" id="w-amount"></span>
          </div>

          <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-3 ml-auto mr-auto">
            <button class="btn btn-sign hover-btn" type="submit" id="withdraw-btn" type="submit">
              <span class="btn-txt">Withdraw</span>
              <div style="display: none;" class="spinner-border spinner-border-sm text-light spin" role="status">
                <span class="sr-only">Processing...</span>
              </div>
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
  $('#withdraw-form').submit(el => {
    el.preventDefault();

    offError();

    let data = new FormData(el.target)
    let url = "{{ url('vendor/withdraw') }}"

    spin()

    $.ajax({
      type: "POST",
      url,
      data,
      processData: false,
      contentType: false,
    })
      .then(res => {
        spin()
        showAlert(true, res.message)

        setTimeout(() => {
          location.reload()
        }, 1800)
      })
      .catch(err => {
        spin()

        if (err.status === 400) {
          errors = err.responseJSON.message;

          if (typeof errors === "object") {
            for (const [key, value] of Object.entries(errors)) {
              $('#w-' + key).html('');
              [...value].forEach(m => {
                $('#w-' + key).append(`<p>${m}</p>`)
              })
            }
          }
          else {
            showAlert(false, errors)
          }
        }

        else {
          showAlert(false, "Oops! Something's not right. Try Again")
        }
      })
  });
</script>