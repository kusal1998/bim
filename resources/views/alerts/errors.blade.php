<div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>×</span>
          </button>
          <strong>Operation Failed!</strong> 
          {{ session()->get('error') }}
          <ul>
              @foreach ($errors->all() as $error)
              
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
        </div>
      </div>