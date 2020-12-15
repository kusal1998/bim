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
              <li class="breadcrumb-item"><a href="#">12<sup>th</sup> Sentence</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create New</li>
            </ol>
          </nav>
          {{-- <h4 class="mg-b-0 tx-spacing--1">12<sup>th</sup> Sentence</h4> --}}
          <h4 class="mg-b-0 tx-spacing--1">{{trans('sentence.publication_of_maps_under_section_12')}}</h4>
        </div>
        <div class="d-none d-md-block">
            @include('buttons._back')
        </div>
      </div>
      @if(Request::segment(2)!='view')
      <form id="main" method="post" @if(Request::segment(2)=='create' ) action="{{ route($url.'-store') }}" @else
      action="{{ route($url.'-update',$form12->id) }}" @endif >
      @csrf
      @endif

    <input type="hidden" class="form-control" name="form12_id" id="form12_id" value="@if(isset($form12_id)) {{$form12_id}} @else {{null}} @endif" placeholder="">
    @if(session()->has('error'))
    @include('alerts.errors')
    @endif
    <div data-label="Example" class="df-example" id="tabs">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">12<sup>th Form Details</a>
                    </li>
                    @if(Request::segment(2)!='create' )
                    <li class="nav-item">
                        <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">Form History</a>
                    </li>
                    @endif
                  </ul>
                  <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                            <label for="file_no">{{trans('sentence.form_12th_ref_no')}}</label>
                            <input type="text" required @if(Request::segment(2)=='view' ) readonly @endif readonly name="file_no" class="form-control" id="file_no" @if(Request::segment(2)=='create' ) value="@if(isset($file_no)){{$file_no}} @endif" @else value="{{$form12->file_no}}" @endif placeholder="">
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
                                      @if(Request::segment(2)!='create' )@if($form12->ag_division==$item->id) selected @endif @endif
                                      >{{$item->ag_name}}</option>
                                  @endforeach
                                @else
                                  <option value="">Please Select</option>
                                  @foreach ($agDivision as $item)
                                  <option value="{{$item->id}}" @if(isset($element))
                                      @if(old('ag_div_id',$element->id)==$item->id)
                                      selected="selected"
                                      @endif @endif
                                      @if(Request::segment(2)!='create' )@if($form12->ag_division==$item->id) selected @endif @endif
                                      >{{$item->sinhala_name}}</option>
                                  @endforeach
                                  @endif
                              </select>
                            @endif
                            </div>
                            <div class="form-group col-md-3">
                            <label for="map_no">{{trans('sentence.map_number')}}</label>
                                <input type="text" pattern="^\d{0,6}?$" minlength="6" maxlength="6" autocomplete="off" required @if(Request::segment(2)=='view' ) readonly @endif  class="form-control" id="map_no" maxlength="6" name="map_no" @if(Request::segment(2)!='create' ) value="{{$form12->map_no}}" @endif>
                            </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="zonal_no">{{trans('sentence.block_number')}}</label>
                                <input type="text" pattern="^([0-9][0-9]?|)$" minlength="1" maxlength="2" autocomplete="off" @if(Request::segment(2)=='view' ) readonly @endif required class="form-control" id="block_no" name="block_no" @if(Request::segment(2)!='create' ) value="{{$form12->block_no}}" @endif>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="gn_div_id">{{trans('sentence.gn_Division')}}</label>
                                <select @if(isset($form12)) @if($form12->current_stage=='Regional commissioner' || $form12->current_stage=='Regional data entry') id="gn_div_id"  @else  disabled  @endif @else id="gn_div_id"  @endif  name="gn_div_id[]" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="gn_div_id[]" multiple required>
                                    @if(trans('sentence.lang')=='EN')
                                    <option value="">Please Select</option>
                                    @foreach ($gnDivision as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('gn_div_id',$element->id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->gn_division) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->gn_name}}</option>
                                    @endforeach
                                    @else
                                    <option value="">Please Select</option>
                                    @foreach ($gnDivision as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('gn_div_id',$element->id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->gn_division) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->sinhala_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                              </div>

                              <div class="form-group col-md-3">
                                <label for="village_id">{{trans('sentence.village')}}</label>
                                <select @if(isset($form12)) @if($form12->current_stage=='Regional commissioner' || $form12->current_stage=='Regional data entry') id="village_id"  @else  disabled  @endif @else id="village_id"  @endif  name="village_id[]" @if(Request::segment(2)=='view') disabled @endif class="form-control form-control-sm select2" name="village_id[]" multiple required readonly>
                                  @if( Request::segment(2)!='create')
                                    @if(trans('sentence.lang')=='EN')
                                    <option value="">Please Select</option>
                                     @foreach ($villages as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('village_id',$element->id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->village) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->village}}</option>
                                    @endforeach 
                                    @else
                                    <option value="">Please Select</option>
                                    @foreach ($villages as $item)
                                    <option value="{{$item->id}}" @if(isset($element))
                                        @if(old('village_id',$element->id)==$item->id)
                                        selected="selected"
                                        @endif @endif
                                        @if(Request::segment(2)!='create' ) @foreach(explode(',',$form12->village) as $str)@if($str==$item->id) selected @endif @endforeach @endif
                                        >{{$item->sinhala_name}}</option>
                                    @endforeach
                                    @endif
                                    @endif
                                   
                                </select>
                              </div>
                          </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="gov_lands">{{trans('sentence.goverment_lands')}}</label>
                                <input type="Number" @if(Request::segment(2)=='view' ) readonly @endif required autocomplete="off" class="form-control" id="gov_lands" name="gov_lands" @if(Request::segment(2)!='create' ) value="{{$form12->government_lands}}" @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pri_lands">{{trans('sentence.private_lands')}}</label>
                                <input type="Number" @if(Request::segment(2)=='view' ) readonly @endif required autocomplete="off" class="form-control" id="pri_lands" name="pri_lands" @if(Request::segment(2)!='create' ) value="{{$form12->private_lands}}" @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.total_lands')}}</label>
                                <input type="Number" @if(Request::segment(2)=='view' ) readonly @endif required autocomplete="off" readonly class="form-control" id="tot_lands" name="tot_lands" @if(Request::segment(2)!='create' ) value="{{$form12->total_lands}}" @endif>
                            </div>
                            @if($UtilityService->getAccessGazette(Request::segment(1)))
                            @if($form12->current_stage=='Gov Press without G')
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_12th_gazzette_date')}}</label>
                                <input type="text" @if(Request::segment(2)=='view' ) readonly @endif required autocomplete="off" class="form-control datepicker1" id="" name="gazzette_date" @if(Request::segment(2)!='create' ) value="{{ date('Y-m-d',strtotime($form12->gazette_date))}}" @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_12th_gazzette_no')}}</label>
                                <input type="text" @if(Request::segment(2)=='view' ) readonly @endif required autocomplete="off" class="form-control" id="tot_lands" name="gazzette_no" @if(Request::segment(2)!='create' ) value="{{$form12->gazette_no}}" @endif>
                            </div>
                            @endif
                            @endif
                             {{-- @if($UtilityService->getAccessPubVerify(Request::segment(1)))
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_12th_gazzette_ref')}}</label>
                                <input type="text" @if(Request::segment(2)=='view' ) readonly @endif required class="form-control" id="ref_no" name="ref_no" @if(Request::segment(2)!='create' ) value="{{$form12->ref_no}}" @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tot_lands">{{trans('sentence.form_12th_ref_no')}}</label>
                                <input type="text" @if(Request::segment(2)=='view' ) readonly @endif required class="form-control" id="ref_no2" name="ref_no2" @if(Request::segment(2)!='create' ) value="{{$form12->ref_no2}}" @endif>
                            </div>
                            @endif --}}
                            <div class="form-group col-md-3">
                            <label for="comment">Comment</label>
                                <textarea  rows="2" cols="50" class="form-control" id="comment"  name="comment" @if(Request::segment(2)!='create' ) value="{{$form12->comment}}" @endif>
                                </textarea>
                            </div>
                          </div>
                          

                          @include('modals.remarks')
                          @include('modals.12recheckmodel')
                          @include('modals.computerbranch')
                          @include('buttons._action')

                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        @include('pages.form12.aprovalinfo')
                    </div>
                  </div>
                </div>
        </form>
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
  placeholder: 'Please Select',
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

