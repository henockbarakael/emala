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
              <h5 style="text-transform: none">Ajouter un client</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.customer.store')}}" novalidate=""method="POST">
                @csrf
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Prénom</label>
                    <input class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="validationCustom01" type="text" value="{{ old('firstname') }}" required="">
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Nom</label>
                    <input class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="validationCustom02" type="text" value="{{ old('lastname') }}" required="">
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                </div>

                <div class="row g-3 mt-3">
                  <div class="col-md-6">
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
                    <label class="form-label" for="validationCustom01">Ville</label>
                    <input class="form-control input-air-primary @error('ville') is-invalid @enderror" name="ville" id="validationCustom01" type="text" value="{{ old('ville') }}" required="">
                    @error('ville')
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
                
                <div class="mb-3">
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