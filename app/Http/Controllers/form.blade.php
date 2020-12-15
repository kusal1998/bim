@extends('layouts.wyse2')
@section('optional_css')
@include('css.datepicker')
@include('css.timepicker')
@endsection
@section('content')

@inject('UtilityService', 'App\Services\UtilityService')
<div class="content content-fixed">
    @php
    $title = Request::segment(1);
    $singular = str_singular(Request::segment(1));
    $url = Request::segment(1);
    @endphp
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Amendments Sentence</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.amendments')}}</h4>
                <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.title_registration_act_no_21_of_1998')}}</h4>
                <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.article_14')}}</h4>
                <h4 class="mg-b-0 tx-spacing--1">
                    {{trans('sentence.declaration_of_the_commissioner_of_title_settlement')}}</h4>
            </div>
            <div class="d-none d-md-block">
                @include('buttons._back')
            </div>
        </div>

        <div data-label="Example" class="df-example" id="tabs">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Amendments Form Details</a>
                </li>
                @if(Request::segment(2)!='create' )
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Related Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab"
                        aria-controls="history" aria-selected="false">Form History</a>
                </li>
                @endif
            </ul>
            <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="post" @if(Request::segment(2)=='create' ) action="{{ route($url.'-store') }}" @else
                        action="{{ route($url.'-update',$element->id) }}" @endif novalidate="" _lpchecked="1">
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <label for="province_id">{{trans('sentence.province')}}</label>
                                @if(Request::segment(2)=='create')
                                @if(trans('sentence.lang')=='EN')
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getProvinceByUser()->province_name}}"/>
                                @else
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getProvinceByUser()->sinhala_name}}"/>
                                @endif
                                <input type="hidden" name="province_id" value="{{$UtilityService->getProvinceByUser()->id}}"/>
                                @else
                                <select id="province_id" @if((Request::segment(2)=='view') || (Request::segment(2)=='update')) disabled @endif class="form-control form-control-sm select2"
                                name="province_id" required>
                                @if(trans('sentence.lang')=='EN')
                                <option value="">Please Select</option>
                                @foreach ($province as $item)
                                <option value="{{$item->id}}" @if(isset($element))
                                    @if(old('province_id',$element->id)==$item->id)
                                    selected="selected"
                                    @endif @endif
                                    @if(Request::segment(2)!='create' )@if($form12->province_id==$item->id) selected @endif @endif
                                    >{{$item->province_name}}</option>
                                @endforeach
                                @else
                                <option value="">Please Select</option>
                                @foreach ($province as $item)
                                <option value="{{$item->id}}" @if(isset($element))
                                    @if(old('province_id',$element->id)==$item->id)
                                    selected="selected"
                                    @endif @endif
                                    @if(Request::segment(2)!='create' )@if($form12->province_id==$item->id) selected @endif @endif
                                    >{{$item->sinhala_name}}</option>
                                @endforeach
                                @endif
                            </select>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="district_id">{{trans('sentence.district')}}</label>
                                @if(Request::segment(2)=='create')
                                @if(trans('sentence.lang')=='EN')
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getDistrictByUser()->districts_name}}"/>
                                @else
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getDistrictByUser()->sinhala_name}}"/>
                                @endif
                                <input type="hidden" name="district_id" value="{{$UtilityService->getDistrictByUser()->id}}"/>
                                @else
                                <select id="district_id" disabled @if((Request::segment(2)=='view') || (Request::segment(2)=='update')) disabled @endif class="form-control form-control-sm select2"
                                name="district_id" required>
                                @if(trans('sentence.lang')=='EN')
                                <option value="">Please Select</option>
                                @foreach ($district as $item)
                                <option value="{{$item->id}}" @if(isset($element))
                                    @if(old('district_id',$element->id)==$item->id)
                                    selected="selected"
                                    @endif @endif
                                    @if(Request::segment(2)!='create' )@if($form12->district_id==$item->id) selected @endif @endif
                                    >{{$item->districts_name}}</option>
                                @endforeach
                                @else
                                <option value="">Please Select</option>
                                @foreach ($district as $item)
                                <option value="{{$item->id}}" @if(isset($element))
                                    @if(old('district_id',$element->id)==$item->id)
                                    selected="selected"
                                    @endif @endif
                                    @if(Request::segment(2)!='create' )@if($form12->district_id==$item->id) selected @endif @endif
                                    >{{$item->sinhala_name}}</option>
                                @endforeach
                                @endif
                            </select>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="ag_division_id">{{trans('sentence.ag_division')}}</label>
                                @if(Request::segment(2)=='create')
                                @if(trans('sentence.lang')=='EN')
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getAgDivByUser()->ag_name}}"/>
                                @else
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getAgDivByUser()->sinhala_name}}"/>
                                @endif
                                <input type="hidden" name="ag_div_id" value="{{$UtilityService->getAgDivByUser()->id}}"/>
                            @else
                                <select id="ag_div_id" disabled @if(Request::segment(2)=='view' ) readonly @endif name="ag_div_id" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="ag_div_id" required>
                                    @if(trans('sentence.lang')=='EN')
                                    <option value="">Please Select</option>
                                  @foreach ($agDivision as $item)
                                  <option value="{{$item->id}}" @if(isset($element))
                                      @if(old('ag_div_id',$element->id)==$item->id)
                                      selected="selected"
                                      @endif @endif
                                      @if(Request::segment(2)!='create' )@if($form12->ag_division_id==$item->id) selected @endif @endif
                                      >{{$item->ag_name}}</option>
                                  @endforeach
                                  @else
                                  <option value="">Please Select</option>
                                  @foreach ($agDivision as $item)
                                  <option value="{{$item->id}}" @if(isset($element))
                                      @if(old('ag_div_id',$element->id)==$item->id)
                                      selected="selected"
                                      @endif @endif
                                      @if(Request::segment(2)!='create' )@if($form12->ag_division_id==$item->id) selected @endif @endif
                                      >{{$item->sinhala_name}}</option>
                                  @endforeach
                                  @endif
                              </select>
                            @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="gn_division_id">{{trans('sentence.gn_Division')}}</label>
                                <select id="gn_div_id" disabled name="gn_div_id[]" disabled
                                     class="form-control form-control-sm select2" required multiple>
                                     @if(trans('sentence.lang')=='EN')
                                        <option value="">Please Select</option>
                                        @foreach ($gnDivision as $item)
                                        <option value="{{$item->id}}" @if(isset($element))
                                            @if(old('gn_division_id',$element->gn_division_id)==$item->id)
                                            selected="selected"
                                            @endif @endif
                                             @if(Request::segment(2)!='create' ) @foreach(explode(',',$element->gn_division_id) as $str)@if($str==$item->id) selected @endif @endforeach @endif>{{$item->gn_name}}</option>
                                        @endforeach
                                        @else
                                        <option value="">Please Select</option>
                                        @foreach ($gnDivision as $item)
                                        <option value="{{$item->id}}" @if(isset($element))
                                            @if(old('gn_division_id',$element->gn_division_id)==$item->id)
                                            selected="selected"
                                            @endif @endif
                                             @if(Request::segment(2)!='create' ) @foreach(explode(',',$element->gn_division_id) as $str)@if($str==$item->id) selected @endif @endforeach @endif>{{$item->sinhala_name}}</option>
                                        @endforeach
                                        @endif
                                  </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="map_no">{{trans('sentence.map_number')}}</label>
                                {{-- <input type="text" pattern="\d*" minlength="6" maxlength="6" required autocomplete="off" class="form-control" name="map_no" id="map_no" value="@if(isset($element)){{$element->map_no}} @endif" placeholder=""> --}}
                                <select id="map_no"  @if(Request::segment(2)=='view' ) readonly @endif name="map_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="map_no" required>
                                    <option value="">Please Select</option>
                                    @foreach ($map_numbers as $item)
                                    <option value="{{$item->map_no}}" @if(isset($element))
                                        @if(old('map_no',$element->map_no)==$item->map_no)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' )@if($form12->map_no==$item->map_no) selected @endif @endif
                                        >{{$item->map_no}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="block_no">{{trans('sentence.block_number')}}</label>
                                {{-- <input type="text" pattern="\d*" minlength="2" maxlength="2" required autocomplete="off" class="form-control" name="block_no" id="block_no" value="@if(isset($element)){{$element->block_no}} @endif" placeholder=""> --}}
                                <select id="block_no"  @if(Request::segment(2)=='view' ) readonly @endif name="block_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="block_no" required>
                                    <option value="">Please Select</option>
                                    @foreach ($block_numbers as $item)
                                    <option value="{{$item->block_no}}" @if(isset($element))
                                        @if(old('block_no',$element->block_no)==$item->block_no)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' )@if($form12->block_no==$item->block_no) selected @endif @endif
                                        >{{$item->block_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="lot_no">{{trans('sentence.lot_no')}}</label>
                                {{-- <input type="text" pattern="\d*" minlength="4" maxlength="4" required autocomplete="off"  class="form-control" name="lot_no" id="lot_no" value="@if(isset($element)){{$element->lot_no}} @endif" placeholder=""> --}}
                                <select id="lot_no"  @if(Request::segment(2)=='view' ) readonly @endif name="lot_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="lot_no" required>
                                    <option value="">Please Select</option>
                                    @foreach ($lot_numbers as $item)
                                    <option value="{{$item->lot_no}}" @if(isset($element))
                                        @if(old('lot_no',$element->lot_no)==$item->lot_no)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' )@if($element->lot_no==$item->lot_no) selected @endif @endif
                                        >{{$item->lot_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="village">{{trans('sentence.village')}}</label>
                                <input type="text" required autocomplete="off" class="form-control" name="village" id="village" value="@if(isset($element)){{$element->village}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="_14_gazette_date">{{trans('sentence.gazzetted_date')}}</label>
                                <input type="text" required autocomplete="off" class="form-control datepicker1" id="_14_gazette_date"
                                name="_14_gazette_date" value="@if(isset($element)){{date('Y-m-d',strtotime($element->_14_gazette_date))}} @endif">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="_14_gazzert">{{trans('sentence.gazzette_number')}}</label>
                                <input type="text" required class="form-control" id="_14_gazzert" name="_14_gazzert" value="@if(isset($element)){{$element->_14_gazzert}} @endif">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="regional_officer">{{trans('sentence.regional_officer')}}</label>
                                <input type="text" required class="form-control" id="regional_officer" name="regional_officer" value="@if(isset($amendmentRegenioalOfficer)){{$amendmentRegenioalOfficer->name}} @endif">
                        </div>
                            <div class="form-group col-md-6">
                                <label for="column_name">{{trans('sentence.column_name')}}</label>
                                <select id="column" class="form-control form-control-sm select2" name="column_name[]" multiple @if(Request::segment(2)!='create' && $element->current_stage!='Regional data entry') disabled @endif>
                                        <option value="">Please Select</option>
                                    @if(isset($element))
                                    @foreach(explode(',',$element->column_name) as $str)
                                    <!--<option value="lot_no" @if($str=='lot_no') selected @endif>{{trans('sentence.lot_no')}}</option>-->
                                    <option value="type" @if($str=='type') selected @endif >{{trans('sentence.land_type')}}</option>
                                    <option value="sub_type" @if($str=='sub_type') selected @endif>{{trans('sentence.land_sub_type')}}</option>
                                    <!--<option value="gns" @if($str=='gns') selected @endif>{{trans('sentence.gn_Division')}}</option>-->
                                    <option value="size" @if($str=='size') selected @endif>{{trans('sentence.size')}}</option>
                                    <option value="class" @if($str=='class') selected @endif>{{trans('sentence.Class')}}</option>
                                    <option value="ownership_type" @if($str=='ownership_type') selected @endif>{{trans('sentence.nature_of_ownership')}}</option>
                                    <option value="nic_number" @if($str=='nic_number') selected @endif>{{trans('sentence.Owner_nic')}}</option>
                                    <option value="name" @if($str=='name') selected @endif>{{trans('sentence.name')}}</option>
                                    <option value="addres" @if($str=='addres') selected @endif>{{trans('sentence.address')}}</option>
                                    <option value="mortgages" @if($str=='mortgages') selected @endif>{{trans('sentence.mortgages')}}</option>
                                    <option value="other_boudages" @if($str=='other_boudages') selected @endif>{{trans('sentence.other_bonds')}}</option>
                                    @endforeach
                                    @else
                                    <!--<option value="lot_no" >{{trans('sentence.lot_no')}}</option>-->
                                    <option value="type" >{{trans('sentence.land_type')}}</option>
                                    <option value="sub_type" >{{trans('sentence.land_sub_type')}}</option>
                                    <!--<option value="gns" >{{trans('sentence.gn_Division')}}</option>-->
                                    <option value="size" >{{trans('sentence.size')}}</option>
                                    <option value="class" >{{trans('sentence.Class')}}</option>
                                    <option value="ownership_type" >{{trans('sentence.nature_of_ownership')}}</option>
                                    <option value="nic_number">{{trans('sentence.Owner_nic')}}</option>
                                    <option value="name" >{{trans('sentence.name')}}</option>
                                    <option value="addres" >{{trans('sentence.address')}}</option>
                                    <option value="mortgages" >{{trans('sentence.mortgages')}}</option>
                                    <option value="other_boudages" >{{trans('sentence.other_bonds')}}</option>
                                    @endif
                                </select>
                                {{-- @if(Request::segment(2)=='update')
                                <input type="hidden"  class="form-control" name="column_name"  value="@if(isset($element)){{$element->column_name}} @endif" placeholder="">
                                @endif --}}
                            </div>
                        </div>
                        <div class="form-row">
                            @if(Request::segment(2)!='create' )
                             <div class="form-group col-md-12">
                                <label for="column_value_to_be_changed">{{trans('sentence.column_value_to_be_changed')}}</label>
                                <input type="text" required class="form-control" disabled name="column_value_to_be_changed" id="column_value_to_be_changed" value="@if(isset($element)){{$element->column_value_to_be_changed}} @endif" placeholder="">
                            </div>
                            <!--<div class="form-group col-md-12">-->
                            <!--    <label for="column_new_value">{{trans('sentence.column_value_new')}}</label>-->
                            <!--    <input type="text" required class="form-control" disabled name="column_new_value" id="column_new_value" value="@if(isset($element)){{$element->column_new_value}} @endif" placeholder="">-->
                            <!--</div> -->
                            @endif
                            <div class="form-group col-md-12">
                                <label for="reasons">{{trans('sentence.reasons')}}</label>
                                <input type="text" required class="form-control" autocomplete="off" name="reasons" id="reasons" value="@if(isset($element)){{$element->reasons}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="complain_date">{{trans('sentence.date')}}</label>
                                <input type="text" required class="form-control datepicker1" autocomplete="off" name="complain_date" id="complain_date" value="@if(isset($element))@if(isset($element->complain_date)){{date('Y-m-d',strtotime($element->complain_date))}} @endif @endif"
                                    placeholder="">
                            </div>
                            @if($UtilityService->getAccessGazette(Request::segment(1))&&($form12->current_stage=='Gov Press without G'))
                            <div class="form-group col-md-4">
                                <label for="gazette_no">{{trans('sentence.amendement_gazzette_number')}}</label>
                                <input type="text" required class="form-control" name="gazette_no" id="gazette_no" value="@if(isset($element)){{$element->gazette_no}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="gazette_date">{{trans('sentence.amendement_gazzette_date')}}</label>
                                <input type="text" required class="form-control datepicker3" name="gazette_date" id="gazette_date" value="@if(isset($element)){{$element->gazette_date}} @endif">
                            </div>
                            @endif
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="lot_no">{{trans('sentence.lot_no')}}</label>
                            <input type="text" autocomplete="off" class="form-control" name="new[lot_no]" id="lot_number" value="@if(isset($newelementDetails)){{$newelementDetails->lot_no}} @endif" placeholder="">
                            <input type="hidden" autocomplete="off" class="form-control" name="new[lot_no]" id="hide_lot_number" value="@if(isset($newelementDetails)){{$newelementDetails->lot_no}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="lot_no">{{trans('sentence.land_type')}}</label>
                                    <select id="type" name="new[land_type]" class="form-control form-control-sm" required>
                                            <option value="">{{trans('sentence.please_select')}}</option>
                                            <option value="Government" @if(isset($newelementDetails))@if($newelementDetails->type=="Government") selected  @endif @endif>{{trans('sentence.gov')}}</option>
                                            <option value="Private" @if(isset($newelementDetails))@if($newelementDetails->type=="Private") selected  @endif @endif>{{trans('sentence.pvt')}}</option>
                                    </select>
                                    <input type="hidden" autocomplete="off" class="form-control" name="new[land_type]" id="hide_type" value="@if(isset($newelementDetails)){{$newelementDetails->type}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="sub_type">{{trans('sentence.land_sub_type')}}</label>
                                    <select id="sub_type" name="new[sub_type]" class="form-control form-control-sm" required>
                                    </select>
                                    <input type="hidden" autocomplete="off" class="form-control" name="new[sub_type]" id="hide_sub_type" value="@if(isset($newelementDetails)){{$newelementDetails->sub_type}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="size">{{trans('sentence.size')}}</label>
                                <input type="text" class="form-control" id="size" name="new[size]" value="@if(isset($newelementDetails)){{$newelementDetails->size}} @endif">
                                <input type="hidden" autocomplete="off" class="form-control" name="new[size]" id="hide_size" value="@if(isset($newelementDetails)){{$newelementDetails->size}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="class">{{trans('sentence.Class')}}</label>
                                {{-- <input type="text" class="form-control" id="class" name="class"> --}}
                                <select id="class" name="new[class]" class="form-control form-control-sm" required>
                                <option value="">{{trans('sentence.please_select')}}</option>
                                <option value="1st_Class" @if(isset($newelementDetails))@if($newelementDetails->class=="1st_Class")selected @endif @endif>{{trans('sentence.1st_class')}}</option>
                                <option value="2nd_Class" @if(isset($newelementDetails))@if($newelementDetails->class=="2nd_Class")selected @endif @endif>{{trans('sentence.2nd_class')}}</option>
                                </select>
                                <input type="hidden" autocomplete="off" class="form-control" name="new[class]" id="hide_class" value="@if(isset($newelementDetails)){{$newelementDetails->class}} @endif" placeholder="">
                            </div>
                           
                            <div class="form-group col-md-3">
                                    <label for="owner_details_gn_div_id">{{trans('sentence.gn_Division')}}</label>
                                    <select id="owner_details_gn_div_id" name="new[owner_details_gn_division_id][]" @if((Request::segment(2)=='view' ))
                                        disabled @endif class="form-control form-control-sm select2" multiple required>
                                        <!--<option value="">Please Select</option>-->
                                         @if(Request::segment(2)!='create' )
                                            @for($i=0;$i<sizeof($amdGnDivision);$i++)
                                                <option value="{{$amdGnDivision[$i]->id}}" @if($newelementDetails) @foreach(explode(',',$newelementDetails->owner_details_gn_division_id) as $str)@if($str==$amdGnDivision[$i]->id) selected @endif @endforeach @endif>{{$amdGnDivision[$i]->gn_name}}</option>
                                            @endfor
                                         @endif
                                    </select>
                                    <input type="hidden" autocomplete="off" class="form-control" name="new[owner_details_gn_division_id][]" id="hide_owner_details_gn_div_id" value="@if(isset($newelementDetails)){{$newelementDetails->owner_details_gn_division_id}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nic">{{trans('sentence.nature_of_ownership')}}</label>
                                <select id="natowner" name="new[natowner]" class="form-control form-control-sm" required>
                                    <option value="">{{trans('sentence.please_select')}}</option>
                                    <option value="full" @if(isset($newelementDetails))@if($newelementDetails->ownership_type=='full')selected @endif @endif >{{trans('sentence.full_ownership')}}</option>
                                    <option value="Equal" @if(isset($newelementDetails))@if($newelementDetails->ownership_type=='Equal')selected @endif @endif>{{trans('sentence.equal_ownership')}}</option>
                                    <option value="Disproportionate" @if(isset($newelementDetails))@if($newelementDetails->ownership_type=='Disproportionate')selected @endif @endif>{{trans('sentence.disproportionate_ownership')}}</option>
                                </select>
                                <input type="hidden" autocomplete="off" class="form-control" name="new[natowner]" id="hide_natowner" value="@if(isset($newelementDetails)){{$newelementDetails->ownership_type}} @endif" placeholder="">
                            </div>

                            <div class="row col-md-12 nicdetails" id="Equal" style="display:none">
                            <div class="form-group col-md-3">
                                <label for="natowner">{{trans('sentence.Owner_nic')}}</label>
                                <input type="text" class="form-control" id="id_number"  autocomplete="off" />
                            </div>
                            <div class="form-group col-md-3">
                                <label for="name">{{trans('sentence.name')}} </label>
                                <textarea type="text" class="form-control" id="owner_name" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="address">{{trans('sentence.address')}}</label>
                                <textarea type="text" class="form-control" id="owner_address" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="natowner">{{trans('sentence.owenrship_size')}}</label>
                                <input type="text" class="form-control" id="owner_size" autocomplete="off" />

                            </div>
                            <div class="form-group col-md-1">
                                    <label for="other">ADD</label>
                                    <input type="button" id="ownership_add" class="btn btn-success" value="+">
                            </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="natowner">{{trans('sentence.Owner_nic')}}</label>
                            <textarea type="text" class="form-control" id="nic" name="new[nic]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->nic_number}}@endif</textarea>
                            <input type="hidden" autocomplete="off" class="form-control" name="new[nic]" id="hide_nic" value="@if(isset($newelementDetails)){{$newelementDetails->nic_number}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name">{{trans('sentence.name')}} </label>
                                <textarea type="text" class="form-control" id="name" name="new[name]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->name}}@endif</textarea>
                                <input type="hidden" autocomplete="off" class="form-control" name="new[name]" id="hide_name" value="@if(isset($newelementDetails)){{$newelementDetails->name}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="address">{{trans('sentence.address')}}</label>
                                <textarea type="text" class="form-control" id="address" name="new[address]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->addres}}@endif</textarea>
                                <input type="hidden" autocomplete="off" class="form-control" name="new[address]" id="hide_address" value="@if(isset($newelementDetails)){{$newelementDetails->addres}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mortgages">{{trans('sentence.mortgages')}}</label>
                                <textarea type="text" class="form-control" id="mortgages" name="new[mortgages]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->mortgages}}@endif</textarea>
                                <input type="hidden" autocomplete="off" class="form-control" name="new[mortgages]" id="hide_mortgages" value="@if(isset($newelementDetails)){{$newelementDetails->mortgages}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="other">{{trans('sentence.other_bonds')}}</label>
                                <textarea type="text" class="form-control" id="other" name="new[other]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->other_boudages}}@endif</textarea>
                                <input type="hidden" autocomplete="off" class="form-control" name="new[other]" id="hide_other" value="@if(isset($newelementDetails)){{$newelementDetails->other_boudages}} @endif" placeholder="">
                            </div>
                        </div>


                        <input type="hidden" class="form-control" name="form_name" id="form_name" value="header" placeholder="">
                        @if(Request::segment(2)=='update')
                            @if($UtilityService->getAccessRegVerify(Request::segment(1))=='Yes'||$UtilityService->getAccessRegApprove(Request::segment(1))=='Yes'||
                                $UtilityService->getAccessPubVerify(Request::segment(1))=='Yes'||$UtilityService->getAccessGazette(Request::segment(1))=='Yes'||$UtilityService->getAccessUpdate(Request::segment(1))=='Yes')
                            @if($form12->current_stage=='Regional officer' || $form12->current_stage=='Regional data entry' || $form12->current_stage=='Regional commissioner'|| $form12->current_stage=='Publication verify'||$form12->current_stage=='Gov Press without G')
                                <button class="btn btn-info" type="submit" name="button" value="save">Save</button>
                                <button class="btn btn-warning">Clear</button>
                            @endif
                            @endif
                        @elseif(Request::segment(2)=='create' )
                        @if($UtilityService->getAccessCreate(Request::segment(1))=='Yes')
                        <button class="btn btn-info" type="submit" id="btn_save" name="button" value="save">Save</button>
                        @endif
                        <button class="btn btn-warning">Clear</button>
                        @endif
                    </form>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form id="main" method="post" @if(Request::segment(2)=='create' )
                        action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$element->id) }}"
                        @endif novalidate="" _lpchecked="1">
                        @csrf
                        <input type="hidden" class="form-control" name="form_name" id="form_name" value="details">
                        <input type="hidden" name="row" id="row" value="0">
                        @if(($UtilityService->getAccessCreate(Request::segment(1))=='Yes') and (Request::segment(2)!='view'))
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nature_if_identification">{{trans('sentence.nature_of_identification')}}</label>
                                <input type="text" class="form-control" name="nature_if_identification" id="nature_if_identification" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    for="document_evidence">{{trans('sentence.documents_evidence_used_for_confirmation')}}</label>
                                <input type="text" class="form-control" id="document_evidence" name="document_evidence">
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    for="parties_noticed">{{trans('sentence.parties_were_noticed_about_the_amendments')}}</label>
                                <input type="text" class="form-control" name="parties_noticed" id="parties_noticed" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    for="conclution">{{trans('sentence.conclution_on_whether_amendments_will_have_an_effect_on_near_lands')}}</label>
                                <input type="text" class="form-control" id="conclution" name="conclution">
                            </div>
                            <div class="form-group col-md-6">
                                <label
                                    for="name_of_the_officer">{{trans('sentence.name_of_the_officer_responsible_for_error')}}</label>
                                <input type="text" class="form-control" id="name_of_the_officer" name="name_of_the_officer">
                            </div>
                          
                            <div class="form-group col-md-1">
                                <label for="other">ADD ROW</label>
                                <input type="button" class="add-row btn btn-success" value="+">
                            </div>
                           
                        </div>
                        @endif
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice bd-b" id="myTable">
                                    <tr>
<th colspan="3"></th>
                                      {{--   <th class="wd-20p">{{trans('sentence.nature_of_identification')}}</th>
                                        <th class="wd-20p">{{trans('sentence.documents_evidence_used_for_confirmation')}}</th>
                                        <th class="wd-20p ">{{trans('sentence.parties_were_noticed_about_the_amendments')}}</th>
                                        <th class="wd-20p">{{trans('sentence.conclution_on_whether_amendments_will_have_an_effect_on_near_lands')}}</th>
                                        <th class="wd-10p">{{trans('sentence.name_of_the_officer_responsible_for_error')}}</th>
                                       --}}  <td>Actions</td>
                                    </tr>

                                <tbody>
                                </tbody>
                            </table>

                        </div>
                        <div class="form-group col-md-3">
                            <label for="comment">Comment</label>
                                <textarea  rows="2" cols="50" class="form-control" id="comment"  name="comment">
                                </textarea>
                         </div>
                        @include('modals.remarks')
                        @include('modals.12recheckmodel')
                        @include('modals.amendmentmodel')
                        @include('modals.amendmentrejectmodel')
                        @include('buttons._action')
                        @include('modals.computerbranch')
                    </form>

                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    @include('pages.form12.aprovalinfo')
                </div>
            </div>
        </div>
    </div>
</div>

</div>


</div>
</section>

@endsection
@section('after_scripts')
<script src="{{ asset('lib/jquery-steps/jquery.steps.min.js') }}"></script>
{{-- @include('scripts.province_district_ag_gn'); --}}
<script>
    $('.select2').select2({
        placeholder: 'Please Select',
        searchInputPlaceholder: 'Search options'
    });

    $(".datepicker1").datepicker({
        dateFormat: "yy-mm-dd"
    });

    $(".datepicker2").datepicker({
        dateFormat: "yy-mm-dd"
    });

    $(".datepicker3").datepicker({
        dateFormat: "yy-mm-dd"
    });

</script>
<script type="text/javascript">
var lan="{{trans('sentence.lang')}}";
    $(document).ready(function () {
        var subtype='@if(isset($newelementDetails)){{$newelementDetails->sub_type}} @endif';
        var gndivisions='@if(isset($newelementDetails)){{$newelementDetails->owner_details_gn_division_id}} @endif';
    
        // Find and remove selected table rows
        $(".delete-row").click(function () {
            $("table tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").remove();
                }
            });
        });
        $('#sub_type').empty().append('<option value="">Please Select</option>');
    if($('#type').val()==="Private")
    {   
         $('#sub_type').append('<option value="Private">{{trans('sentence.Private')}}</option>');
         $('#sub_type').append('<option value="Grant">{{trans('sentence.Grant')}}</option>');
         $('#sub_type').append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type').append('<option value="thrashing_floor">{{trans('sentence.thrashing_floor')}}</option>');
         $('#sub_type').append('<option value="Edges">{{trans('sentence.Edges')}}</option>');
         $('#sub_type').append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type').append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type').append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type').append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
    else if($('#type').val()==="Government")
    {
         $('#sub_type').append('<option value="The_State">{{trans('sentence.The_State')}}</option>');
         $('#sub_type').append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type').append('<option value="Brook">{{trans('sentence.Brook')}}</option>');
         $('#sub_type').append('<option value="Lake">{{trans('sentence.Lake')}}</option>');
         $('#sub_type').append('<option value="River">{{trans('sentence.River')}}</option>');
         $('#sub_type').append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type').append('<option value="Roundabout">{{trans('sentence.Roundabout')}}</option>');
         $('#sub_type').append('<option value="Turning_circle">{{trans('sentence.Turning_circle')}}</option>');
         $('#sub_type').append('<option value="Reservation">{{trans('sentence.Reservation')}}</option>');
         $('#sub_type').append('<option value="Boulder">{{trans('sentence.Boulder')}}</option>');
         $('#sub_type').append('<option value="Licensee">{{trans('sentence.Licensee')}}</option>');
         $('#sub_type').append('<option value="Owned_by_institutions">{{trans('sentence.Owned_by_institutions')}}</option>');
         $('#sub_type').append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type').append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type').append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
        $("#sub_type option[value='"+subtype.trim()+"']").prop('selected',true);
        if($("#lot_no").val()!='' && $("#lot_no").val().length>=4){
                var urlseg1='{{url("/get-form14/")}}';
                $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val()+'/'+$('#lot_no').val(),type:'get', success: function(response) 
                {
                    if(response){
                        if(response.message){
                            alert(response.message);
                        }
                    }
                }
                });
        }
    });

