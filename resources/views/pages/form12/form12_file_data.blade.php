@extends('layouts.wyse2')
@section('optional_css')
@include('css.datatables')
@endsection
@inject('UtilityService', 'App\Services\UtilityService')
@section('content')

<div class="content content-fixed">
        <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0" style="max-width: 1400px;">
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
            <h4 class="mg-b-0 tx-spacing--1">{{ ucwords(trans(str_replace('-', ' ', $title))) }}  {{ ucwords(trans(str_replace('-', ' ', $title2))) }} - {{$form12->code}}</h4>
            </div>
            <div class="d-none d-md-block">
                @include('buttons._back')
            </div>
          </div>
          <div class="row">
                @if(session()->has('success'))
                @include('alerts.success')
                @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="tbl">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>AG Div</th>
                                        <th>Map</th>
                                        <th>Block</th>
                                        <th>GN Div</th>
                                        <th>Villages</th>
                                        <th>State</th>
                                        <th>Private</th>
                                        <th>total</th>
                                        <th>Notice No</th>
                                        @if($form12->current_stage=='Gazette with G'|| $form12->current_stage=='Publication with G'||$form12->current_stage=='Gov press with G')
                                        <th>Gazette No</th>
                                        <th>Gazette Date</th>
                                        @endif
                                        <th>Comment</th>
                                        @if($UtilityService->getAccessRegReject('12th-sentence')=='Yes')
                                            <th width=15%;>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($form12_records as $item)
                                    @php
                                        $ag_division=App\Models\AgDivisions::find($item->ag_division);
                                        $gns=explode(',',$item->gn_division);
                                        $gn_names='';
                                        foreach($gns as $gn){
                                            if($gn!=""){
                                                $gn_div=App\Models\GnDivisions::find($gn);
                                                if($gn_names==''){
                                                    $gn_names=$gn_div->gn_name;
                                                }else{
                                                    $gn_names=$gn_names.','.$gn_div->gn_name;
                                                }

                                            }
                                        }
                                        $villages=explode(',',$item->village);
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
                                    @endphp
                                    <tr>
                                        <td>{{$ag_division->ag_name}}</td>
                                        <td>{{$item->map_no}}</td>
                                        <td>{{$item->block_no}}</td>
                                        <td>{{$gn_names}}</td>
                                        <td>{{$village_names}}</td>
                                        <td>{{$item->government_lands}}</td>
                                        <td>{{$item->private_lands}}</td>
                                        <td>{{$item->total_lands}}</td>
                                        <td>{{$item->file_no}}</td>
                                        @if($form12->current_stage=='Gazette with G' || $form12->current_stage=='Publication with G'||$form12->current_stage=='Gov press with G')
                                        <td>{{$item->gazette_no}}</td>
                                        <td>{{explode(' ',$item->gazette_date)[0]}}</td>
                                        @endif
                                        <td>{{$item->comment}}</td>
                                        @if($UtilityService->getAccessRegReject('12th-sentence')=='Yes')
                                        <td>
                                            <a href="javascript:;" data-toggle="modal" onclick="reject({{$item->id}})"
                                                data-target="#RejectModal" class="btn btn-icon btn-danger"><i class="fas fa-times"></i> Reject</a>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                       
                        <form action="{{url('/12th-sentence-file/update/'.$form12->id)}}" method="POST">
                            @csrf
                            <input type="hidden" name="from" value="file_index"/>
                            <div class="form-group col-md-12">
                            <label for="comment">Comment</label>
                                <textarea  rows="2" cols="50" class="form-control" id="comment"  name="comment" @if(Request::segment(2)!='create' ) value="{{$form12->comment}}" @endif>
                                </textarea>
                            </div>
                            
                            @include('buttons._form12fileaction')
                            @include('modals.computerbranch')
                            @include('modals.12gazetteinfomodal')
                        </form>
            </div>
        </div>
   </div>


@section('modals')
@include('modals.12rejectmodal')

@endsection
@endsection
@section('after_scripts')
@include('scripts.datatables')
<script type="text/javascript">
    function reject(id)
    {
        var url = '{{ url("12th-sentence/update") }}';
        url = url+'/'+id
        $("#reject_form").attr('action', url);
        $("#form12_id").val(id);
    }
    $('.select2').select2({
  placeholder: 'Please Select',
  searchInputPlaceholder: 'Search options'
});
 </script>
@endsection
