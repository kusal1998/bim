@extends('layouts.wyse2')
@section('optional_css')
{{-- <style>
.ui-datepicker-calendar {
    display: none;!important;
    }
</style> --}}
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
                    <div class="form-group col-md-4">
                        <input name="startDate2" id="startDate2" class="datepicker2 form-control-sm form-control" />
                    </div>
                    <div class="form-group col-md-4 d-flex align-items-end">
                        <input name="startDate" id="startDate" class="datepicker1 form-control-sm form-control" />
                    </div>
                    <div class="form-group col-md-4 d-flex align-items-end">
                     
                    <a id="download"  class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" target="_blank"><i data-feather="printer" class="wd-10 mg-r-5"></i>Generate Report</a>
                    </div>
                </div>





                {{--  <button class="btn btn-sm pd-x-15 btn-white btn-uppercase mg-l-5"><i data-feather="printer" class="wd-10 mg-r-5"></i> Print</button> --}}

            </div>
        </div>
        <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
        <h5 id="section1" class="mg-b-10 text-center"><u>{{$fdate}}  {{trans('sentence.from')}} {{$tdate}} {{trans('sentence.to_date')}}{{trans('sentence.no_of_blocks_sent_to_goverment_press')}}</u> </h5>
        <div class="row row-xs">
            <div class="col mg-t-10">
                {{-- card-dashboard-table --}}
                <div class="card ">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">

                            </thead>
                            <tbody>

                                <tr>
                                    <th rowspan="2" class="wd-300 text-center">{{trans('sentence.district')}}</th>
                                    <th rowspan="2" class="wd-300 text-center">{{trans('sentence.ag_division')}}</th>
                                    <th colspan="3" class="text-center">{{trans('sentence.no_of_blocks_sent_to_goverment_press')}}</th>
                                </tr>
                                <tr>
                                    <th class="text-center">{{trans('sentence.private')}}</th>
                                    <th class="text-center">{{trans('sentence.goverment')}}</th>
                                    <th class="text-center">{{trans('sentence.total')}}</th>
                                </tr>
                                @foreach($elements as $attribute)
                                @php
                                $distric=App\Models\Districts::where('id',$attribute->district)->first();
                                $aglist =App\Models\Form12::selectRaw('SUM(government_lands) as gl,SUM(private_lands) as
                                pl,SUM(total_lands) as total,ag_division as agdivision')
                                ->where('district_id',$attribute->district)
                                ->groupBy('agdivision')->get();
                                $rowsCount=sizeof($aglist);
                                @endphp
                                <tr>
                                    <td rowspan={{$rowsCount}}>{{$distric->sinhala_name}}</td>
                                    @foreach($aglist as $values)
                                    @php
                                    $agoffice=App\Models\AgDivisions::where('id',$values->agdivision)->first();
                                    @endphp
                                    <td>{{$agoffice->sinhala_name}}</td>
                                    <td class="text-right">{{$values->gl}}</td>
                                    <td class="text-right">{{$values->pl}}</td>
                                    <td class="text-right">{{$values->total}}</td>

                                </tr>
                                @endforeach
                                @endforeach

                            </tbody>
                            <tfoot class="text-right">
                                <tr>
                                    <td colspan="2">{{trans('sentence.total')}}</td>
                                    <td><b>{{$grandGlTot}}</b></td>
                                    <td><b>{{$grandPlTot}}</b></td>
                                    <td><b>{{$grandTotal}}</b></td>
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
 
/*  $(function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
}); */

$( ".datepicker1" ).datepicker({
  dateFormat: "yy-mm-dd"
});

$( ".datepicker2" ).datepicker({
  dateFormat: "yy-mm-dd"
});


$('.select2').select2({
  placeholder: 'Choose one',
  searchInputPlaceholder: 'Search options'
});

 </script>


@include('scripts.datatables')

<script>
$('#tbl').DataTable({
  language: {
    searchPlaceholder: 'Search...',
    sSearch: '',
   // lengthMenu: '_MENU_ items/page',
  }
});
    </script>
<script type="text/javascript">
    $(function () {
    $("#download").click(function () {
      if($("#startDate2").val()=='')
      {
        alert('Fill The Date');
        return;
      }
      else if($("#startDate").val()=='')
      {
        alert('Fill The Date');
        return;
      }
      else
      {
        //window.location=("/report6/export/"+ $("#startDate2").val() +"/"+ $("#startDate").val());
        window.open('/report9/export/'+ $("#startDate2").val() +'/'+ $("#startDate").val(),'_blank');
      }
    });
});


</script>



@endsection