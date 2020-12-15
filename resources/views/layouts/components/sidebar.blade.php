@inject('UtilityService', 'App\Services\UtilityService')
<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="/"> {{-- <img alt="image" src="assets/img/logo.png" class="header-logo" />  --}}<span
                class="logo-name">WYSE-BILLING</span>
            </a>
          </div>
          <div class="sidebar-user">
           {{--  <div class="sidebar-user-picture">
              <img alt="image" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}+{{ Auth::user()->last_name }}&background=0D8ABC&color=fff"">
            </div> --}}
            <div class="sidebar-user-details">
              <div class="user-name">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
              <div class="user-role">Administrator</div>
            </div>
          </div>
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
          <ul class="sidebar-menu">
          {{--   <li class="menu-header">Main</li> --}}
            <li class="dropdown">
              <a href="/" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            @if($MasterCount!=0)
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="folder"></i><span>Master Files</span></a>
              <ul class="dropdown-menu">
                 
                @foreach ($AccessPermissions as $item)
                @php
                $Modules = $UtilityService->getAccessModule($item->module_code,1);
                @endphp
                @if(isset($Modules))
                <li><a class="nav-link" href="{{$Modules->url}}">{{$Modules->md_name}}</a></li>
                @endif
                @endforeach
              </ul>
            </li>
            @endif
            @if($f12Count!=0)
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="file"></i><span>12th Sentence</span></a>
              <ul class="dropdown-menu">
               {{--    @foreach ($AccessPermissions as $item)
                @php
                $Modules = $UtilityService->getAccessModule($item->module_code,3);
                @endphp
                @if(isset($Modules))
                <li><a class="nav-link" href="{{$Modules->url}}">{{$Modules->md_name}}</a></li>
                @endif
                @endforeach --}}
                <li><a class="nav-link" href="/12th-sentence/create">Create New</a></li>
                <li><a class="nav-link" href="/12th-sentence/new-requests">New Requests</a></li>
                <li><a class="nav-link" href="/12th-sentence/approved-requests">Approved Requests</a></li>
                <li><a class="nav-link" href="/12th-sentence/pending-requests">Pending Approvals</a></li>
                <li><a class="nav-link" href="/12th-sentence/rejected-requests">Rejected Requests</a></li>
                <li><a class="nav-link" href="/12th-sentence/gazetted-requests">Gazetted Requests</a></li>
              </ul>
            </li>
            @endif
            @if($f14Count!=0)
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="file"></i><span>14th Sentence</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="/14th-sentence/create">Create New</a></li>
                <li><a class="nav-link" href="/14th-sentence/new-requests">New Requests</a></li>
                <li><a class="nav-link" href="/14th-sentence/approved-requests">Approved Requests</a></li>
                <li><a class="nav-link" href="/14th-sentence/pending-requests">Pending Approvals</a></li>
                <li><a class="nav-link" href="/14th-sentence/rejected-requests">Rejected Requests</a></li>
                <li><a class="nav-link" href="/14th-sentence/gazetted-requests">Gazetted Requests</a></li>
              </ul>
            </li>
            @endif
            @if($f55Count!=0)
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="file"></i><span>55th Sentence</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="/55th-sentence/create">Create New</a></li>
                <li><a class="nav-link" href="/55th-sentence/new-requests">New Requests</a></li>
                <li><a class="nav-link" href="/55th-sentence/approved-requests">Approved Requests</a></li>
                <li><a class="nav-link" href="/55th-sentence/pending-requests">Pending Approvals</a></li>
                <li><a class="nav-link" href="/55th-sentence/rejected-requests">Rejected Requests</a></li>
                <li><a class="nav-link" href="/55th-sentence/gazetted-requests">Gazetted Requests</a></li>
              </ul>
            </li>
            @endif
            @if($amndCount!=0)
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="file"></i><span>Amendments</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="/amendments/create">Create New</a></li>
                <li><a class="nav-link" href="/amendments/new-requests">New Requests</a></li>
                <li><a class="nav-link" href="/amendments/approved-requests">Approved Requests</a></li>
                <li><a class="nav-link" href="/amendments/pending-requests">Pending Approvals</a></li>
                <li><a class="nav-link" href="/amendments/rejected-requests">Rejected Requests</a></li>
                <li><a class="nav-link" href="/amendments/gazetted-requests">Gazetted Requests</a></li>
              </ul>
            </li>
            @endif
            @if($ReportCount!=0)
          {{--   <li class="menu-header">Reports</li> --}}
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="pie-chart"></i><span>Reprots</span></a>
              <ul class="dropdown-menu">
                  @foreach ($AccessPermissions as $item)
                  @php
                  $Modules = $UtilityService->getAccessModule($item->module_code,4);
                  @endphp
                  @if(isset($Modules))
                  <li><a class="nav-link" href="{{$Modules->url}}">{{$Modules->md_name}}</a></li>
                  @endif
                  @endforeach
              </ul>
            </li>
            @endif
            @if($MainCount!=0)
           {{--  <li class="menu-header">Administration</li> --}}
            <li class="dropdown">
              <a href="#" class="nav-link has-dropdown"><i data-feather="settings"></i><span>Main Configurations</span></a>
              <ul class="dropdown-menu">
                  @foreach ($AccessPermissions as $item)
                  @php
                  $Modules = $UtilityService->getAccessModule($item->module_code,2);
                  @endphp
                  @if(isset($Modules))
                  <li><a class="nav-link" href="{{$Modules->url}}">{{$Modules->md_name}}</a></li>
                  @endif
                  @endforeach
              </ul>
            </li>
            @endif
          </ul>
        </aside>
      </div>