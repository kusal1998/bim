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
                    $singular = str_singular(Request::segment(1));
                    $url = Request::segment(1);
                    @endphp
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                  <li class="breadcrumb-item"><a href="#">Master Files</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ ucwords(trans(str_replace('-', ' ', $title))) }}</li>
                </ol>
              </nav>
              <h4 class="mg-b-0 tx-spacing--1">{{ ucwords(trans(str_replace('-', ' ', $title))) }}</h4>
            </div>
            <div class="d-none d-md-block">
             @include('buttons._create')
            </div>
          </div>
  

@if(session()->has('success'))
@include('alerts.success')
@endif
<div class="row row-xs">
                        <div class="table-responsive">
                            <table class="table table-striped" id="modules_table">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Group</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('modals')
@include('modals.delete')
@endsection
@section('after_scripts')




@include('scripts.datatables')


<script type="text/javascript">
    function deleteData(id)
    {
        var id = id;
        var url = '{{ route("modules-destroy", ":id") }}';
        url = url.replace(':id', id);
        $("#deleteForm").attr('action', url);
    }

    function formSubmit()
    {
        $("#deleteForm").submit();
    }
 </script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#modules_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('modules.getdata') }}",
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'md_name',
                    name: 'md_name'
                },
                {
                    data: 'md_code',
                    name: 'md_code'
                },
                {
                    data: 'md_group',
                    name: 'md_group'
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
