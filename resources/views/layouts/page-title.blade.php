<div class="container-fluid">
    <div class="page-title ">
      <div class="row mt-2">
        <div class="col-6">
          <h3 style="text-transform: none">@yield('page')</h3>
        </div>
        <div class="col-6 ">
          <ol class="breadcrumb">
            @if (Auth::check() && Auth::user()->role_name == "Super Admin")
                <li class="breadcrumb-item"><a href="{{route('superadmin.dashboard')}}"><i data-feather="home"></i></a></li>
            @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i data-feather="home"></i></a></li>
            @elseif (Auth::check() && Auth::user()->role_name == "Gérant")
                <li class="breadcrumb-item"><a href="{{route('gerant.dashboard')}}"><i data-feather="home"></i></a></li>
            @elseif (Auth::check() && Auth::user()->role_name == "Caissier")
                <li class="breadcrumb-item"><a href="{{route('caissier.dashboard')}}"><i data-feather="home"></i></a></li>
            @endif
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active" style="text-transform: none">@yield('page')</li>
          </ol>
        </div>
      </div>
    </div>
</div>