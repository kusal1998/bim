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
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0" style="max-width: 1400px;">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">14<sup>th</sup> Sentence</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New</li>
                    </ol>
                </nav>
                {{-- <h4 class="mg-b-0 tx-spacing--1">14<sup>th</sup> Sentence</h4> --}}
                <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.registration_of_titles')}}</h4>
                <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.title_registration_act_no_21_of_1998')}}</h4>
                <h4 class="mg-b-0 tx-spacing--1">
                    {{trans('sentence.declaration_of_decision_of_the_commissioner_of_title_settlement_under_section_14')}}
                </h4>
            </div>
            <div class="d-none d-md-block">
                @include('buttons._back')
            </div>
        </div>

        <div data-label="Example" class="df-example" id="tabs">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">14<sup>th Form Details</a>
                </li>
                @if(Request::segment(2)!='create')
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                aria-controls="profile" aria-selected="false">Owner's Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab"
                        aria-controls="history" aria-selected="false">Form History</a>
                </li>
                @endif
            </ul>
            <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form id="main" method="post" @if(Request::segment(2)=='create' )
                        action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$element->id) }}"
                        @endif>
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="file_no">Reference Number</label>
                                <input type="text" required @if((Request::segment(2)=='view' )) readonly @endif readonly name="file_no"
                                    class="form-control" id="file_no" value="@if(isset($element)){{$element->file_no}} @else @if(isset($file_no)){{$file_no}} @endif @endif"
                                    placeholder=""d>
                            </div>
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
                        </div>
                        <div class="form-row">
                             <div class="form-group col-md-3">
                                <label for="map_no">{{trans('sentence.map_number')}}</label>
                                {{-- <input type="text" pattern="\d*" minlength="6" maxlength="6" autocomplete="off" @if((Request::segment(2)=='view' )) readonly @endif
                                    class="form-control" id="map_no" name="map_no" required value="@if(isset($element)){{$element->map_no}}@endif"> --}}
                                    <select id="map_no"  @if(Request::segment(2)=='view' ) readonly @endif name="map_no" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="map_no" required @if(isset($element)){{$element->current_stage=='Online Publish'}} disabled @endif>
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
                                <label for="zonal_no">{{trans('sentence.block_number')}}</label>
                                {{-- <input type="text" pattern="\d*" minlength="2" maxlength="2" autocomplete="off" @if((Request::segment(2)=='view' ))  readonly @endif  @if(isset($form12)) @if($form12->current_stage=='Online Publish') disable @endif @endif
                                    class="form-control" id="block_no" name="block_no" required value="@if(isset($element)){{$element->block_no}}@endif"> --}}

                                    <select id="block_no"  @if(Request::segment(2)=='view' ) readonly @endif name="block_no" @if(Request::segment(2)=='view') disabled @endif  class="form-control form-control-sm select2" name="block_no" required  @if(isset($element)){{$element->current_stage=='Online Publish'}} disabled @endif>
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
                                <label for="gn_div_id">{{trans('sentence.gn_Division')}}</label>
                                <select id="gn_div_id" name="gn_division_id[]" @if((Request::segment(2)=='view' )) 
                                    disabled @endif class="form-control form-control-sm select2" multiple required @if(isset($element)){{$element->current_stage=='Online Publish'}} disabled @endif> 
                                    @if(trans('sentence.lang')=='EN')
                                    <option value=""></option>
                                    @foreach ($gnDivision as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('gn_division_id',$element->gn_division_id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->gn_division_id) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->gn_name}}</option>
                                    @endforeach
                                    @else
                                    <option value=""></option>
                                    @foreach ($gnDivision as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('gn_division_id',$element->gn_division_id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->gn_division_id) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->sinhala_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="village_id">{{trans('sentence.village')}}</label>
                                <select @if(isset($form12)) @if($form12->current_stage=='Regional commissioner' || $form12->current_stage=='Regional data entry') id="village_id"  @else  disabled  @endif @else id="village_id"  @endif  name="village_name[]" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="village_name[]" multiple required>
                                    @if(trans('sentence.lang')=='EN')
                                    <option value="">Please Select</option>
                                    @foreach ($villages as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('village_id',$element->village_name)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->village_name) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->village}}</option>
                                    @endforeach
                                    @else
                                    <option value="">Please Select</option>
                                    @foreach ($villages as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('village_id',$element->id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->village_name) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->sinhala_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                              </div>


                            <div class="form-group col-md-3">
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
                            <div class="form-group col-md-3">
                                <label for="gov_lands">{{trans('sentence.goverment_lands')}}</label>
                                <input type="number" @if((Request::segment(2)=='view' )) readonly @endif
                                    class="form-control" id="governments_lands" name="governments_lands" autocomplete="off" required value="@if(isset($element)){{$element->governments_lands}}@endif">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pri_lands">{{trans('sentence.private_lands')}}</label>
                                <input type="number" @if((Request::segment(2)=='view' )) readonly @endif
                                    class="form-control" id="private_lands" name="private_lands" required autocomplete="off" value="@if(isset($element)){{$element->private_lands}}@endif">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.total_lands')}}</label>
                                <input type="number" @if((Request::segment(2)=='view' )) readonly @endif readonly
                                    class="form-control" id="total_lands" name="total_lands" required autocomplete="off" value="@if(isset($element)){{$element->total_lands}}@endif">
                            </div>
                            @if($UtilityService->getAccessGazette(Request::segment(1))&&($form12->current_stage=='Gov Press without G'))
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_14th_gazzette_date')}}</label>
                                <input type="text" @if((Request::segment(2)=='view' )) readonly @endif class="form-control datepicker2" id="gazetted_date"
                                name="gazetted_date" required autocomplete="off" value="@if(isset($element)){{date('Y-m-d',strtotime($element->gazetted_date))}}@endif">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_14th_gazzette_no')}}</label>
                                <input type="text" @if((Request::segment(2)=='view' )) readonly autocomplete="off" @endif  class="form-control" id="gazetted_no" name="gazetted_no" required value="@if(isset($element)){{$element->gazetted_no}}@endif">
                            </div>
                            @endif
                            @if($UtilityService->getAccessCertificate(Request::segment(1)))
                            @if($form12!=null)
                            @if($form12->current_stage=='Online Publish'||$form12->current_stage=='Certificate issued')
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.certificate_isssue_gazzette')}}</label>
                                <input type="text" @if((Request::segment(2)=='view' || $form12->current_stage=='Certificate issued')) readonly @endif class="form-control" id="certificate_isssue_gazzette"
                                    name="certificate_isssue_gazzette" required autocomplete="off" value="@if(isset($element)){{$element->gazetted_no}}@endif" @if(isset($element)){{$element->current_stage=='Online Publish'}} disabled @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.certificate_isssue_gazzette_date')}}</label>
                                <input type="text" @if((Request::segment(2)=='view' )) readonly @endif class="form-control datepicker3"
                                    id="certificate_isssue_gazzette_date" required autocomplete="off" name="certificate_isssue_gazzette_date" value="@if(isset($element)){{date('Y-m-d',strtotime($element->gazetted_date))}}@endif" @if(isset($element)){{$element->current_stage=='Online Publish'}} disabled @endif>
                            </div>
                            @endif
                            @endif
                            @endif
                        </div>
                        <input type="hidden" class="form-control" name="form_name" id="form_name" value="header" placeholder="">


                        @if(Request::segment(2)=='update')
                            @if($UtilityService->getAccessGazette(Request::segment(1))=='Yes'||$UtilityService->getAccessUpdate(Request::segment(1))=='Yes'||$UtilityService->getAccessCertificate(Request::segment(1))=='Yes')
                            @if($form12->current_stage=='Regional data entry' || $form12->current_stage=='Gov Press without G')
                                <button class="btn btn-info" type="submit" name="button" value="save">Save</button>
                                <button class="btn btn-warning">Clear</button>
                            @endif
                            @endif
                        @elseif(Request::segment(2)!='view' )
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
                        <!--@if($UtilityService->getAccessPubVerify(Request::segment(1)))-->
                        <!--<div class="form-group col-md-3">-->
                        <!--    <label for="tot_lands">{{trans('sentence.form_12th_ref_no')}}</label>-->
                        <!--    <input type="text" @if(Request::segment(2)=='view' ) readonly @endif required autocomplete="off" class="form-control" onKeyUp="findref()" id="ref_no" name="ref_no" @if(Request::segment(2)!='create' ) value="{{$element->ref_no}}" @endif>-->
                        <!--</div>-->
                        <!--@endif-->
                        @if(($UtilityService->getAccessCreate(Request::segment(1))=='Yes') and (Request::segment(2)!='view'))
                        @if($form12!=null)
                        @if($form12->current_stage!='Online Publish' && $form12->current_stage!='Certificate issued')
                        <div class="form-row">
                            <div class="form-group col-md-1">
                                <label for="lot_no">{{trans('sentence.lot_no')}}</label>
                                <input type="text" autocomplete="off" class="form-control" name="lot_no" id="lot_no" placeholder="">
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="lot_no">{{trans('sentence.land_type')}}</label>
                                    {{-- <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder=""> --}}
                                    <select id="type" name="land_type" class="form-control form-control-sm" required>
                                            <option value="">{{trans('sentence.please_select')}}</option>
                                            <option value="Government">{{trans('sentence.gov')}}</option>
                                            <option value="Private">{{trans('sentence.pvt')}}</option>
                                    </select>
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="sub_type">{{trans('sentence.land_sub_type')}}</label>
                                    <select id="sub_type" name="sub_type" class="form-control form-control-sm decimal" required>
                                    </select>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="size">{{trans('sentence.size')}}</label>
                                <input type="text" pattern="^\d{0,5}(\.\d{0,4})?$" onkeypress="return isNumberKey(event,this.id)"   min="1" class="form-control" id="size" name="size">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="class">{{trans('sentence.Class')}}</label>
                                {{-- <input type="text" class="form-control" id="class" name="class"> --}}
                                <select id="class" name="class" class="form-control form-control-sm" required>
                                <option value="">{{trans('sentence.please_select')}}</option>
                                <option value="1st_Class">{{trans('sentence.1st_class')}}</option>
                                <option value="2nd_Class">{{trans('sentence.2nd_class')}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                    <label for="owner_details_gn_div_id">{{trans('sentence.gn_Division')}}</label>
                                    <select id="owner_details_gn_div_id" name="owner_details_gn_division_id[]" @if((Request::segment(2)=='view' ))
                                        disabled @endif class="form-control form-control-sm select2" multiple required>
                                        @if(trans('sentence.lang')=='EN')
                                        @foreach ($from14GnDivision as $item)
                                        <option value="{{$item->id}}">{{$item->gn_name}}</option>
                                        @endforeach
                                        @else
                                        @foreach ($from14GnDivision as $item)
                                        <option value="{{$item->id}}">{{$item->sinhala_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="nic">{{trans('sentence.nature_of_ownership')}}</label>
                                <select id="natowner" name="natowner" class="form-control form-control-sm" required>
                                    <option value="">{{trans('sentence.please_select')}}</option>
                                    <option value="full">{{trans('sentence.full_ownership')}}</option>
                                    <option value="Equal">{{trans('sentence.equal_ownership')}}</option>
                                    <option value="Disproportionate">{{trans('sentence.disproportionate_ownership')}}</option>
                                </select>
                            </div>

                            <div class="row col-md-12 nicdetails" id="Equal" style="display:none">
                            <div class="form-group col-md-3">
                                <label for="natowner">{{trans('sentence.Owner_nic')}}</label>
                                <input type="text" class="form-control" id="id_number" oninput=valitationNic(this) autocomplete="off" />

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
                                <textarea type="text" class="form-control" id="nic" name="nic" oninput=valitationNic(this) autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="name">{{trans('sentence.name')}} </label>
                                <textarea type="text" class="form-control" id="name" name="name" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="address">{{trans('sentence.address')}}</label>
                                <textarea type="text" class="form-control" id="address" name="address" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mortgages">{{trans('sentence.mortgages')}}</label>
                                <textarea type="text" class="form-control" id="mortgages" name="mortgages" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="other">{{trans('sentence.other_bonds')}}</label>
                                <textarea type="text" class="form-control" id="other" name="other" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="other">ADD ROW</label>
                                <input type="button" class="add-row btn btn-success" value="+">
                            </div>
                        </div>
                        @endif
                        @endif
                        @endif
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice bd-b" id="myTable">

                                <thead>
                                        <th > Details</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                             {{-- <button type="button" class="delete-row btn btn-danger">-</button> --}}

                             <div class="form-group col-md-3">
                            <label for="comment">Comment</label>
                                <textarea  rows="2" cols="50" class="form-control" id="comment"  name="comment" @if(Request::segment(2)!='create' ) value="{{$form12->comment}}" @endif>
                                </textarea>
                            </div>
                          </div>
                             @include('modals.14model')
                         @include('modals.remarks')
                         @include('modals.12recheckmodel')
                         @include('modals.14certificate')
                         @include('modals.14detailsrejectmodel')
                        @include('buttons._action')
                        @include('modals.computerbranch')
                        </div>

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
        placeholder: '',
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
<script>
    function isNumberKey(evt,id)
    {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if(charCode==46){
            var txt=document.getElementById(id).value;
            if(!(txt.indexOf(".") > -1)){

                return true;
            }
        }
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    {
     return false;
    }
  return true;
}
</script>


<script type="text/javascript">
    $(document).ready(function () {
        // Find and remove selected table rows
        $(".delete-row").click(function () {
            $("table tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").remove();
                }
            });
        });
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

function valitationNic(elem)
{
    var oldNic = new RegExp(/^\d{0,9}[v|V|x|X]?$/);
    var newNic = new RegExp(/^\d{0,12}?$/);
    if (oldNic.test($('#'+elem.id).val())) {
        lastValid = $('#'+elem.id).val();
    }
    else if(newNic.test($('#'+elem.id).val()))
    {
        lastValid = $('#'+elem.id).val();
    }
     else
    {
        $('#'+elem.id).val(lastValid);
    }
}
var nicnumber=[];
function tablecreatValidation(row_index)
{
    nicnumber[row_index] = $("#nic"+row_index).val();
    if(nicnumber[row_index]=='null')
    {
        $("#nic"+row_index).val('');
    }

}
function tablevalitationNic(row_index,elem)
{
    var oldNic = new RegExp(/^\d{0,9}[v|V|x|X]?$/);
    var newNic = new RegExp(/^\d{0,12}?$/);
    if (oldNic.test($('#'+elem.id).val())) {
        nicnumber[row_index] = $('#'+elem.id).val();
    }
    else if(newNic.test($('#'+elem.id).val()))
    {
        nicnumber[row_index] = $('#'+elem.id).val();
    }
    else
    {
        $('#'+elem.id).val(nicnumber[row_index]);
    }
}
</script>
@if(Request::segment(2)!='create')
<script>
    var details_list=[];
    var refnumbers=[];
    var lan="{{trans('sentence.lang')}}";
    $(document).ready(function(){
        var modelhtml='';
        var rejectmodel='';
        var certificateModel='';
          $.ajaxSetup({
                      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                      url: "{{ url('14th-sentence/details/'.Request::segment(3)) }}",
                      type: "GET",
                      success: function(data){
                      var translate='1st_Class';
                      var translate2='2nd_Class';
                      var type=[];
                        var land_class=[];
                        var sub_type=[];
                        var gn_division=[];
                      for (i = 0; i <data.message.length; i++) {
                        details_list.push(data.message[i]);
                        var html='';
                        var cert_number='';
                        if(data.message[i].certificate_number==null){
                            cert_number='';
                        }else{
                            cert_number=data.message[i].certificate_number;
                        }
                        html=html+"<tr id='row"+i+"' class='table-row'><td><table style='width:100%;'><tr><th class='wd-10p'>{{trans('sentence.lot_no')}}</th> <th class='wd-10p'>{{trans('sentence.size')}}</th> <th class='wd-15p'>{{trans('sentence.Class')}}</th><th class='wd-15p'>{{trans('sentence.land_type')}}</th><th class='wd-15p'>{{trans('sentence.land_sub_type')}}</th> <th class='wd-15p'>{{trans('sentence.gn_Division')}}</th> <th class='wd-15p'>{{trans('sentence.nature_of_ownership')}}</th></tr><tr scope='row'> <td><textarea id='lot_no"+ i +"' name='form14Details["+ i +"][lot_no]' onkeyup=tableLotNumOnchange("+ i +") onfocusout=checkSize("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly  @endif>"+ data.message[i].lot_no +"</textarea> </td><td><textarea   id='size"+ i +"' name='form14Details["+ i +"][size]' oninput='sizeTextChangeValidation("+ i +",this)' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].size + "</textarea> </td><td><select id='class"+ i +"' name='form14Details["+ i +"][class]' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif><option value='1st_Class'>{{trans('sentence.1st_class')}}</option> <option value='2nd_Class'>{{trans('sentence.2nd_class')}}</option> </select> </td><td><select id='type"+ i +"' onchange='subType("+ i +")' name='form14Details["+ i +"][type]' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif> <option value='Government'>{{trans('sentence.gov')}}</option> <option value='Private'>{{trans('sentence.pvt')}}</option> </select> </td><td><select id='sub_type"+ i +"' name='form14Details["+ i +"][sub_type]' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' required @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif> </select> </td><td><select id='owner_details_gn_div_id"+ i +"' name='form14Details["+ i +"][owner_details_gn_division_id][]' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm select2' multiple required @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif></select> </td> <td><textarea id='natowner"+ i +"' name='form14Details["+ i +"][natowner]' onfocusout=modelUpdate("+ i +") readonly class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].ownership_type + " </textarea></td><td> @if(($UtilityService->getAccessRegReject(Request::segment(1))=='Yes' or $UtilityService->getAccessCreate(Request::segment(1))=='Yes') and (Request::segment(2)=='update') and ($form12->current_stage!='Online Publish' or $form12->current_stage!='Certificate issued')) <a href='javascript:;' data-toggle='modal' data-target='#RejectModal"+ i +"' class='btn btn-icon btn-danger' title='Approve'><i class='fas fa-times-circle'></i></a>@endif @if(isset($form12->current_stage)) @if(($UtilityService->getAccessCertificate(Request::segment(1))=='Yes')&&($form12->current_stage=='Online Publish'))<a href='javascript:;' data-toggle='modal' data-target='#CertificateModal"+ i +"' class='btn btn-icon btn-success' title='Approve'><i class='fas fa-plus'></i></a>@endif @endif </td></tr></table><table width='100%'><tr><th class='wd-20p' >{{trans('sentence.Owner_nic')}}</th> <th class='wd-20p' >{{trans('sentence.name')}}</th> <th class='wd-20p' >{{trans('sentence.address')}}</th> <th class='wd-20p' >{{trans('sentence.mortgages')}}</th> <th class='wd-20p' >{{trans('sentence.other_bonds')}}</th> @if(isset($form12))@if(($UtilityService->getAccessCreate(Request::segment(1))=='Yes') and(($form12->current_stage=='Certificate issued' or $form12->current_stage=='Online Publish' )))<th class='wd-30p'>{{trans('sentence.certificate_number')}}</th> @endif @endif </tr><tr> <td><textarea id='nic"+ i +"' name='form14Details["+ i +"][nic]' oninput='tablevalitationNic("+ i +",this)' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].nic_number + "</textarea></td><td> <textarea id='name"+ i +"' name='form14Details["+ i +"][name]' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].name + "</textarea> </td><td> <textarea id='address"+ i +"' name='form14Details["+ i +"][address]' type='text' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].addres + "</textarea> </td><td> <textarea id='mortgages"+ i +"' name='form14Details["+ i +"][mortgages]' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].mortgages + "</textarea> </td><td> <textarea id='other"+ i +"' name='form14Details["+ i +"][other]' type='text' onfocusout=modelUpdate("+ i +") class='form-control form-control-sm' @if(isset($element)){{$element->current_stage=='Online Publish'}} readonly @endif>" + data.message[i].other_boudages + "</textarea> </td>@if(isset($form12))@if(($UtilityService->getAccessCreate(Request::segment(1))=='Yes') and(($form12->current_stage=='Certificate issued' or $form12->current_stage=='Online Publish' )))<td> <textarea id='certificate_number"+ i +"' name='form14Details["+ i +"][certificate_number]' onfocusout=modelUpdate("+ i +") type='text' class='form-control form-control-sm'>" + cert_number + "</textarea></td>@endif @endif </tr></table></td></tr>";
                       
                        // console.log($('#myTable tbody').children('>tr').each().html());
                        rejectmodel="<form action='{{url('14th-sentence/reject/update')}}' method='get'><div  class='modal fade' id='RejectModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-sm' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>Reject</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='form-group col-md-12'><label for='tot_lands'>Reason</label><input type='hidden' name='detail_id'  value='"+data.message[i].id+"'><input type='text' class='form-control' id='' name='reason'></div></div><div class='modal-footer'><button type='button' class='btn btn-info' data-dismiss='modal'>Cancel</button><button type='submit' name='button' value='reject'  class='btn btn-info'>Save</button></div></div></div></div></form>";
                             
                        certificateModel="<form action='{{url('14th-sentence/certification/update ')}}' method='get'><div class='modal fade' id='CertificateModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'> <div class='modal-dialog modal-dialog-centered modal-sm' role='document'> <div class='modal-content tx-14'> <div class='modal-header'> <h6 class='modal-title' id='exampleModalLabel5'>Enter Certificate Number</h6> <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button> </div><div class='modal-body'> <div class='form-group col-md-12'> <label for='tot_lands'></label> <input type='hidden' name='detail_id' value='"+data.message[i].id+"'><lable>NIC No</lable><textarea type='text' name='id'  class='form-control'>" + data.message[i].nic_number + "</textarea> &nbsp <div class='row'><div class='col-md-6'><lable>NIC 01 Cer. No</lable> <input type='text' class='form-control' id='' name='certificateNumber'></div><div class='col-md-6'> @if('"+data.message[i].ownership_type+"'!='full') <lable>NIC 02 Cer. No</lable><input type='text' class='form-control' id='' name='certificateNumber1' > @endif </div> </div> </div></div><div class='modal-footer'> <button type='button' class='btn btn-info' data-dismiss='modal'>Cancel</button> <button type='submit' name='button' value='reject' class='btn btn-info'>Save</button> </div></div></div></div></form>";
                        
                        modelhtml="<div class='modal fade' id='ViewModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'><div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>14th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'><div class='row'><div class='col-md-6'><b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+data.message[i].lot_no+"</p><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+data.message[i].size+"</p>"+
                            "<b>{{trans('sentence.name')}}</b><p class='text-danger'>"+data.message[i].name+"</p><b>{{trans('sentence.address')}}</b><p class='text-danger'>"+data.message[i].addres+"</p><b>{{trans('sentence.Owner_nic')}}</b><p class='text-danger'>"+data.message[i].nic_number+"</p></div><div class='col-md-6'>"+
                            "<b>{{trans('sentence.nature_of_ownership')}}</b><p class='text-danger'>"+data.message[i].ownership_type+"</p><b>{{trans('sentence.Class')}}</b><p class='text-danger'>"+data.message[i].class+"</p><b>{{trans('sentence.mortgages')}}</b><p class='text-danger'>"+data.message[i].mortgages+"</p><b>{{trans('sentence.other_bonds')}}</b><p class='text-danger'>"+data.message[i].other_boudages+"</p> <b>{{trans('sentence.certificate_number')}}</b><p class='text-danger'>"+data.message[i].certificate_number+"</p></div></div></div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>";


                        type[i]=data.message[i].type;
                        land_class[i]=data.message[i].class;
                        sub_type[i]=data.message[i].sub_type;
                        gn_division[i]=data.message[i].owner_details_gn_division_id;

                    var content=$("#myTable tbody").html();
                    html=content+html;
                    // console.log(details_list);
                    $("#myTable tbody").html(html);
                    $("#myTable tbody>tr.table-row").css( "background", "#AED6F1" );
                    $("#myTable tbody>tr.table-row:nth-child(2n)").css( "background", "#DCDCDC" );
                    // console.log($('#myTable > tr').each());
                     $('#14model').append(modelhtml);
                     $('#14certificate').append(certificateModel);
                     $('#14rejectmodel').append(rejectmodel);

                     $('#row').val(i);
                     $("#class"+i+" option[value='"+data.message[i].class+"']").prop('selected',true);
                     $("#type"+i+" option[value='"+data.message[i].type+"']").prop('selected',true);
                      subType(i);
                      sizeValidate(i);
                      tablecreatValidation(i);
                     $("#sub_type"+i+" option[value='"+data.message[i].sub_type+"']").prop('selected',true);
                     var form14Gn=@json($from14GnDivision);
                     for(k=0;k<form14Gn.length;k++){
                         if(lan=='EN')
                         {
                            $("#owner_details_gn_div_id"+i).append('<option value="'+form14Gn[k]['id']+'">'+form14Gn[k]['gn_name']+'</option>');
                         }
                         else
                         {
                            $("#owner_details_gn_div_id"+i).append('<option value="'+form14Gn[k]['id']+'">'+form14Gn[k]['sinhala_name']+'</option>');
                         }
                     }
                   // var options=$("#owner_details_gn_div_id > option").clone();
                   // $("#owner_details_gn_div_id"+i).append(options);
                     var selectedGn='';
                     if(data.message[i].owner_details_gn_division_id)
                     {
                         selectedGn=data.message[i].owner_details_gn_division_id.split(",");
                     }

                    for(j=0;j<selectedGn.length;j++)
                    {
                        $("#owner_details_gn_div_id"+i+" option[value='"+selectedGn[j]+"']").prop('selected',true);
                    }
                    }
                    for(j=0;j<$('#row').val();j++){
                        $("#class"+j+" option[value='"+land_class[j]+"']").prop('selected',true);
                        $("#type"+j+" option[value='"+type[j]+"']").prop('selected',true);
                        $("#sub_type"+j+" option[value='"+sub_type[j]+"']").prop('selected',true);
                        $("#owner_details_gn_div_id"+j+" option[value='"+gn_division[j]+"']").prop('selected',true);
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
                if(get_counts()){
                    if($('#lot_no').val()=='')
                    {
                        alert('Lot Number is Required');
                        $('#lot_no').focus();
                        return;
                    }
                    if($('#lot_no').val().length>4)
                    {
                        alert('Lot Number Size is Invalid, Maximum 4 digit allowed');
                        $('#lot_no').val('');
                        $('#lot_no').focus();
                        return;
                    }
                    if($('#size').val()=='')
                    {
                        alert('Size is Required');
                        $('#size').val('');
                        $('#size').focus();
                        return;
                    }
                    if( Number($('#size').val())==0)
                    {
                        alert("Size is Can't be Zero");
                        $('#size').val('');
                        $('#size').focus();
                        return;
                    }
                    if($('#name').val()=='')
                    {
                        alert('Name is Required');
                        $('#name').focus();
                        return;
                    }
                    // if($('#address').val()=='')
                    // {
                    //     alert('Address is Required');
                    //     return;
                    // }
                    // if($('#nic').val()=='')
                    // {
                    //     alert('NIC is Required');
                    //     return;
                    // }
                    if($('#natowner').val()=='')
                    {
                        alert('Nature of Ownership is Required');
                        return;
                    }
                    if($('#class').val()=='')
                    {
                        alert('Class is Required');
                        return;
                    }
                    // if($('#mortgages').val()=='')
                    // {
                    //     alert('Mortgages is Required');
                    //     return;
                    // }
                    if($('#type').val()=='')
                    {
                        alert('Type is Required');
                        return;
                    }
                    else
                    {
                        var formData = {
                            'lot_no': $('#lot_no').val(),
                            'map_no':$('#map_no').val(),
                            'block_no':$('#block_no').val()
                        };

                        $.ajax({
                            url: "/14th-sentence/dupicate",
                            type: "post",
                            data: formData,
                            success: function(d) {
                                if(find_duplicate()==1)
                                {
                                    alert('Duplicate Entries!!!');
                                    return;
                                }
                                else if(d.message!=0)
                                {
                                    alert('Duplicate Entries!');
                                    return;
                                }
                                else
                                {
                                    details_list.push({addres:$("#address").val(),class:$("#class").val(),type:$("#type").val(),created_at:null,deleted_at:null,form_14_Header_id:null,id:null,lot_no:$('#lot_no').val(),mortgages:$("#mortgages").val(),name:$("#name").val(),nic_number:$("#nic").val(),other_boudages:$("#other").val(),ownership_type:$("#natowner").val(),rejected:0,size:$('#size').val(),updated_at:null});

                                    var html="<tr id='row"+$('#row').val()+"' class='table-row'><td><table style='width:100%;'><tr><th class='wd-10p'>{{trans('sentence.lot_no')}}</th> <th class='wd-10p'>{{trans('sentence.size')}}</th> <th class='wd-15p'>{{trans('sentence.Class')}}</th><th class='wd-15p'>{{trans('sentence.land_type')}}</th><th class='wd-15p'>{{trans('sentence.land_sub_type')}}</th><th class='wd-20p'>{{trans('sentence.gn_Division')}}</th><th class='wd-15p'>{{trans('sentence.nature_of_ownership')}}</th></tr><tr><td><textarea rows='1' id='lot_no"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][lot_no]' onkeyup=tableLotNumOnchange("+$('#row').val()+")  onfocusout=checkSize("+$('#row').val()+") class='form-control form-control-sm'>"+$('#lot_no').val()+"</textarea></td><td><textarea rows='1' id='size"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][size]' oninput='sizeTextChangeValidation("+$('#row').val()+",this)' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'>" + $('#size').val()+ "</textarea></td><td><select id='class"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][class]' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'><option value='1st_Class'>{{trans('sentence.1st_class')}}</option> <option value='2nd_Class'>{{trans('sentence.2nd_class')}}</option> </select> </td><td><select id='type"+$('#row').val()+"' onchange='subType("+$('#row').val()+")' name='form14Details["+$('#row').val()+"][type]' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'><option value=''>please select</option> <option value='Government'>{{trans('sentence.gov')}}</option> <option value='Private'>{{trans('sentence.pvt')}}</option> </select></td><td><select id='sub_type"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][sub_type]' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm' required> </select> </td><td><select id='owner_details_gn_div_id"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][owner_details_gn_division_id][]' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm select2' multiple required> </select></td><td><textarea id='natowner"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][natowner]' onfocusout=modelUpdate("+$('#row').val()+") readonly class='form-control form-control-sm'>" + $('#natowner').val() + " </textarea></td><td><button type='button' onclick='Geeks(this)' class='btn btn-icon btn-danger' title='Reject'><i class='fas fa-times-circle'></i> </button> </td></tr></table><table style='width:100%;'><tr><th style='width:12%;'>{{trans('sentence.Owner_nic')}}</th><th class='wd-20p'>{{trans('sentence.name')}}</th> <th class='wd-20p'>{{trans('sentence.address')}}</th><th class='wd-20p'>{{trans('sentence.mortgages')}}</th><th class='wd-20p'>{{trans('sentence.other_bonds')}}</th></tr><tr><td><textarea id='nic"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][nic]' oninput='tablevalitationNic("+$('#row').val()+",this)' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'>" + $('#nic').val() + "</textarea> </td><td><textarea id='name"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][name]' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'>" + $('#name').val() + "</textarea></td><td><textarea id='address"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][address]' type='text' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'>" + $('#address').val() + "</textarea> </td><td><textarea id='mortgages"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][mortgages]' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'>" + $('#mortgages').val() + "</textarea></td><td> <textarea id='other"+$('#row').val()+"' name='form14Details["+$('#row').val()+"][other]' type='text' onfocusout=modelUpdate("+$('#row').val()+") class='form-control form-control-sm'>" + $('#other').val() + "</textarea></td></tr></table></td></tr> ";

                                    modelhtml="<div class='modal fade' id='ViewModal"+$('#row').val()+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel5' aria-hidden='true'>"+
                                        "<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>14th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'>"+
                                        "<div class='row'><div class='col-md-6'><b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+$("#lot_no").val()+"</p><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size").val()+"</p>"+
                                        "<b>{{trans('sentence.name')}}</b><p class='text-danger'>"+$("#name").val()+"</p><b>{{trans('sentence.address')}}  </b><p class='text-danger'>"+$("#address").val()+"</p><b>{{trans('sentence.Owner_nic')}}</b><p class='text-danger'>"+$("#nic").val()+"</p> </div><div class='col-md-6'>"+
                                        "<b>{{trans('sentence.nature_of_ownership')}}</b><p class='text-danger'>"+$("#natowner").val()+"</p><b>{{trans('sentence.Class')}}</b><p class='text-danger'>"+$("#class").val()+"</p><b>{{trans('sentence.mortgages')}}</b><p class='text-danger'>"+$("#mortgages").val()+"</p><b>{{trans('sentence.other_bonds')}}</b><p class='text-danger'>"+$("#other").val()+"</p></div><div class='modal-footer'><button type='button' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>";

                                    var type=[];
                                    var land_class=[];
                                    var sub_type=[];
                                    var gn_division=[];
                                    for(i=0;i<$('#row').val();i++){
                                        type[i]=$('#type'+i).val();
                                        land_class[i]=$('#class'+i).val();
                                        sub_type[i]=$('#sub_type'+i).val();
                                        gn_division[i]=$('#owner_details_gn_div_id'+i).val();
                                    }
                                    var content=$("#myTable tbody").html();
                                    html=content+html;
                                    $("#myTable tbody").html(html);
                                    $("#myTable tbody>tr.table-row").css( "background", "#AED6F1" );
                                    $("#myTable tbody>tr.table-row:nth-child(2n)").css( "background", "#DCDCDC" );
                                    $("#class"+$('#row').val()+" option[value='"+$('#class').val()+"']").prop('selected',true);
                                    $("#type"+$('#row').val()+" option[value='"+$('#type').val()+"']").prop('selected',true);
                                    //$("#type"+$('#row').val()).trigger('change');
                                    subType($('#row').val());
                                    sizeValidate($('#row').val());
                                    tablecreatValidation($('#row').val());
                                    $("#sub_type"+$('#row').val()+" option[value='"+$('#sub_type').val()+"']").prop('selected',true);
                                    var options=$("#owner_details_gn_div_id > option").clone();
                                    $("#owner_details_gn_div_id"+$('#row').val()).append(options);
                                    $('#14model').append(modelhtml);
                                    var selectedGn=$("#owner_details_gn_div_id").val();
                                    for(i=0;i<selectedGn.length;i++)
                                    {
                                        $("#owner_details_gn_div_id"+$('#row').val()+" option[value='"+selectedGn[i]+"']").prop('selected',true);
                                    }

                                    for(i=0;i<$('#row').val();i++){
                                        $("#class"+i+" option[value='"+land_class[i]+"']").prop('selected',true);
                                        $("#type"+i+" option[value='"+type[i]+"']").prop('selected',true);
                                        $("#sub_type"+i+" option[value='"+sub_type[i]+"']").prop('selected',true);
                                        $("#owner_details_gn_div_id"+i+" option[value='"+gn_division[i]+"']").prop('selected',true);
                                    }


                                    $('#row').val($('#row').val() * 1 + 1);
                                    $('#lot_no').val('');
                                    $('#size').val('');
                                    $('#name').val('');
                                    $('#address').val('');
                                    $('#nic').val('');
                                    $('#natowner').val('');
                                    $('#class').val('');
                                    $('#mortgages').val('');
                                    $('#other').val('');
                                    $("#class option[value='']").prop('selected',true);
                                    $('#class').change();
                                    $("#natowner option[value='']").prop('selected',true);
                                    $('#natowner').change();
                                    $("#type option[value='']").prop('selected',true);
                                    $('#type').trigger('change');
                                    $("#sub_type option[value='']").prop('selected',true);
                                    $('#sub_type').trigger('change');

                                    if(($('#myTable tr:last').index() + 1)>1){
                                        $('#forward_regional_officer').prop('disabled',false);
                                        $('#save').prop('disabled',false);
                                    }else{
                                        $('#forward_regional_officer').prop('disabled',true);
                                        $('#save').prop('disabled',true);
                                    }
                                }
                            }
                        });
                    }
                }else{
                    alert('Land type mismatch in owners list');
                }
            });
        $('#type').change(function(){
            get_counts();
        });
        function get_counts(){
            var govt_lands=1;
            var pvt_lands=1;
            for(var i=0; i<details_list.length; i++)
            {
                if(details_list[i].type ===  "Government"){
                    govt_lands=govt_lands+1;
                }
                else if(details_list[i].type ===  "Private"){
                    pvt_lands=pvt_lands+1;
                }
            }
            if($('#type').val()==="Government")
            {
                if($('#type').val()==="Government" && govt_lands>$('#governments_lands').val()*1){
                alert('Government land limit reached in owners list');
                $("#type option[value='']").prop('selected',true);
                $('#type').val('');
                return false;
            }
            }
            if($('#type').val()==="Private")
            {
                if($('#type').val()==="Private" && pvt_lands>$('#private_lands').val()*1){
                alert('Private land limit reached in owners list');
                $("#type option[value='']").prop('selected',true);
                $('#type').val('');
                return false;
            }
            }

            return true;
        }
        function find_duplicate()
        {
            var statues=0;
            for(var i=0; i<details_list.length; i++)
            {
                if(details_list[i].lot_no ==  $('#lot_no').val())
                {
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
        if($('#gazetted_no').val()==''||$('#gazetted_date').val()==''||$('#gazetted_no').val()==null||$('#gazetted_date').val()==null){
            $('#computer_with_G').prop('disabled',true);
        }else{
            $('#computer_with_G').prop('disabled',false);
        }
    });

    function find_duplicateTable(row_index)
    {
            var statues=0;
            if($('#lot_no'+row_index).val()!='')
            {
            for(var i=0; i<details_list.length; i++){
                if((details_list[i].lot_no ==$('#lot_no'+row_index).val())){
                  statues=1;
                  break;
                }
                else
                {
                statues=0;
                }
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
        if(lotno.length>4)
        {
            alert('Lot Number Size is Invalid, 4 digit Required');
            $('#lot_no'+row_index).val('');
            $('#lot_no'+row_index).focus();
            return;
        }
        else
        {
            var tblformData = {
                            'lot_no': $('#lot_no'+row_index).val(),
                            'map_no':$('#map_no').val(),
                            'block_no':$('#block_no').val()
                        };
            $.ajax({
            url: "/14th-sentence/dupicate",
            type: "post",
            data: tblformData,
            success: function(d) {
                if(d.message!=0)
                {
                    alert('Duplicate Entries,Beacuse This Lot Number Already Inserted!');
                    $('#lot_no'+row_index).val('');
                    $('#lot_no'+row_index).focus();
                    return;
                }
                else
                {
                    var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>14th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'>"+
"<div class='row'><div class='col-md-6'><b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+lotno+"</p><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size"+row_index).val()+"</p>"+
"<b>{{trans('sentence.name')}}</b><p class='text-danger'>"+$("#name"+row_index).val()+"</p><b>{{trans('sentence.address')}}  </b><p class='text-danger'>"+$("#address"+row_index).val()+"</p><b>{{trans('sentence.Owner_nic')}}</b><p class='text-danger'>"+$("#nic"+row_index).val()+"</p> </div><div class='col-md-6'>"+
"<b>{{trans('sentence.nature_of_ownership')}}</b><p class='text-danger'>"+$("#natowner"+row_index).val()+"</p><b>{{trans('sentence.Class')}}</b><p class='text-danger'>"+$("#class"+row_index).val()+"</p><b>{{trans('sentence.mortgages')}}</b><p class='text-danger'>"+$("#mortgages"+row_index).val()+"</p><b>{{trans('sentence.other_bonds')}}</b><p class='text-danger'>"+$("#other"+row_index).val()+"</p></div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>"
$('#ViewModal'+row_index).html(html);
                }

            }
        });
    }
        }
    }

    function modelUpdate(row_index)
    {
    var oldNic = new RegExp(/^\d{0,9}[v|V|x|X]?$/);
    var newNic = new RegExp(/^\d{0,12}?$/);
    // var status=0;
    // if($('#nic'+row_index).val().length!=0)
    // {
    // if (oldNic.test($('#nic'+row_index).val())) {
    //     status=1;
    // }
    // else if(newNic.test($('#nic'+row_index).val()))
    // {
    //     status=1;
    // }
    // else
    // {
    //     status=0;
    // }
    // }
    // if(status==0)
    // {
    //     alert('Please Check NIC Number Format');
    //     $('#nic'+row_index).val('');
    //     $('#nic'+row_index).focus();
    //     return;
    // }
var html="<div class='modal-dialog modal-dialog-centered modal-lg' role='document'><div class='modal-content tx-14'><div class='modal-header'><h6 class='modal-title' id='exampleModalLabel5'>14th Model Details</h6><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><div class='modal-body'>"+
"<div class='row'><div class='col-md-6'><b>{{trans('sentence.lot_no')}}</b><p class='text-danger'>"+$('#lot_no'+row_index).val()+"</p><b>{{trans('sentence.size')}}</b><p class='text-danger'>"+$("#size"+row_index).val()+"</p>"+
"<b>{{trans('sentence.name')}}</b><p class='text-danger'>"+$("#name"+row_index).val()+"</p><b>{{trans('sentence.address')}}  </b><p class='text-danger'>"+$("#address"+row_index).val()+"</p><b>{{trans('sentence.Owner_nic')}}</b><p class='text-danger'>"+$("#nic"+row_index).val()+"</p> </div><div class='col-md-6'>"+
"<b>{{trans('sentence.nature_of_ownership')}}</b><p class='text-danger'>"+$("#natowner"+row_index).val()+"</p><b>{{trans('sentence.Class')}}</b><p class='text-danger'>"+$("#class"+row_index).val()+"</p><b>{{trans('sentence.mortgages')}}</b><p class='text-danger'>"+$("#mortgages"+row_index).val()+"</p><b>{{trans('sentence.other_bonds')}}</b><p class='text-danger'>"+$("#other"+row_index).val()+"</p></div><div class='modal-footer'><button type='butto' class='btn btn-info' data-dismiss='modal'>Close</button></div></form></div></div></div>"
$('#ViewModal'+row_index).html(html);
    }
  </script>
  @endif
 <script>
        function Geeks(id) {
            var x = id.parentNode.parentNode.rowIndex;
            for(i=0;i<6;i++){
                $("#myTable tr:eq("+(x-1)+")").remove();
            }
            details_list.splice(Math.floor((x/6)),1);
            if(($('#myTable tr:last').index() + 1)>1){
                $('#forward_regional_officer').prop('disabled',false);
                $('#save').prop('disabled',false);
            }else{
                $('#forward_regional_officer').prop('disabled',true);
                $('#save').prop('disabled',true);
            }
        }

function tableLotNumOnchange(row_index)
{
    var lotno=$('#lot_no'+row_index).val();
    if(find_duplicateTable(row_index)==1)
    {
    alert('Duplicate Lot Number');
    $('#lot_no'+row_index).val('');
    }
    else
    {
    details_list[row_index].lot_no=lotno;
    }

}
</script>
<script>
    $("#governments_lands").keyup(function () {
        if($('#governments_lands').val()!='')
        {
            $('#governments_lands').val($('#governments_lands').val() * 1);

        }else
        {
            $('#governments_lands').val('0');
        }
        if($('#private_lands').val()!='')
        {
            $('#private_lands').val($('#private_lands').val() * 1);
        }
        else
        {
            $('#private_lands').val('0');
        }
        if(($('#governments_lands').val()!='') && ($('#private_lands').val()!=''))
        {
            $('#total_lands').val(($('#governments_lands').val() * 1)+($('#private_lands').val() * 1));
        }
    });
@if($UtilityService->getAccessCreate(Request::segment(1))=='Yes')
$(document).ready(function(){
    $('#village_name').prop('readonly',true);
    $('#btn_save').prop('disabled',true);
   // $('#regional_officer').prop('disabled',true);
    $('#governments_lands').prop('readonly',true);
    $('#private_lands').prop('readonly',true);
   // $('#gn_div_id').prop('disabled',true);

});
$("#map_no").keyup(function () {
    if($("#map_no").val()!='' && $("#map_no").val().length>=4){
        $('#block_no').prop('disabled',false);
    }else{
        $('#block_no').prop('disabled',true);
    }
});

$("#map_no").change(function () {
    if($("#map_no").val()!='' && $("#map_no").val().length>=6){
        var urlseg1='{{url("/get-form12-maps-numbers/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#block_no').empty().append('<option value="">Please Select</option>');
                        $(response).each(function(i,element){
                            $('#block_no').append('<option value="'+element.block_no+'">'+element.block_no+'</option>');
                        });
                }
            }else{
                $('#village_name').prop('readonly',true);
                $('#btn_save').prop('disabled',true);
               // $('#regional_officer').prop('disabled',true);
                $('#governments_lands').prop('readonly',true);
                $('#private_lands').prop('readonly',true);
              //  $('#gn_div_id').prop('disabled',true);
                $('#block_no').val('');
            }
        }});

    }else{
       // $('#block_no').prop('disabled',true);
    }
});

