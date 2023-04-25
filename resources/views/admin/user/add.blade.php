@extends('layouts.master')
@section('content')
@section('title','User Form')
@section('page','User Management')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h5 style="text-transform: none">Ajouter un gérant</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.user.store')}}" novalidate=""method="POST">
                @csrf
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Prénom</label>
                    <input class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="validationCustom01" type="text" value="{{ old('firstname') }}" required="">
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom02">Nom</label>
                    <input class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="validationCustom02" type="text" value="{{ old('lastname') }}" required="">
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Téléphone</label>
                    <input class="form-control input-air-primary @error('phone_number') is-invalid @enderror" name="phone_number" id="validationCustom01" type="text" value="{{ old('phone_number') }}" required="">
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="email">E-mail</label>
                    <input class="form-control input-air-primary @error('email') is-invalid @enderror" name="email" id="e_email" type="email" value="{{ old('email') }}" >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Ville</label>
                    <input class="form-control input-air-primary @error('ville') is-invalid @enderror" name="ville" id="validationCustom01" type="text" value="{{ old('ville') }}" required="">
                    @error('ville')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="role">Rôle</label>
                    <select name="role" class="form-select input-air-primary digits" id="role">
                      <option selected disabled>Choisir un rôle</option>
                      @foreach ($role_name as $role )
                        <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                      @endforeach
                    </select>
                    @error('role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom02">Adresse physique</label>
                    <textarea class="form-control input-air-primary @error('adresse') is-invalid @enderror" name="adresse" id="validationCustom02" type="text" value="{{ old('adresse') }}" required=""></textarea>
                    @error('adresse')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                </div>
                <div class="col-sm-12 mt-5">
                  <div class="card">
                    <div class="media p-20">
                      <div class="media-body">
                        <h6 class="mt-0 mega-title-badge">INFORMATIONS SUR L'AGENCE<span class="badge badge-primary pull-right digits">Générer une agence</span></h6>
                        <p>En rempliçant les champs ci-dessous, une agence sera automatiquement créée.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row g-3 mt-3">
                  <div class="col-md-6">
                    <label class="form-label" for="btownship">Commune</label>
                    <input class="form-control input-air-primary @error('btownship') is-invalid @enderror" name="btownship" id="e_btownship" type="text" value="{{ old('btownship') }}" >
                    @error('btownship')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="bcity">Ville</label>
                    <input class="form-control input-air-primary @error('bcity') is-invalid @enderror" name="bcity" id="e_bcity" type="text" value="{{ old('bcity') }}" >
                    @error('bcity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="bcity">E-mail de l'agence</label>
                    <input class="form-control input-air-primary @error('emaila') is-invalid @enderror" name="emaila" id="e_emaila" type="email" value="{{ old('emaila') }}" >
                    @error('emaila')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="branche_type">Type</label>
                    <input readonly class="form-control input-air-primary @error('branche_type') is-invalid @enderror" name="branche_type" id="e_branche_type" type="text" value="Inner" >
                    @error('branche_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Valider</button>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
@endsection