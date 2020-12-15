<div class="dropdown dropdown-profile">
    <a href="#" class="dropdown-link" data-toggle="dropdown" data-display="static">
      <div class="avatar avatar-sm"><img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}+{{ Auth::user()->last_name }}&background=0D8ABC&color=fff" class="rounded-circle" alt=""></div>
    </a><!-- dropdown-link -->
    <div class="dropdown-menu dropdown-menu-right tx-13">
      <div class="avatar avatar-lg mg-b-15"><img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}+{{ Auth::user()->last_name }}&background=0D8ABC&color=fff" class="rounded-circle" alt=""></div>
      <h6 class="tx-semibold mg-b-5">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h6>
      <!-- <p class="mg-b-25 tx-12 tx-color-03">Administrator</p> -->

      {{-- <a href="#" class="dropdown-item"><i data-feather="edit-3"></i> Edit Profile</a> --}}
      {{-- <a href="page-profile-view.html" class="dropdown-item"><i data-feather="user"></i> View Profile</a> --}}
      <div class="dropdown-divider"></div>
     {{--  <a href="page-help-center.html" class="dropdown-item"><i data-feather="help-circle"></i> Help Center</a>
      <a href="#" class="dropdown-item"><i data-feather="life-buoy"></i> Forum</a>
      <a href="#" class="dropdown-item"><i data-feather="settings"></i>Account Settings</a>
      <a href="#" class="dropdown-item"><i data-feather="settings"></i>Privacy Settings</a> --}}
     

    
          <a class="dropdown-item" href="{{ route('logout') }}"
          onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
         > <i data-feather="log-out"></i>Sign Out</a>
           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
        

    </div><!-- dropdown-menu -->
  </div><!-- dropdown -->