@extends('layouts.errors')
@section('optional_css')

@endsection
@section('content')


<div class="content content-fixed content-auth-alt">
        <div class="container ht-100p tx-center">
          <div class="ht-100p d-flex flex-column align-items-center justify-content-center">
            <div class="wd-70p wd-sm-250 wd-lg-300 mg-b-15"><img src="../../assets/img/img20.png" class="img-fluid" alt=""></div>
            <h1 class="tx-color-01 tx-24 tx-sm-32 tx-lg-36 mg-xl-b-5">500 Internal Server Error</h1>
            <h5 class="tx-16 tx-sm-18 tx-lg-20 tx-normal mg-b-20">Oopps. There was an error, please try again later.</h5>
            <p class="tx-color-03 mg-b-30">The server encountered an internal server error and was unable to complete your request.</p>
            <p class="lead">
                    We tried it, but failed when requesting data to the server, sorry. <br> (Code: Error
                    500 ) <br> (Error Message : <a href="#" class="bb"> {{  $exception->getMessage() }}
                    </a>)
                </p>
                <a href="{{url()->previous()}}" class="btn btn-warning mt-4">Back to Home</a>
  
          </div>
        </div><!-- container -->
      </div><!-- content -->


@endsection

@section('after_scripts')
@endsection
