@extends('layouts.app_caissier')
@section('content')
{!! Toastr::message() !!}
<!-- Main Wrapper -->
<div class="main-wrapper">

    <div class="account-content">

        <div class="container">
            {{-- <div class="account-logo">
                <a href="{{ route('login') }}"><img src="{{ URL::to('assets/img/logo.png') }}" height="90px" width="50px" alt="Emala"></a>
            </div> --}}
            <div class="account-box">
                <div class="account-wrapper">
                    <h3 class="account-title" style="font-family: var(--brand-font),Helvetica,Arial,sans-serif;
                    font-weight: 800; font-size: 20px">ETAT DE CAISSE</h3>
                    <p class="account-subtitle mt-2" style="font-family: var(--brand-font),Helvetica,Arial,sans-serif; font-size: 13px"><i>Enregistrez ici le montant réel dont vous disposez en espèce dans votre tiroir caisse.</i></p>

                    <!-- Account Form -->
                    <form action="{{route('caissier.validation.fondcaisse')}}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4 ">
                                <input readonly type="text" placeholder="Fond en caisse" class="form-control badge-light text-white" >
                            </div>
                            <div class="col-md-4">
                                <input readonly type="text" placeholder="{{$balance_2}} $" class="form-control text-right">
                            </div>
                            <div class="col-md-4">
                                <input readonly type="text"  placeholder="{{$balance_1}} Fc" class="form-control text-right">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 ">
                                <input type="text" placeholder="Fond en espèces" class="form-control badge-light text-white" readonly>
                            </div>


                            <div class="col-md-4">
                                <input type="text" name="usd" placeholder="$" class="form-control text-right @error('usd') is-invalid @enderror">
                                @error('usd')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="cdf" placeholder="Fc" class="form-control text-right @error('cdf') is-invalid @enderror">
                                @error('cdf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary account-btn" type="submit" style="text-transform: uppercase; font-size: 13px">Ouverture caisse</button>
                        </div>
                        <div class="account-footer">
                            <p>Bonjour  <a href="">{{Auth::user()->firstname." ".Auth::user()->name}} !</a></p>
                        </div>
                    </form>
                    <!-- /Account Form -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->
@endsection