$("#block_no").change(function () {
    if($("#block_no").val()!='' && $("#block_no").val().length>=1){
        var urlseg1='{{url("/get-form12/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message){
                    alert(response.message);
                }else{
                    $('#village_name').prop('readonly',true);
                    $('#village_name').prop('readonly',true);
                    var gn_array=response.gn_division.split(",");
                    gn_array=gn_array.filter(function(e){return e});
                    var vlg_array=response.village.split(",");
                    vlg_array=vlg_array.filter(function(e){return e});
                    //$('#gn_div_id').prop('disabled',false);
                    $('#governments_lands').attr({"max" : (response.government_lands*1)});
                    $('#private_lands').attr({"max" : (response.private_lands*1)});
                    $('#gn_div_id').val(gn_array);$('#gn_div_id').trigger("change");
                    $('#village_id').val(vlg_array);$('#village_id').trigger("change");
                  //  $('#regional_officer').prop('disabled',false);
                    $('#governments_lands').val(response.government_lands)
                    $('#governments_lands').prop('readonly',true);
                    $('#private_lands').val(response.private_lands)
                    $('#private_lands').prop('readonly',true);
                    $('#total_lands').val(response.total_lands);
                    $('#total_lands').prop('readonly',true);
                    $('#btn_save').prop('disabled',false);
                }
            }else{
                $('#village_name').prop('readonly',true);
                $('#btn_save').prop('disabled',true);
                $('#regional_officer').prop('disabled',true);
                $('#governments_lands').prop('readonly',true);
                $('#private_lands').prop('readonly',true);
               // $('#gn_div_id').prop('disabled',true);
                $('#block_no').val('');
            }
        }});
    }else{
        // $('#block_no').prop('disabled',true);
    }
});
@endif
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
function subType(row_index)
{
    $('#sub_type'+row_index).empty().append('<option value="">Please Select</option>');
    if($('#type'+row_index).val()==="Private")
    {
         $('#sub_type'+row_index).append('<option value="Private">{{trans('sentence.Private')}}</option>');
         $('#sub_type'+row_index).append('<option value="Grant">{{trans('sentence.Grant')}}</option>');
         $('#sub_type'+row_index).append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type'+row_index).append('<option value="thrashing_floor">{{trans('sentence.thrashing_floor')}}</option>');
         $('#sub_type'+row_index).append('<option value="Edges">{{trans('sentence.Edges')}}</option>');
         $('#sub_type'+row_index).append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type'+row_index).append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type'+row_index).append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type'+row_index).append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
    else if($('#type'+row_index).val()==="Government")
    {
         $('#sub_type'+row_index).append('<option value="The_State">{{trans('sentence.The_State')}}</option>');
         $('#sub_type'+row_index).append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type'+row_index).append('<option value="Brook">{{trans('sentence.Brook')}}</option>');
         $('#sub_type'+row_index).append('<option value="Lake">{{trans('sentence.Lake')}}</option>');
         $('#sub_type'+row_index).append('<option value="River">{{trans('sentence.River')}}</option>');
         $('#sub_type'+row_index).append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type'+row_index).append('<option value="Roundabout">{{trans('sentence.Roundabout')}}</option>');
         $('#sub_type'+row_index).append('<option value="Turning_circle">{{trans('sentence.Turning_circle')}}</option>');
         $('#sub_type'+row_index).append('<option value="Reservation">{{trans('sentence.Reservation')}}</option>');
         $('#sub_type'+row_index).append('<option value="Boulder">{{trans('sentence.Boulder')}}</option>');
         $('#sub_type'+row_index).append('<option value="Licensee">{{trans('sentence.Licensee')}}</option>');
         $('#sub_type'+row_index).append('<option value="Owned_by_institutions">{{trans('sentence.Owned_by_institutions')}}</option>');
         $('#sub_type'+row_index).append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type'+row_index).append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type'+row_index).append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
}
$("select[id ^='type']").change(function (){

    var id=$(this).attr('id');
    var row_index=id.substring(4);
    if(row_index!='')
    {
    $('#sub_type'+row_index).empty().append('<option value="">Please Select</option>');
    if($(this).val()==="Private")
    {
         $('#sub_type'+row_index).append('<option value="Private">{{trans('sentence.Private')}}</option>');
         $('#sub_type'+row_index).append('<option value="Grant">{{trans('sentence.Grant')}}</option>');
         $('#sub_type'+row_index).append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type'+row_index).append('<option value="thrashing_floor">{{trans('sentence.thrashing_floor')}}</option>');
         $('#sub_type'+row_index).append('<option value="Edges">{{trans('sentence.Edges')}}</option>');
         $('#sub_type'+row_index).append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type'+row_index).append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type'+row_index).append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type'+row_index).append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
    else if($(this).val()==="Government")
    {
         $('#sub_type'+row_index).append('<option value="The_State">{{trans('sentence.The_State')}}</option>');
         $('#sub_type'+row_index).append('<option value="Road">{{trans('sentence.Road')}}</option>');
         $('#sub_type'+row_index).append('<option value="Brook">{{trans('sentence.Brook')}}</option>');
         $('#sub_type'+row_index).append('<option value="Lake">{{trans('sentence.Lake')}}</option>');
         $('#sub_type'+row_index).append('<option value="River">{{trans('sentence.River')}}</option>');
         $('#sub_type'+row_index).append('<option value="Drain">{{trans('sentence.Drain')}}</option>');
         $('#sub_type'+row_index).append('<option value="Roundabout">{{trans('sentence.Roundabout')}}</option>');
         $('#sub_type'+row_index).append('<option value="Turning_circle">{{trans('sentence.Turning_circle')}}</option>');
         $('#sub_type'+row_index).append('<option value="Reservation">{{trans('sentence.Reservation')}}</option>');
         $('#sub_type'+row_index).append('<option value="Boulder">{{trans('sentence.Boulder')}}</option>');
         $('#sub_type'+row_index).append('<option value="Licensee">{{trans('sentence.Licensee')}}</option>');
         $('#sub_type'+row_index).append('<option value="Owned_by_institutions">{{trans('sentence.Owned_by_institutions')}}</option>');
         $('#sub_type'+row_index).append('<option value="Cemetery">{{trans('sentence.Cemetery')}}</option>');
         $('#sub_type'+row_index).append('<option value="Well">{{trans('sentence.Well')}}</option>');
         $('#sub_type'+row_index).append('<option value="Other">{{trans('sentence.Other')}}</option>');
    }
    }

});
    </script>
    <script>
            $("#governments_lands").change(function () {
                if($('#governments_lands').val()!='')
                {
                    $('#governments_lands').val($('#governments_lands').val() * 1);

                }else
                {
                    $('#governments_lands').val('0');
                }
                if($('#private_lands').val()!='')
                {
                    $('#private_lands').val($('#private_lands').val() * 1);
                }
                else
                {
                    $('#private_lands').val('0');
                }
                if(($('#governments_lands').val()!='') && ($('#private_lands').val()!=''))
                {
                    $('#total_lands').val(($('#governments_lands').val() * 1)+($('#private_lands').val() * 1));
                }
            });
            </script>
    <script>
            $("#private_lands").keyup(function () {
                if($('#governments_lands').val()!='')
                {
                    $('#governments_lands').val($('#governments_lands').val() * 1);

                }else
                {
                    $('#governments_lands').val('0');
                }
                if($('#private_lands').val()!='')
                {
                    $('#private_lands').val($('#private_lands').val() * 1);
                }
                else
                {
                    $('#private_lands').val('0');
                }
                if(($('#governments_lands').val()!='') && ($('#private_lands').val()!=''))
                {
                    $('#total_lands').val(($('#governments_lands').val() * 1)+($('#private_lands').val() * 1));
                }
            });
    </script>
        <script>
                $("#private_lands").change(function () {
                    if($('#governments_lands').val()!='')
                    {
                        $('#governments_lands').val($('#governments_lands').val() * 1);

                    }else
                    {
                        $('#governments_lands').val('0');
                    }
                    if($('#private_lands').val()!='')
                    {
                        $('#private_lands').val($('#private_lands').val() * 1);
                    }
                    else
                    {

                        $('#private_lands').val('0');
                    }
                    if(($('#governments_lands').val()!='') && ($('#private_lands').val()!=''))
                    {
                        $('#total_lands').val(($('#governments_lands').val() * 1)+($('#private_lands').val() * 1));
                    }
                });
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
            //     $('#nic').val('No NIC');
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
                    $('#nic').val($('#nic').val()+''+count+'. '+$('#id_number').val()+' - '+$('#owner_size').val());
                    $('#name').val($('#name').val()+''+count+'. '+$('#owner_name').val());
                    $('#address').val($('#address').val()+''+count+'. '+$('#owner_address').val());
                }else{
                    $('#nic').val($('#nic').val()+'\n'+''+count+'. '+$('#id_number').val()+' - '+$('#owner_size').val());
                    $('#name').val($('#name').val()+'\n'+''+count+'. '+$('#owner_name').val());
                    $('#address').val($('#address').val()+'\n'+''+count+'. '+$('#owner_address').val());
                }
                count=count+1;
                if($('#natowner').val()!='Equal'){
                    if($('#size').val()==''){
                        $('#size').val($('#owner_size').val()*1);
                    }else{
                        var total_size=$('#size').val()*1+$('#owner_size').val()*1;
                        $('#size').val(total_size.toFixed(4));
                    }
                }else{
                    var elements=$('#nic').val().split('\n');
                    var element_count=1;
                    $('#nic').val('');
                    var owned_extent=($('#size').val()*1)/(elements.length*1);
                    for(k=0;k<elements.length;k++){
                        var nic=elements[k].split('-')[0];
                        var nic_n_extend=nic+'- '+owned_extent.toFixed(4);
                        if($('#nic').val()==''){
                            $('#nic').val($('#nic').val()+''+nic_n_extend);
                        }else{
                            $('#nic').val($('#nic').val()+'\n'+''+nic_n_extend);
                        }
                    }
                }
                $('#id_number').val('');
                $('#owner_name').val('');
                $('#owner_address').val('');
                $('#owner_size').val('')
            }
        });
    });

    </script>
    <script>
         $(document).ready(function(){
            var urlseg1='{{url("/get-form14-ref-number")}}';
        $.ajax({url:urlseg1,type:'get', success: function(response) {
            if(response){
                refnumbers=[];
                $(response).each(function(i,element){
                    refnumbers.push(element.ref_no);
                });
                refnumbers=refnumbers.filter(function(e){return e});
            }
        }});
        });

        function findref()
        {
            autocomplete(document.getElementById("ref_no"), refnumbers);
        }
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
    // autocomplete(document.getElementById("ref_no"), refnumbers);
</script>
@include('scripts.size_validate');
@endsection
