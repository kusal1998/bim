<div class="col-lg-6">

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="md_name">Is Active</label>
            <div class="radio radiofill radio-success radio-inline">
                <div class="pretty p-icon p-round p-plain p-smooth">
                    <input @if(Request::segment(2)=='view' ) disabled @endif type="radio" name="is_active"
                        @if(isset($element)) @if($element->is_active=='1')
                    checked="checked"
                    @endif @endif
                    value="1" @if(Request::segment(2)=='create' ) checked="checked" @endif >
                    <div class="state p-success-o">
                        <i data-feather="check-circle"></i>
                        <label>Yes</label>
                    </div>
                </div>
                <div class="pretty p-icon p-round p-plain p-smooth">
                    <input @if(Request::segment(2)=='view' ) disabled @endif type="radio" name="is_active"
                        @if(isset($element)) @if($element->is_active=='0')
                    checked="checked"
                    @endif @endif
                    value="0">
                    <div class="state p-danger-o">
                            <i data-feather="x-circle"></i>
                        <label>No</label>
                    </div>
                </div>
            </div>


        </div>
    </div>


</div>
