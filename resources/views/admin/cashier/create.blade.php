@extends('layouts.master')
@section('content')
@section('title','Gestion des utilisateurs')
@section('page','Ajout d\'un caissier')
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
              <h5 style="text-transform: none">Ajouter un caissier</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.branche.cashier.store')}}" novalidate=""method="POST">
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
                  <div class="col-md-6">
                    <label class="form-label" for="email">E-mail</label>
                    <input class="form-control input-air-primary @error('email') is-invalid @enderror" name="email" id="e_email" type="email" value="{{ old('email') }}" >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Ville</label>
                    <input class="form-control input-air-primary @error('ville') is-invalid @enderror" name="ville" id="validationCustom01" type="text" value="{{ old('ville') }}" required="">
                    @error('ville')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  {{-- <div class="col-md-4">
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
                  </div> --}}
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