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
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="thead-dark">
                   
                  </thead>
                  <tbody>
                   
                    <tr>
                      <th rowspan="2" class="wd-800 text-center">{{trans('sentence.Activity')}}</th>
                      <th colspan="9" class="text-center" >{{trans('sentence.Extent_of_land')}} </th>
                    </tr>
                    <tr>
                      <th class="text-center">{{trans('sentence.goverment')}}</th>
                      <th class="text-center">{{trans('sentence.private')}}</th>
                      <th class="text-center">{{trans('sentence.total')}}</th>

                    
                  
                    </tr>
                    @foreach($elements as $values)
                    <tr>
                    <td class="text-center">{{trans('sentence.'.$values['activity'])}}</td>
                      <td class="text-right">{{$values['gov_land']}}</td>
                      <td class="text-right">{{$values['pvt_land']}}</td>
                      <td class="text-center">{{$values['total']}}</td>  
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