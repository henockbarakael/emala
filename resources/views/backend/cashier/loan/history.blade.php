@extends('layouts.master')
@push('style')
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portefeuille numérique à la pointe de la technologie, Emala vous permet de faire des transactions financières sécurisées.">
    <meta name="keywords" content="EMALA, Emala, emala, emalafintech, fintech">
    <meta property="og:image" content="http://dashboard.emalafintech.net/assets/img/logo.png" />
    <meta property="og:image:secure_url" content="https://dashboard.emalafintech.net/assets/img/logo.png" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Emala Fintech" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Henock BARAKAEL | barahenock@gmail.com | +243828584688">
    <link rel="icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <title>Emala - Historique de prêt</title>
    <link href="{{ asset('dist/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-1?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/icofont.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/themify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/flag-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/feather-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link
      href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet"/>
    <style>.myshadow{
      box-shadow: 0 10px 5px -1px rgba(0,0,0,.2),0 15px 18px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12)!important;
      border-radius: 8px;
    }</style>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/daterange-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('backend/css/color-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/css/toastr.min.css') }}">
    <script src="{{ asset('backend/js/toastr_jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/toastr.min.js') }}"></script>
</head>
@endpush
@section('content')
@section('page','Historique de prêt')
@section('page_1','Prêt')
@section('page_2','Historique de prêt')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('sweetalert::alert')
    @include('layouts.page-title')
    
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <!-- Flexible table width Starts-->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive product-table">
                <table class="display" id="basic-8">
                  <thead>
                    <tr>
                      <th hidden>ID</th>
                      <th>N° Dossier</th>
                      <th style="min-width: 130px">Montant du prêt</th>
                      <th>Devise</th>
                      <th>Durée</th>
                      <th>Fréquence</th>
                      <th style="min-width: 80px">Status</th>
                      <th style="min-width: 200px">Action sur la demande</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($loans as $key => $value)
                      <tr>
                        <td class="control_number"><a href="{{ route('cashier.loans.show', ['id' => $value->id]) }}">{{ $value->control_number }}</a></td>
                        <td hidden class="id">{{$value->id}}</td>
                        <td class="loan_amount">{{$value->amount}}</td>
                        <td class="loan_currency">{{$value->currency}}</td>
                        <td class="loan_duration">{{$value->duration}} mois</td>
                        @if ($value->payment_frequency == 'weekly')
                        <td class="payment_frequency">Semaine </td>
                        @elseif ($value->payment_frequency == 'daily')
                        <td class="payment_frequency">Jour </td>
                        @else
                        <td class="payment_frequency">Mois </td>
                        @endif
                        <td class="loan_status">{{$value->status}}</td>
                        <td class="action">
                          @if ($value->status == 'Approuvé' || $value->status == 'Rejeté')
                            <button class="btn btn-danger btn-sm annulerDemandeBtn" type="button" disabled>Refuser</button>
                            <button class="btn btn-success btn-sm validerDemandeBtn" type="button" disabled>Approuvé</button>
                          @else
                            <button class="btn btn-danger btn-sm annulerDemandeBtn" type="button" data-bs-toggle="modal" data-bs-target="#annulerModal{{$value->id}}" data-original-title="btn btn-danger btn-sm" title="">Refuser</button>
                            <button class="btn btn-success btn-sm validerDemandeBtn" type="button" data-bs-toggle="modal" data-bs-target="#validerModal{{$value->id}}" data-original-title="btn btn-danger btn-sm" title="">Approuvé</button>
                          @endif
                        </td>
                      </tr>
                  
                      <!-- Modal de confirmation pour l'annulation -->
                      <div class="modal fade custom-modal" id="annulerModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="annulerModalLabel{{$value->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="annulerModalLabel{{$value->id}}">Confirmation d'annulation</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p>Êtes-vous sûr de vouloir annuler cette demande de prêt ?</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              <button type="button" class="btn btn-danger annulerConfirmBtn" data-bs-dismiss="modal" data-id="{{$value->id}}">Rejeté la demande</button>
                            </div>
                          </div>
                        </div>
                      </div>
                  
                      <!-- Modal de confirmation pour la validation -->
                      <div class="modal fade" id="validerModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="validerModalLabel{{$value->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="validerModalLabel{{$value->id}}">Confirmation de validation</h5>
                              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p>Êtes-vous sûr de vouloir valider cette demande de prêt ?</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              <button type="button" class="btn btn-success validerConfirmBtn" data-bs-dismiss="modal" data-id="{{$value->id}}">Valider</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Flexible table width  Ends-->
      </div>
  </div>

@section('script')
<script src="{{ asset('backend/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ asset('backend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('backend/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{ asset('backend/js/icons/feather-icon/feather-icon.js')}}"></script>
<script src="{{ asset('backend/js/scrollbar/simplebar.js')}}"></script>
<script src="{{ asset('backend/js/scrollbar/custom.js')}}"></script>
{{-- <script src="{{ asset('backend/js/product-list-custom.js')}}"></script> --}}
<script src="{{ asset('backend/js/config.js')}}"></script>
<script src="{{ asset('backend/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('backend/js/tooltip-init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('backend/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('backend/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ asset('backend/js/script.js')}}"></script>

<script>
  $(document).ready(function() {

    // Obtenir le jeton CSRF à partir de la balise meta
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Configurer le jeton CSRF dans les en-têtes de la requête AJAX
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    // Gérer le clic sur le bouton d'annulation
    $('.annulerDemandeBtn').on('click', function() {
      var id = $(this).data('id');
      console.log(id);
      $('#annulerModal' + id).modal('show');
    });

    // Gérer le clic sur le bouton de confirmation d'annulation
    $('.annulerConfirmBtn').on('click', function() {
      var id = $(this).data('id');
      // Faire la requête AJAX pour annuler la demande de prêt spécifique
      $.ajax({
        url: '{{ route("cashier.annuler-demande-pret", ["id" => ":id"]) }}'.replace(':id', id),

        type: 'POST',
        success: function(response) {
          if (response.error) {
            // Afficher le message d'erreur dans un SweetAlert avec une icône d'erreur
            Swal.fire({
              icon: 'error',
              title: 'Erreur',
              text: response.message
            });
          } else {
            // Mettre à jour le statut de la demande de prêt dans l'interface utilisateur
            $('#annulerModal' + id).closest('tr').find('.loan_status').text('Rejeté');

            // Afficher la réponse de la requête dans un SweetAlert avec une icône de succès
            Swal.fire({
              icon: 'success',
              title: 'Succès',
              text: response.message
            }).then(function() {
              $('#annulerModal' + id).modal('hide');
              // Recharger la page
              location.reload();
            });;
          }
        },
        error: function(xhr, status, error) {
          // Gérer les erreurs de la requête AJAX ici
          console.log(xhr.responseText);
        }
      });
    });

    // Gérer le clic sur le bouton de validation
    $('.validerDemandeBtn').on('click', function() {
      var id = $(this).data('id');
      $('#validerModal' + id).modal('show');
    });

    // Gérer le clic sur le bouton de confirmation de validation
    $('.validerConfirmBtn').on('click', function() {
      var id = $(this).data('id');
      // Faire la requête AJAX pour valider la demande de prêt spécifique
      $.ajax({
        url: '{{ route("cashier.valider-demande-pret", ["id" => ":id"]) }}'.replace(':id', id),
        type: 'POST',
        success: function(response) {
          if (response.error) {
            // Afficher le message d'erreur dans un SweetAlert avec une icône d'erreur
            Swal.fire({
              icon: 'error',
              title: 'Erreur',
              text: response.message
            });
          } else {
            // Mettre à jour le statut de la demande de prêt dans l'interface utilisateur
            $('#validerModal' + id).closest('tr').find('.loan_status').text('Approuvé');
            // Afficher la réponse de la requête dans un SweetAlert
            Swal.fire({
              icon: 'success',
              title: 'Succès',
              text: response.message
            }).then(function() {
              $('#validerModal' + id).modal('hide');
              // Recharger la page
              location.reload();
            });
          }
        },
        error: function(xhr, status, error) {
          // Gérer les erreurs de la requête AJAX ici
          console.log(xhr.responseText);
        }
      });
    });
  });
</script>

  @endsection
@endsection