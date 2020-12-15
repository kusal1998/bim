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


          <div data-label="Example" class="df-example">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ ucwords(trans(str_replace('-', ' ', $title))) }} Details</a>
                  </li>
                </ul>
                <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    
                    <form id="main" method="post" @if(Request::segment(2)=='create' )
                        action="{{ route($url.'-store') }}" @else action="{{ route($url.'-update',$element->id) }}"
                        @endif novalidate="" _lpchecked="1">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                            
                            @include('alerts.errors')
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">Regional Office Name</label>
                                    <input @if(Request::segment(2)=='view' ) readonly @endif
                                        type="text"
                                        class="form-control" name="name" @if(isset($element))
                                        value="{{ old('name', $element->name) }}" @else value="{{ old('name') }}" @endif
                                        placeholder="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code">Regional Office  Code</label>
                                    <input @if(Request::segment(2)=='view' ) readonly @endif
                                        type="text"
                                        class="form-control" name="code" @if(isset($element))
                                        value="{{ old('code', $element->code) }}" @else
                                        value="{{ old('code') }}" @endif placeholder="">
                                </div>
                                <div class="form-group col-md-6">
                                        <label for="contact_person">Contact Person Name</label>
                                        <input @if(Request::segment(2)=='view' ) readonly @endif
                                            type="text"
                                            class="form-control" name="contact_person" @if(isset($element))
                                            value="{{ old('contact_person', $element->contact_person) }}" @else value="{{ old('contact_person') }}" @endif
                                            placeholder="">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contact_no">Contact Number</label>
                                        <input @if(Request::segment(2)=='view' ) readonly @endif
                                            type="text"
                                            class="form-control" name="contact_no" @if(isset($element))
                                            value="{{ old('contact_no', $element->contact_no) }}" @else
                                            value="{{ old('contact_no') }}" @endif placeholder="">
                                    </div>
                                    <div class="form-group col-md-12">
                                            <label for="address">Address</label>
                                            <input @if(Request::segment(2)=='view' ) readonly @endif
                                                type="text"
                                                class="form-control" name="address" @if(isset($element))
                                                value="{{ old('address', $element->address) }}" @else
                                                value="{{ old('address') }}" @endif placeholder="">
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
        $('.select2').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search options'
        });
        </script>
@endsection
