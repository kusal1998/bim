@extends('layouts.reports')
{{-- @inject('UtilitiService', 'App\Services\UtilitiService') --}}

@section('content')

<div class="panel panel-primary">
 <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
          <h5 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.Title_decision_recommendations')}}</u></h5>
 <h6 id="section1" class="mg-b-10 text-center">@if(isset($formatteDate)){{$formatteDate}} @endif </h6>
</div>

<div class="row row-xs">
  <div class="col mg-t-10">
          {{-- card-dashboard-table --}}
          <div class="card ">
            <div class="table-responsive">
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

<br>
<p><b>Notes:</b>

    <p>This is an electronically generated report, hence does not require signature </p>

    @endsection