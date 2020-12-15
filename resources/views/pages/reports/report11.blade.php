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
           </div>
          </div>
          <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
          <h5 id="section1" class="mg-b-10 text-center"><u>{{$fdate}}  {{trans('sentence.from')}} {{$tdate}} {{trans('sentence.to_date')}} {{trans('sentence.amount_published_in_government_gazette')}}</u> </h5>
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
                                  <th rowspan="2" class="wd-100 text-center">{{trans('sentence.AG_Office')}} </th>
                                  <th colspan="9" class="text-center" >{{trans('sentence.Gazetted_Land_Information')}} </th>
                                </tr>
                                <tr>
                                  <th class="text-center">{{trans('sentence.file_number')}}</th>
                                  <th class="text-center">{{trans('sentence.map_number')}}</th>
                                  <th class="text-center">{{trans('sentence.block_number')}}</th>

                                  <th class="text-center">{{trans('sentence.lot_no')}}</th>
                                  <th class="text-center">{{trans('sentence.name_and_address_other')}}</th>
                                  <th class="text-center">{{trans('sentence.Date_sent_to_Publication')}}</th>
                                 
                                  <th class="text-center">{{trans('sentence.gazzette_number')}}</th>
                                  <th class="text-center">{{trans('sentence.gazzetted_date')}}</th>
                                  <th class="text-center">{{trans('sentence.Date_Uploaded_to_Internet')}}</th>
                              
                                </tr>
                                
                                @foreach($elements as $attribute)
                                @php
                                $agdivision=App\Models\AgDivisions::where('id',$attribute->agdivision)->first();
                                $form12=App\Models\Form12::where('ag_division',$attribute->agdivision)->get();
                                @endphp
                                <tr>
                                  <td><a href="">Balangoda</a></td>
                                  <td class="text-right">350</td>
                                  <td class="text-right">22</td>
                                  <td class="text-right">5</td>
                                  <td class="text-right">5</td>
                                  <td class="text-center">Kumar <br> Sri Lanka.</td>
                                  <td class="text-center">2019-10-03</td>
                                  <td class="text-center">3000</td>
                                  <td class="text-center">2019-10-03</td>
                                  <td class="text-center">2019-10-03</td>
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
  dateFormat: "yy-mm-dd",
  showOtherMonths: true,
  selectOtherMonths: true,
  changeMonth: true,
  changeYear: true
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