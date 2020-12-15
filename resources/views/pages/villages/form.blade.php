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
                                    <label for="gn_name">Village</label>
                                    <input @if(Request::segment(2)=='view' ) readonly @endif
                                        type="text"
                                        class="form-control" name="village" @if(isset($element))
                                        value="{{ old('village', $element->village) }}" @else value="{{ old('village') }}" @endif
                                        placeholder="Village">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sinhala_name">Sinhala Name</label>
                                    <input @if(Request::segment(2)=='view' ) readonly @endif
                                        type="text"
                                        class="form-control" name="sinhala_name" @if(isset($element))
                                        value="{{ old('sinhala_name', $element->sinhala_name) }}" @else
                                        value="{{ old('sinhala_name') }}" @endif placeholder="Village">
                                </div>
                                <div class="form-group col-md-6">
                                        <label for="gn_division">Gn Division</label>
                                        <select id="gn_division" @if(Request::segment(2)=='view') disabled @endif class="form-control select2"
                                            name="gn_division">
                                           @foreach ($gn_divisions as $item)
                                            <option value="{{$item->id}}" @if(isset($element))
                                                @if(old('gn_division',$element->gn_division)==$item->id)
                                                selected="selected"
                                                @endif @endif
                                                >{{$item->gn_name}}</option>
                                            @endforeach
                                        </select>

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
