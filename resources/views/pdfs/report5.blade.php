@extends('layouts.reports')
{{-- @inject('UtilitiService', 'App\Services\UtilitiService') --}}

@section('content')

{{-- @include('reports.logo') --}}
<div class="panel panel-primary">
 <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
 <h5 id="section1" class="mg-b-10 text-center"><u>{{$fdate}} {{trans('sentence.from')}} {{$tdate}} {{trans('sentence.to_date')}} {{trans('sentence.quantity_of_cut_off_maps')}}</u> </h5>

</div>

@php
$date = date('Y-m-d H:i:s');
$y = date('Y-m');
@endphp
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
                        <th rowspan="2" class="wd-100 text-center">{{trans('sentence.district')}}</th>
                        <th rowspan="2" class="wd-100 text-center">{{trans('sentence.ag_division')}}</th>
                        <th rowspan="2" class="text-center" >{{trans('sentence.no_of_maps')}}</th>
                        <th colspan="3" class="text-center" >{{trans('sentence.no_of_lots')}}</th>
                      </tr>
                      <tr>
                          <th class="text-center">{{trans('sentence.private')}}</th>
                          <th class="text-center">{{trans('sentence.goverment')}}</th>
                          <th class="text-center">{{trans('sentence.total')}}</th>
                      </tr>
                      @foreach($elements as $attribute)
                      @php
                      $distric=App\Models\Districts::where('id',$attribute->district)->first();
                      $aglist =App\Models\Form12::selectRaw('COUNT(map_no) as mp,SUM(government_lands) as gl,SUM(private_lands) as
                      pl,SUM(total_lands) as total,ag_division as agdivision')
                      ->where('rejected',0)
                      ->where('district_id',$attribute->district)
                      ->groupBy('agdivision')->get();
                      $rowsCount=sizeof($aglist);
                      @endphp
                      <tr>
                          <td class="text-center" rowspan={{$rowsCount}}>{{$distric->sinhala_name}}</td>
                          @foreach($aglist as $values)
                          @php
                          $agoffice=App\Models\AgDivisions::where('id',$values->agdivision)->first();
                          @endphp
                        <td>{{$agoffice->sinhala_name}}</td>
                      <td>{{$values->mp}}</td>
                      <td>{{$values->pl}}</td>
                      <td>{{$values->gl}}</td>
                      <td>{{$values->total}}</td>
                      </tr>
                      @endforeach
                      @endforeach
                            
                    </tbody>
                    <tfoot class="text-right">
                          <tr>
                            <td class="text-right" colspan="2">Total</td>
                          <td><b>{{$grandMpTot}}</b></td>
                          <td><b>{{$grandPlTot}}</b></td>
                          <td><b>{{$grandGlTot}}</b></td>
                          <td><b>{{$grandTotal}}</b></td>
                          </tr>
                        </tfoot>
                  </table>
                </div><!-- table-responsive -->
              </div><!-- card -->
          </div>

</div>


<br>
<p><b>Notes:</b>

    <p>This is an electronically generated report, hence does not require signature </p>

    @endsection