$("#block_no").keyup(function () {
    var value=$("#block_no").val();
   if(value!="" || value.length!=0)
   {
        if(value.charAt(0)=="0")
        {
            $("#block_no").val('');
        }
   }
})

</script>
<script>
    $("#gov_lands").keyup(function () {
        if($('#gov_lands').val()!='')
        {
            $('#gov_lands').val($('#gov_lands').val() * 1);

        }else
        {
            $('#gov_lands').val('0');
        }
        if($('#pri_lands').val()!='')
        {
            $('#pri_lands').val($('#pri_lands').val() * 1);
        }
        else
        {
            $('#pri_lands').val('0');
        }
        if(($('#gov_lands').val()!='') && ($('#pri_lands').val()!=''))
        {
            $('#tot_lands').val(($('#gov_lands').val() * 1)+($('#pri_lands').val() * 1));
        }
    });
    $('#recheck').click(function(){
        $('#computer').prop('required',false);
    });
    </script>
    <script>
            $("#gov_lands").change(function () {
                if($('#gov_lands').val()!='')
                {
                    $('#gov_lands').val($('#gov_lands').val() * 1);

                }else
                {
                    $('#gov_lands').val('0');
                }
                if($('#pri_lands').val()!='')
                {
                    $('#pri_lands').val($('#pri_lands').val() * 1);
                }
                else
                {
                    $('#pri_lands').val('0');
                }
                if(($('#gov_lands').val()!='') && ($('#pri_lands').val()!=''))
                {
                    $('#tot_lands').val(($('#gov_lands').val() * 1)+($('#pri_lands').val() * 1));
                }
            });
            </script>
    <script>
            $("#pri_lands").keyup(function () {
                if($('#gov_lands').val()!='')
                {
                    $('#gov_lands').val($('#gov_lands').val() * 1);

                }else
                {
                    $('#gov_lands').val('0');
                }
                if($('#pri_lands').val()!='')
                {
                    $('#pri_lands').val($('#pri_lands').val() * 1);
                }
                else
                {
                    $('#pri_lands').val('0');
                }
                if(($('#gov_lands').val()!='') && ($('#pri_lands').val()!=''))
                {
                    $('#tot_lands').val(($('#gov_lands').val() * 1)+($('#pri_lands').val() * 1));
                }
            });
    </script>
        <script>
                $("#pri_lands").change(function () {
                    if($('#gov_lands').val()!='')
                    {
                        $('#gov_lands').val($('#gov_lands').val() * 1);

                    }else
                    {
                        $('#gov_lands').val('0');
                    }
                    if($('#pri_lands').val()!='')
                    {
                        $('#pri_lands').val($('#pri_lands').val() * 1);
                    }
                    else
                    {
                        $('#pri_lands').val('0');
                    }
                    if(($('#gov_lands').val()!='') && ($('#pri_lands').val()!=''))
                    {
                        $('#tot_lands').val(($('#gov_lands').val() * 1)+($('#pri_lands').val() * 1));
                    }
                });
        </script>
