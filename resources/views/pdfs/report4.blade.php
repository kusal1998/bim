@extends('layouts.reports')
{{-- @inject('UtilitiService', 'App\Services\UtilitiService') --}}

@section('content')

<div class="panel panel-primary">
  <h4 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.land_title_settlement')}}</u></h4>
  <h5 id="section1" class="mg-b-10 text-center"><u>{{trans('sentence.Summary_of_the_lots_in_the_cut_off_map')}}</u></h5>
 <h6 id="section1" class="mg-b-10 text-center">@if(isset($formatteDate)){{$formatteDate}} @endif </h6>
</div>

<div class="row row-xs">
  <div class="col mg-t-10">
          {{-- card-dashboard-table --}}
          <div class="card ">
            <div class="table-responsive">
              <table class="table table-bordered text-center" >
                <thead>
                  <tr>
                    <th rowspan="2" class="wd-100">{{trans('sentence.map_number')}}</th>
                    <th rowspan="2" class="wd-100">{{trans('sentence.block_number')}}</th>
                    <th colspan="3">{{trans('sentence.lot_no')}}</th>
                    <th colspan="3">{{trans('sentence.Sent_to_Main_Office')}}</th>
                    <th colspan="3">{{trans('sentence.To_be_completed')}}</th>
                  </tr>
                  <tr>


                    <th class="wd-80">{{trans('sentence.private')}}</th>
                    <th class="wd-80">{{trans('sentence.goverment')}}</th>
                    <th class="wd-80">{{trans('sentence.total')}}</th>
                    <th class="wd-80">{{trans('sentence.private')}}</th>
                    <th class="wd-80">{{trans('sentence.goverment')}}</th>
                    <th class="wd-80">{{trans('sentence.total')}}</th>
                    <th class="wd-80">{{trans('sentence.private')}}</th>
                    <th class="wd-80">{{trans('sentence.goverment')}}</th>
                    <th class="wd-80">{{trans('sentence.total')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($elements['ag_divisions'] as $record)
                    <tr>
                        <td colspan="11" align="left">{{trans('sentence.AG_Office')}}: {{$record['name']}}</td>
                    </tr>
                    @foreach($record['officers'] as $officer)
                    @foreach($officer['jobs'] as $job)
                  <tr>
                          <th>{{$job['map']}}</th>
                    <td>{{$job['block']}}</td>
                    <td><strong>{{$job['lot_string_pvt']}}</strong></td>
                    <td><strong>{{$job['lot_string_govt']}}</strong></td>
                    <td><strong>{{$job['total']}}</strong></td>
                    <td><strong>{{$job['lot_string_pvt_sent']}}</strong></td>
                    <td><strong>{{$job['lot_string_govt_sent']}}</strong></td>
                    <td><strong>{{$job['total_sent']}}</strong></td>
                    <td><strong>{{$job['lot_string_pvt_rem']}}</strong></td>
                    <td><strong>{{$job['lot_string_govt_rem']}}</strong></td>
                    <td><strong>{{$job['total_rem']}}</strong></td>
                  </tr>
                  @endforeach
                  @endforeach
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