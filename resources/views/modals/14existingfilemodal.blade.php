<div class="modal fade" id="ExistingFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel5">Assign to a File</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{url('14th-sentence/create/file')}}" method="post">
            @csrf
            <input type="hidden" name="op_code" value="existing"/>
            <input type="hidden" id="ag_division2" name="ag_division" value=""/>
            <div class="form-group col-md-12">
                @php
                    $existing_files=App\Models\Form14File::where('is_archived',0)->where('current_stage','Publication verify')->get();
                @endphp
                <label>File: </label>
                <select id="file" @if((Request::segment(2)=='view' )) disabled @endif
                    class="form-control form-control-md select2" name="file">
                    <option value="">Please Select</option>
                    @foreach($existing_files as $item)
                    @php
                    $ag=App\Models\AgDivisions::find($item->ag_division);
                    @endphp
                    <option value="{{$item->id}}">{{$item->code}} - {{$ag->ag_name}}</option>
                    @endforeach
                </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
            <button type="submit" name="" class="btn btn-danger">Add to File</button>
        </div>
        </form>
      </div>
    </div>
  </div>

