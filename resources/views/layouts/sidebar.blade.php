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
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
    </div>
    <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/images/logo/mpay.png')}}" height="40px" width="40px" alt=""></a></div>
    <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
      <div id="sidebar-menu">
        <ul class="sidebar-links" id="simple-bar">
          <li class="back-btn"><a href="index.html"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png')}}" alt=""></a>
            <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
          </li>
          @if(Auth::check() && Auth::user()->role_name=='Admin')

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">General</h6>
                <p class="lan-2">Page d'accueil.</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.dashboard')}}"><i data-feather="home"> </i><span>Dashboard</span></a></li>

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Client EMALA</h6>
                <p class="lan-2">Liste des clients Emala</p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.customer.all')}}"><i data-feather="users"> </i><span>Liste des Clients</span></a></li>

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Transaction</h6>
                <p class="lan-2">Historique des transactions</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transactions</span></a>
              <ul class="sidebar-submenu">
                  <li><a href="{{route('admin.transaction.all')}}">Toutes</a></li>
                  <li><a href="{{route('admin.transaction.deposit')}}">Dépôts</a></li>
                  <li><a href="{{route('admin.transaction.withdrawal')}}">Retraits</a></li>
                  <li><a href="{{route('admin.transaction.transfer')}}">Transferts</a></li>
              </ul>
            </li>

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Prêt EMALA</h6>
                <p class="lan-2">Crédits aux particuliers</p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.loans.create')}}"><i data-feather="list"> </i><span style="font-size: 11px;text-transform: none" >Formulaire de demande</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.loans.index')}}"><i data-feather="list"> </i><span style="font-size: 11px;text-transform: none">Historique de prêt</span></a></li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.pret.type')}}"><i data-feather="list"> </i><span>Type prêt</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.pret.plan')}}"><i data-feather="list"> </i><span>Plan prêt</span></a></li> --}}


            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Emala Système</h6>
                <p class="lan-2">Administration Système</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="credit-card"></i><span class="lan-6" style="text-transform: none">Balance des agences</span></a>
              <ul class="sidebar-submenu">
                <li><a  href="{{route('admin.wallet.emala')}}">Agence principale</a></li>
                  <li><a href="{{route('admin.wallet.agence')}}">Agence filiale</a></li>
              </ul>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.user.all')}}"><i data-feather="users"> </i><span style="text-transform: none">Liste des utilisateurs</span></a></li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.recharge.request')}}"><i data-feather="corner-up-right"> </i><span style="text-transform: none">Demande de recharge</span></a></li> --}}
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('admin.branch.all')}}"><i data-feather="list"> </i><span style="text-transform: none">Liste des agences</span></a></li>
          @endif
          @if(Auth::check() && Auth::user()->role_name=='Manager')

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">General</h6>
                <p class="lan-2">Page d'accueil.</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('manager.dashboard')}}"><i data-feather="home"> </i><span>Dashboard</span></a></li>

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Client EMALA</h6>
                <p class="lan-2">Liste des clients Emala</p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('manager.customer.all')}}"><i data-feather="users"> </i><span>Liste des Clients</span></a></li>

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Transaction</h6>
                <p class="lan-2">Historique des transactions</p>
              </div>
            </li>
            
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span>Transactions</span></a>
              <ul class="sidebar-submenu">
                  <li><a href="{{route('manager.transaction.all')}}">Toutes</a></li>
                  <li><a href="{{route('manager.transaction.deposit')}}">Dépôts</a></li>
                  <li><a href="{{route('manager.transaction.withdrawal')}}">Retraits</a></li>
                  <li><a href="{{route('manager.transaction.transfer')}}">Transferts</a></li>
              </ul>
            </li>

            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Prêt EMALA</h6>
                <p class="lan-2">Crédits aux particuliers</p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('manager.pret.demande')}}"><i data-feather="list"> </i><span>Historique de prêt</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('manager.pret.amortissement')}}"><i data-feather="list"> </i><span>Amortissement prêt</span></a></li>


            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Emala Système</h6>
                <p class="lan-2">Administration Système</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('manager.user.all')}}"><i data-feather="users"> </i><span>Liste des Caissiers</span></a></li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="monitor"></i><span class="lan-6">Gestion de l'Agence</span></a>
              <ul class="sidebar-submenu">
                <li><a  href="{{route('manager.wallet.agence')}}">Compte de l'agence</a></li>
                  <li><a href="{{route('manager.wallet.recharge.historique')}}">Demande de recharge</a></li>
              </ul>
            </li>

          @endif
          @if(Auth::check() && Auth::user()->role_name=='Cashier')

            <li class="sidebar-main-title">
              <div>
                {{-- <h6 class="lan-1">General</h6> --}}
                <p class="lan-2">Tableau de bord</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.dashboard')}}"><i data-feather="home"> </i><span style="font-size: 11px">Accueil</span></a></li>

            <li class="sidebar-main-title">
              <div>
                {{-- <h6 class="lan-1">Gestion des clients</h6> --}}
                <p class="lan-2">Gestion des clients</p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.customer.all')}}"><i data-feather="users"> </i><span style="font-size: 11px">Liste des clients</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.customer.create')}}"><i data-feather="user-plus"> </i><span style="font-size: 11px">Nouveau client</span></a></li>

            <li class="sidebar-main-title">
              <div>
                {{-- <h6 class="lan-1">Opérations de caisse</h6> --}}
                <p class="lan-2">Opérations de caisse</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.customer.deposit')}}"><i data-feather="list"> </i><span style="font-size: 11px">Dépôt d'argent</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.customer.withdraw')}}"><i data-feather="list"> </i><span style="font-size: 11px">Retrait d'argent</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.customer.transfer')}}"><i data-feather="list"> </i><span style="font-size: 11px">Transfert d'argent</span></a></li>

            
            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="repeat"></i><span style="font-size: 11px">Historique des transactions</span></a>
              <ul class="sidebar-submenu">
                  <li><a href="{{route('cashier.transaction.all')}}" style="font-size: 11px">Toutes</a></li>
                  <li><a href="{{route('cashier.transaction.deposit')}}" style="font-size: 11px">Dépôts</a></li>
                  <li><a href="{{route('cashier.transaction.withdrawal')}}" style="font-size: 11px">Retraits</a></li>
                  <li><a href="{{route('cashier.transaction.transfer')}}" style="font-size: 11px">Transferts</a></li>
              </ul>
            </li>

            <li class="sidebar-main-title">
              <div>
                {{-- <h6 class="lan-1">Prêt EMALA</h6> --}}
                <p class="lan-2">Gestion des prêts</p>
              </div>
            </li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.loans.create')}}"><i data-feather="list"> </i><span style="font-size: 11px">Formulaire de demande</span></a></li>
            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.loans.index')}}"><i data-feather="list"> </i><span style="font-size: 11px">Historique de prêt</span></a></li>
            {{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.pret.amortissement')}}"><i data-feather="list"> </i><span style="font-size: 11px">Amortissement prêt</span></a></li> --}}


            {{-- <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">Emala Système</h6>
                <p class="lan-2">Administration Système</p>
              </div>
            </li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('cashier.user.all')}}"><i data-feather="users"> </i><span>Liste des Caissiers</span></a></li>

            <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i data-feather="monitor"></i><span class="lan-6">Gestion de l'Agence</span></a>
              <ul class="sidebar-submenu">
                <li><a  href="{{route('cashier.wallet.agence')}}">Compte de l'agence</a></li>
                  <li><a href="{{route('cashier.wallet.recharge.historique')}}">Demande de recharge</a></li>
              </ul>
            </li> --}}

          @endif
        </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
  </div>
</div>