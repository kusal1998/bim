@extends('layouts.errors')
@section('optional_css')

@endsection
@section('content')

    <section class="section">
      <div class="container mt-5">
        <div class="page-error">
          <div class="page-inner">
           
                    <div class="col-12 col-md-12 col-sm-12">
                            <div class="card">
                              <div class="card-header">
                                <h4>Oops!</h4>
                              </div>
                              <div class="card-body">
                                <div class="empty-state" data-height="400">
                                  <div class="empty-state-icon bg-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                  </div>
                                  <h1>403</h1>
                                  <h2>Forbidden Access</h2>
                                  <p class="lead">
                                   Please contact your system administrator. you may not have permission to perform this operation.
                                  </p> 
                                  <a href="/" class="btn btn-primary mt-4">Back to Home</a>
                                  {{-- <a href="#" class="mt-4 bb">Need Help?</a> --}}
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
    @endsection
    