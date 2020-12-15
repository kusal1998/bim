@extends('layouts.reports')
{{-- @inject('UtilitiService', 'App\Services\UtilitiService') --}}

@section('content')

<div>
  <h3 align="center">1998 අංක 21 දරණ හිමිකම් ලියාපදිංච් කීරීමේ පනත</h3>
  <h3 align="center"> දහහතර වන වගන්තිය යටතේ හිමිකම් නිරවුල් කිරීමේ කොමසාරිස්ගේ තීරණ ප්‍රකාශය</h3>
</div>
<div class="panel panel-primary">
<p> {{$file14Header->province_name}} පළාතේ,{{$file14Header->districts_name}} දිස්ත්‍රික්කයේ,{{$file14Header->ag_name}} ප්‍රදේශීය කොට්ටාසයේ,{{$file14Header->gn_name}} ග්‍රමනිලදාරි කොට්ටසය තුළ
,{{$file14Header->village_name}}පිහිටියා වූ ද,{{$file14Header->map_no}} දරණ කඩස්තර සිතියමේ
{{$file14Header->block_no}},දරන ඉඩම් කොටස ලෙස පෙන්නුම් කොට ඇත්තා වුද, හිමිකම් පෑම් ඉදිරිපත් කරන ලෙස දැනුම්
දෙමින් 1998 අංක 21 දරන හිමිකම් ලියාපදිංච් කිරීමේ පනතේ 12 වැනි වගන්තිය ප්‍රකාර _________ වැනි දින අංක _________
දරන ගැසට් පත්‍රයේ යථා පරිදි පලකරන ලද ___________ දරන දැන්වීමේ සදහන් කොට ඇත්තා වූ ද, ඉඩම් කොටස් වල අයිතිය සම්බන්ධයෙන්
 මෙහි උපලේකනයේ දැක්වෙන මාගේ තීරණ, 1998 අංක 21 දරන හිමිකම් ලියාපදිංච් කිරීමේ පනතේ 14 වැනි වගන්තියෙන් පහත අත්සන්
 කරන මා වෙත පවරා ඇති බලතල ප්‍රකාර  මම මෙයින් ප්‍රකාශ කරම්.
 </p>
<h6 id="section1" class="mg-b-10 text-center">Private Lands:{{$file14Header->private_lands}}</h6>
<h6 id="section1" class="mg-b-10 text-center">Governments Lands:{{$file14Header->governments_lands}}</h6>
<h6 id="section1" class="mg-b-10 text-center">Total Lands:{{$file14Header->total_lands}}</h6>
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
                <table class="table table-bordered text-center" id="tbl" > 
                  <thead class="thead-dark">
                        <tr>
                            <th class="wd-5p">{{trans('sentence.lot_no')}} </th>
                            <th class="wd-5p">{{trans('sentence.size')}} </th>
                            <th class="wd-20p">{{trans('sentence.name')}}</th>
                            <th class="wd-10p">{{trans('sentence.address')}}</th>
                            <th class="wd-10p">{{trans('sentence.Owner_nic')}}</th>
                            <th class="">{{trans('sentence.nature_of_ownership')}}</th>
                            <th class="">{{trans('sentence.Class')}}</th>
                            <th class="">{{trans('sentence.mortgages')}}</th>
                            <th >{{trans('sentence.other_bonds')}} </th>  
                        </tr>
                  </thead>
                  <tbody>
                    @foreach($elements as $attribute)
                    <tr>
                        <td>{{$attribute->lot_no}} </td>
                        <td>{{$attribute->size}} </td>
                        <td>{{$attribute->name}} </td>
                        <td>{{$attribute->addres}} </td>
                        <td>{{$attribute->nic_number}} </td>
                        <td>{{$attribute->ownership_type}} </td>
                        <td>{{$attribute->class}} </td>
                        <td>{{$attribute->mortgages}} </td>
                        <td>{{$attribute->other_boudages}} </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div><!-- table-responsive -->
            </div><!-- card -->
          </div>

</div>
<br>
<div class="col-xs-12">
    <table class="table">
            <thead>
                <tr>
                    <th>Prepared By</th>
                    <th>Checked By    </th>
                    <th>Approval By</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>@if(isset($prepared_by)){{$prepared_by->name}}@endif</td>
                <td>@if(isset($check_by)){{$check_by->name}}@endif</td>
                <td>@if(isset($approval_by)){{$approval_by->name}}@endif</td>
                </tr>
                <tr>
                    <td>@if(isset($file14Header->prepared_date)){{date('Y-m-d', strtotime($file14Header->prepared_date))}}@endif</td>
                    <td>@if(isset($file14Header->regional_checked_date)){{date('Y-m-d', strtotime($file14Header->regional_checked_date))}}@endif</td>
                    <td>{{date('Y-m-d', strtotime($approvalDate))}}</td>
                </tr>
            </tbody>
        </table>
</div>
<p><b>Notes:</b>

    <p>This is an electronically generated report, hence does not require signature </p>

    @endsection
