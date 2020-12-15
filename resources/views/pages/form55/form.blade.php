@extends('layouts.wyse2')
@section('optional_css')
@include('css.datepicker')
@include('css.timepicker')
{{-- @include('css.autocomplete') --}}
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
              <li class="breadcrumb-item"><a href="#">55<sup>th</sup> Sentence</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create New</li>
            </ol>
          </nav>
          <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.ownership_of_property_without_last_will')}}</h4>
          <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.notice_to_submit')}}</h4>
          <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.title_registration_act_no_21_of_1998')}}</h4>
          <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.Article_55')}}</h4>
        </div>
        <div class="d-none d-md-block">
            @include('buttons._back')
        </div>
      </div>
              <div data-label="Example" class="df-example" id="tabs">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">55<sup>th Form Details</a>
                    </li>
                    @if(Request::segment(2)!='create' )
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Map's Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">Form History</a>
                    </li>
                    @endif
                  </ul>

                  <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      <form  method="post" @if(Request::segment(2)=='create' ) action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$element->id) }}" @endif>
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
                              <label for="ag_div_id">{{trans('sentence.ag_division')}}</label>
                              @if(Request::segment(2)=='create')
                              @if(trans('sentence.lang')=='EN')
                                <input type="text" readonly class="form-control" value="{{$UtilityService->getAgDivByUser()->ag_name}}"/>
                              @else
                              <input type="text" readonly class="form-control" value="{{$UtilityService->getAgDivByUser()->sinhala_name}}"/>
                              @endif
                                <input type="hidden" name="ag_division_id" value="{{$UtilityService->getAgDivByUser()->id}}"/>
                            @else
                                <select id="ag_div_id" disabled @if(Request::segment(2)=='view' ) readonly @endif name="ag_division_id" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="ag_div_id" required>
                                  @if(trans('sentence.lang')=='EN')
                                  <option value="">Please Select</option>
                                  @foreach ($agDivision as $item)
                                  <option value="{{$item->id}}" @if(isset($element))
                                      @if(old('ag_division_id',$element->id)==$item->id)
                                      selected="selected"
                                      @endif @endif
                                      @if(Request::segment(2)!='create' )@if($form12->ag_division_id==$item->id) selected @endif @endif
                                      >{{$item->ag_name}}</option>
                                  @endforeach
                                  @else
                                  <option value="">Please Select</option>
                                  @foreach ($agDivision as $item)
                                  <option value="{{$item->id}}" @if(isset($element))
                                      @if(old('ag_division_id',$element->id)==$item->id)
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
                                    <label for="gn_div_id">{{trans('sentence.gn_Division')}}</label>
                                    <select id="gn_div_id"  name="gn_division_id[]" @if(Request::segment(2)=='view') disabled @endif   class="form-control form-control-sm select2" multiple required>
                                      @if(trans('sentence.lang')=='EN')
                                      <option value="">Please Select </option>
                                        @foreach ($gnDivision as $item)
                                        <option value="{{$item->id}}" @if(isset($element))
                                            @if(old('gn_division_id',$element->gn_division_id)==$item->id)
                                            selected="selected"
                                            @endif @endif 
                                            @if(Request::segment(2)!='create' ) @foreach(explode(',',$element->gn_division_id) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                            >{{$item->gn_name}}</option>
                                        @endforeach
                                        @else
                                        <option value="">Please Select</option>
                                        @foreach ($gnDivision as $item)
                                        <option value="{{$item->id}}" @if(isset($element))
                                            @if(old('gn_division_id',$element->gn_division_id)==$item->id)
                                            selected="selected"
                                            @endif @endif
                                            @if(Request::segment(2)!='create' ) @foreach(explode(',',$element->gn_division_id) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                            >{{$item->sinhala_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label for="map_no">{{trans('sentence.map_number')}}</label>
                                    <select id="header_map_no"  @if(Request::segment(2)=='view' ) readonly @endif name="header_map_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="header_map_no" required>
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
    
                                <div class="form-group col-md-4">
                                    <label for="block_no">{{trans('sentence.block_number')}}</label>
                                    <select id="header_block_no"  @if(Request::segment(2)=='view' ) readonly @endif name="header_block_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="header_block_no" required>
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
                                <div class="form-group col-md-4">
                                    <label for="lot_no">{{trans('sentence.lot_no')}}</label>
                                    <select id="header_lot_no"  @if(Request::segment(2)=='view' ) readonly @endif name="header_lot_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="header_lot_no" required>
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
                          </div>
                          
                        <div class="form-row">
                          <input type="hidden" class="form-control" name="village" id="headervillage" value="">
                            <div class="form-group col-md-4">
                                <label for="name_of_the_deceased">{{trans('sentence.name')}}</label>
                                <input type="text" class="form-control" name="name_of_the_deceased" id="name_of_the_deceased" value="@if(isset($element)){{$element->name_of_the_deceased}} @endif" placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date_of_notice">{{trans('sentence.date_of_the_notice')}}</label>
                                <input type="text" required class="form-control datepicker1" id="date_of_notice" autocomplete="off" name="date_of_notice" value="@if(isset($element)){{date('Y-m-d',strtotime($element->date_of_notice))}} @endif">
                            </div>
                            <div class="form-group col-md-4">
                              <label for="date_of_last_notice">{{trans('sentence.date_of_the_last_notice')}}</label>
                              <input type="text" required class="form-control datepicker2" id="date_of_last_notice" autocomplete="off" name="date_of_last_notice" value="@if(isset($element)){{date('Y-m-d',strtotime($element->date_of_last_notice))}} @endif">
                            </div>

                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label for="regional_officer">{{trans('sentence.regional_officer')}}</label>
                                  @if(Request::segment(2)=='create')
                                    <select id="regional_officer" @if((Request::segment(2)=='view' )) disabled @endif
                                        class="form-control form-control-sm select2" name="regional_officer" required>
                                        <option value="">Please Select</option>
                                        @foreach ($ReginalOfficers as $item)
                                        <option value="{{$item->id}}" @if(isset($element)) @if(old('regional_officer',$element->regional_officer)==$item->id)
                                            selected="selected"
                                            @endif @endif
                                        >{{$item->name}} {{$item->last_name}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="text" readonly class="form-control" id="regional_officer" disabled value="@if(isset($element)){{$UtilityService->getUserName($element->regional_officer)}}@endif">
                                <input type="hidden" name="regional_officer" @if(isset($element))value="{{$element->regional_officer}}"@endif/>
                                    @endif
                              </div>
                              <div class="form-group col-md-6">
                                <label for="office_of_registration">{{trans('sentence.office_of_registration')}}</label>
                                <input type="text" @if(Request::segment(2)=='view') readonly @endif class="form-control"  name="office_of_registration" id="office_of_registration"  value="@if(isset($element)){{$element->office_of_registration}}@endif">
                              </div>
                              @if($UtilityService->getAccessPubVerify(Request::segment(1)))
                              <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_12th_ref_no')}}</label>
                                <input type="text" @if(Request::segment(2)=='view' ) readonly @endif required class="form-control" id="ref_no2" name="ref_no2" @if(Request::segment(2)!='create' ) value="{{$element->ref_no2}}" @endif>
                            </div>
                              @endif
                                @if($UtilityService->getAccessGazette(Request::segment(1))&&($form12->current_stage=='Gov Press without G'))
                                <div class="form-group col-md-3">
                                    <label for="gazette_date">{{trans('sentence.form_55th_gazzette_date')}}</label>
                                    <input type="text"  @if((Request::segment(2)=='view')) readonly @endif required  class="form-control datepicker2"
                                    id="gazette_date" name="gazette_date" value="@if(isset($element)){{date('Y-m-d',strtotime($element->gazette_date))}}@endif" >
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="gazette_number">{{trans('sentence.form_55th_gazzette_no')}}</label>
                                    <input type="text"  @if((Request::segment(2)=='view')) readonly @endif required class="form-control"
                                     id="gazette_number" name="gazette_number" value="@if(isset($element)){{$element->gazette_number}}@endif">
                                </div>
                                @endif
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="lot_no">{{trans('sentence.lot_no')}}</label>
                            <input type="text" disabled autocomplete="off" class="form-control" name="new[lot_no]" id="lot_number" value="" placeholder="">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="lot_no">{{trans('sentence.land_type')}}</label>
                                    <select id="type" name="new[land_type]" class="form-control form-control-sm" disabled>
                                            <option value="">{{trans('sentence.please_select')}}</option>
                                            <option value="Government">{{trans('sentence.gov')}}</option>
                                            <option value="Private">{{trans('sentence.pvt')}}</option>
                                    </select>
                                
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="sub_type">{{trans('sentence.land_sub_type')}}</label>
                                    <select id="sub_type" name="new[sub_type]" class="form-control form-control-sm" disabled>
                                    </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="size">{{trans('sentence.size')}}</label>
                                <input type="text" disabled class="form-control" id="header_size" name="new[size]" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="class">{{trans('sentence.Class')}}</label>
                                <select id="class" name="new[class]" class="form-control form-control-sm" disabled>
                                <option value="">{{trans('sentence.please_select')}}</option>
                                <option value="1st_Class">{{trans('sentence.1st_class')}}</option>
                                <option value="2nd_Class">{{trans('sentence.2nd_class')}}</option>
                                </select>
                               
                            </div>
                           
                            <div class="form-group col-md-4">
                                    <label for="owner_details_gn_div_id">{{trans('sentence.gn_Division')}}</label>
                                    <select id="owner_details_gn_div_id" name="new[owner_details_gn_division_id][]"
                                        disabled class="form-control form-control-sm select2" multiple >
                                    </select>
                                </div>
                            <div class="form-group col-md-4">
                                <label for="nic">{{trans('sentence.nature_of_ownership')}}</label>
                                <select id="natowner" name="new[natowner]" class="form-control form-control-sm" disabled>
                                    <option value="">{{trans('sentence.please_select')}}</option>
                                    <option value="full">{{trans('sentence.full_ownership')}}</option>
                                    <option value="Equal">{{trans('sentence.equal_ownership')}}</option>
                                    <option value="Disproportionate">{{trans('sentence.disproportionate_ownership')}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="natowner">{{trans('sentence.Owner_nic')}}</label>
                            <textarea type="text" class="form-control" disabled id="nic" name="new[nic]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->nic_number}}@endif</textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">{{trans('sentence.name')}} </label>
                                <textarea type="text" class="form-control" disabled id="name" name="new[name]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->name}}@endif</textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address">{{trans('sentence.address')}}</label>
                                <textarea type="text" class="form-control" disabled id="address" name="new[address]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->addres}}@endif</textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mortgages">{{trans('sentence.mortgages')}}</label>
                                <textarea type="text" class="form-control" disabled id="mortgages" name="new[mortgages]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->mortgages}}@endif</textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="other">{{trans('sentence.other_bonds')}}</label>
                                <textarea type="text" class="form-control" disabled id="other" name="new[other]" autocomplete="off">@if(isset($newelementDetails)){{$newelementDetails->other_boudages}}@endif</textarea>
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
                        @elseif(Request::segment(2)!='view' )
                        @if($UtilityService->getAccessCreate(Request::segment(1))=='Yes')
                        <button class="btn btn-info" type="submit" name="button" value="save">Save</button>
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
                        <div class="form-group col-md-3">
                            <label for="map_no">{{trans('sentence.map_number')}}</label>
                            {{-- <input type="text" class="form-control" name="map_no" id="map_no" placeholder=""> --}}
                          <select id="map_no"  name="map_no" class="form-control form-control-sm select2" required>
                              <option value="">Please Select</option>
                              @foreach ($map_numbers as $item)
                              <option value="{{$item->map_no}}">{{$item->map_no}}</option>
                              @endforeach
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="block_no">{{trans('sentence.block_number')}}</label>
                            {{-- <input type="text" class="form-control" id="block_no" name="block_no"> --}}

                            <select id="block_no" name="block_no" class="form-control form-control-sm select2" required>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="lot_no">{{trans('sentence.lot_no')}}</label>
                            <input type="text" pattern="^\d{0,4}" onKeyUp="numericFilter(this);" minlength="4" maxlength="4" class="form-control" name="lot_no" id="lot_no" autocomplete="off"  placeholder="">
                            {{-- <input id="myInput" type="text" name="myCountry" placeholder="Country"> --}}
                            {{-- <select id="lot_no" name="lot_no" class="form-control form-control-sm select2" required>
                            </select> --}}
                        </div>
                        <div class="form-group col-md-3">
                            <label for="size">{{trans('sentence.size')}}</label>
                            <input type="number" pattern="^\d{0,5}(\.\d{0,4})?$" class="form-control" id="size" name="size">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="certificate_number">{{trans('sentence.owernship_certificate_no')}}</label>
                          <input type="text" class="form-control" id="certificate_number" name="certificate_number">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="village">{{trans('sentence.village')}}</label>
                          <input type="text" class="form-control" name="village" id="village" >
                        </div>

                        {{-- <div class="form-group col-md-3">
                            <label for="registerd_office">{{trans('sentence.registered_office')}}</label> --}}
                            <input type="hidden" class="form-control" id="registerd_office" name="registerd_office">
                        {{-- </div> --}}
                        <div class="form-group col-md-1">
                            <label for="other">ADD ROW</label>
                            <input type="button" class="add-row btn btn-success" value="+" >
                        </div>
                        </div>
                        @endif
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice bd-b" id="myTable">
                              <thead>
                                <tr>
                                  <th colspan="3"></th>
                                  {{-- <th class="wd-5p">{{trans('sentence.map_number')}}</th>
                                  <th class="wd-20p">{{trans('sentence.block_number')}}</th>
                                  <th class="wd-20p ">{{trans('sentence.lot_no')}}</th>
                                  <th class="wd-20p">{{trans('sentence.size')}}</th>
                                  <th class="wd-20p">{{trans('sentence.owernship_certificate_no')}}</th>
                                  <th class="wd-20p">{{trans('sentence.registered_office')}}</th> --}}
                                  <th class="wd-5p">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>

                          </div>
                          @include('modals.55model')
                           @include('modals.remarks')
                           @include('modals.12recheckmodel')
                           @include('modals.55rejectmodel')
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
@include('scripts.province_district_ag_gn');
<script>
$('.select2').select2({
  placeholder: '',
  searchInputPlaceholder: 'Search options'
});

$( ".datepicker1" ).datepicker({
  dateFormat: "yy-mm-dd"
});

$( ".datepicker2" ).datepicker({
  dateFormat: "yy-mm-dd"
});

$( ".datepicker3" ).datepicker({
  dateFormat: "yy-mm-dd"
});

$(document).on('keydown', 'input[pattern]', function(e){
  var input = $(this);
  var oldVal = input.val();
  var regex = new RegExp(input.attr('pattern'), 'g');

  setTimeout(function(){
    var newVal = input.val();
    if(!regex.test(newVal)){
      input.val(oldVal); 
    }
  }, 0);
});

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}


</script>
 <script type="text/javascript">
  $(document).ready(function(){
      // Find and remove selected table rows
      $(".delete-row").click(function(){
          $("table tbody").find('input[name="record"]').each(function(){
            if($(this).is(":checked")){
                  $(this).parents("tr").remove();
              }
          });
      });
      if($('#gazette_number').val()==''||$('#gazette_date').val()==''||$('#gazette_number').val()==null||$('#gazette_date').val()==null){
            $('#computer_with_G').prop('disabled',true);
        }else{
            $('#computer_with_G').prop('disabled',false);
        }
  });
</script>


<script>
  var details_list=[];
  var countries=[],tempRef;
  var lotNumbers=[];
  var lan="{{trans('sentence.lang')}}";
  $(document).ready(function(){
                      var modelhtml='';
                      var rejectmodel='';          
        $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: "{{ url('55th-sentence/details/'.Request::segment(3)) }}",
                    type: "GET",
                    success: function(data){
                  for (i = 0; i <data.message.length; i++) {
                    details_list.push(data.message[i]);
                 var html='';
                 html=html+ "<tr> <th class='wd-20p'>{{trans('sentence.map_number')}}</th> <th class='wd-20p'>{{trans('sentence.block_number')}}</th> <th class='wd-20p'>{{trans('sentence.lot_no')}}</th></tr><tr scope='row'> <td>"+ " <select id='map_no"+i+"' name='form55Details["+i+"][map_no]' onchange='tableMapOnchange("+i+",this)' class='form-control form-control-sm' required> </select> </td><td> <select id='block_no"+i+"' name='form55Details["+i+"][block_no]' onchange='tableBlockOnchange("+i+",this)' class='form-control form-control-sm' required> </select> </td><td> <input type='text' class='form-control' name='form55Details["+i+"][lot_no]' id='lot_no"+i+"' onkeyup=tableLotNumOnchange("+i+") onfocusout=checkSize("+i+")  pattern='\d*' minlength='4' maxlength='4' autocomplete='off' value='"+data.message[i].lot_no+"' placeholder=''> </td><td> <a href='javascript:;' data-toggle='modal' data-target='#ViewModal"+i+"' class='btn btn-icon btn-info'><i class='fas fa-book-open'></i></a>@if(($UtilityService->getAccessRegReject(Request::segment(1))=='Yes') and (Request::segment(2)=='update'))<a href='javascript:;' data-toggle='modal' data-target='#RejectModal"+i+"' class='btn btn-icon btn-danger' title='Approve'><i class='fas fa-times-circle'></i></a>@endif </td></tr><tr> <th class='wd-20p'>{{trans('sentence.size')}}</th> <th class='wd-20p'>{{trans('sentence.owernship_certificate_no')}}</th> <th class='wd-20p'>{{trans('sentence.village')}}</th> </tr><tr> <td> <textarea   id='size"+i+"' name='form55Details["+i+"][size]' oninput='sizeTextChangeValidation("+i+",this)' onfocusout=checkSize("+i+") class='form-control form-control-sm'>" + data.message[i].size+ "</textarea> </td><td> <textarea id='certificate_number"+i+"' name='form55Details["+i+"][certificate_number]' readonly onfocusout=checkSize("+i+") class='form-control form-control-sm' >" + data.message[i].certificate_number + " </textarea></td><td> <textarea id='village"+i+"' name='form55Details["+i+"][village]' onfocusout=checkSize("+i+") class='form-control form-control-sm'>" + data.message[i].village + " </textarea> </td></tr>";
                
                 modelhtml="<div class='modal fade' id='ViewModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+data.message[i].map_no+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+data.message[i].block_no+"</p>"+
                "<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+data.message[i].lot_no+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+data.message[i].size+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+data.message[i].certificate_number+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+data.message[i].village+"</p>"+
                "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>";
                
                rejectmodel=rejectmodel+"<form action='{{url('55th-sentence/reject/update')}}' method='get'><div  class='modal fade' id='RejectModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-sm' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>Reject</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='form-group col-md-12'><label for='tot_lands'>Reason</label><input type='hidden' name='detail_id'  value='"+data.message[i].id+"'><input type='text' class='form-control' id='' name='reason'></div></div><div class='modal-footer'><button type='button' class='btn btn-info' data-dismiss='modal'>Cancel</button><button type='submit' name='button' value='reject'  class='btn btn-info'>Save</button></div></div></div></div></form>";
                $("table tbody").append(html);
                $('#55model').append(modelhtml);
                $('#55rejectmodel').html(rejectmodel);
                $('#row').val(i);
                tableMap(i,data.message[i].map_no);
                tableBlock(i,data.message[i].map_no,data.message[i].block_no);
                tableLot(i,data.message[i].map_no,data.message[i].block_no,data.message[i].lot_no)
                autoSuggest(i);
                sizeValidate(i);
                  } 
                  $('#row').val(data.message.length);    
                  if(($('#myTable tr:last').index() + 1)>1){
                            $('#forward_regional_officer').prop('disabled',false);
                            $('#save').prop('disabled',false);
                        }else{
                            $('#forward_regional_officer').prop('disabled',true);
                            $('#save').prop('disabled',true);
                        }
                    }
                    });
                    $.ajax();

            $(".add-row").click(function () {
              if(find_duplicate()==1)
                {
                    alert('Duplicate Entries!!!');
                    return;
                }
                else
                {
                if($('#lot_no').val().length!=4)
                {
                  alert('Lot Number Size is Invalid, 4 digit Required');
                  $('#lot_no').val('');
                  $('#lot_no').focus();
                  return;
                }
                if($('#map_no').val()=='')
                {
                alert('Map Number is Required');
                return;
                }
                if($('#block_no').val()=='')
                {
                    alert('Block Number is Required');
                    return;
                }
                if($('#lot_no').val()=='')
                {
                    alert('Lot Number is Required');
                    return;
                }
                if($('#lot_no').val().length!=4)
                {
                  alert('Lot Number Size is Invalid, 4 digit Required');
                  $('#lot_no').val('');
                  return;
                }
                if($('#size').val()=='')
                {
                    alert('Size is Required');
                    return;
                }
                // if($('#certificate_number').val()=='')
                // {
                //     alert('Certificate Number is Required');
                //     return;
                // }
                // if($('#registerd_office').val()=='')
                // {
                //     alert('Registerd Office is Required');
                //     return;
                // }
                else
                {
                 // $('#row').val($('#row').val() * 1 + 1);
                  details_list.push({id:null,form_55_header_id:null,created_at:null,deleted_at:null,map_no:$("#map_no").val(),block_no:$("#block_no").val(),lot_no:$("#lot_no").val(),size:$("#size").val(),certificate_number:$("#certificate_number").val(),registerd_office:$("#registerd_office").val(),rejected:0,updated_at:null});
                //var html = "<tr> <th class='wd-20p'>{{trans('sentence.map_number')}}</th> <th class='wd-20p'>{{trans('sentence.block_number')}}</th> <th class='wd-20p'>{{trans('sentence.lot_no')}}</th></tr><tr scope='row'> <td>"+ "<select id='map_no"+$('#row').val()+"' name='form55Details["+$('#row').val()+"][map_no]' class='form-control form-control-sm' required> </select> </td><td> <select id='block_no"+$('#row').val()+"' name='form55Details["+$('#row').val()+"][block_no]' class='form-control form-control-sm' required> </select> </td><td> <select id='lot_no"+$('#row').val()+"' name='form55Details["+$('#row').val()+"][lot_no]' onchange='changeLotNumber("+$('#row').val()+","+$('#map_no').val()+","+$('#block_no').val()+")' class='form-control form-control-sm' required> </select>	 </td><td> <a href='javascript:;' data-toggle='modal' data-target='#ViewModal"+$('#row').val()+"' class='btn btn-icon btn-info'><i class='fas fa-book-open'></i></a><button type='button' onclick ='Geeks(this)' class='btn btn-icon btn-danger' title='Approve'><i class='fas fa-times-circle'></i> </button> </td></tr><tr> <th class='wd-20p'>{{trans('sentence.size')}}</th> <th class='wd-20p'>{{trans('sentence.owernship_certificate_no')}}</th> <th class='wd-20p'>{{trans('sentence.village')}}</th> </tr><tr> <td> <textarea id='size"+$('#row').val()+"' name='form55Details["+$('#row').val()+"][size]' class='form-control form-control-sm'> " + $('#size').val() + "</textarea> </td><td> <textarea id='certificate_number"+$('#row').val()+"' name='form55Details["+$('#row').val()+"][certificate_number]' class='form-control form-control-sm'>" + $('#certificate_number').val() + " </textarea> </td><td> <textarea id='village"+$(' #row ').val()+"' name='form55Details["+$(' #row ').val()+"][village]' class='form-control form-control-sm'>" + $('#village').val() + " </textarea> </td></tr>";
                var html="<tr> <th class='wd-20p'>{{trans('sentence.map_number')}}</th> <th class='wd-20p'>{{trans('sentence.block_number')}}</th> <th class='wd-20p'>{{trans('sentence.lot_no')}}</th></tr><tr scope='row'> <td>"+ " <select id='map_no"+$(' #row ').val()+"' name='form55Details["+$(' #row ').val()+"][map_no]' onchange='tableMapOnchange("+$('#row').val()+",this)' class='form-control form-control-sm' required> </select> </td><td> <select id='block_no"+$(' #row ').val()+"' name='form55Details["+$(' #row ').val()+"][block_no]' onchange='tableBlockOnchange("+$('#row').val()+",this)' class='form-control form-control-sm' required> </select> </td><td> <input type='text' class='form-control' name='form55Details["+$(' #row ').val()+"][lot_no]' id='lot_no"+$(' #row ').val()+"' onkeyup=tableLotNumOnchange("+$('#row').val()+") onfocusout=checkSize("+$('#row').val()+") pattern='\d*' minlength='4' maxlength='4' autocomplete='off' value='"+$('#lot_no').val()+"' placeholder=''> </td><td> <a href='javascript:;' data-toggle='modal' data-target='#ViewModal"+$(' #row ').val()+"' class='btn btn-icon btn-info'><i class='fas fa-book-open'></i></a> <button type='button' onclick='Geeks(this)' class='btn btn-icon btn-danger' title='Approve'><i class='fas fa-times-circle'></i> </button> </td></tr><tr> <th class='wd-20p'>{{trans('sentence.size')}}</th> <th class='wd-20p'>{{trans('sentence.owernship_certificate_no')}}</th> <th class='wd-20p'>{{trans('sentence.village')}}</th></tr><tr> <td> <textarea   id='size"+$(' #row ').val()+"' name='form55Details["+$(' #row ').val()+"][size]' oninput='sizeTextChangeValidation("+$('#row').val()+",this)' onfocusout=checkSize("+$('#row').val()+") class='form-control form-control-sm'>" + $('#size').val()+ "</textarea> </td><td> <textarea id='certificate_number"+$(' #row ').val()+"' name='form55Details["+$(' #row ').val()+"][certificate_number]' readonly onfocusout=checkSize("+$('#row').val()+") class='form-control form-control-sm'>" + $('#certificate_number').val() + " </textarea> </td><td> <textarea id='village"+$(' #row ').val()+"' name='form55Details["+$(' #row ').val()+"][village]' onfocusout=checkSize("+$('#row').val()+") class='form-control form-control-sm'>" + $('#village').val() + " </textarea> </td></tr>";
               
                modelhtml="<div class='modal fade' id='ViewModal"+$('#row').val()+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+$("#map_no").val()+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+$("#block_no").val()+"</p>"+
                "<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+$("#lot_no").val()+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size").val()+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+$("#certificate_number").val()+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+$("#village").val()+"</p>"+
                "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>";
            $("table tbody").append(html);
            $('#55model').append(modelhtml);
            tableMap($('#row').val(),$('#map_no').val());
            tableBlock($('#row').val(),$('#map_no').val(),$('#block_no').val());
            tableLot($('#row').val(),$('#map_no').val(),$('#block_no').val(),$('#lot_no').val())
            autoSuggest($('#row').val());
            sizeValidate($('#row').val());
            lotNumbers=[];
            $('#row').val($('#row').val() * 1 + 1);
            $("#map_no option[value='']").prop('selected',true);
             $('#map_no').trigger('change');
            $("#block_no option[value='']").prop('selected',true);
             $('#block_no').trigger('change');
            $("#lot_no").val('');
            $('#size').val('');
            $('#certificate_number').val('');
            $("#village").val('');
            $('#registerd_office').val('');
            if(($('#myTable tr:last').index() + 1)>1){
                            //$('#forward_regional_officer').prop('disabled',false);
                            $('#save').prop('disabled',false);
                        }else{
                            //$('#forward_regional_officer').prop('disabled',true);
                            $('#save').prop('disabled',true);
                        }
                }
                   }
        });
  });

  function find_duplicate()
        { var statues=0;
            for(var i=0; i<details_list.length; i++){
                if((details_list[i].lot_no ==$('#lot_no').val()) && (details_list[i].map_no ==$('#map_no').val()) && (details_list[i].block_no ==  $('#block_no').val())){
                  statues=1;
                  break;
                }
                else
                {
                statues=0;
                }
            }
            if(statues==1)
            {
              return statues;
            }
            else
            {
              return statues;
            }
        
        }
        function find_duplicateTable(row_index)
        { var statues=0;
            for(var i=0; i<details_list.length; i++){
                if((details_list[i].lot_no ==$('#lot_no'+row_index).val()) && (details_list[i].map_no ==$('#map_no'+row_index).val()) && (details_list[i].block_no ==  $('#block_no'+row_index).val())){
                  statues=1;
                  break;
                }
                else
                {
                statues=0;
                }
            }
            if(statues==1)
            {
              return statues;
            }
            else
            {
              return statues;
            }
        
        }

    function checkSize(row_index)
    {
        var lotno=$('#lot_no'+row_index).val();
        if(lotno.length!=0)
        {
        if(lotno.length!=4)
        {
            alert('Lot Number Size is Invalid, 4 digit Required');
            $('#lot_no'+row_index).val('');
            $('#lot_no'+row_index).focus();
            return;
        }
        else
        {
var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+$("#map_no"+row_index).val()+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+$("#block_no"+row_index).val()+"</p>"+
"<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+$("#lot_no"+row_index).val()+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size"+row_index).val()+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+$("#certificate_number"+row_index).val()+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+$("#village"+row_index).val()+"</p>"+
"</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>"
$('#ViewModal'+row_index).html(html);
        }
    }
        }
</script>
<script>
  $(document).ready(function(){
    $('#gn_div_id').prop('disabled',true);
  if($("#header_lot_no").val()!='' && $("#header_map_no").val()!='' && $("#header_block_no").val()!=''){
        var urlseg1='{{url("/get-form14/")}}';
        $.ajax({url:urlseg1+'/'+$("#header_map_no").val()+'/'+$("#header_block_no").val()+'/'+$("#header_lot_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else
                {
                  $('#gn_div_id').prop('disabled',false);
                  $('#gn_div_id').empty().append('<option value=""></option>');
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
                    $('#type').val(response.element.type);$('#type').trigger('change');
                    $('#sub_type').val(response.element.sub_type);
                    $('#lot_number').val(response.element.lot_no);
                    $('#header_size').val(response.element.size);
                    $('#class').val(response.element.class);$('#class').trigger('change');
                    $('#natowner').val(response.element.ownership_type);$('#natowner').trigger('change');
                    $('#nic').val(response.element.nic_number);
                    $('#name').val(response.element.name);
                    $('#address').val(response.element.addres);
                    $('#mortgages').val(response.element.mortgages);
                    $('#other').val(response.element.other_boudages);
                }
            }
        }});
    }

  });
</script>
 <script>
    
    function Geeks(id) {
      var x = id.parentNode.parentNode.rowIndex;
        for(i=0;i<4;i++){
                $("#myTable tr:eq("+(x-1)+")").remove();
            }
            details_list.splice(Math.floor((x/4)),1);
       // document.getElementById("myTable").deleteRow(i);

        if(($('#myTable tr:last').index() + 1)>1){
                            $('#forward_regional_officer').prop('disabled',false);
                            $('#save').prop('disabled',false);
                        }else{
                            $('#forward_regional_officer').prop('disabled',true);
                            $('#save').prop('disabled',true);
                        }
    }

    $("#map_no").change(function () {
    if($("#map_no").val()!='' && $("#map_no").val().length>=6){
        var urlseg1='{{url("/get-form14-maps-numbers/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#block_no').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#block_no').append('<option value="'+element.block_no+'">'+element.block_no+'</option>');
                        });  
                        $("#lot_no").val('');
                        $('#size').val('');
                        $('#certificate_number').val('');
                        $("#village").val(''); 
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
});

