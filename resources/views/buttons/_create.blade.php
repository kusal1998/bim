@inject('UtilityService', 'App\Services\UtilityService')

@if($UtilityService->getAccessCreate(Request::segment(1)=="Yes"))

{{-- <button class="btn btn-sm pd-x-15 btn-white btn-uppercase"><i data-feather="mail" class="wd-10 mg-r-5"></i> Email</button>
<button class="btn btn-sm pd-x-15 btn-white btn-uppercase mg-l-5"><i data-feather="printer" class="wd-10 mg-r-5"></i> Print</button> --}}
<a href="/{{Request::segment(1)}}/create" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5"><i data-feather="file" class="wd-10 mg-r-5"></i>Create New</a>


@endif