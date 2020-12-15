@extends('layouts.wyse2') @section('optional_css')
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
@endsection @section('content')

<div class="content content-fixed">
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                @php $title = Request::segment(1); $singular = str_singular(Request::segment(1)); $url = Request::segment(1); @endphp
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Master Files</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ ucwords(trans(str_replace('-', ' ', $title))) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create New</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">
                    @if(Request::segment(2)=='create')
                    Create a new {{ ucwords(trans(str_replace('-', ' ', $singular))) }}
                    @endif
                    @if(Request::segment(2)=='view')
                    View  {{ ucwords(trans(str_replace('-', ' ', $singular))) }} details
                    @endif
                    @if(Request::segment(2)=='update')
                    Update an exsisting  {{ ucwords(trans(str_replace('-', ' ', $singular))) }}
                    @endif
              </h4>
            </div>
            <div class="d-none d-md-block">
                @include('buttons._back')
            </div>
        </div>

        <form id="main" method="post" @if(Request::segment(2)=='create' ) action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$element->id) }}" @endif novalidate="" _lpchecked="1">
            @csrf
            <div class="card-body">
                @if ($errors->any()) @include('alerts.errors') @endif
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="md_name">Module Name</label>
                        <input @if(Request::segment(2)=='view' ) readonly @endif @if(Request::segment(2)=='update' ) readonly @endif type="text" class="form-control" name="md_name" @if(isset($element)) value="{{ old('md_name', $element->md_name) }}" @else value="{{ old('md_name') }}" @endif placeholder="Module Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="md_group">Module Group</label>
                        <select @if(Request::segment(2)=='view' ) disabled @endif class="form-control js-example-basic-single" name="md_group">

                            <option value="1" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='1') selected="selected" @endif @endif >Master Files</option>
                            <option value="2" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='2') selected="selected" @endif @endif >Main Configurations</option>
                            <option value="4" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='4') selected="selected" @endif @endif >Reports
                            </option>
                            <option value="3" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='3') selected="selected" @endif @endif >12th Sentence</option>
                            <option value="5" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='5') selected="selected" @endif @endif >14th Sentence</option>
                            <option value="6" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='6') selected="selected" @endif @endif >55th Sentence</option>
                            <option value="7" @if(isset($element)) @if(old( 'md_group',$element->md_group)=='7') selected="selected" @endif @endif >Amendements
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="url">Module URL</label>
                        <input @if(Request::segment(2)=='view' ) readonly @endif @if(Request::segment(2)=='update' ) readonly @endif type="text" class="form-control" name="url" @if(isset($element)) value="{{ old('url', $element->url) }}" @else value="{{ old('url') }}" @endif placeholder="URL">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="icon">Module Code</label>
                        <input @if(Request::segment(2)=='view' ) readonly @endif @if(Request::segment(2)=='update' ) readonly @endif type="text" class="form-control" name="md_code" @if(isset($element)) value="{{ old('md_code', $element->md_code) }}" @else value="{{ old('md_code') }}" @endif placeholder="Module Code">
                    </div>
                </div>
                @if(Request::segment(2)=='create') @php $next_order_menu=$order_menu+1; @endphp @endif
                <input @if(Request::segment(2)=='view' ) readonly @endif @if(Request::segment(2)=='update' ) readonly @endif type="hidden" class="form-control" name="order_menu" @if(isset($element)) value="{{ old('order_menu', $element->order_menu) }}" @else value="{{$next_order_menu}}" @endif>
                <span class="messages"></span>
                <div class="form-row">
                    <div>
                        <table class="table-header-rotated">
                            <thead>
                                <tr>
                                    <th class="rotate">
                                        <div><span>Can Create?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Can Read?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Can Update?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Can Delete?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Can Approve?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Can Reject?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Proof Read?</span></div>
                                    </th>
                                    <th class="rotate">
                                        <div><span>Can Close?</span></div>
                                    </th>
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

                                <tr>
                                    <td>
                                        <div class="pretty p-default p-curve p-toggle">
                                            <input id="create" name="can_create" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_create" name="can_create" type="checkbox" @if(isset($element)) @if($element->can_create=='on') checked @endif @endif>
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
                                            <input id="create" name="can_read" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="read" name="can_read" type="checkbox" @if(isset($element)) @if($element->can_read=='on') checked @endif @endif >
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
                                            <input id="create" name="can_update" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="update" name="can_update" type="checkbox" @if(isset($element)) @if($element->can_update=='on') checked @endif @endif >
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
                                            <input id="create" name="can_delete" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="delete" name="can_delete" type="checkbox" @if(isset($element)) @if($element->can_delete=='on') checked @endif @endif >
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
                                            <input id="approve" name="can_approve" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="approve" name="can_approve" type="checkbox" @if(isset($element)) @if($element->can_approve=='on') checked @endif @endif >
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
                                            <input id="reject" name="can_reject" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="reject" name="can_reject" type="checkbox" @if(isset($element)) @if($element->can_reject=='on') checked @endif @endif >
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
                                            <input id="can_proof_read" name="can_proof_read" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_proof_read" name="can_proof_read" type="checkbox" @if(isset($element)) @if($element->can_proof_read=='on') checked @endif @endif >
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
                                            <input id="can_close" name="can_close" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_close" name="can_close" type="checkbox" @if(isset($element)) @if($element->can_close=='on') checked @endif @endif >
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
                                            <input id="can_certificate" name="can_certificate" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_certificate" name="can_certificate" type="checkbox" @if(isset($element)) @if($element->can_certificate=='on') checked @endif @endif >
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
                                            <input id="can_gazzete" name="can_gazzete" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_gazzete" name="can_gazzete" type="checkbox" @if(isset($element)) @if($element->can_gazzete=='on') checked @endif @endif >
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
                                            <input id="can_press" name="can_press" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_press" name="can_press" type="checkbox" @if(isset($element)) @if($element->can_press=='on') checked @endif @endif >
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
                                            <input id="can_recheck" name="can_recheck" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_recheck" name="can_recheck" type="checkbox" @if(isset($element)) @if($element->can_recheck=='on') checked @endif @endif >
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
                                            <input id="can_verify" name="can_verify" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_verify" name="can_verify" type="checkbox" @if(isset($element)) @if($element->can_verify=='on') checked @endif @endif >
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
                                            <input id="can_asst_comm" name="can_asst_comm" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_asst_comm" name="can_asst_comm" type="checkbox" @if(isset($element)) @if($element->can_asst_comm=='on') checked @endif @endif >
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
                                            <input id="can_bimsaviya_comm" name="can_bimsaviya_comm" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_bimsaviya_comm" name="can_bimsaviya_comm" type="checkbox" @if(isset($element)) @if($element->can_bimsaviya_comm=='on') checked @endif @endif >
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
                                            <input id="can_comm_general" name="can_comm_general" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_comm_general" name="can_comm_general" type="checkbox" @if(isset($element)) @if($element->can_comm_general=='on') checked @endif @endif >
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
                                            <input id="can_forward_to_proof" name="can_forward_to_proof" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_forward_to_proof" name="can_forward_to_proof" type="checkbox" @if(isset($element)) @if($element->can_forward_to_proof=='on') checked @endif @endif >
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
                                            <input id="can_forward_to_translate" name="can_forward_to_translate" type="hidden" value="off">
                                            <input @if(Request::segment(2)=='view' ) disabled @endif id="can_forward_to_translate" name="can_forward_to_translate" type="checkbox" @if(isset($element)) @if($element->can_forward_to_translate=='on') checked @endif @endif >
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
                                                <input id="can_translate_proof" name="can_translate_proof" type="hidden" value="off">
                                                <input @if(Request::segment(2)=='view' ) disabled @endif id="can_translate_proof" name="can_translate_proof" type="checkbox" @if(isset($element)) @if($element->can_translate_proof=='on') checked @endif @endif >
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
                                                    <input id="can_publication_verify" name="can_publication_verify" type="hidden" value="off">
                                                    <input @if(Request::segment(2)=='view' ) disabled @endif id="can_publication_verify" name="can_publication_verify" type="checkbox" @if(isset($element)) @if($element->can_publication_verify=='on') checked @endif @endif >
                                                    <div class="state p-success p-on">
                                                        <label>Y</label>
                                                    </div>
                                                    <div class="state p-danger p-off">
                                                        <label>N</label>
                                                    </div>
                                                </div>
                                            </td>
    

                                        

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">

                <button class="btn btn-primary">{{ ucwords(trans(str_replace('-', ' ', Request::segment(2)))) }}</button>

            </div>
        </form>
    </div>
</div>
</div>
</div>
</section>

@endsection @section('after_scripts')
<script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/modernizr-2.7.1.js'></script>
@endsection