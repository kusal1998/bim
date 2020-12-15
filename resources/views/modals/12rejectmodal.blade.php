{{-- <form action="{{ url('12th-sentence/update/'.$form12->id) }}" id="rej" method="post" novalidate="" _lpchecked="1"> --}}
    <div class="modal fade" id="RejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
          <div class="modal-content tx-14">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel5">Remarks</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" id="reject_form">
            @csrf
            <input type="hidden" name="from" value="file_index"/>
             <input type="hidden" name="form12_id" id="form12_id"/>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                            <label for="tot_lands">Reason</label>
                            <input type="text" @if(Request::segment(2)=='view' ) readonly @endif class="form-control" id="" name="reason">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="button" id="btnreject" value="reject"  class="btn btn-info">Save</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    {{-- </form> --}}

