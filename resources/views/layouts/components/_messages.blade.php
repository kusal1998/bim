<div class="dropdown dropdown-message">
    <a href="#" class="dropdown-link new-indicator" data-toggle="dropdown">
        @if(trans('sentence.lang')=='EN')
        <img src="../../img/eng_32.png" class="rounded" alt="">
        <span>{{trans('sentence.lan_name')}}</span>
        @else
        <img src="../../img/sl_32.png" class="rounded" alt="">
        <span>{{trans('sentence.lan_name')}}</span>
        @endif
      </a>
    <div class="dropdown-menu dropdown-menu-right">
      <div class="dropdown-header">Localization</div>
      <a href="/lang/sl" class="dropdown-item">
        <div class="media">
          <div class="avatar avatar-sm avatar-online"><img src="../../img/sl_48.png" class="rounded" alt=""></div>
          <div class="media-body mg-l-15">
            <strong>සිංහල</strong>
          </div><!-- media-body -->
        </div><!-- media -->
      </a>
      <a href="/lang/en" class="dropdown-item">
        <div class="media">
          <div class="avatar avatar-sm avatar-online"><img src="../../img/eng_48.png" class="rounded" alt=""></div>
          <div class="media-body mg-l-15">
            <strong>ENGLISH</strong>
          </div><!-- media-body -->
        </div><!-- media -->
      </a>
    </div><!-- dropdown-menu -->
  </div><!-- dropdown -->