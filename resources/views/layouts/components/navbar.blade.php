@inject('UtilityService', 'App\Services\UtilityService')
@php
$AccessPermissions = $UtilityService->getAccessByRole();
$MasterCount = $UtilityService->getPermissionsCount(1);
$MainCount = $UtilityService->getPermissionsCount(2);
$f12Count = $UtilityService->getPermissionsCount(3);
$f14Count = $UtilityService->getPermissionsCount(5);
$f55Count = $UtilityService->getPermissionsCount(6);
$amndCount = $UtilityService->getPermissionsCount(7);
$ReportCount = $UtilityService->getPermissionsCount(4);
@endphp
<ul class="nav navbar-menu">
    <li class="nav-item active">
    <a href="/" class="nav-link"><i data-feather="pie-chart"></i> Dashboard</a>
    </li>
      @if($MasterCount!=0)
      <li class="nav-item with-sub active">
        <a href="#" class="nav-link"><i data-feather="pie-chart"></i> Master Files</a>
        <ul class="navbar-menu-sub">
            @foreach ($AccessPermissions as $item)
                @php
                $Modules = $UtilityService->getAccessModule($item->module_code,1);
                @endphp
                @if(isset($Modules))
                <li class="nav-sub-item"><a href="{{$Modules->url}}" class="nav-sub-link"><i data-feather="bar-chart-2"></i>{{$Modules->md_name}}</a></li>
                @endif
           @endforeach
        </ul>
      </li>
      @endif

      @if($f12Count!=0)
      <li class="nav-item with-sub active">
        <a href="#" class="nav-link"><i data-feather="package"></i> 12<sup>th </sup> Form</a>
        <ul class="navbar-menu-sub">
                @foreach ($AccessPermissions as $item)
                @php
                $Modules = $UtilityService->getAccessModule($item->module_code,3);
                @endphp
                @if(isset($Modules))
                @if($Modules->module_type=='menu' || $Modules->module_type=='both')
                <li class="nav-sub-item"><a href="{{$Modules->url}}" class="nav-sub-link"><i data-feather="{{$Modules->icon}}"></i>{{$Modules->md_name}}</a></li>
                @endif
                @endif
           @endforeach
          {{-- <li class="nav-sub-item"><a href="/12th-sentence/create" class="nav-sub-link"><i data-feather="plus-circle"></i>Create New</a></li>
          <li class="nav-sub-item"><a href="/12th-sentence/new-requests" class="nav-sub-link"><i data-feather="mail"></i>New Requests</a></li>
          <li class="nav-sub-item"><a href="/12th-sentence/approved-requests" class="nav-sub-link"><i data-feather="check-circle"></i>Current Files</a></li> --}}
          {{-- <li class="nav-sub-item"><a href="/12th-sentence/pending-requests" class="nav-sub-link"><i data-feather="help-circle"></i>Pending Approvals</a></li>
          <li class="nav-sub-item"><a href="/12th-sentence/rejected-requests" class="nav-sub-link"><i data-feather="x-circle"></i>Rejected Files</a></li>
          <li class="nav-sub-item"><a href="/12th-sentence/gazetted-requests" class="nav-sub-link"><i data-feather="book"></i>Gazetted Files</a></li> --}}
        </ul>
      </li>
      @endif
      @if($f14Count!=0)
      <li class="nav-item with-sub active">
        <a href="#" class="nav-link"><i data-feather="package"></i> 14<sup>th </sup> Form</a>
        <ul class="navbar-menu-sub">
                @foreach ($AccessPermissions as $item)
                @php
                $Modules = $UtilityService->getAccessModule($item->module_code,5);
                @endphp
                @if(isset($Modules))
                @if($Modules->module_type=='menu' || $Modules->module_type=='both')
                <li class="nav-sub-item"><a href="{{$Modules->url}}" class="nav-sub-link"><i data-feather="{{$Modules->icon}}"></i>{{$Modules->md_name}}</a></li>
                @endif
                @endif
           @endforeach
        </ul>
      </li>
      @endif
      @if($f55Count!=0)
      <li class="nav-item with-sub active">
        <a href="#" class="nav-link"><i data-feather="package"></i> 55<sup>th </sup> Form</a>
        <ul class="navbar-menu-sub">
            @foreach ($AccessPermissions as $item)
            @php
            $Modules = $UtilityService->getAccessModule($item->module_code,6);
            @endphp
            @if(isset($Modules))
            @if($Modules->module_type=='menu' || $Modules->module_type=='both')
            <li class="nav-sub-item"><a href="{{$Modules->url}}" class="nav-sub-link"><i data-feather="{{$Modules->icon}}"></i>{{$Modules->md_name}}</a></li>
            @endif
            @endif
       @endforeach
        </ul>
      </li>
      @endif
      @if($amndCount!=0)
      <li class="nav-item with-sub active">
        <a href="#" class="nav-link"><i data-feather="package"></i> Amendments Form</a>
        <ul class="navbar-menu-sub">
          @foreach ($AccessPermissions as $item)
            @php
            $Modules = $UtilityService->getAccessModule($item->module_code,7);
            @endphp
            @if(isset($Modules))
            @if($Modules->module_type=='menu' || $Modules->module_type=='both')
            <li class="nav-sub-item"><a href="{{$Modules->url}}" class="nav-sub-link"><i data-feather="{{$Modules->icon}}"></i>{{$Modules->md_name}}</a></li>
            @endif
            @endif
       @endforeach
        </ul>
      </li>
      @endif
      @if($ReportCount!=0)
      <li class="nav-item with-sub active">
        <a href="#" class="nav-link"><i data-feather="pie-chart"></i> Reports</a>
        <ul class="navbar-menu-sub">
          {{--   @foreach ($AccessPermissions as $item)
                @php
                $Modules = $UtilityService->getAccessModule($item->module_code,4);
                @endphp
                @if(isset($Modules))
                <li class="nav-sub-item"><a href="{{$Modules->url}}" class="nav-sub-link"><i data-feather="bar-chart-2"></i>{{$Modules->md_name}}</a></li>
                @endif
           @endforeach --}}

           {{-- <li class="nav-sub-item"><a href="/on-process/all" class="nav-sub-link"><i data-feather="help-circle"></i>On Process Report</a></li> --}}
           {{-- @if(Auth::User()->role_code=='RE1570278277' or Auth::User()->role_code=='AS1570278425' or Auth::User()->role_code=='BI1570278454' or Auth::User()->role_code=='DI1570279796' ) --}}
           {{-- <li class="nav-sub-item"><a href="/report1" class="nav-sub-link"><i data-feather="file"></i>Report 1</a></li> --}}
           <li class="nav-sub-item"><a href="/report2" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_02')}}</a></li>
           <li class="nav-sub-item"><a href="/report3" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_03')}}</a></li>

           <li class="nav-sub-item"><a href="/report4" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_04')}}</a></li>
           <li class="nav-sub-item"><a href="/report5" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_05')}}</a></li>
           <li class="nav-sub-item"><a href="/report6" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_06')}}</a></li>
           <li class="nav-sub-item"><a href="/report7" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_07')}}</a></li>

           <li class="nav-sub-item"><a href="/report8" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_08')}}</a></li>
           <li class="nav-sub-item"><a href="/report9" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_09')}}</a></li>
           <li class="nav-sub-item"><a href="/report10" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_10')}}</a></li>
           <li class="nav-sub-item"><a href="/report11" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_11')}}</a></li>
           <li class="nav-sub-item"><a href="/report12" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_12')}}</a></li>
           <li class="nav-sub-item"><a href="/report13" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_13')}}</a></li>
           <li class="nav-sub-item"><a href="/report14" class="nav-sub-link"><i data-feather="file"></i>{{trans('sentence.Report_14')}}</a></li>
           {{-- @endif --}}

          </ul>
      </li>
      @endif
    </ul>
