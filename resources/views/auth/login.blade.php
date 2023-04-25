@extends('layouts.app')
@section('content')
{!! Toastr::message() !!}
<div class="row m-0">
    <div class="col-12 p-0">    
      <div class="login-card ">
        <div>
          <div><a class="logo" href="index.html"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo-header.png')}}" alt="looginpage"></a></div>
          <div class="login-main myshadow"> 
            <form class="theme-form" action="{{route('authenticate')}}" method="POST">
                @csrf
              <h4 class="text-center mb-5">Connectez-vous</h4>
              {{-- <p>Entrez votre numéro et mot de passe pour vous connecter</p> --}}
              <div class="form-group">
                <label class="col-form-label">Téléphone</label>
                <input name="telephone" class="form-control myshadow" type="tel" required="" placeholder="243828584688">
              </div>
              <div class="form-group">
                <label class="col-form-label">Mot de asse</label>
                <div class="form-input position-relative">
                  <input class="form-control myshadow" type="password" name="password" required="" placeholder="*********">
                  <div class="show-hide"><span class="show">                         </span></div>
                </div>
              </div>
              <div class="form-group mb-0">
                <div class="checkbox p-0">
                  <input id="checkbox1" type="checkbox">
                  <label class="text-muted" for="checkbox1">Remember password</label>
                </div><a class="link" href="forget-password.html">Mot de passe oublié?</a>
                <div class="text-end mt-3 myshadow">
                  <button class="btn btn-primary btn-block w-100" type="submit">Se Connecter</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
