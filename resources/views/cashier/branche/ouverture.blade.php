@extends('layouts.app')
@section('content')
{!! Toastr::message() !!}
<div class="row m-0">
    <div class="col-12 p-0">    
      <div class="login-card ">
        <div>
          <div><a class="logo" href="{{route('login')}}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png')}}" alt="looginpage"><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo-header.png')}}" alt="looginpage"></a></div>
          <div class="login-caisse myshadow"> 
            <form class="theme-form">
                
                <input type="hidden" name="user_id" id="e_id" value="{{$user_id}}">
                
                <div class="row g-3">
                    <div class="card-header text-center">
                        <h5 class="text-center text-capitalize">Saisie des fonds d'ouverture de caisse</h5>
                        <span class="text-center">
                            Bienvenue ! Veuillez saisir le montant des fonds d'ouverture disponibles dans votre tiroir de caisse. 
                            Cela devrait inclure tous les billets en votre possession pour démarrer la journée. Assurez-vous de compter avec précision et de saisir le montant total dans le champ ci-dessous.
                        </span>
                    </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Franc Congolais</label>
                    <input class="form-control input-air-primary @error('new_solde_cdf') is-invalid @enderror" name="new_solde_cdf" id="e_new_solde_cdf" type="text" value="" >
                    @error('new_solde_cdf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Dollar Américain</label>
                    <input class="form-control input-air-primary @error('new_solde_usd') is-invalid @enderror" name="new_solde_usd" id="e_new_solde_usd" type="text" value="" >
                    @error('new_solde_usd')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="card-header">
                    {{-- <h5>ETAT DE CAISSE</h5> --}}
                    <span>
                        Ancien solde
                    </span>
                 </div>
                  <div class="col-md-6" style="margin-top: -10px">
                    <label class="form-label" for="validationCustom01"></label>
                    <input readonly class="form-control input-air-primary @error('last_solde_cdf') is-invalid @enderror" name="last_solde_cdf" id="e_last_solde_cdf" type="tel" value="{{$amount_cdf}}" >
                    @error('last_solde_cdf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6" style="margin-top: -10px">
                    <label class="form-label" for="validationCustom01"></label>
                    <input readonly class="form-control input-air-primary @error('last_solde_usd') is-invalid @enderror" name="last_solde_usd" id="e_last_solde_usd" type="tel" value="{{$amount_usd}}" >
                    @error('last_solde_usd')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                </div>
                
                <div class="modal-footer mt-5">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Valider</button>
                  </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @section('script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
        $('.theme-form').submit(function(event) {
            event.preventDefault(); // Empêche la soumission du formulaire par défaut

            var form = $(this);
            // Récupérer les valeurs des champs
            var lastSoldeCDF = parseFloat(form.find('[name="last_solde_cdf"]').val());
            var newSoldeCDF = parseFloat(form.find('[name="new_solde_cdf"]').val());
            var lastSoldeUSD = parseFloat(form.find('[name="last_solde_usd"]').val());
            var newSoldeUSD = parseFloat(form.find('[name="new_solde_usd"]').val());

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Vérifier si les valeurs sont différentes
            if (lastSoldeCDF !== newSoldeCDF || lastSoldeUSD !== newSoldeUSD) {
                Swal.fire({
                    title: 'Attention!',
                    text: 'Les fonds d\'ouverture doivent correspondre aux fonds en caisse.',
                    icon: 'warning'
                });
                event.preventDefault(); // Empêcher la soumission du formulaire
                return; // Sortir de la fonction pour éviter d'envoyer la requête Ajax
            }

            // Sérialiser le formulaire
            var formData = form.serialize();
            formData += '&_token=' + encodeURIComponent(csrfToken);

            // Envoyer la requête Ajax
            $.ajax({
                url: 'ouverture-caisse',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Traitement de la réponse réussie
                    if (response.status === true) {
                        console.log(response);
                        Swal.fire({
                            title: 'Excellent!',
                            text: response.message,
                            icon: 'success'
                        }).then(function() {
                            window.location = '{{ route("cashier.dashboard") }}';
                        });
                    } else if (response.status === false) {
                        console.log(response);
                        Swal.fire({
                            title: 'Erreur!',
                            text: response.message,
                            icon: 'error'
                        }).then(function() {
                        window.location = '{{ route("cashier.ouverture_caisse") }}';
                    });
                    }
                },
                error: function(xhr, status, error) {
                    // Traitement de l'erreur de la requête Ajax
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
  @endsection
@endsection
