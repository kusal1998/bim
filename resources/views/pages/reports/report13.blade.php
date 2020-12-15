@extends('layouts.wyse2')
@section('optional_css')


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
                            <div class="form-group col-md-4 d-flex align-items-end">
                                    <input name="startDate" id="startDate" class="datepicker1 form-control-sm form-control" />
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-end">
                   
                              <a id="download"  class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" target="_blank"><i data-feather="printer" class="wd-10 mg-r-5"></i>Generate Report</a>
                              </div>
                          </div>          
           </div>
          </div>
          <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
          <h5 id="section1" class="mg-b-10 text-center"><u>{{$fdate}} {{trans('sentence.from')}} {{$tdate}} {{trans('sentence.Progress_of_the_day')}}</u> </h5>
          <p class="mg-b-30 text-center"></p>
         
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
                                  <th rowspan="2" >{{trans('sentence.Activity')}}</th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Jan')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Feb')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Mar')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Apr')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.May')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Jun')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Jul')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Aug')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Sep')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Oct')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Nov')}} </th>
                                  <th colspan="2" class="text-center" >{{trans('sentence.Dec')}} </th>
                                  <th colspan="3" class="text-center" >{{trans('sentence.Annual_Progress')}} </th>
                                </tr>
                                <tr>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.goverment')}}</th>
                                  <th class="text-center">{{trans('sentence.private')}}</th>
                                  <th class="text-center">{{trans('sentence.total')}}</th>

                                </tr>
                                @foreach($elements as $values)
                                <tr>
                                <td>{{trans('sentence.'.$values['activity'])}}</td>
                                @if(sizeof($values['month'])>0)
                                 @foreach($values['month'] as $records)
                                <td class="text-right">{{$records['gov_land']}}</td>
                                <td class="text-right">{{$records['pvt_land']}}</td>
                                 @endforeach
                                @else
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                @endif

                                <td class="text-right">{{$values['total_gov_land']}}</td>
                                <td class="text-right">{{$values['total_pvt_land']}}</td>
                                <td class="text-right">{{$values['Grand_total']}}</td>
                                </tr>
                                
                              @endforeach
                            
                              
                                      
                              </tbody>
                              
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>

<script type="text/javascript">

$(".datepicker1").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years"
});

$( ".datepicker2" ).datepicker({
  dateFormat: "yy-mm-dd",
  showOtherMonths: true,
  selectOtherMonths: true,
  changeMonth: true,
  changeYear: true
});


$('.select2').select2({
  placeholder: 'Choose one',
  searchInputPlaceholder: 'Search options'
});

 </script>
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
       window.open('/report13/export/'+ $("#startDate").val(),'_blank');
     }
   });
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


@endsection