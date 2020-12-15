<div class="modal fade" id="RecheckModal12" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel5">Remarks</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @csrf
        <div class="modal-body">
                <div class="form-group col-md-12">
                        <label for="tot_lands">Reason</label>
                        <input type="text" @if(Request::segment(2)=='view' ) readonly @endif class="form-control" id="" name="recheck_reason">
                    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
            <button type="submit" name="button" value="recheck"  class="btn btn-info">Save</button>
        </div>

      </div>
    </div>
  </div>

