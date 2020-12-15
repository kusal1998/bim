@extends('layouts.wyse2')
@section('optional_css')
@include('css.datepicker')
@include('css.timepicker')
<style>
.table-header-rotated {
    border-collapse: collapse;
  }
  .csstransforms .table-header-rotated td {
    width: 30px;
  }
  .no-csstransforms .table-header-rotated th {
    padding: 5px 10px;
  }
  .table-header-rotated td {
    text-align: center;
    padding: 10px 5px;
    border: 1px solid #ccc;
  }
  .csstransforms .table-header-rotated th.rotate {
    height: 140px;
    white-space: nowrap;
  }
  .csstransforms .table-header-rotated th.rotate > div {
    -webkit-transform: translate(25px, 51px) rotate(270deg);
            transform: translate(25px, 51px) rotate(270deg);
    width: 30px;
  }
  .csstransforms .table-header-rotated th.rotate > div > span {
    border-bottom: 1px solid #ccc;
    padding: 5px 10px;
  }
  .table-header-rotated th.row-header {
    padding: 0 10px;
    border-bottom: 1px solid #ccc;
  }
</style>
@endsection
@inject('UtilityService', 'App\Services\UtilityService')
@section('content')

<div class="content content-fixed">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
      <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
                @php
                $title = Request::segment(1);
                $singular = str_singular(Request::segment(1));
                $url = Request::segment(1);
                @endphp
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item"><a href="#">Main Configurations</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ ucwords(trans(str_replace('-', ' ', $title))) }}</li>
              <li class="breadcrumb-item active" aria-current="page">Create New</li>
            </ol>
          </nav>
          <h4 class="mg-b-0 tx-spacing--1">
                @if(Request::segment(2)=='create')
                Create a new User Role
                @endif
                @if(Request::segment(2)=='view')
                View User Role details
                @endif
                @if(Request::segment(2)=='update')
                Update an exsisting User Role
                @endif
          </h4>
        </div>
        <div class="d-none d-md-block">
                @include('buttons._back')
      </div>
      </div>
      <form id="main" method="post" @if(Request::segment(2)=='create' )
                            action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$elementur->id) }}"
                            @endif novalidate="" _lpchecked="1">
                            @csrf
              <div data-label="Example" class="df-example" id="tabs">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">User Role Details</a>
                    </li>
                  </ul>
                  <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="file_no">User Role</label>
                              <input @if(Request::segment(2)=='view' ) readonly @endif type="text"
                              class="form-control" name="name" @if(isset($elementur))
                              value="{{ old('name', $elementur->name) }}" @else value="{{ old('name') }}"
                              @endif placeholder="User Role Name">
                            </div>
                          </div>
                        <div class="form-row">

                                <h6>Permissions</h6>
                                <div class="col-lg-12 row">
                                    <div class="">
                                <table class="table-header-rotated">
                                    <thead>
                                      <tr>
                                        <!-- First column header is not rotated -->
                                        <th></th>
                                        <!-- Following headers are rotated -->
                                        <th class="rotate"><div><span>Is Enable?</span></div></th>
                                          <th class="rotate"><div><span>Can Create?</span></div></th>
                                          <th class="rotate"><div><span>Can Read?</span></div></th>
                                          <th class="rotate"><div><span>Can Update?</span></div></th>
                                          <th class="rotate"><div><span>Can Delete?</span></div></th>
                                          <th class="rotate"><div><span>Can Approve?</span></div></th>
                                          <th class="rotate"><div><span>Can Reject?</span></div></th>
                                          <th class="rotate"><div><span>Proof Read?</span></div></th>
                                          <th class="rotate"><div><span>Can Forward to pub?</span></div></th>
                                          <th class="rotate">
                                                <div><span>Can Certify?</span></div>
                                            </th>

                                            <th class="rotate">
                                                <div><span>Can Gazzete?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Press?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Re-check?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Veify?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Asst:Comm?</span></div>
                                            </th>

                                            <th class="rotate">
                                                <div><span>Can Bims:Comm?</span></div>
                                            </th>

                                            <th class="rotate">
                                                <div><span>Can Comm:Gen?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Fw:to Proof?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Fw:to Translate?</span></div>
                                            </th>
                                            <th class="rotate">
                                                <div><span>Can Translate Proof?</span></div>
                                            </th>
                                             <th class="rotate">
                                                <div><span>Can Publication Verify?</span></div>
                                            </th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                            @foreach($elements as $key => $element)
                                            @if(Request::segment(2)=='update')
                                            @php
                                            $permissions =
                                            $UtilityService->getAccess($elementur->code,$element->md_code);
                                            @endphp
                                            @endif
                                            @if(Request::segment(2)=='view')
                                            @php
                                            $permissions =
                                            $UtilityService->getAccess($elementur->code,$element->md_code);
                                            @endphp
                                            @endif
                                      <tr>
                                        <th class="row-header">
                                                {{$element->md_name}}
                                                <input type="hidden" name="{{'element['.$element->id.'][0][]'}}"
                                                    value="{{$element->md_code}}">
                                                <input type="hidden" name="{{'element['.$element->id.'][6][]'}}"
                                                    value="{{$element->md_group}}">
                                        </th>
                                        <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input id="enable{{$element->id}}"
                                                        name="{{'element['.$element->id.'][1][]'}}" type="checkbox"
                                                        @if(isset($permissions)) @if($permissions->is_enable=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label>N</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input @if(isset($element)) @if($element->can_create=='off')
                                                    disabled @endif @endif id="create{{$element->id}}"
                                                    name="{{'element['.$element->id.'][2][]'}}" type="checkbox"
                                                    @if(isset($permissions)) @if($permissions->can_create=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label>N </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input @if(isset($element)) @if($element->can_read=='off')
                                                    disabled @endif @endif id="read{{$element->id}}"
                                                    name="{{'element['.$element->id.'][3][]'}}" type="checkbox"
                                                    @if(isset($permissions)) @if($permissions->can_read=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label> N</label>
                                                    </div>
                                            </td>
                                            <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input @if(isset($element)) @if($element->can_update=='off')
                                                    disabled @endif @endif id="update{{$element->id}}"
                                                    name="{{'element['.$element->id.'][4][]'}}" type="checkbox"
                                                    @if(isset($permissions)) @if($permissions->can_update=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label> N</label>
                                                    </div>
                                            </td>
                                            <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input @if(isset($element)) @if($element->can_delete=='off')
                                                    disabled @endif @endif id="delete{{$element->id}}"
                                                    name="{{'element['.$element->id.'][5][]'}}" type="checkbox"
                                                    @if(isset($permissions)) @if($permissions->can_delete=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label> N</label>
                                                    </div>
                                            </td>
                                            <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input @if(isset($element)) @if($element->can_approve=='off')
                                                    disabled @endif @endif id="approve{{$element->id}}"
                                                    name="{{'element['.$element->id.'][7][]'}}" type="checkbox"
                                                    @if(isset($permissions)) @if($permissions->can_approve=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label>N </label>
                                                    </div>
                                            </td>
                                            <td>
                                                <div class="pretty p-default p-curve p-toggle">
                                                    <input @if(isset($element)) @if($element->can_reject=='off')
                                                    disabled @endif @endif id="reject{{$element->id}}"
                                                    name="{{'element['.$element->id.'][8][]'}}" type="checkbox"
                                                    @if(isset($permissions)) @if($permissions->can_reject=='1')
                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                    @endif>
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label> N</label>
                                                    </div>
                                            </td>
                                            <td>
                                                    <div class="pretty p-default p-curve p-toggle">
                                                        <input @if(isset($element)) @if($element->can_proof_read=='off')
                                                        disabled @endif @endif id="proof_read{{$element->id}}"
                                                        name="{{'element['.$element->id.'][9][]'}}" type="checkbox"
                                                        @if(isset($permissions)) @if($permissions->can_proof_read=='1')
                                                        checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                        @endif>
                                                        <div class="state p-success p-on">
                                                            <label>Y</label>
                                                        </div>
                                                        <div class="state p-danger p-off">
                                                            <label>N </label>
                                                        </div>
                                                </td>
                                                <td>
                                                        <div class="pretty p-default p-curve p-toggle">
                                                            <input @if(isset($element)) @if($element->can_close=='off')
                                                            disabled @endif @endif id="close{{$element->id}}"
                                                            name="{{'element['.$element->id.'][10][]'}}" type="checkbox"
                                                            @if(isset($permissions)) @if($permissions->can_close=='1')
                                                            checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                            @endif>
                                                            <div class="state p-success p-on">
                                                                <label>Y</label>
                                                            </div>
                                                            <div class="state p-danger p-off">
                                                                <label>N</label>
                                                            </div>
                                                    </td>


                                                    <td>
                                                            <div class="pretty p-default p-curve p-toggle">
                                                                <input @if(isset($element)) @if($element->can_certificate=='off')
                                                                disabled @endif @endif id="can_certificate{{$element->id}}"
                                                                name="{{'element['.$element->id.'][11][]'}}" type="checkbox"
                                                                @if(isset($permissions)) @if($permissions->can_certificate=='1')
                                                                checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                @endif>
                                                                <div class="state p-success p-on">
                                                                    <label>Y</label>
                                                                </div>
                                                                <div class="state p-danger p-off">
                                                                    <label>N</label>
                                                                </div>
                                                        </td>

                                                        <td>
                                                                <div class="pretty p-default p-curve p-toggle">
                                                                    <input @if(isset($element)) @if($element->can_gazzete=='off')
                                                                    disabled @endif @endif id="can_gazzete{{$element->id}}"
                                                                    name="{{'element['.$element->id.'][12][]'}}" type="checkbox"
                                                                    @if(isset($permissions)) @if($permissions->can_gazzete=='1')
                                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                    @endif>
                                                                    <div class="state p-success p-on">
                                                                        <label>Y</label>
                                                                    </div>
                                                                    <div class="state p-danger p-off">
                                                                        <label>N</label>
                                                                    </div>
                                                            </td>
                                                            <td>
                                                                    <div class="pretty p-default p-curve p-toggle">
                                                                        <input @if(isset($element)) @if($element->can_press=='off')
                                                                        disabled @endif @endif id="can_press{{$element->id}}"
                                                                        name="{{'element['.$element->id.'][13][]'}}" type="checkbox"
                                                                        @if(isset($permissions)) @if($permissions->can_press=='1')
                                                                        checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                        @endif>
                                                                        <div class="state p-success p-on">
                                                                            <label>Y</label>
                                                                        </div>
                                                                        <div class="state p-danger p-off">
                                                                            <label>N</label>
                                                                        </div>
                                                                </td>
                                                                <td>
                                                                        <div class="pretty p-default p-curve p-toggle">
                                                                            <input @if(isset($element)) @if($element->can_recheck=='off')
                                                                            disabled @endif @endif id="can_recheck{{$element->id}}"
                                                                            name="{{'element['.$element->id.'][14][]'}}" type="checkbox"
                                                                            @if(isset($permissions)) @if($permissions->can_recheck=='1')
                                                                            checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                            @endif>
                                                                            <div class="state p-success p-on">
                                                                                <label>Y</label>
                                                                            </div>
                                                                            <div class="state p-danger p-off">
                                                                                <label>N</label>
                                                                            </div>
                                                                    </td>
                                                                    <td>
                                                                            <div class="pretty p-default p-curve p-toggle">
                                                                                <input @if(isset($element)) @if($element->can_verify=='off')
                                                                                disabled @endif @endif id="can_verify{{$element->id}}"
                                                                                name="{{'element['.$element->id.'][15][]'}}" type="checkbox"
                                                                                @if(isset($permissions)) @if($permissions->can_verify=='1')
                                                                                checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                @endif>
                                                                                <div class="state p-success p-on">
                                                                                    <label>Y</label>
                                                                                </div>
                                                                                <div class="state p-danger p-off">
                                                                                    <label>N</label>
                                                                                </div>
                                                                        </td>
                                                                        <td>
                                                                                <div class="pretty p-default p-curve p-toggle">
                                                                                    <input @if(isset($element)) @if($element->can_asst_comm=='off')
                                                                                    disabled @endif @endif id="can_asst_comm{{$element->id}}"
                                                                                    name="{{'element['.$element->id.'][16][]'}}" type="checkbox"
                                                                                    @if(isset($permissions)) @if($permissions->can_asst_comm=='1')
                                                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                    @endif>
                                                                                    <div class="state p-success p-on">
                                                                                        <label>Y</label>
                                                                                    </div>
                                                                                    <div class="state p-danger p-off">
                                                                                        <label>N</label>
                                                                                    </div>
                                                                            </td>
                                                                            <td>
                                                                                    <div class="pretty p-default p-curve p-toggle">
                                                                                        <input @if(isset($element)) @if($element->can_bimsaviya_comm=='off')
                                                                                        disabled @endif @endif id="can_bimsaviya_comm{{$element->id}}"
                                                                                        name="{{'element['.$element->id.'][17][]'}}" type="checkbox"
                                                                                        @if(isset($permissions)) @if($permissions->can_bimsaviya_comm=='1')
                                                                                        checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                        @endif>
                                                                                        <div class="state p-success p-on">
                                                                                            <label>Y</label>
                                                                                        </div>
                                                                                        <div class="state p-danger p-off">
                                                                                            <label>N</label>
                                                                                        </div>
                                                                                </td>
                                                                                <td>
                                                                                        <div class="pretty p-default p-curve p-toggle">
                                                                                            <input @if(isset($element)) @if($element->can_comm_general=='off')
                                                                                            disabled @endif @endif id="can_comm_general{{$element->id}}"
                                                                                            name="{{'element['.$element->id.'][18][]'}}" type="checkbox"
                                                                                            @if(isset($permissions)) @if($permissions->can_comm_general=='1')
                                                                                            checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                            @endif>
                                                                                            <div class="state p-success p-on">
                                                                                                <label>Y</label>
                                                                                            </div>
                                                                                            <div class="state p-danger p-off">
                                                                                                <label>N</label>
                                                                                            </div>
                                                                                    </td>
                                                                                    <td>
                                                                                            <div class="pretty p-default p-curve p-toggle">
                                                                                                <input @if(isset($element)) @if($element->can_forward_to_proof=='off')
                                                                                                disabled @endif @endif id="can_forward_to_proof{{$element->id}}"
                                                                                                name="{{'element['.$element->id.'][19][]'}}" type="checkbox"
                                                                                                @if(isset($permissions)) @if($permissions->can_forward_to_proof=='1')
                                                                                                checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                                @endif>
                                                                                                <div class="state p-success p-on">
                                                                                                    <label>Y</label>
                                                                                                </div>
                                                                                                <div class="state p-danger p-off">
                                                                                                    <label>N</label>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td>
                                                                                                <div class="pretty p-default p-curve p-toggle">
                                                                                                    <input @if(isset($element)) @if($element->can_forward_to_translate=='off')
                                                                                                    disabled @endif @endif id="can_forward_to_translate{{$element->id}}"
                                                                                                    name="{{'element['.$element->id.'][20][]'}}" type="checkbox"
                                                                                                    @if(isset($permissions)) @if($permissions->can_forward_to_translate=='1')
                                                                                                    checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                                    @endif>
                                                                                                    <div class="state p-success p-on">
                                                                                                        <label>Y</label>
                                                                                                    </div>
                                                                                                    <div class="state p-danger p-off">
                                                                                                        <label>N</label>
                                                                                                    </div>
                                                                                            </td>
                                                                                            <td>
                                                                                                    <div class="pretty p-default p-curve p-toggle">
                                                                                                        <input @if(isset($element)) @if($element->can_translate_proof=='off')
                                                                                                        disabled @endif @endif id="can_translate_proof{{$element->id}}"
                                                                                                        name="{{'element['.$element->id.'][21][]'}}" type="checkbox"
                                                                                                        @if(isset($permissions)) @if($permissions->can_translate_proof=='1')
                                                                                                        checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                                        @endif>
                                                                                                        <div class="state p-success p-on">
                                                                                                            <label>Y</label>
                                                                                                        </div>
                                                                                                        <div class="state p-danger p-off">
                                                                                                            <label>N</label>
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                        <div class="pretty p-default p-curve p-toggle">
                                                                                                            <input @if(isset($element)) @if($element->can_publication_verify=='off')
                                                                                                            disabled @endif @endif id="can_publication_verify{{$element->id}}"
                                                                                                            name="{{'element['.$element->id.'][22][]'}}" type="checkbox"
                                                                                                            @if(isset($permissions)) @if($permissions->can_publication_verify=='1')
                                                                                                            checked @endif @endif @if(Request::segment(2)=='view') disabled
                                                                                                            @endif>
                                                                                                            <div class="state p-success p-on">
                                                                                                                <label>Y</label>
                                                                                                            </div>
                                                                                                            <div class="state p-danger p-off">
                                                                                                                <label>N</label>
                                                                                                            </div>
                                                                                                    </td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                    </div>
                                </div>



                        </div>
                        <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="md_name">Is Active</label>
                                    <div class="radio radiofill radio-success radio-inline">
                                        <div class="pretty p-icon p-round p-plain p-smooth">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif type="radio"
                                                name="is_active" @if(isset($elementur))
                                                @if($elementur->is_active=='1')
                                            checked="checked"
                                            @endif @endif
                                            value="1" @if(Request::segment(2)=='create' ) checked="checked" @endif >
                                            <div class="state p-success-o">
                                                <i class="icon material-icons">check_circle</i>
                                                <label>Yes</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-icon p-round p-plain p-smooth">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif type="radio"
                                                name="is_active" @if(isset($elementur))
                                                @if($elementur->is_active=='0')
                                            checked="checked"
                                            @endif @endif
                                            value="0">
                                            <div class="state p-danger-o">
                                                <i class="icon material-icons">highlight_off</i>
                                                <label>No</label>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>


                            @if(Request::segment(2)!='view' )
                            <button
                                class="btn btn-primary">{{ ucwords(trans(str_replace('-', ' ', Request::segment(2)))) }}</button>
                            @endif
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
<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/modernizr-2.7.1.js'></script>
<script>
$('.select2').select2({
  placeholder: 'Choose one',
  searchInputPlaceholder: 'Search options'
});

$('.datepicker1').datepicker();
$('.datepicker2').datepicker();
$('.datepicker3').datepicker();

</script>

@endsection