$("#map_no").keyup(function () {
    if($("#map_no").val()!='' && $("#map_no").val().length>=4){
        $('#block_no').prop('disabled',false);
    }else{
        $('#block_no').prop('disabled',true);
    }
});
//2020/01/12 change
$("#header_map_no").change(function () {
    if($("#header_map_no").val()!='' && $("#header_map_no").val().length>=4){
        var urlseg1='{{url("/get-form14-maps-numbers/")}}';
        $.ajax({url:urlseg1+'/'+$("#header_map_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#header_block_no').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#header_block_no').append('<option value="'+element.block_no+'">'+element.block_no+'</option>');
                        }); 
                }
            }
        }});
    }
});

$("#header_block_no").change(function () {
    if($("#header_block_no").val()!=''){
        var urlseg1='{{url("/get-form14/")}}';
        $.ajax({url:urlseg1+'/'+$("#header_map_no").val()+'/'+$("#header_block_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                  $('#header_lot_no').empty().append('<option value="">Please Select</option>');
                      $(response).each(function(i,element){
                         $('#header_lot_no').append('<option value="'+element.lot_no+'">'+element.lot_no+'</option>');
                      }); 
                }
            }
        }});
    }
});

$("#header_lot_no").change(function(){
  if($("#header_lot_no").val()!=''){
        var urlseg1='{{url("/get-form14/")}}';
        $.ajax({url:urlseg1+'/'+$("#header_map_no").val()+'/'+$("#header_block_no").val()+'/'+$("#header_lot_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else
                {
                  $('#gn_div_id').prop('disabled',false);
                  $('#gn_div_id').empty().append('<option value=""></option>');
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
                    $('#type').val(response.element.type);$('#type').trigger('change');
                    $('#sub_type').val(response.element.sub_type);
                    $('#lot_number').val(response.element.lot_no);
                    $('#header_size').val(response.element.size);
                    $('#class').val(response.element.class);$('#class').trigger('change');
                    $('#natowner').val(response.element.ownership_type);$('#natowner').trigger('change');
                    $('#nic').val(response.element.nic_number);
                    $('#name').val(response.element.name);
                    $('#address').val(response.element.addres);
                    $('#mortgages').val(response.element.mortgages);
                    $('#other').val(response.element.other_boudages);
                }
            }
        }});
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


$("#block_no").change(function () {
    if($("#block_no").val()!=''){
        var urlseg1='{{url("/get-form14/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                      lotNumbers=[];
                      $(response).each(function(i,element){
                          // $('#lot_no').append('<option value="'+element.lot_no+'">'+element.lot_no+'</option>');
                          lotNumbers.push(element.lot_no);
                        }); 
                        autocomplete(document.getElementById("lot_no"), lotNumbers);
                        $("#lot_no").val('');
                        $('#size').val('');
                        $('#certificate_number').val('');
                        $("#village").val(''); 
                }
            }else{
             // $('#lot_no').empty().append('<option value="">Please Select</option>');
            }
        }});
    }else{
        // $('#block_no').prop('disabled',true);
    }
});

