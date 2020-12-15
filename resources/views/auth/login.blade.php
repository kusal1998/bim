@extends('layouts.app')

@section('content')
<div class="content content-fixed content-auth">
  <div class="container">
    <div class="media align-items-stretch justify-content-center ht-100p pos-relative">
      <div class="media-body align-items-center d-none d-lg-flex">
        <div class="mx-wd-600">
          <img src="../../assets/img/img15.png" class="img-fluid" alt="">
        </div>

      </div><!-- media-body -->
      <div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
        <div class="wd-100p">
          <h3 class="tx-color-01 mg-b-5">Sign In</h3>
          <p class="tx-color-03 tx-16 mg-b-40">Welcome back! Please signin to continue.</p>
          <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
            @csrf
          <div class="form-group">
            <label>Email address</label>
            <input id="email" type="email" tabindex="1" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
             @enderror
          </div>
          <div class="form-group">
            <div class="d-flex justify-content-between mg-b-5">
              <label class="mg-b-0-f">Password</label>
            {{--   <a href="#" class="tx-13">Forgot password?</a> --}}
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" tabindex="2" required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <button class="btn btn-brand-02 btn-block">Sign In</button>
          </form>

        </div>
      </div><!-- sign-wrapper -->
    </div><!-- media -->
  </div><!-- container -->
</div><!-- content -->

@endsection
