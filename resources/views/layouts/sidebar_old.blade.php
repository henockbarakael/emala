<div class="sidebar-wrapper">
    <div>
      <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""></a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
      </div>
      <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            @if (Auth::check() && Auth::user()->role_name == "Root")
              <li class="sidebar-main-title">
                <div>
                  <h6 class="lan-1">Root</h6>
                  <p class="lan-2">{{Auth::user()->firstname." ".Auth::user()->lastname}}</p>
                </div>
              </li>
              <li class="sidebar-list">
                <a class="sidebar-link sidebar-title link-nav" href="{{route('root.dashboard')}}"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
              </li>
            @elseif (Auth::check() && Auth::user()->role_name=='Admin')
              <li class="sidebar-main-title">
                <div>
                  <h6 class="lan-1">Admin</h6>
                  <p class="lan-2">{{Auth::user()->firstname." ".Auth::user()->lastname}}</p>
                </div>
              </li>
              <li class="sidebar-list">
                <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.dashboard')}}"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
              </li>
            @elseif (Auth::check() && Auth::user()->role_name=='Manager')
              <li class="sidebar-main-title">
                <div>
                  <h6 class="lan-1">Manager</h6>
                  <p class="lan-2">{{Auth::user()->firstname." ".Auth::user()->lastname}}</p>
                </div>
              </li>
              <li class="sidebar-list">
                <a class="sidebar-link sidebar-title link-nav" href="#"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
              </li>
            @elseif (Auth::check() && Auth::user()->role_name=='Cashier')
              <li class="sidebar-main-title">
                <div>
                  <h6 class="lan-1">Cashier</h6>
                  <p class="lan-2">{{Auth::user()->firstname." ".Auth::user()->lastname}}</p>
                </div>
              </li>
              <li class="sidebar-list">
                <a class="sidebar-link sidebar-title link-nav" href="#"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
              </li>
            @endif
        {{-- Start Transfert Interne --}}
            <li class="sidebar-main-title">
              <div>
                <h6>Transfert d'argent</h6>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.customer.index')}}"><i data-feather="list"></i>Liste des clients</a></li>

            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transfert Interne</span></a>
              <ul class="sidebar-submenu">
                    @if (Auth::check() && Auth::user()->role_name == "Root")
                    <li><a href="{{route('root.saving.index')}}">Wallet to Wallet</a></li>
                    @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                    <li><a href="{{route('admin.interne.wallet_wallet.expeditaire')}}">Wallet to Wallet</a></li>
                    @endif
              </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transfert Externe</span></a>
              <ul class="sidebar-submenu">
                    @if (Auth::check() && Auth::user()->role_name == "Root")
                    <li><a href="{{route('root.saving.verify')}}">Wallet to Mobile</a></li>
                    @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                    <li><a href="{{route('admin.interne.wallet_mobile.expeditaire')}}">Wallet to Mobile</a></li>
                    @endif
              </ul>
            </li> --}}
        {{-- End Transfert Interne --}}
        {{-- Start Account Management --}}
            <li class="sidebar-main-title">
              <div>
                <h6>Gestion des comptes</h6>
                <p>Manage account </p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="lock"></i><span>Compte Epargne</span></a>
              <ul class="sidebar-submenu">
                    @if (Auth::check() && Auth::user()->role_name == "Root")
                    <li><a href="{{route('root.saving.index')}}">Liste des comptes</a></li>
                    <li><a href="{{route('root.saving.verify')}}">Ajouter un compte</a></li>
                    @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                    <li><a href="{{route('admin.saving.index')}}">Liste des comptes</a></li>
                    <li><a href="{{route('admin.saving.verify')}}">Ajouter un compte</a></li>
                    @endif
              </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="lock"></i><span>Compte Courant</span></a>
              <ul class="sidebar-submenu">
                    @if (Auth::check() && Auth::user()->role_name == "Root")
                    <li><a href="{{route('root.current.index')}}">Liste des comptes</a></li>
                    @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                    <li><a href="{{route('admin.current.index')}}">Liste des comptes</a></li>
                    @endif
              </ul>
            </li>
            {{-- Start Historique --}}
            <li class="sidebar-main-title">
              <div>
                <h6>Historique</h6>
                <p>Transactions reports</p>
              </div>
            </li>
            <li class="sidebar-list">
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.historique')}}"><i data-feather="pie-chart"></i><span>Rapport transactions</span></a>
            </li>
        {{-- End Historique --}}
        {{-- Start User Management --}}
            <li class="sidebar-main-title">
              <div>
                <h6>Gestion des utilisateurs</h6>
                <p>Manage user system </p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="users"></i><span>Clients Emala</span></a>
              <ul class="sidebar-submenu">
                    @if (Auth::check() && Auth::user()->role_name == "Root")
                    <li><a href="{{route('root.customer.index')}}">Liste des clients</a></li>
                    <li><a href="{{route('root.customer.create')}}">Ajouter un client</a></li>
                    @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                    <li><a href="{{route('admin.customer.index')}}">Liste des clients</a></li>
                    <li><a href="{{route('admin.customer.create')}}">Ajouter un client</a></li>
                    @endif
              </ul>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="users"></i><span>Utiisateur Emala</span></a>
              <ul class="sidebar-submenu">
                @if (Auth::check() && Auth::user()->role_name == "Root")
                <li><a href="{{route('root.user.index')}}">Liste des utilisateurs</a></li>
                <li><a href="{{route('root.user.create')}}">Ajout utilisateur</a></li>
                @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                <li><a href="{{route('admin.user.index')}}">Liste des utilisateurs</a></li>
                <li><a href="{{route('admin.user.create')}}">Ajout utilisateur</a></li>
                @endif
              </ul>
            </li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="users"></i><span>Emala Merchants</span></a>
              <ul class="sidebar-submenu">
                <li><a class="submenu-title" href="#">Merchants<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                  <ul class="nav-sub-childmenu submenu-content">
                    <li><a href="form-validation.html">Liste des marchands</a></li>
                    <li><a href="base-input.html">Ajpouter un marchand</a></li>
                  </ul>
                </li>
              </ul>
            </li> --}}
        {{-- End User Management --}}

        {{-- Start Agence Management --}}
            <li class="sidebar-main-title">
              <div>
                <h6>Gestion des agences</h6>
                <p>Manage emala agence </p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="home"></i><span>Agence Principale</span></a>
              <ul class="sidebar-submenu">
                @if (Auth::check() && Auth::user()->role_name == "Root")
                <li><a href="{{route('root.branche.index')}}">Liste des agences</a></li>
                {{-- <li><a class="sidebar-link" href="{{route('root.branche.create')}}">Ajouter une agence</a></li> --}}
                @elseif (Auth::check() && Auth::user()->role_name == "Admin")
                <li><a href="{{route('admin.branche.master')}}">Détails de l'agence</a></li>
                <li><a href="{{route('admin.branche.cashier.create')}}">Ajouter un caissier</a></li>
                <li><a href="{{route('admin.branche.cashier.index')}}">Liste des caissiers</a></li>
                @endif
              </ul>
            </li>
            @if (Auth::check() && Auth::user()->role_name == "Admin")
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="home"></i><span class="lan-6">Agence Secondaire</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{route('admin.branche.index')}}">Liste des agences</a></li>
                {{-- <li><a href="{{route('admin.branche.create')}}">Ajouter une agence</a></li> --}}
              </ul>
            </li>
            @endif
        {{-- End Agence Management --}}

        {{-- Start Wallet Management --}}
            <li class="sidebar-main-title">
              <div>
                <h6>Gestion des wallets</h6>
                <p>Manage all wallet</p>
              </div>
            </li>
            <li class="sidebar-list">
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.wallet.main')}}">
                <i data-feather="server"></i><span>Wallet Principal</span>
              </a>
            </li>
            <li class="sidebar-list">
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.wallet.index')}}">
                <i data-feather="server"></i><span>Wallet Client</span>
              </a>
            </li>
        {{-- End Wallet Management --}}

        {{-- Start Agence Management --}}
        <li class="sidebar-main-title">
          <div>
            <h6>Journal d'activités</h6>
            <p>Manage activity log </p>
          </div>
        </li>

            @if (Auth::check() && Auth::user()->role_name == "Root")
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('root.user_activity_log')}}"><i data-feather="list"></i>Activité utilisateurs</a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('root.emala.activity.log')}}"><i data-feather="list"></i>Activité système</a></li>
            @elseif (Auth::check() && Auth::user()->role_name == "Admin")
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.user_activity_log')}}"><i data-feather="list"></i>Activité utilisateurs</a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.emala.activity.log')}}"><i data-feather="list"></i>Activité système</a></li>
            @endif
 
            <li class="sidebar-main-title">
              <div>
                <h6>Gestion de caisse</h6>
                <p>Comptabilité</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.branche.cashier.macaisse')}}"><i data-feather="list"></i>Ma caisse</a></li>
    {{-- End Agence Management --}}
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
</div>