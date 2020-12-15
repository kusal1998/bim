@extends('layouts.wyse2')
@section('optional_css')

@endsection
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
                  <li class="breadcrumb-item"><a href="#">Users</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
              </nav>
              <h4 class="mg-b-0 tx-spacing--1">
                    @if(Request::segment(2)=='create')
                    Create a new User
                    @endif
                    @if(Request::segment(2)=='view')
                    View User details
                    @endif
                    @if(Request::segment(2)=='update')
                    Update an exsisting User
                    @endif
              </h4>
            </div>
            <div class="d-none d-md-block">
                    @include('buttons._back')
          </div>
          </div>


          <div data-label="Example" class="df-example">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">User Details</a>
                  </li>
                </ul>
                <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <form id="main" method="post" @if(Request::segment(2)=='create' )
                        action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$element->id) }}"
                        @endif  _lpchecked="1">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                            @include('alerts.errors')
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">First Name</label>
                                    <input @if(Request::segment(2)=='view' ) readonly @endif
                                        type="text"
                                        class="form-control" name="name" @if(isset($element))
                                        value="{{ old('name', $element->name) }}" @else value="{{ old('name') }}" @endif
                                        placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">Last Name</label>
                                    <input @if(Request::segment(2)=='view' ) readonly @endif
                                        type="text"
                                        class="form-control" name="last_name" @if(isset($element))
                                        value="{{ old('last_name', $element->last_name) }}" @else
                                        value="{{ old('last_name') }}" @endif placeholder="Last Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                        <label for="name">Email</label>
                                        <input @if(Request::segment(2)=='view' ) readonly @endif
                                            type="email"
                                            class="form-control" name="email" @if(isset($element))
                                            value="{{ old('email', $element->email) }}" @else
                                            value="{{ old('email') }}" @endif placeholder="Email" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                            <label for="name">Contact No</label>
                                            <input @if(Request::segment(2)=='view' ) readonly @endif
                                            type="tel" pattern="[0-9]{3}-[0-9]{7}"
                                                class="form-control" name="contact_no" @if(isset($element))
                                                value="{{ old('contact_no', $element->contact_no) }}" @else
                                                value="{{ old('contact_no') }}" @endif placeholder="xxx-xxxxxxx" required>
                                        </div>
                                <div class="form-group col-md-6">
                                    <label for="role_code">User Role</label>
                                    <select id="role_code" @if(Request::segment(2)=='view') disabled @endif class="form-control select2"
                                        name="role_code" required>
                                        <option value="">Please Select</option>
                                        @foreach ($UserRoles as $item)
                                        <option value="{{$item->code}}" @if(isset($element))
                                            @if(old('role_code',$element->role_code)==$item->code)
                                            selected="selected"
                                            @endif @endif
                                            >{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="role_code">AG Office</label>
                                  <select id="branch_id" @if(Request::segment(2)=='view') disabled @endif class="form-control select2"
                                      name="branch_id" required>
                                      <option value="">Please Select</option>
                                     
                                      @foreach ($AgDivisions as $item)
                                      <option value="{{$item->id}}" @if(isset($element))
                                          @if(old('branch_id',$element->branch_id)==$item->id)
                                          selected="selected"
                                          @endif @endif
                                          >{{$item->ag_name}}</option>
                                      @endforeach
                                  </select>
                              </div>
                                <div class="form-group col-md-6">
                                        <label for="name">Password</label>
                                        <input @if(Request::segment(2)=='view' ) readonly @endif
                                          type="password" class="form-control" name="password"  placeholder="Password" required>
                                    </div>
                            </div>
                            @include('buttons._is_active')
                        </div>
                        <div class="card-footer">
                            @if(Request::segment(2)!='view' )
                            <button
                                class="btn btn-primary">{{ ucwords(trans(str_replace('-', ' ', Request::segment(2)))) }}</button>
                            @endif
                        </div>
                    </form>
                  </div>

                </div>
              </div>


                </div>
            </div>


@endsection

@section('after_scripts')
<script>
    $('#role_code').change(function(){
            var urlseg1='{{url("/get-permissions/")}}';
            $.ajax({url:urlseg1+'/'+$("#role_code").val(),type:'get', success: function(response) {
                if(response){
                    if(response=='true'){
                        $('#branch_id').prop('required', true);
                    }else{
                        $('#branch_id').prop('required', false);
                    }
                }
            }});
        });
</script>
<script>
        $('.select2').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search options'
        });

        </script>
@endsection
