<div class="modal fade" id="GazetteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel5">Gazette Information</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @csrf
        <div class="modal-body">
                <div class="form-group col-md-12">
                    @if($UtilityService->getAccessGazette(Request::segment(1)))
                    @if($form12->current_stage=='Gov Press without G')
                    <div class="form-group col-md-12">
                        <label for="tot_lands">{{trans('sentence.form_12th_gazzette_date')}}</label>
                        <input type="date" required autocomplete="off" class="form-control datepicker1" id="" name="gazzette_date">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="tot_lands">{{trans('sentence.form_12th_gazzette_no')}}</label>
                        <input type="text" required autocomplete="off" class="form-control" id="tot_lands" name="gazzette_no">
                    </div>
                    @endif
                    @endif
                    </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
            <button type="submit" name="button" id="computer_with_G" value="computer_with_G" class="btn btn-info">Save</button>
        </div>

      </div>
    </div>
  </div>
