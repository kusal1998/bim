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
              <h4 class="mg-b-0 tx-spacing--1">{{ ucwords(trans(str_replace('-', ' ', $title))) }} - {{ ucwords(trans(str_replace('-', ' ', $title2))) }}</h4>
            </div>
            <!--<div class="d-none d-md-block">-->
            <!-- @include('buttons._create')-->
            <!--</div>-->
          </div>
          <div class="row row-xs">
                @if(session()->has('success'))
                @include('alerts.success')
                @endif
@if(sizeof($ag_divisions)==0)
<div class="table-responsive">
    <table class="table table-striped" id="tbl">
        <thead class="thead-primary">
            <tr>
                <th>
                    #
                </th>
                <th>ID</th>
                <th>File Number</th>
                <th>GN Divsion</th>
                <th>Map No</th>
                <th>Block No</th>
                <th>Stage</th>
                <th>Comment</th>
                <th width=15%;>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($new_requests as $key=>$item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->id}}</td>
                <td>{{$item->file_no}}</td>
                <td>{{$item->gn_name}}</td>
                <td>{{$item->map_no}}</td>
                <td>{{$item->block_no}}</td>
                <td>{{$item->current_stage}}</td>
                <td>{{$item->comment}}</td>
                <td>
                    <a href="{{url('/'.Request::segment(1).'/update/'.$item->id)}}" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>
                    <a href="{{url('/'.Request::segment(1).'/view/'.$item->id)}}" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>
                    {{-- <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$item->id}})"
                       data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a> --}}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@else
	@foreach($ag_divisions as $index=>$office)
                        <div class="table-responsive">
                            
                            <table class="table table-striped" id="tbl{{$index}}">

                                @php
                                    $ag_div=App\Models\AGDivisions::find($office->ag_division_id);
                                @endphp
                                <thead class="thead-primary">
                                    <tr>
                                        <th colspan="7">
                                            <h5 style="color: white;">{{$ag_div->ag_name}}</h5>
                                        </th>
                                        <th colspan="2">
                                            @if($UtilityService->getAccessPubVerify(Request::segment(1))=='Yes')
                                            <a href="javascript:;" data-toggle="modal" onclick="setID({{$ag_div->id}})"
                                                data-target="#NewFileModal" class="btn btn-icon btn-success"><i class="fas fa-folder-open"></i></a>
                                            <a href="javascript:;" data-toggle="modal" onclick="setID({{$ag_div->id}})"
                                                    data-target="#ExistingFileModal" class="btn btn-icon btn-warning"><i class="fas fa-folder"></i></a>
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>ID</th>
                                        <th>File Number</th>
                                        <!-- <th>Village</th> -->
                                        <th>Map No</th>
                                        <th>Block No</th>
                                        <th>Stage</th>
                                        <th>Comment</th>
                                        <th width=15%;>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($new_requests as $key=>$item)
                                    @if($item->ag_division_id==$ag_div->id)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->file_no}}</td>
                                        <!-- <td>{{$item->village_name}}</td> -->
                                        <td>{{$item->map_no}}</td>
                                        <td>{{$item->block_no}}</td>
                                        <td>{{$item->current_stage}}</td>
                                        <td>{{$item->comment}}</td>
                                        <td>
                                            <a href="{{url('/'.Request::segment(1).'/update/'.$item->id)}}" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>
                                            <a href="{{url('/'.Request::segment(1).'/view/'.$item->id)}}" class="btn btn-icon btn-info"><i class="fas fa-eye"></i></a>
                                            {{-- <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$item->id}})"
                                               data-target="#DeleteModal" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a> --}}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>

                            </table>
                            
                        </div>
						@endforeach
                        @endif
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
 </script>


@include('scripts.datatables')




<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Please Select',
  searchInputPlaceholder: 'Search options'
});
    $(document).ready(function () {
        $('#tbl').DataTable();
    });

</script>



@endsection
