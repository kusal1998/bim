{{-- <form action="{{ url('12th-sentence/update/'.$form12->id) }}" id="rej" method="post" novalidate="" _lpchecked="1"> --}}
    <div class="modal fade" id="ComputerModal"  role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
          <div class="modal-content tx-14">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel5">Computer branch officer</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <select id="computer" @if((Request::segment(2)=='view' )) disabled @endif
                        class="form-control form-control-md select2" name="computer_officer" @if($form12) @if($form12->current_stage=='Assistant commisioner') required @endif @endif>
                        <option value="">Please Select</option>
                        @foreach ($computer_officers as $item)
                        <option value="{{$item->id}}" @if(isset($element)) @if(old('regional_officer',$element->computer_officers)==$item->id)
                            selected="selected"
                            @endif @endif
                        >{{$item->name}} {{$item->last_name}}</option>
                        @endforeach
                    </select>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <button type="submit" name="button" value="forward_bim_comm"  class="btn btn-primary">Save</button>
            </div>

          </div>
        </div>
      </div>
    {{-- </form> --}}

