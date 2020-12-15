@extends('layouts.reports')
{{-- @inject('UtilitiService', 'App\Services\UtilitiService') --}}

@section('content')
<div class="panel panel-primary">
    <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
    <h5 id="section1" class="mg-b-10 text-center"><u>{{$fdate}} {{trans('sentence.to_date')}} {{$tdate}} {{trans('sentence.Progress_of_the_day')}}</u> </h5>
</div>

<div class="row row-xs">
    <div class="col mg-t-10">
              {{-- card-dashboard-table --}}
              <div class="card ">
                <div class="">
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

<br>
<p><b>Notes:</b>

    <p>This is an electronically generated report, hence does not require signature </p>

    @endsection