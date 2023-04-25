<div class="sidebar-wrapper">
  <div>
    <div class="logo-wrapper">
      @if (Auth::check() && Auth::user()->role_name=='Admin')
      <a href="{{route('admin.dashboard')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""></a>
      @elseif (Auth::check() && Auth::user()->role_name=='Manager') 
      <a href=""><img class="img-fluid for-light" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""></a>
      @elseif (Auth::check() && Auth::user()->role_name=='Cashier') 
      <a href=""><img class="img-fluid for-light" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""></a>
      @endif

      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      {{-- <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div> --}}
    </div>
    <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
    <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
      <div id="sidebar-menu">
        <ul class="sidebar-links" id="simple-bar">
          <li class="back-btn"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
            <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
          </li>
          @if(Auth::check() && Auth::user()->role_name=='Admin')
            <li class="sidebar-main-title mt-2">
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.dashboard')}}"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
            </li>
            
            <li class="sidebar-main-title">
              <div>
                <a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transactions</span></a>
                <ul class="sidebar-submenu">
                  <li><a href="{{route('admin.transaction.all')}}">Toutes</a></li>
                  <li><a href="{{route('admin.transaction.deposit')}}">Dépôts</a></li>
                  <li><a href="{{route('admin.transaction.withdrawal')}}">Retraits</a></li>
                  <li><a href="{{route('admin.transaction.transfer')}}">Transferts</a></li>
                </ul>
              </div>
            </li>

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.current.create')}}"><i data-feather="user-plus"></i><span class="lan-3">Création de compte</span></a>
              </div>
            </li>
            
            <li class="sidebar-main-title">
              <div>
                <a class="sidebar-link sidebar-title" href="#"><i data-feather="users"></i><span>Utilisateurs</span></a>
                <ul class="sidebar-submenu">
                  <li><a href="{{route('admin.liste_client')}}">Liste des clients</a></li>
                  <li><a href="{{route('admin.liste_caissier')}}">Liste des caissiers</a></li>
                  <li><a href="{{route('admin.liste_gerant')}}">Liste des gérants</a></li>
                  <li><a href="{{route('admin.liste_admin')}}">Liste des admins</a></li>
                  <li><a href="{{route('admin.liste_marchand')}}">Liste des marchands</a></li>
                </ul>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="eye"></i><span class="lan-3">Journaux d'activités</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{route('admin.activity_log')}}">Logs du système</a></li>
                <li><a href="{{route('admin.user_activity_log')}}">Activités usagers</a></li>
              </ul>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.ticket.create')}}"><i data-feather="list"></i><span class="lan-3">Ticket</span></a>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.reharge.request.create')}}"><i data-feather="list"></i><span class="lan-3">Demande de recharge</span></a>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="command"></i><span class="lan-3">Gestion agence</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{route('admin.branche.all.treller')}}">Liste des caissiers</a></li>
                <li><a href="{{route('admin.branche.all.account')}}">Balance caissiers</a></li>
                <li><a href="{{route('admin.branche.account.manager')}}">Balance agence</a></li>

              </ul>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="settings"></i><span class="lan-3">Configuration Système</span></a>
              <ul class="sidebar-submenu">
                {{-- <li><a href="{{route('admin.activity_log')}}">Détails système</a></li> --}}
                <li><a href="{{route('admin.branche.all')}}">Liste des agences</a></li>
                <li><a href="{{route('admin.branche.manager.all')}}">Recharge agences</a></li>
                <li><a href="{{route('admin.wallet.all')}}">Liste des wallets</a></li>
                {{-- <li><a href="{{route('admin.gateway.all')}}">Méthode de paiement</a></li> --}}
                {{-- <li><a href="{{route('admin.transaction.limit')}}">Limites des transactions</a></li> --}}
              </ul>
              </div>
            </li>
          @elseif(Auth::check() && Auth::user()->role_name=='Manager')
            <li class="sidebar-main-title mt-2">
              <a class="sidebar-link sidebar-title link-nav" href="{{route('manager.dashboard')}}"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
            </li>
            
            <li class="sidebar-main-title">
              <div>
                <a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transactions</span></a>
                <ul class="sidebar-submenu">
                  <li><a href="{{route('manager.transaction.all')}}">Toutes</a></li>
                  <li><a href="{{route('manager.transaction.deposit')}}">Dépôts</a></li>
                  <li><a href="{{route('manager.transaction.withdrawal')}}">Retraits</a></li>
                  <li><a href="{{route('manager.transaction.transfer')}}">Transferts</a></li>
                </ul>
              </div>
            </li>

            

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('manager.liste_client')}}"><i data-feather="users"></i><span class="lan-3">Liste des clients</span></a>
              </div>
            </li>

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('admin.current.create')}}"><i data-feather="user-plus"></i><span class="lan-3">Création de compte</span></a>
              </div>
            </li>

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="eye"></i><span class="lan-3">Journaux d'activités</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{route('manager.activity_log')}}">Logs du système</a></li>
                <li><a href="{{route('manager.user_activity_log')}}">Activités usagers</a></li>
              </ul>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('manager.ticket.create')}}"><i data-feather="list"></i><span class="lan-3">Ticket</span></a>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('manager.reharge.request.create')}}"><i data-feather="list"></i><span class="lan-3">Demande de recharge</span></a>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="settings"></i><span class="lan-3">Gestion agence</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{route('manager.branche.all.treller')}}">Liste des caissiers</a></li>
                <li><a href="{{route('manager.branche.all.account')}}">Balance caissiers</a></li>
                <li><a href="{{route('manager.branche.account.manager')}}">Balance de l'agence</a></li>
              </ul>
              </div>
            </li>
          @elseif(Auth::check() && Auth::user()->role_name=='Cashier')
            <li class="sidebar-main-title mt-2">
              <a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.dashboard')}}"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a>
            </li>
            
            <li class="sidebar-main-title">
              <div>
                <a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transactions</span></a>
                <ul class="sidebar-submenu">
                  <li><a href="{{route('cashier.transaction.all')}}">Toutes</a></li>
                  <li><a href="{{route('cashier.transaction.deposit')}}">Dépôts</a></li>
                  <li><a href="{{route('cashier.transaction.withdrawal')}}">Retraits</a></li>
                  <li><a href="{{route('cashier.transaction.transfer')}}">Transferts</a></li>
                </ul>
              </div>
            </li>

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.liste_client')}}"><i data-feather="users"></i><span class="lan-3">Liste des clients</span></a>
              </div>
            </li>

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.current.create')}}"><i data-feather="user-plus"></i><span class="lan-3">Création de compte</span></a>
              </div>
            </li>
            

            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.ticket.create')}}"><i data-feather="message-square"></i><span class="lan-3">Soumettre un ticket</span></a>
              </div>
            </li>


            <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="download"></i><span class="lan-3">Rechargement compte</span></a>
                <ul class="sidebar-submenu">
                  <li><a href="{{route('cashier.branche.account.cashier')}}">Demande de recharge</a></li>
                  <li><a href="{{route('cashier.reharge.request.create')}}">Historique de demande</a></li>
                </ul>
              </div>
            </li>

            {{-- <li class="sidebar-main-title">
              <div>
              <a class="sidebar-link sidebar-title" href="#"><i data-feather="settings"></i><span class="lan-3">Gestion agence</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{route('cashier.branche.all.treller')}}">Liste des comptes</a></li>
                <li><a href="{{route('cashier.branche.all.account')}}">Balance caissiers</a></li>
              </ul>
              </div>
            </li> --}}
          @endif
        </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
  </div>
</div>