$("#lot_no").keyup(function(){
  if($("#lot_no").val()!=''){
        var urlseg1='{{url("/get-form14-lot-number/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val()+'/'+$("#lot_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else
                {
                  if(response.length>0)
                  {
                  $("#size").val(response[0]['size']);
                  $("#certificate_number").val(response[0]['certificate_number']);
                  $("#village").val(response[0]['village_name']);
                  $('#size').prop('readonly',true);
                  $('#certificate_number').prop('readonly',true);
                  $('#village').prop('readonly',true);
                  }
                  else
                  {
                    $("#size").val('');
                    $("#certificate_number").val('');
                    $("#village").val('');
                    $('#size').prop('readonly',false);
                    $('#village').prop('readonly',false);
                    $('#certificate_number').prop('readonly',true);
                  }
                }
            }else{
              //$('#lot_no').empty().append('<option value="">Please Select</option>');
            }
        }});
    }else{
        // $('#block_no').prop('disabled',true);
    }
});

function tableLotNumOnchange(row_index)
{
  var mapno=$('#map_no'+row_index).val();
  var blockno=$('#block_no'+row_index).val();
  var lotno=$('#lot_no'+row_index).val();
  if(find_duplicateTable(row_index)==1)
  {
    alert('Duplicate Entry');
    $('#lot_no'+row_index).val('');
  }
  else
  {
    details_list[row_index].lot_no=lotno;
  if($("#lot_no"+row_index).val()!=''){
        var urlseg1='{{url("/get-form14-lot-number/")}}';
        $.ajax({url:urlseg1+'/'+mapno+'/'+blockno+'/'+lotno,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                  if(response.length>0)
                  {
                  $("#size"+row_index).val(response[0]['size']);
                  $("#certificate_number"+row_index).val(response[0]['certificate_number']);
                  $("#village"+row_index).val(response[0]['village_name']);
                  var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+mapno+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+blockno+"</p>"+
                "<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+lotno+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+response[0]['size']+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+response[0]['certificate_number']+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+response[0]['village_name']+"</p>"+
                "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div>";
                  $('#ViewModal'+row_index).html(html);
                  //$('#size'+row_index).prop('readonly',true);
                 // $('#certificate_number'+row_index).prop('readonly',true);
                  //$('#village'+row_index).prop('readonly',true);
                  }
                  else
                  {
                    $("#size"+row_index).val('');
                    $("#certificate_number"+row_index).val('');
                    $("#village"+row_index).val('');
                    $('#size'+row_index).prop('readonly',false);
                // $('#certificate_number'+row_index).prop('readonly',true);
                    $('#village'+row_index).prop('readonly',false);
                  }
  
                }
            }else{
              //$('#lot_no'+row_index).empty().append('<option value="">Please Select</option>');
            }
        }});
    }
  }

}
function tableBlockOnchange(row_index,value)
{
  var mapno=$('#map_no'+row_index).val();
  var blockno=$('#'+value.id).val();
  if(find_duplicateTable(row_index)==1)
  {
    alert('Duppicate Entry');
    $('#block_no'+row_index).empty().append('<option value="">Please Select</option>');
  }
  else
  {
    details_list[row_index].block_no=blockno;
  var urlseg1='{{url("/get-form14/")}}';
        $.ajax({url:urlseg1+'/'+mapno+'/'+blockno,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else
                {
                 // $('#lot_no'+row_index).empty().append('<option value="">Please Select</option>');
                 tempRef=countries[row_index]=[];
                 $(response).each(function(i,element){
                  tempRef.push(element.lot_no);
                        });
                        $('#lot_no'+row_index).val('');  
                        $('#size'+row_index).val('');
                        $('#certificate_number'+row_index).val('');
                        $('#village'+row_index).val('');
                        var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+$("#map_no"+row_index).val()+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+$("#block_no"+row_index).val()+"</p>"+
                        "<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+$("#lot_no"+row_index).val()+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size"+row_index).val()+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+$("#certificate_number"+row_index).val()+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+$("#village"+row_index).val()+"</p>"+
                        "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>"
                        $('#ViewModal'+row_index).html(html); 
                }
            }else{
             // $('#lot_no'+row_index).empty().append('<option value="">Please Select</option>');
            }
        }});
  }

}
function tableMapOnchange(row_index,value)
{
  var mapno=$('#'+value.id).val(); 
  if(find_duplicateTable(row_index)==1)
  {
    alert('Duppicate Entry');
    $('#map_no'+row_index).empty().append('<option value="">Please Select</option>');
  }
  else
  {
    details_list[row_index].map_no=mapno;
  var urlseg1='{{url("/get-form14-maps-numbers/")}}';
        $.ajax({url:urlseg1+'/'+mapno,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#block_no'+row_index).empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                        $('#block_no'+row_index).append('<option value="'+element.block_no+'">'+element.block_no+'</option>');
                        }); 
                        $('#lot_no'+row_index).val('');  
                        $('#size'+row_index).val('');
                        $('#certificate_number'+row_index).val('');
                        $('#village'+row_index).val('');
                        var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+$("#map_no"+row_index).val()+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+$("#block_no"+row_index).val()+"</p>"+
                        "<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+$("#lot_no"+row_index).val()+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size"+row_index).val()+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+$("#certificate_number"+row_index).val()+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+$("#village"+row_index).val()+"</p>"+
                        "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>"
                        $('#ViewModal'+row_index).html(html);
                }
            }else{
               // $('#btn_save').prop('disabled',true);
               // $('#block_no').val('');
            }
        }});
  }
}

