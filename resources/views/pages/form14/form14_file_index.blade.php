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
                        <div class="table-responsive">
                            <table class="table table-striped" id="tbl">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>File No</th>
                                        @if($UtilityService->getAccessBimsaviyaComm(Request::segment(1))=='Yes' || $UtilityService->getAccessCommGen(Request::segment(1))=='Yes'
                                            || $UtilityService->getAccessForwardProof(Request::segment(1))=='Yes' || $UtilityService->getAccessProof(Request::segment(1))=='Yes'
                                            || $UtilityService->getAccessForwardTransProof(Request::segment(1))=='Yes' || $UtilityService->getAccessTransProof(Request::segment(1))=='Yes'
                                            || $UtilityService->getAccessForwardPublication(Request::segment(1))=='Yes' || $UtilityService->getAccessForwardPress(Request::segment(1))=='Yes'
                                            || $UtilityService->getAccessGazette(Request::segment(1))=='Yes' || $UtilityService->getAccessCertificate(Request::segment(1))=='Yes')
                                        <th>Computer branch officer</th>
                                        @endif
                                        @if($UtilityService->getAccessForwardPress(Request::segment(1))=='Yes' || $UtilityService->getAccessGazette(Request::segment(1))=='Yes' ||$UtilityService->getAccessCertificate(Request::segment(1))=='Yes')
                                        <th>Gazette No</th>
                                        <th>Gazette Date</th>
                                        @endif
                                        <th>Stage</th>
                                        <th width=15%;>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
            </div>
        </div>
   </div>


{{-- @section('modals')
@include('modals.delete')
@include('modals.12filemodel')
@include('modals.12existingfilemodal')
@endsection --}}
@endsection
@section('after_scripts')
@include('scripts.datatables')
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('form14file-pending.getnewdata') }}",
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'code',
                    name: 'code',
                },
                @if($UtilityService->getAccessBimsaviyaComm(Request::segment(1))=='Yes' || $UtilityService->getAccessCommGen(Request::segment(1))=='Yes'
                || $UtilityService->getAccessForwardProof(Request::segment(1))=='Yes' || $UtilityService->getAccessProof(Request::segment(1))=='Yes'
                || $UtilityService->getAccessForwardTransProof(Request::segment(1))=='Yes' || $UtilityService->getAccessTransProof(Request::segment(1))=='Yes'
                || $UtilityService->getAccessForwardPublication(Request::segment(1))=='Yes' || $UtilityService->getAccessForwardPress(Request::segment(1))=='Yes'
                || $UtilityService->getAccessGazette(Request::segment(1))=='Yes' || $UtilityService->getAccessCertificate(Request::segment(1))=='Yes')
                {
                    data: 'computer_officer',
                    name: 'computer_officer',
                },
                @endif
                @if($UtilityService->getAccessForwardPress(Request::segment(1))=='Yes' || $UtilityService->getAccessGazette(Request::segment(1))=='Yes' ||$UtilityService->getAccessCertificate(Request::segment(1))=='Yes')
                {
                    data: 'gazette_no',
                    name: 'gazette_no',
                },
                {
                    data: 'gazette_date',
                    name: 'gazette_date',
                },
                @endif
                {
                    data: 'current_stage',
                    name: 'current_stage',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
    $('.select2').select2({
  placeholder: 'Please Select',
  searchInputPlaceholder: 'Search options'
});

</script>
@endsection
