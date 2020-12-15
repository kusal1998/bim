@extends('layouts.wyse2')
@section('optional_css')
<style>
.ui-datepicker-calendar {
    display: none;!important;
    }
</style>
@include('css.datatables')
@endsection
@section('content')

<div class="content content-fixed">
        <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
          <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                  <li class="breadcrumb-item"><a href="#">Reports</a></li>
                 
                </ol>
              </nav>
              
            </div>
            
            <div class="d-none d-md-block form-row col-md-7">
                    <div class="form-row ">
                            <div class="form-group col-md-5">
                                    <select id="file_id" name="file_id" @if(Request::segment(2)=='view') disabled @endif class="form-control-sm form-control select2" name="ag_div_id">
              
                                    </select>
                            </div>
                            <div class="form-group col-md-3 d-flex align-items-end">
                                    <input name="startDate" id="startDate" class="date-picker form-control-sm form-control" />
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-end">
                                    <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="file" class="wd-10 mg-r-5"></i> Generate Report</button>
                            </div>
                          </div>
             {{--  <button class="btn btn-sm pd-x-15 btn-white btn-uppercase mg-l-5"><i data-feather="printer" class="wd-10 mg-r-5"></i> Print</button> --}}
              
           </div>
          
          </div>
          <h4 id="section1" class="mg-b-10 text-center">Land Title Settlement</h4>
          <h5 id="section1" class="mg-b-10 text-center">Report Title Here </h5>
          <h6 id="section1" class="mg-b-10 text-center">2019-August | Weligalpola </h6>
          <div class="row row-xs">
                <div class="col mg-t-10">
                        {{-- card-dashboard-table --}}
                        <div class="card ">
                          <div class="table-responsive">
                            <table class="table table-bordered text-center" id="tbl" > 
                              <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th class="wd-5p">{{trans('sentence.lot_no')}} </th>
                                        <th class="wd-5p">{{trans('sentence.size')}} </th>
                                        <th class="wd-20p">{{trans('sentence.name')}}</th>
                                        <th class="wd-10p">{{trans('sentence.address')}}</th>
                                        <th class="wd-10p">{{trans('sentence.Owner_nic')}}</th>
                                        <th class="">{{trans('sentence.nature_of_ownership')}}</th>
                                        <th class="">{{trans('sentence.Class')}}</th>
                                        <th class="">{{trans('sentence.mortgages')}}</th>
                                        <th >{{trans('sentence.other_bonds')}}</th>
                                       
                                    </tr>
                              </thead>
                            </table>
                          </div><!-- table-responsive -->
                        </div><!-- card -->
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
 
 $(function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
});

$('.select2').select2({
  placeholder: 'Choose one',
  searchInputPlaceholder: 'Search options'
});

 </script>

@include('scripts.datatables')
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ url('report1/list/'.Request::segment(4)) }}",
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'lot_no',
                    name: 'lot_no',
                },
                {
                    data: 'size',
                    name: 'size',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'addres',
                    name: 'addres',
                },
                {
                    data: 'nic_number',
                    name: 'nic_number',
                },
                {
                  data:'ownership_type',
                  name:'ownership_type',

                },
                {
                  data:'class',
                  name:'class'
                },
                {
                  data:'mortgages',
                  name:'mortgages',
                },
                {
                  data:'other_boudages',
                  name:'other_boudages',
                  orderable: false,
                  searchable: false
                },
                
            ]
        });
    });

</script>



@endsection