function tableMap(row_index,value)
{
  var urlseg1='{{url("/get-form14-map-number/")}}';
        $.ajax({url:urlseg1,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#map_no'+row_index).empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                          if(element.map_no==value)
                          {
                            $('#map_no'+row_index).append('<option value="'+element.map_no+'" selected>'+element.map_no+'</option>');
                          }
                          else
                          {
                            $('#map_no'+row_index).append('<option value="'+element.map_no+'">'+element.map_no+'</option>');
                          }
                        });   
                }
            }else{
               
            }
        }});
}
function tableBlock(row_index,mapno,value)
{
  var urlseg1='{{url("/get-form14-maps-numbers/")}}';
        $.ajax({url:urlseg1+'/'+mapno,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#block_no'+row_index).empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                          if(element.block_no==value)
                          {
                            $('#block_no'+row_index).append('<option value="'+element.block_no+'" selected>'+element.block_no+'</option>');
                          }
                          else
                          {
                            $('#block_no'+row_index).append('<option value="'+element.block_no+'">'+element.block_no+'</option>');
                          }
                        });   
                }
            }else{
               // $('#btn_save').prop('disabled',true);
               // $('#block_no').val('');
            }
        }});
}
function tableLot(row_index,mapno,blockno,value)
{
  var urlseg1='{{url("/get-form14/")}}';
        $.ajax({url:urlseg1+'/'+mapno+'/'+blockno,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else
                {
                 // $('#lot_no'+row_index).empty().append('<option value="">Please Select</option>');
                 tempRef=countries[row_index]=[];
                 $(response).each(function(i,element){
                  tempRef.push(element.lot_no);
                   //countries.push(element.lot_no);
                          // if(element.lot_no==value)
                          // {
                          //   $('#lot_no'+row_index).append('<option value="'+element.lot_no+'" selected>'+element.lot_no+'</option>');
                          // }
                          // else
                          // {
                          //   $('#lot_no'+row_index).append('<option value="'+element.lot_no+'">'+element.lot_no+'</option>');
                          // }

                        }); 
                }
            }else{
             // $('#lot_no'+row_index).empty().append('<option value="">Please Select</option>');
            }
        }});
}
function autoSuggest(row_index)
{
  autocomplete2(document.getElementById("lot_no"+row_index), countries,row_index); 
}
function changeLotNumber(row_index,mapno,blockno)
{
  var lotno=$('#lot_no'+row_index).val();
  if($("#lot_no"+row_index).val()!=''){
        var urlseg1='{{url("/get-form14-lot-number/")}}';
        $.ajax({url:urlseg1+'/'+mapno+'/'+blockno+'/'+lotno,type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                  if(response.length>0)
                  {
                  $("#size"+row_index).val(response[0]['size']);
                  $("#certificate_number"+row_index).val(response[0]['certificate_number']);
                  $("#village"+row_index).val(response[0]['village_name']);
                  var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>55th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.map_number')}}</b><p class='text-danger'>"+mapno+"</p><b>{{trans('sentence.block_number')}}</b><p class='text-danger'>"+blockno+"</p>"+
                "<b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+lotno+"</p></div><div class='col-md-6'><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+response[0]['size']+"</p><b>{{trans('sentence.owernship_certificate_no')}}</b><p class='text-danger'>"+response[0]['certificate_number']+"</p> <b>{{trans('sentence.village')}}</b><p class='text-danger'>"+response[0]['village_name']+"</p>"+
                "</div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div>";
                  $('#ViewModal'+row_index).html(html);
                  //$('#size'+row_index).prop('readonly',true);
                 // $('#certificate_number'+row_index).prop('readonly',true);
                  //$('#village'+row_index).prop('readonly',true);
                  }
                  else
                  {
                    $("#size"+row_index).val('');
                    $("#certificate_number"+row_index).val('');
                    $("#village"+row_index).val('');
                    $('#size'+row_index).prop('readonly',false);
                      // $('#certificate_number'+row_index).prop('readonly',true);
                    $('#village'+row_index).prop('readonly',false);
                  }
              
                }
            }else{
              //$('#lot_no'+row_index).empty().append('<option value="">Please Select</option>');
            }
        }});
    }
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
          function autocomplete2(inp, arr,index) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) { return false;}
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr[index].length; i++) {
                  /*check if the item starts with the same letters as the text field value:*/
                  if (arr[index][i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[index][i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[index][i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[index][i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                  }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                  /*If the arrow DOWN key is pressed,
                  increase the currentFocus variable:*/
                  currentFocus++;
                  /*and and make the current item more visible:*/
                  addActive(x);
                } else if (e.keyCode == 38) { //up
                  /*If the arrow UP key is pressed,
                  decrease the currentFocus variable:*/
                  currentFocus--;
                  /*and and make the current item more visible:*/
                  addActive(x);
                } else if (e.keyCode == 13) {
                  /*If the ENTER key is pressed, prevent the form from being submitted,*/
                  e.preventDefault();
                  if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                  }
                }
            });
            function addActive(x) {
              /*a function to classify an item as "active":*/
              if (!x) return false;
              /*start by removing the "active" class on all items:*/
              removeActive(x);
              if (currentFocus >= x.length) currentFocus = 0;
              if (currentFocus < 0) currentFocus = (x.length - 1);
              /*add class "autocomplete-active":*/
              x[currentFocus].classList.add("autocomplete-active");
            }
            function removeActive(x) {
              /*a function to remove the "active" class from all autocomplete items:*/
              for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
              }
            }
            function closeAllLists(elmnt) {
              /*close all autocomplete lists in the document,
              except the one passed as an argument:*/
              var x = document.getElementsByClassName("autocomplete-items");
              for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                  x[i].parentNode.removeChild(x[i]);
                }
              }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });
          }
          /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
         // autocomplete(document.getElementById("lot_no"), countries);
