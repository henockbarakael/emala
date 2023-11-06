<div class="page-header">
    <div class="header-wrapper row m-0">
      <form class="form-inline search-full col" action="#" method="get">
        <div class="form-group w-100">
          <div class="Typeahead Typeahead--twitterUsers">
            <div class="u-posRelative">
              <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus="">
              <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
            </div>
            <div class="Typeahead-menu"></div>
          </div>
        </div>
      </form>
      <div class="header-logo-wrapper col-auto p-0">
        <div class="logo-wrapper"><a href="#"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png')}}" alt=""></a></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
      </div>
      <div class="left-header col horizontal-wrapper ps-0">
        <ul class="horizontal-menu">
            {{-- @if (Auth::user()->role_name == "Admin") --}}
            {{-- <li class="level-menu outside"><a class="nav-link btn btn-air-primary" id="btn_ajax" href="{{route('admin.cloture_caisse')}}"><i data-feather="lock"></i><span>Clôture de caisse</span></a></li>
            @elseif (Auth::user()->role_name == "Manager")
            <li class="level-menu outside"><a class="nav-link btn btn-air-primary" id="btn_ajax" href="{{route('manager.cloture_caisse')}}"><i data-feather="lock"></i><span>Clôture de caisse</span></a></li> --}}
            @if (Auth::user()->role_name == "Cashier")
            <li class="level-menu outside"><a class="nav-link btn btn-air-primary" id="btn_ajax" href="{{route('cashier.cloture_caisse')}}"><i data-feather="lock"></i><span>Clôture de caisse</span></a></li> 
            @endif
        </ul>
      </div>
      <div class="nav-right col-8 pull-right right-header p-0">
        <ul class="nav-menus">
          <li class="language-nav">
            <div class="translate_wrapper">
              <div class="current_lang">
                <div class="lang"><i class="flag-icon flag-icon-fr"></i><span class="lang-txt">FR</span></div>
              </div>
              <div class="more_lang">
                <div class="lang selected" data-value="fr"><i class="flag-icon flag-icon-fr"></i><span class="lang-txt">Français</span></div>
                <div class="lang" data-value="en"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">English</span></div>
              </div>
            </div>
          </li>
          <li>
            <div class="mode"><i class="fa fa-moon-o"></i></div>
          </li>
          <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
          <li class="profile-nav onhover-dropdown p-0 me-0">
            <div class="media profile-media"><img class="b-r-10" src="{{ URL::to('/assets/images/user/'. Auth::user()->avatar) }}" alt="">
              <div class="media-body"><span>{{Auth::user()->firstname." ".Auth::user()->lastname}}</span>
                <p class="mb-0 font-roboto">{{Auth::user()->role_name}} <i class="middle fa fa-angle-down"></i></p>
              </div>
            </div>
            <ul class="profile-dropdown onhover-show-div">
              {{-- @if (Auth::user()->role_name == "Manager")
              <li><a href="{{route('manager.account.profil.user')}}"><i data-feather="user"></i><span>Mon Compte </span></a></li>
              @elseif (Auth::user()->role_name == "Cashier")
              <li><a href="{{route('cashier.account.profil.user')}}"><i data-feather="user"></i><span>Mon Compte </span></a></li>
              @elseif (Auth::user()->role_name == "Admin")
              <li><a href="{{route('admin.account.profil.user')}}"><i data-feather="user"></i><span>Mon Compte </span></a></li>
              @endif --}}
              <li>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf <!-- Ajoutez cette ligne pour inclure le jeton CSRF -->
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                  <i data-feather="log-in"></i>
                  <span>Déconnexion</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <script class="result-template" type="text/x-handlebars-template">
        <div class="ProfileCard u-cf">                        
        <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
        <div class="ProfileCard-details">
        <div class="ProfileCard-realName">{{ Auth::user()->firstname }}</div>
        </div>
        </div>
      </script>
      <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
    </div>
</div>
