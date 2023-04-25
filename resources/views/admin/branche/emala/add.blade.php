@extends('layouts.master')
@section('content')
@section('title','Main agence')
@section('page','Main agence')
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
              <h5 style="text-transform: none">Add new agence</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.branche.store')}}" novalidate=""method="POST">
                @csrf
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label" for="btownship">Commune</label>
                    <input class="form-control input-air-primary @error('btownship') is-invalid @enderror" name="btownship" id="e_btownship" type="text" value="{{ old('btownship') }}" >
                    @error('btownship')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="bcity">Ville</label>
                    <input class="form-control input-air-primary @error('bcity') is-invalid @enderror" name="bcity" id="e_bcity" type="text" value="{{ old('bcity') }}" >
                    @error('bcity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="btype">Type</label>
                    <select name="btype" class="form-select input-air-primary digits" id="btype">
                      <option selected disabled>Choisir un type</option>
                      <option value="Parent">Parent</option>
                      <option value="Inner">Inner</option>
                    </select>
                    @error('btype')
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