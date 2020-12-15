@extends('layouts.reports')
{{-- @inject('UtilitiService', 'App\Services\UtilitiService') --}}

@section('content')

{{-- @include('reports.logo') --}}
<div class="panel panel-primary">
 {{--    <div class="panel-heading" style="text-align:center"><b>ITEMS REPORT</b></div> --}}
 <h4 id="section1" class="mg-b-10 text-center">Land Title Settlement</h4>
 <h5 id="section1" class="mg-b-10 text-center">Report Title Here </h5>
 <h6 id="section1" class="mg-b-10 text-center">2019-01-01 to 2019-12-31 </h6>
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
                        <th rowspan="2" class="wd-100 text-center">AG Office</th>
                        <th colspan="9" class="text-center" >Gazetted Land Information </th>
                      </tr>
                      <tr>
                        <th class="text-center">File No</th>
                        <th class="text-center">Map No</th>
                        <th class="text-center">Zonal No</th>

                        <th class="text-center">Block No</th>
                        <th class="text-center">Name & Address</th>
                        <th class="text-center">Date sent to Publication</th>
                       
                        <th class="text-center">Gazetted No</th>
                        <th class="text-center">Gazetted Date</th>
                        <th class="text-center">Date Uploaded to Internet</th>
                    
                      </tr>
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