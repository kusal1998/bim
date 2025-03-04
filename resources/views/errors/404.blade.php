@extends('layouts.errors')
@section('optional_css')

@endsection
@section('content')

<div class="content content-fixed content-auth-alt">
    <div class="container ht-100p tx-center">
      <div class="ht-100p d-flex flex-column align-items-center justify-content-center">
        <div class="wd-70p wd-sm-250 wd-lg-300 mg-b-15"><img src="../../assets/img/img19.png" class="img-fluid" alt=""></div>
        <h1 class="tx-color-01 tx-24 tx-sm-32 tx-lg-36 mg-xl-b-5">404 Page Not Found</h1>
        <h5 class="tx-16 tx-sm-18 tx-lg-20 tx-normal mg-b-20">Oopps. The page you were looking for doesn't exist.</h5>
        <p class="tx-color-03 mg-b-30">You may have mistyped the address or the page may have moved. Try searching below.</p>
        <div class="d-flex mg-b-40">
          {{-- <input type="text" class="form-control wd-200 wd-sm-250" placeholder="Search"> --}}
          <a href="{{url()->previous()}}"
              class="btn btn-brand-02 bd-0 mg-l-5 pd-sm-x-25">Back
              </a>
        </div>

      </div>
    </div><!-- container -->
  </div><!-- content -->

   
@endsection

@section('after_scripts')
@endsection
    