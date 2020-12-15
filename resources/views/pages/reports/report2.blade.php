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

                            <div class="form-group col-md-3 d-flex align-items-end">
                                    <input name="startDate" id="startDate" class="date-picker form-control-sm form-control" />
                            </div>
                            <div class="form-group col-md-5 d-flex align-items-end">
                                <a id="download"  class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" target="_blank"><i data-feather="printer" class="wd-10 mg-r-5"></i>Generate Report</a>
                                  </div>
                          </div>


           </div>
          </div>
          <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
          <h5 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.summary_of_detailed_plots_of_land_for_which_recommendations_have_been_made')}}</u> </h5>
          <div class="row row-xs">
                <div class="col mg-t-10">
                        {{-- card-dashboard-table --}}
                        <div class="card ">
                          <div class="table-responsive">
                          <h5></h5>
                            <table class="table table-bordered" width="100%">
                              <thead>
                                <tr>
                                  <th rowspan="2" class="wd-100" style="width:10%">{{trans('sentence.map_number')}}</th>
                                  <th rowspan="2" class="wd-100" style="width:10%">{{trans('sentence.block_number')}}</th>
                                  <th colspan="3" align="center">{{trans('sentence.lot_no')}}</th>
                                </tr>
                                <tr>
                                  <th class="wd-80">{{trans('sentence.private')}}</th>
                                  <th class="wd-80">{{trans('sentence.goverment')}}</th>
                                  <th class="wd-80">{{trans('sentence.total')}}</th>
                                </tr>
                              </thead>
                              <tbody>
                                  @foreach($elements['ag_divisions'] as $record)
                                <tr>
                                    <td colspan="5">{{trans('sentence.AG_Office')}}:{{$record['name']}} </td>
                                </tr>
                                @foreach($record['officers'] as $officer)
                                <tr>
                                      <td colspan="5">{{trans('sentence.Officer')}}: {{$officer['name']}}</td>
                                </tr>
                                @foreach($officer['jobs'] as $job)
                                <tr>
                                  <td>{{$job['map']}}</td>
                                  <td>{{$job['block']}}</td>
                                  <td><strong>{{$job['lot_string_pvt']}}</strong></td>
                                  <td><strong>{{$job['lot_string_govt']}}</strong></td>
                                  <td><strong>{{$job['total']}}</strong></td>
                                </tr>
                                @endforeach
                                <tr class="text-right" >
                                    <td colspan="4">Sub Total</td>
                                    <td><strong>{{$officer['officer_total']}}</strong></td>
                                </tr>
                                @endforeach
                                <tr class="text-right" >
                                        <td colspan="4">Sub Total</td>
                                        <td><strong>{{$record['ag_total']}}</strong></td>
                                </tr>
                                @endforeach
                              </tbody>
                              <tfoot>

                                          <tr class="text-right" >
                                                <td colspan="4"> Total</td>
                                                <td><strong>{{$elements['total']}}</strong></td>

                                              </tr>
                              </tfoot>
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
  $(function () {
    $("#download").click(function () {
      if($("#startDate").val()=='')
      {
        alert('Fill The Date');
        return;
      }
      else
      {
        //window.location=("/report6/export/"+ $("#startDate2").val() +"/"+ $("#startDate").val());
        window.open('/report2/export/'+ $("#startDate").val(),'_blank');
      }
    });
});
</script>
<script>
$('#tbl').DataTable({
  language: {
    searchPlaceholder: 'Search...',
    sSearch: '',
   // lengthMenu: '_MENU_ items/page',
  }
});
    </script>
{{-- <script type="text/javascript">
    $(document).ready(function () {
        $('#tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('provinces.getdata') }}",
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'contact_no',
                    name: 'contact_no',
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

</script> --}}



@endsection
