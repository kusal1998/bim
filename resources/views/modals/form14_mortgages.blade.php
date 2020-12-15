<div class="modal fade" id="MortgagesModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLabel5">Mortgages & Other Bonds</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="form-group col-md-12">
            <h5><b>Mortgages:<b> </h5>
            <p>{{$item->mortgages}}</p>
        </div>
        <div class="form-group col-md-12">
            <h5><b>Other:</b> </h5>
            <p>{{$item->other_boudages}}</p>
        </div>
      </div>
    </div>
  </div>

