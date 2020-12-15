<div class="modal fade" id="NewFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content tx-14">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel5">Assign to a File</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    <form action="{{url('amendment-sentence/create/file')}}" method="post">
            @csrf
            <input type="hidden" id="new_id" name="new_id" value=""/>
        <div class="modal-body">
            <p class="text-danger">Are You Sure that you want to create a new file ?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">No,Don't</button>
            <button type="submit" name="" class="btn btn-danger">Yes, Create</button>
        </div>
        </form>
      </div>
    </div>
  </div>

