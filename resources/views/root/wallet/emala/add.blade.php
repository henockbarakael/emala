@extends('layouts.master')
@section('content')
@section('title','Main wallet')
@section('page','Main wallet')
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
              <h5 style="text-transform: none">Add new wallet</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('root.wallet.store')}}" novalidate=""method="POST">
                @csrf
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label" for="exampleFormControlSelect9">Wallet Type</label>
                    <select name="wallet_type" class="form-select digits" id="exampleFormControlSelect9">
                      <option selected disabled>Choisir un type</option>
                      <option value="emala">Emala</option>
                      <option value="mobile-money">Mobile money</option>
                    </select>
                    @error('wallet_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="exampleFormControlSelect9">Wallet Level</label>
                    <select name="wallet_level" class="form-select digits" id="exampleFormControlSelect9">
                      <option selected disabled>Choisir un niveau</option>
                      <option value="Parent">Parent</option>
                      <option value="Inner">Inner</option>
                    </select>
                    @error('wallet_level')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="exampleFormControlSelect9">Wallet Status</label>
                    <select name="wallet_status" class="form-select digits" id="exampleFormControlSelect9">
                      <option selected disabled>Choisir un status</option>
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Disable">Disable</option>
                    </select>
                    @error('wallet_status')
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