</script>

<script>
    @if(Request::segment(2)!='create' )
        $(document).ready(function(){
            var gndivisions='@if(isset($newelementDetails)){{$newelementDetails->owner_details_gn_division_id}} @endif';
            var gn_array=gndivisions.split(",");        
            var details_list=[];
            var modelhtml='';
            var rejectmodel='';
              $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "{{ url('amendments/details/'.Request::segment(3)) }}",
                type: "GET",
                success: function(data){
                for (i = 0; i <data.message.length; i++) {
                details_list.push(data.message[i]);
                var html='';
                html=html+ "<tr> <th class='wd-20p'>{{trans('sentence.nature_of_identification')}}</th> <th class='wd-20p'>{{trans('sentence.documents_evidence_used_for_confirmation')}}</th></tr><tr scope='row'> <td>"+ " <textarea id='nature_if_identification"+i+"' name='amendmentDetails["+i+"][nature_if_identification]' class='form-control form-control-sm' >" + data.message[i].nature_if_identification + " </textarea></td><td> <textarea id='document_evidence"+i+"' name='amendmentDetails["+i+"][document_evidence]' class='form-control form-control-sm' > " + data.message[i].document_evidence + "</textarea></td><td></td><td><a href='javascript:;' data-toggle='modal' data-target='#ViewModal"+i+"' class='btn btn-icon btn-info'><i class='fas fa-book-open'></i></a> @if(($UtilityService->getAccessRegReject(Request::segment(1))=='Yes')and (Request::segment(2)=='update')) <a href='javascript:;' data-toggle='modal' data-target='#RejectModal"+i+"' class='btn btn-icon btn-danger' title='Approve'><i class='fas fa-times-circle'></i></a>@endif </td></tr><tr> <th class='wd-40p'>{{trans('sentence.parties_were_noticed_about_the_amendments')}}</th> <th class='wd-40p'>{{trans('sentence.conclution_on_whether_amendments_will_have_an_effect_on_near_lands')}}</th> <th class='wd-20p'>{{trans('sentence.name_of_the_officer_responsible_for_error')}}</th></tr><td> <textarea id='parties_noticed"+i+"' name='amendmentDetails["+i+"][parties_noticed]' class='form-control form-control-sm' >" + data.message[i].parties_noticed + " </textarea></td><td> <textarea id='conclution"+i+"' name='amendmentDetails["+i+"][conclution]' type='text' class='form-control form-control-sm' >" + data.message[i].conclution + " </textarea></td><td> <textarea id='name_of_the_officer"+i+"' name='amendmentDetails["+i+"][name_of_the_officer]' class='form-control form-control-sm' >" + data.message[i].name_of_the_officer + " </textarea></td></tr>";

                modelhtml="<div class='modal fade' id='ViewModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>Amendment Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'>"+
                "<div class='row'><div class='col-md-6'><b>{{trans('sentence.nature_of_identification')}}</b><p class='text-danger'>"+data.message[i].nature_if_identification+"</p><b>{{trans('sentence.documents_evidence_used_for_confirmation')}}</b><p class='text-danger'>"+data.message[i].document_evidence+"</p>"+
                "<b>{{trans('sentence.parties_were_noticed_about_the_amendments')}}</b><p class='text-danger'>"+data.message[i].parties_noticed+"</p> </div><div class='col-md-6'> <b>{{trans('sentence.conclution_on_whether_amendments_will_have_an_effect_on_near_lands')}}</b><p class='text-danger'>"+data.message[i].conclution+"</p><b>{{trans('sentence.name_of_the_officer_responsible_for_error')}}</b><p class='text-danger'>"+data.message[i].name_of_the_officer+"</p>"+
                "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>";
                rejectmodel="<form action='{{url('amendment/reject/update')}}' method='get'><div  class='modal fade' id='RejectModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-sm' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>Reject</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='form-group col-md-12'><label for='tot_lands'>Reason</label><input type='hidden' name='detail_id'  value='"+data.message[i].id+"'><input type='text' class='form-control' id='' name='reason'></div></div><div class='modal-footer'><button type='button' class='btn btn-info' data-dismiss='modal'>Cancel</button><button type='submit' name='button' value='reject'  class='btn btn-info'>Save</button></div></div></div></div></form>";
                  $("#myTable > tbody:last-child").append(html);
                  $('#amendmentmodel').append(modelhtml);
                  $('#amendmentrejectmodel').append(rejectmodel);
                  $('#row').val(i);
                }
                $('#row').val(data.message.length);
                }
                });
                $.ajax();

            $(".add-row").click(function () {
                var html='';
                if(find_duplicate()==1)
                {
                    alert('Duplicate Entries!!!');
                    return;
                }
                else
                {
                    if($('#nature_if_identification').val()=='')
                    {
                    alert('Nature of identification is Required');
                    return;
                    }
                    if($('#document_evidence').val()=='')
                    {
                    alert('Document And Evidence is Required');
                    return;
                    }
                    if($('#parties_noticed').val()=='')
                    {
                    alert('Parties And Noticed is Required');
                    return;
                    }
                    if($('#conclution').val()=='')
                    {
                    alert('Conclution is Required');
                    return;
                    }
                    if($('#name_of_the_officer').val()=='')
                    {
                    alert('Name of the office is Required');
                    return;
                    }
                    else
                    {
                    details_list.push({id:null,amendment_header_id:null,created_at:null,deleted_at:null,nature_if_identification:$("#nature_if_identification").val(),document_evidence:$("#document_evidence").val(),parties_noticed:$("#parties_noticed").val(),conclution:$("#conclution").val(),name_of_the_officer:$("#name_of_the_officer").val(),rejected:0,updated_at:null});
                     html = "<tr> <th class='wd-20p'>{{trans('sentence.nature_of_identification')}}</th> <th class='wd-20p'>{{trans('sentence.documents_evidence_used_for_confirmation')}}</th></tr><tr scope='row'> <td>"+ " <textarea id='nature_if_identification"+$('#row').val()+"' name='amendmentDetails["+$('#row').val()+"][nature_if_identification]' class='form-control form-control-sm'>" + $('#nature_if_identification').val() + " </textarea> </td><td> <textarea id='document_evidence"+$('#row').val()+"' name='amendmentDetails["+$('#row').val()+"][document_evidence]' class='form-control form-control-sm'> " + $('#document_evidence').val() + "</textarea> </td><td></td><td><a href='javascript:;' data-toggle='modal' data-target='#ViewModal"+$('#row').val()+"' class='btn btn-icon btn-info'><i class='fas fa-book-open'></i></a> <button onclick ='Geeks(this)' class='btn btn-icon btn-danger' title='Approve'><i class='fas fa-times-circle'></i> </button> </td></tr><tr> <th class='wd-40p'>{{trans('sentence.parties_were_noticed_about_the_amendments')}}</th> <th class='wd-40p'>{{trans('sentence.conclution_on_whether_amendments_will_have_an_effect_on_near_lands')}}</th> <th class='wd-20p'>{{trans('sentence.name_of_the_officer_responsible_for_error')}}</th></tr><td> <textarea id='parties_noticed"+$('#row').val()+"' name='amendmentDetails["+$('#row').val()+"][parties_noticed]' class='form-control form-control-sm'>" + $('#parties_noticed').val() + " </textarea></td><td> <textarea id='conclution"+$('#row').val()+"' name='amendmentDetails["+$('#row').val()+"][conclution]' type='text' class='form-control form-control-sm'>" + $('#conclution').val() + " </textarea></td><td> <textarea id='name_of_the_officer"+$('#row').val()+"' name='amendmentDetails["+$('#row').val()+"][name_of_the_officer]' class='form-control form-control-sm'>" + $('#name_of_the_officer').val() + " </textarea></td></tr>";
                    modelhtml="<div class='modal fade' id='ViewModal"+$('#row').val()+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>Amendment Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'> <div class='row'><div class='col-md-6'> <b>{{trans('sentence.nature_of_identification')}}</b><p class='text-danger'>"+$("#nature_if_identification").val()+"</p><b>{{trans('sentence.documents_evidence_used_for_confirmation')}}</b><p class='text-danger'>"+$("#document_evidence").val()+"</p><b>{{trans('sentence.parties_were_noticed_about_the_amendments')}}</b><p class='text-danger'>"+$("#parties_noticed").val()+"</p></div><div class='col-md-6'><b>{{trans('sentence.conclution_on_whether_amendments_will_have_an_effect_on_near_lands')}}</b><p class='text-danger'>"+$("#conclution").val()+"</p><b>{{trans('sentence.name_of_the_officer_responsible_for_error')}}</b><p class='text-danger'>"+$("#name_of_the_officer").val()+"</p></div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>";
                    $("#myTable > tbody:last-child").append(html);
                    $('#amendmentmodel').append(modelhtml);
                    $('#row').val($('#row').val() * 1 + 1);
                    $('#nature_if_identification').val('');
                    $('#document_evidence').val('');
                    $('#parties_noticed').val('');
                    $('#conclution').val('');
                    $('#name_of_the_officer').val('');
                    }
                }
            });

        function find_duplicate()
        { var statues=0;
            for(var i=0; i<details_list.length; i++){
                if((details_list[i].nature_if_identification ===  $('#nature_if_identification').val())&&(details_list[i].document_evidence ===  $('#document_evidence').val())&&(details_list[i].parties_noticed ===  $('#parties_noticed').val())){
                statues=1;
                }
                else
                {
                statues=0;
                }
            }
         return statues;
        }

        });
        @endif
      </script>
       <script>
            function Geeks(id) {
                var i = id.parentNode.parentNode.rowIndex;
                document.getElementById("myTable").deleteRow(i);
            }
        </script>

