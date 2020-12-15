@extends('layouts.wyse2')
@section('optional_css')
@include('css.datatables')
@endsection
@section('content')
@inject('UtilityService', 'App\Services\UtilityService')
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
              @php
              $regional_officer=App\User::find($form12->regional_officer);
              $regional_office=App\Models\AgDivisions::find($form12->ag_division_id);
              $file=App\Models\Form14File::find($form12->ref_no);
              @endphp
            <h4 class="mg-b-0 tx-spacing--1">{{$regional_office->ag_name}} / {{$form12->map_no}} / {{$form12->block_no}} / ({{$file->code}}) - {{$regional_officer->name.' '.$regional_officer->last_name}}</h4>
            </div>
            <div class="d-none d-md-block">
            @include('buttons._back')
            </div>
          </div>

          <div class="row row-xs">
                @if(session()->has('success'))
                @include('alerts.success')
                @endif
<div class="table-responsive">
    <table class="table table-striped" id="tbl">
        <thead class="thead-primary">
            <tr>

                <th width="5%">Lot No</th>
                <th width="5%">Extend</th>
                <th width="15%">Owner(s)</th>
                <th width="15%">NIC</th>
                <th width="5%">Class</th>
                <th width="8%">Type</th>
                <th width="8%">Sub Type</th>
                <th width="16%">Mortgages</th>
                <th width="16%">Other</th>
                @if($UtilityService->getAccessRegReject('14th-sentence')=='Yes')
                <th width="7%">Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($details as $key=>$item)
            <tr>
                <td>{{$item->lot_no}}</td>
                <td>{{$item->size}}</td>
                <td><p style="white-space: pre-line">{{$item->name}} <br/>{{$item->addres}}</p></td>
                <td> @if($item->ownership_type=='full'){{explode('-',$item->nic_number)[0]}} - Full @elseif($item->ownership_type=='Equal'){{explode('-',$item->nic_number)[0]}} - Equal @else {{$item->nic_number}} @endif</td>
                <td>{{$item->class}}</td>
                <td>{{$item->type}}</td>
                <td>{{$item->sub_type}}</td>
                <td>{{$item->mortgages}}</td>
                <td>{{$item->other_boudages}}</td>
                @if($UtilityService->getAccessRegReject('14th-sentence')=='Yes')
                <td>
                    <a href="javascript:;" data-toggle="modal" onclick="reject({{$item->id}})"
                       data-target="#RejectModal{{$item->id}}" class="btn btn-icon btn-danger"><i class="fas fa-times"></i> Reject</a>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>

    </table>
</div>


            </div>
        </div>
   </div>


@section('modals')
@include('modals.delete')
@include('modals.14existingfilemodal')
@include('modals.14filemodal')
@foreach($details as $key=>$item)
@include('modals.14rejectmodel')
@endforeach
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

@endsection
