@extends('layouts.wyse2')
@section('optional_css')
@include('css.datatables')
@endsection
@section('content')
@inject('UtilityService', 'App\Services\UtilityService')
<div class="content content-fixed">
        <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
          <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                    @php
                    $title = Request::segment(1);
                    $title2 = Request::segment(2);
                    $singular = str_singular(Request::segment(1));
                    $url = Request::segment(1);
                    @endphp
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                  <li class="breadcrumb-item"><a href="#">{{ ucwords(trans(str_replace('-', ' ', $title))) }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ ucwords(trans(str_replace('-', ' ', $title2))) }}</li>
                </ol>
              </nav>
            <h4 class="mg-b-0 tx-spacing--1">{{ ucwords(trans(str_replace('-', ' ', $title))) }} {{ ucwords(trans(str_replace('-', ' ', $title2))) }} - {{$form12->code}}</h4>
            </div>
            <!--<div class="d-none d-md-block">-->
            <!-- @include('buttons._create')-->
            <!--</div>-->
          </div>

          <div class="row row-xs">
                @if(session()->has('success'))
                @include('alerts.success')
                @endif
<div class="table-responsive">
    <table class="table table-striped" id="tbl">
        <thead class="thead-primary">
            <tr>
                <th>
                    #
                </th>
                <th>Reference Number</th>
                <th>Village</th>
                <th>Map No</th>
                <th>Block No</th>
                <th>Investigating Officer</th>
                <th>Number of Parcels</th>
                <th>Comment</th>
                <th width=15%;>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($form14_records as $key=>$item)
            @php
            $villages=explode(',',$item->village_name);
            $village_names='';
            foreach($villages as $village){
                if($village!=''){
                    $V=App\Models\Village::find($village);
                    if($village_names==''){
                        $village_names=$V->village;
                    }else{
                        $village_names=$village_names.','.$V->village;
                    }
                }
            }
            $form14details=App\Models\Form14Details::where('rejected',0)->where('form_14_Header_id',$item->id)->get();
            @endphp
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->file_no}}</td>
                <td>{{$village_names}}</td>
                <td>{{$item->map_no}}</td>
                <td>{{$item->block_no}}</td>
                <td>{{$item->current_stage}}</td>
                <td>{{sizeof($form14details)}}</td>
                <td>{{$item->comment}}</td>
                <td>
                    <a target="_blank" href="{{url('/'.Request::segment(1).'/details-update/'.$item->id)}}" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>
                    {{-- <a href="{{url('/'.Request::segment(1).'/view/'.$item->id)}}" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a> --}}
                    {{-- <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$item->id}})"
                       data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a> --}}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
<form action="{{url('/14th-sentence-file/update/'.$form12->id)}}" method="POST">
    @csrf
    <input type="hidden" name="from" value="file_index"/>
    <div class="form-group col-md-11>
    <label for="comment">Comment</label>
    <textarea  rows="2" cols="50" class="form-control" id="comment"  name="comment" @if(Request::segment(2)!='create' ) value="{{$form12->comment}}" @endif>
     </textarea>
     </div>
    </div>
    @include('buttons._form14fileaction')
    @include('modals.computerbranch')
    @include('modals.12gazetteinfomodal')
</form>
            </div>
        </div>
   </div>


@section('modals')
@include('modals.delete')
@include('modals.14existingfilemodal')
@include('modals.14filemodal')
@endsection
@endsection

@section('after_scripts')

<script type="text/javascript">
    function deleteData(id)
    {
        var id = id;
        var url = '{{ route("provinces-destroy", ":id") }}';
        url = url.replace(':id', id);
        $("#deleteForm").attr('action', url);
    }

    function formSubmit()
    {
        $("#deleteForm").submit();
    }
    function setID(id){
        console.log(id);
        $("#ag_division1").val(id);
        $("#ag_division2").val(id);
    }
    $('.select2').select2({
  placeholder: 'Please Select',
  searchInputPlaceholder: 'Search options'
});
 </script>


@include('scripts.datatables')

@endsection