</script>
  <script>
          function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) { return false;}
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                  /*check if the item starts with the same letters as the text field value:*/
                  if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                  }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                  /*If the arrow DOWN key is pressed,
                  increase the currentFocus variable:*/
                  currentFocus++;
                  /*and and make the current item more visible:*/
                  addActive(x);
                } else if (e.keyCode == 38) { //up
                  /*If the arrow UP key is pressed,
                  decrease the currentFocus variable:*/
                  currentFocus--;
                  /*and and make the current item more visible:*/
                  addActive(x);
                } else if (e.keyCode == 13) {
                  /*If the ENTER key is pressed, prevent the form from being submitted,*/
                  e.preventDefault();
                  if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                  }
                }
            });
            function addActive(x) {
              /*a function to classify an item as "active":*/
              if (!x) return false;
              /*start by removing the "active" class on all items:*/
              removeActive(x);
              if (currentFocus >= x.length) currentFocus = 0;
              if (currentFocus < 0) currentFocus = (x.length - 1);
              /*add class "autocomplete-active":*/
              x[currentFocus].classList.add("autocomplete-active");
            }
            function removeActive(x) {
              /*a function to remove the "active" class from all autocomplete items:*/
              for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
              }
            }
            function closeAllLists(elmnt) {
              /*close all autocomplete lists in the document,
              except the one passed as an argument:*/
              var x = document.getElementsByClassName("autocomplete-items");
              for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                  x[i].parentNode.removeChild(x[i]);
                }
              }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });
          }
          /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
          //autocomplete(document.getElementById("lot_no"), lotNumbers);
</script>
@include('scripts.size_validate');
@endsection