<script>

        $('#myTab a').click(function(e) {
          e.preventDefault();
          $(this).tab('show');
        });

        // store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
          var id = $(e.target).attr("href").substr(1);
          window.location.hash = id;
        });

        // on load of the page: switch to the currently selected tab
        var hash = window.location.hash;
        $('#myTab a[href="' + hash + '"]').tab('show');

            </script>
    <script>
        var form14_data={form_14_Header_id:'',lot_no:'',name:'',addres:'',nic_number:'',size:'',ownership_type:'',class:'',mortgages:'',other_boudages:'',type:'',sub_type:'',gns:''};
        $(document).ready(function(){
            $('#village').prop('readonly',true);
            $('#btn_save').prop('disabled',true);
            $('#regional_officer').prop('readonly',true);
            $('#_14_gazette_date').prop('readonly',true);
            $('#_14_gazzert').prop('readonly',true);
            $('#gn_div_id').prop('disabled',true);
            $('#block_no').prop('readonly',true);
            $('#lot_no').prop('readonly',true);
            @if(Request::segment(2)=='create' )
            $('#column').prop('disabled',false);
            $('#column_value_to_be_changed').prop('disabled',true);
            $('#column_new_value').prop('disabled',true);
            $('#reasons').prop('readonly',false);
            $('#complain_date').prop('readonly',false);
            @endif
        });
        $("#map_no").keyup(function () {
            if($("#map_no").val()!='' && $("#map_no").val().length>=6){
                $('#block_no').prop('readonly',false);
            }else{
                $('#block_no').prop('readonly',true);
            }
        });

        $("#map_no").change(function () {
            if($("#map_no").val()!='' && $("#map_no").val().length>=6){
                if($("#map_no").val()!='' && $("#map_no").val().length>=6){
        var urlseg1='{{url("/get-amendments-maps-numbers/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                    clearForm();
                }else{
                    clearForm();
                    $('#lot_no').empty().append('<option value="">Please Select</option>');
                    $('#block_no').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#block_no').append('<option value="'+element.block_no+'">'+element.block_no+'</option>');
                        });   
                }
            }else{
                $('#btn_save').prop('disabled',true);
               // $('#regional_officer').prop('disabled',true);
                $('#block_no').val('');
            }
        }});
  
    }else{
       // $('#block_no').prop('disabled',true);
    }
            }else{
                $('#block_no').prop('readonly',true);
            }
        });

        $("#block_no").keyup(function () {
            if($("#block_no").val()!='' && $("#block_no").val().length>=2){
                $('#lot_no').prop('readonly',false);
            }else{
                $('#lot_no').prop('readonly',true);
            }
        });
        $("#block_no").change(function () {
            if($("#block_no").val()!='' && $("#block_no").val().length>=2){
                var urlseg1='{{url("/get-amendments/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                    clearForm();
                }else{
                    clearForm();
                    $('#lot_no').empty().append('<option value="">Please Select</option>');
                      $(response).each(function(i,element){
                           $('#lot_no').append('<option value="'+element.lot_no+'">'+element.lot_no+'</option>');
                        }); 
                }
            }else{
              $('#lot_no').empty().append('<option value="">Please Select</option>');
            }
        }});
            }else{
                $('#lot_no').prop('readonly',true);
            }
        });

        $("#lot_no").change(function () {
            if($("#lot_no").val()!=''){
                var urlseg1='{{url("/get-form14/")}}';
                $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val()+'/'+$('#lot_no').val(),type:'get', success: function(response) {
                    if(response){
                        if(response.message){
                            alert(response.message);
                            clearForm();
                        }else
                        {
                            clearForm();
                            @if(Request::segment(2)=='create' )
                            for(k=0;k<response.gns.length;k++){
                                if(lan=='EN')
                                {
                                $("#owner_details_gn_div_id").append('<option value="'+response.gns[k]['id']+'" selected>'+response.gns[k]['gn_name']+'</option>');
                                $('#gn_div_id').append('<option value="'+response.gns[k]['id']+'" selected>'+response.gns[k]['gn_name']+'</option>');
                                }
                                else
                                {
                                $("#owner_details_gn_div_id").append('<option value="'+response.gns[k]['id']+'" selected>'+response.gns[k]['sinhala_name']+'</option>');
                                $('#gn_div_id').append('<option value="'+response.gns[k]['id']+'" selected>'+response.gns[k]['sinhala_name']+'</option>');
                                }
                             }
                            @endif
                            @if(Request::segment(2)=='create' )
                            $('#gn_div_id').prop('disabled',false);
                            $('#village').val(response.element.village_name);
                            $('#village').prop('readonly',false);
                            $('#village').prop('readonly',true);
                            $('#_14_gazette_date').val(response.element.gazetted_date);
                            $('#_14_gazette_date').prop('readonly',true);
                            $('#_14_gazzert').val(response.element.gazetted_no);
                            $('#regional_officer').val(response.element.regional);
                            $('#_14_gazzert').prop('readonly',true);
                            $('#column').prop('disabled',false);
                            $('#column_value_to_be_changed').prop('disabled',false);
                            $('#column_new_value').prop('disabled',false);
                            $('#reasons').prop('readonly',false);
                            $('#complain_date').prop('readonly',false);
                            @endif
                            form14_data.form_14_Header_id=response.element.form_14_Header_id;
                            form14_data.addres=response.element.addres;
                            form14_data.class=response.element.class;
                            form14_data.lot_no=response.element.lot_no;
                            form14_data.mortgages=response.element.mortgages;
                            form14_data.name=response.element.name;
                            form14_data.nic_number=response.element.nic_number;
                            form14_data.other_boudages=response.element.other_boudages;
                            form14_data.ownership_type=response.element.ownership_type;
                            form14_data.size=response.element.size;
                            form14_data.type=response.element.type;
                            form14_data.sub_type=response.element.sub_type;
                            form14_data.gns=response.gns;

                            $('#type').val(response.element.type);$('#type').trigger('change');
                            $('#sub_type').val(response.element.sub_type);
                            $('#lot_number').val(response.element.lot_no);
                            $('#size').val(response.element.size);
                            $('#class').val(response.element.class);$('#class').trigger('change');
                            $('#natowner').val(response.element.ownership_type);$('#natowner').trigger('change');
                            $('#nic').val(response.element.nic_number);
                            $('#name').val(response.element.name);
                            $('#address').val(response.element.addres);
                            $('#mortgages').val(response.element.mortgages);
                            $('#other').val(response.element.other_boudages);

                            $('#hide_lot_number').val(response.element.lot_no);
                            $('#hide_size').val(response.element.size);
                            $('#hide_class').val(response.element.class);
                            $('#hide_natowner').val(response.element.ownership_type);
                            $('#hide_nic').val(response.element.nic_number);
                            $('#hide_name').val(response.element.name);
                            $('#hide_address').val(response.element.addres);
                            $('#hide_mortgages').val(response.element.mortgages);
                            $('#hide_other').val(response.element.other_boudages);
                            $('#hide_type').val(response.element.type);
                            $('#hide_sub_type').val(response.element.sub_type);
                            $('#hide_owner_details_gn_div_id').val(response.element.gn_division_id);

                            $('#lot_number').prop('disabled',true);
                            $('#size').prop('disabled',true);
                            $('#class').prop('disabled',true);
                            $('#natowner').prop('disabled',true);
                            $('#nic').prop('disabled',true);
                            $('#name').prop('disabled',true);
                            $('#address').prop('disabled',true);
                            $('#mortgages').prop('disabled',true);
                            $('#other').prop('disabled',true);
                            $('#type').prop('disabled',true);
                            $('#sub_type').prop('disabled',true);
                            $('#owner_details_gn_div_id').prop('disabled',true);

                            $('#hide_lot_number').prop('disabled',false);
                            $('#hide_size').prop('disabled',false);
                            $('#hide_class').prop('disabled',false);
                            $('#hide_natowner').prop('disabled',false);
                            $('#hide_nic').prop('disabled',false);
                            $('#hide_name').prop('disabled',false);
                            $('#hide_address').prop('disabled',false);
                            $('#hide_mortgages').prop('disabled',false);
                            $('#hide_other').prop('disabled',false);
                            $('#hide_type').prop('disabled',false);
                            $('#hide_sub_type').prop('disabled',false);
                            $('#hide_owner_details_gn_div_id').prop('disabled',false);

                            $('#btn_save').prop('disabled',false);
                        }
                    }else{
                        clearForm();
                        $('#village').prop('readonly',true);
                        $('#btn_save').prop('disabled',true);
                        $('#regional_officer').prop('readonly',true);
                        $('#_14_gazette_date').prop('readonly',true);
                        $('#_14_gazzert').prop('readonly',true);
                        $('#gn_div_id').prop('disabled',true);
                        $('#block_no').prop('readonly',true);
                        $('#lot_no').prop('readonly',true);
                        $('#column').prop('disabled',false);
                        $('#column_value_to_be_changed').prop('disabled',true);
                        $('#column_new_value').prop('disabled',true);
                        $('#reasons').prop('readonly',false);
                        $('#complain_date').prop('readonly',false);
                    }
                }});
            }
        });

        $('#column').change(function(){
                    $('#lot_number').prop('disabled',true);
                    $('#size').prop('disabled',true);
                    $('#class').prop('disabled',true);
                    $('#natowner').prop('disabled',true);
                    $('#nic').prop('disabled',true);
                    $('#name').prop('disabled',true);
                    $('#address').prop('disabled',true);
                    $('#mortgages').prop('disabled',true);
                    $('#other').prop('disabled',true);
                    $('#type').prop('disabled',true);
                    $('#sub_type').prop('disabled',true);
                    $('#owner_details_gn_div_id').prop('disabled',true);

                    $('#hide_lot_number').prop('disabled',false);
                    $('#hide_size').prop('disabled',false);
                    $('#hide_class').prop('disabled',false);
                    $('#hide_natowner').prop('disabled',false);
                    $('#hide_nic').prop('disabled',false);
                    $('#hide_name').prop('disabled',false);
                    $('#hide_address').prop('disabled',false);
                    $('#hide_mortgages').prop('disabled',false);
                    $('#hide_other').prop('disabled',false);
                    $('#hide_type').prop('disabled',false);
                    $('#hide_sub_type').prop('disabled',false);
                    $('#hide_owner_details_gn_div_id').prop('disabled',false);

            if($('#column').val()!=''){
                var selected_columns=$('#column').val();
                for(i=0;i<selected_columns.length;i++){
                    switch(selected_columns[i]){
                        case 'lot_no':
                            $('#lot_number').prop('disabled',false);
                            $('#hide_lot_number').prop('disabled',true);
                            break;
                        case 'type':
                            $('#type').prop('disabled',false);
                            $('#hide_type').prop('disabled',true);
                            break;
                        case 'sub_type':
                            $('#sub_type').prop('disabled',false);
                            $('#hide_sub_type').prop('disabled',true);
                            break;
                        case 'gns':
                            $('#owner_details_gn_div_id').prop('disabled',false);
                            $('#hide_owner_details_gn_div_id').prop('disabled',true);
                            break;
                        case 'size':
                            $('#size').prop('disabled',false);
                            $('#hide_size').prop('disabled',true);
                            break;
                        case 'class':
                            $('#class').prop('disabled',false);
                            $('#hide_class').prop('disabled',true);
                            break;
                        case 'ownership_type':
                            $('#natowner').prop('disabled',false);
                            $('#hide_natowner').prop('disabled',true);
                            break;
                        case 'nic_number':
                            $('#nic').prop('disabled',false);
                            $('#hide_nic').prop('disabled',true);
                            break;
                        case 'name':
                            $('#name').prop('disabled',false);
                            $('#hide_name').prop('disabled',true);
                            break;
                        case 'addres':
                            $('#address').prop('disabled',false);
                            $('#hide_address').prop('disabled',true);
                            break;
                        case 'mortgages':
                            $('#mortgages').prop('disabled',false);
                            $('#hide_mortgages').prop('disabled',true);
                            break;
                        case 'other_boudages':
                            $('#other').prop('disabled',false);
                            $('#hide_other').prop('disabled',true);
                            break;
                    }
                }
            }
        });
    $("#type").change(function (){
    $('#sub_type').empty().append('<option value="">Please Select</option>');
    if($('#type').val()==="Private")
    {
         $('#sub_type').append('<option value="Private">{{trans('sentence.Private')}}</option>');
         $('#sub_type').append('<option value="Grant">{{trans('sentence.Grant')}}</option>');
         $('#sub_type').append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type').append('<option value="thrashing_floor">{{trans('sentence.thrashing_floor')}}</option>');
         $('#sub_type').append('<option value="Edges">{{trans('sentence.Edges')}}</option>');
         $('#sub_type').append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type').append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type').append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type').append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
    else if($('#type').val()==="Government")
    {
         $('#sub_type').append('<option value="The_State">{{trans('sentence.The_State')}}</option>');
         $('#sub_type').append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type').append('<option value="Brook">{{trans('sentence.Brook')}}</option>');
         $('#sub_type').append('<option value="Lake">{{trans('sentence.Lake')}}</option>');
         $('#sub_type').append('<option value="River">{{trans('sentence.River')}}</option>');
         $('#sub_type').append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type').append('<option value="Roundabout">{{trans('sentence.Roundabout')}}</option>');
         $('#sub_type').append('<option value="Turning_circle">{{trans('sentence.Turning_circle')}}</option>');
         $('#sub_type').append('<option value="Reservation">{{trans('sentence.Reservation')}}</option>');
         $('#sub_type').append('<option value="Boulder">{{trans('sentence.Boulder')}}</option>');
         $('#sub_type').append('<option value="Licensee">{{trans('sentence.Licensee')}}</option>');
         $('#sub_type').append('<option value="Owned_by_institutions">{{trans('sentence.Owned_by_institutions')}}</option>');
         $('#sub_type').append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type').append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type').append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }

});
$(function() {
    var count=0;
        $('#natowner').change(function(){
            $('.nicdetails').hide();
            count=1;
            if($('#natowner').val()=='Disproportionate')
            {
                $('#size').val('');
            }
            $('#name').val('');
            $('#address').val('');
            $('#nic').val('');
            if($('#natowner').val()=='Equal'||$('#natowner').val()=='Disproportionate'){
                $('#Equal').show();
                $('#nic').prop('disabled',false);
                $('#name').prop('disabled',false);
                $('#address').prop('disabled',false);
                $('#hide_nic').prop('disabled',true);
                $('#hide_name').prop('disabled',true);
                $('#hide_address').prop('disabled',true);
                $('#nic').prop('readonly',true);
                $('#name').prop('readonly',true);
                $('#address').prop('readonly',true);
              
            }else{
                $('#nic').prop('readonly',false);
                $('#name').prop('readonly',false);
                $('#address').prop('readonly',false);
            }
            if($('#natowner').val()=='Equal'){
                $('#owner_size').prop('readonly',true);
            }else{
                $('#owner_size').prop('readonly',false);
            }
        });
        $('#ownership_add').click(function(){
            // if($('#id_number').val()==''){
            //     alert('NIC required!!');
            //     return;
            // }
             if($('#owner_name').val()==''){
                alert('Name is required!!');
                return;
            }
            // else if($('#owner_address').val()==''){
            //     alert('Address is required!!');
            //     return;
            // }
            else if($('#owner_size').val()=='' && $('#natowner').val()!='Equal'){
                alert('Owned amount is required!!');
                return;
            }else{
                if($('#nic').val()==''){
                    $('#nic').val($('#nic').val()+''+count+'). '+$('#id_number').val()+' - '+$('#owner_size').val());
                    $('#name').val($('#name').val()+''+count+'). '+$('#owner_name').val());
                    $('#address').val($('#address').val()+''+count+'). '+$('#owner_address').val());
                }else{
                    $('#nic').val($('#nic').val()+'\n'+''+count+'). '+$('#id_number').val()+' - '+$('#owner_size').val());
                    $('#name').val($('#name').val()+'\n'+''+count+'). '+$('#owner_name').val());
                    $('#address').val($('#address').val()+'\n'+''+count+'). '+$('#owner_address').val());
                }
                count=count+1;
                if($('#natowner').val()!='Equal'){
                    if($('#size').val()==''){
                        $('#size').val($('#owner_size').val()*1);
                    }else{
                        $('#size').val($('#size').val()*1+$('#owner_size').val()*1);
                    }
                }
                $('#id_number').val('');
                $('#owner_name').val('');
                $('#owner_address').val('');
                $('#owner_size').val('')
            }
        });
    });
    $(function(){
                    $('#lot_number').prop('disabled',true);
                    $('#size').prop('disabled',true);
                    $('#class').prop('disabled',true);
                    $('#natowner').prop('disabled',true);
                    $('#nic').prop('disabled',true);
                    $('#name').prop('disabled',true);
                    $('#address').prop('disabled',true);
                    $('#mortgages').prop('disabled',true);
                    $('#other').prop('disabled',true);
                    $('#type').prop('disabled',true);
                    $('#sub_type').prop('disabled',true);
                    $('#owner_details_gn_div_id').prop('disabled',true);

                    $('#hide_lot_number').prop('disabled',false);
                    $('#hide_size').prop('disabled',false);
                    $('#hide_class').prop('disabled',false);
                    $('#hide_natowner').prop('disabled',false);
                    $('#hide_nic').prop('disabled',false);
                    $('#hide_name').prop('disabled',false);
                    $('#hide_address').prop('disabled',false);
                    $('#hide_mortgages').prop('disabled',false);
                    $('#hide_other').prop('disabled',false);
                    $('#hide_type').prop('disabled',false);
                    $('#hide_sub_type').prop('disabled',false);
                    $('#hide_owner_details_gn_div_id').prop('disabled',false);

            if($('#column').val()!=''){
                var selected_columns=$('#column').val();
                for(i=0;i<selected_columns.length;i++){
                    switch(selected_columns[i]){
                        case 'lot_no':
                            $('#lot_number').prop('disabled',false);
                            $('#hide_lot_number').prop('disabled',true);
                            break;
                        case 'type':
                            $('#type').prop('disabled',false);
                            $('#hide_type').prop('disabled',true);
                            break;
                        case 'sub_type':
                            $('#sub_type').prop('disabled',false);
                            $('#hide_sub_type').prop('disabled',true);
                            break;
                        case 'gns':
                            $('#owner_details_gn_div_id').prop('disabled',false);
                            $('#hide_owner_details_gn_div_id').prop('disabled',true);
                            break;
                        case 'size':
                            $('#size').prop('disabled',false);
                            $('#hide_size').prop('disabled',true);
                            break;
                        case 'class':
                            $('#class').prop('disabled',false);
                            $('#hide_class').prop('disabled',true);
                            break;
                        case 'ownership_type':
                            $('#natowner').prop('disabled',false);
                            $('#hide_natowner').prop('disabled',true);
                            break;
                        case 'nic_number':
                            $('#nic').prop('disabled',false);
                            $('#hide_nic').prop('disabled',true);
                            break;
                        case 'name':
                            $('#name').prop('disabled',false);
                            $('#hide_name').prop('disabled',true);
                            break;
                        case 'addres':
                            $('#address').prop('disabled',false);
                            $('#hide_address').prop('disabled',true);
                            break;
                        case 'mortgages':
                            $('#mortgages').prop('disabled',false);
                            $('#hide_mortgages').prop('disabled',true);
                            break;
                        case 'other_boudages':
                            $('#other').prop('disabled',false);
                            $('#hide_other').prop('disabled',true);
                            break;
                    }
                }
            }
    });

    function clearForm()
    {
        $('#village').val('');
        $('#_14_gazette_date').val('');
        $('#_14_gazzert').val('');
        $('#regional_officer').val('');
        $('#gn_div_id').empty().append('<option value=""></option>');
        $('#type').val('');
        $('#sub_type').val('');
        $('#lot_number').val('');
        $('#size').val('');
        $('#class').val('');
        $('#natowner').val('');
        $('#nic').val('');
        $('#name').val('');
        $('#address').val('');
        $('#mortgages').val('');
        $('#other').val('');
        $('#owner_details_gn_div_id').empty().append('<option value=""></option>');
    }
    </script>


@endsection