<script>
//     $("#map_no").keyup(function () {
//     if($("#map_no").val()!='' && $("#map_no").val().length>=6){
//         var urlseg1='{{url("/check-form12-map-number/")}}';
//         $.ajax({url:urlseg1+'/'+$("#map_no").val(),type:'get', success: function(response) {
//             if(response){
//                 if(response.message==1){
//                     alert('This Map Number Already Inserted');
//                     $("#map_no").val('');
//                 }
//             }
//         }});
//     }
// });
$("#block_no").focusout(function () {
    if($("#block_no").val()!='' && $("#block_no").val().length<=2 && $("#map_no").val()!=''){
        var urlseg1='{{url("/check-form12-block-number/")}}';
        $.ajax({url:urlseg1+'/'+$("#map_no").val()+'/'+$("#block_no").val(),type:'get', success: function(response) {
            if(response){
                if(response.message==1){
                    alert('This Map Number And Block Number Already Inserted');
                    $("#block_no").val('');
                    $("#block_no").focus();
                }
            }
        }});
    }
});
</script>
<script>
    $('#reject').click(function(evt){
        $('#ref_no').prop('required',false);
        $('#ref_no2').prop('required',false);
        $('#computer').prop('required',false);
    });
</script>

<script>
$("select[name='gn_div_id[]']").change(function(){
    $("select[name='village_id[]']").removeAttr("readonly");
});
</script> 
@endsection
