@extends('layouts.wyse2')
@section('optional_css')
@include('css.datatables')
@endsection
@section('content')

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
                  <li class="breadcrumb-item active" aria-current="page">Current Files</li>
                </ol>
              </nav>
              <h4 class="mg-b-0 tx-spacing--1">{{ ucwords(trans(str_replace('-', ' ', $title))) }} - Current Files </h4>
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
                                        <th>ID</th>
                                        <!-- <th>File Number</th> -->
                                        <th>Reference Number</th>
                                        <th>Map No</th>
                                        <th>Block No</th>
                                        <th>Stage</th>
                                        <th width=15%;>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>


            </div>
        </div>
   </div>


@section('modals')
@include('modals.delete')
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
 </script>


 @include('scripts.datatables')


<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('form12.getcurrentfiles') }}",
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'id',
                    name: 'id',
                },
                // {
                //     data: 'code',
                //     name: 'code',
                // },
                {
                    data: 'file_no',
                    name: 'file_no',
                },
                {
                    data: 'map_no',
                    name: 'map_no',
                },
                {
                    data: 'block_no',
                    name: 'block_no',
                },
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

</script>

@endsection
