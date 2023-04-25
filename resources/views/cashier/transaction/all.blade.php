@extends('layouts.master')
@section('content')
@section('title','Rapport des transactions')
@section('page','Rapport des transactions')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <form class="row date-range-picker" action="{{route('cashier.transaction.recherche')}}" method="POST">
              @csrf
                <div class="col-xl-3">
                    <div class="theme-form">
                        {{-- <input class="form-control input-air-primary" type="text" name="daterange" placeholder="01/15/2017 - 02/15/2017"> --}}
                        <input class="datepicker-here form-control digits input-air-primary" type="text" placeholder="Choisir une date" name="date" data-language="en">
                    </div>
                </div>
                <div class="col-xl-3">
                  <div class="theme-form">
                        <input class="form-control input-air-primary" type="text" name="sender_number" placeholder="Numéro expéditeur">
                      </div>
                </div>
                <div class="col-xl-3">
                  <div class="theme-form">
                      <input class="form-control input-air-primary" type="text" name="receiver_number" placeholder="Numéro destinataire">
                  </div>
                </div>
                <div class="col-xl-3">
                  <div class="theme-form">
                      <button type="submit" class="btn btn-success btn-lg pull-right text-small" style="font-size: 14px"><span><i class="fa fa-search" style="font-size: 12px"></i></span> Rechercher </button>
                    </div>
                </div>
            </form>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th hidden>Id</th>
                    <th class="text-left">Expéditeur</th>
                    <th class="text-left">Montant</th>
                    <th hidden class="text-left">Frais</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Bénéficiaire</th>
                    <th hidden class="text-center">Action</th>
                    <th class="text-left">Type</th> 
                    <th hidden class="text-center">Référence</th>
                    <th class="text-center">Détails</th>
                    {{-- <th class="text-center">Status</th> --}}
                  </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $key => $value)
                @php
                    if($value->currency_id == 1){
                      $currency = "CDF";
                    }
                    elseif($value->currency_id == 2){
                      $currency = "USD";
                    }
                    $receiver_phone = $value->receiver_phone;
                    $sender_phone = $value->sender_phone;
                    // $check_receiver = DB::table('users')->where('phone_number', $receiver_phone)->first();
                    $check_receiver = DB::connection('mysql2')->table('users')->where('phone', $receiver_phone)->first();
                    $check_sender = DB::connection('mysql2')->table('users')->where('phone', $sender_phone)->first();
                    
                @endphp
                  <tr>
                    <td class="date text-left">{{$value->created_at}}</td>
                    <td hidden class="id">{{$value->id}}</td>
                    @if ($check_sender == null)
                    <td class="sender text-left">{{$value->sender_phone}}</td>
                    @else
                    <td class="sender text-left"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->sender_phone)) }}">{{$value->sender_phone}}</a></td>
                    @endif
                    <td class="amount text-left">{{$value->amount}}</td>
                    <td hidden class="fees text-center">{{$value->fees}}</td>
                    <td hidden class="reference text-center">{{$value->reference}}</td>
                    <td class="currency text-left">{{$currency}}</td>
                    @if ($check_receiver == null)
                    <td class="receiver text-left">{{$value->receiver_phone}}</td>
                    @else
                    <td class="receiver text-left"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->receiver_phone)) }}">{{$value->receiver_phone}}</a></td>
                    @endif
                    <td hidden class="action text-center"><span style="text-transform: capitalize">{{$value->action}}</span></td>
                    <td class="type text-left"><span style="text-transform: capitalize">{{$value->type}}</span></td> 
                    <td class="text-center">
                      <a href="#" class="btn btn-primary btn-xs details" title="Détails" data-bs-toggle="modal" data-original-title="test" data-bs-target="#details">Détails</a>
                  </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Date</th>
                    <th hidden>Id</th>
                    <th class="text-left">Expéditeur</th>
                    <th class="text-left">Montant</th>
                    <th hidden class="text-left">Frais</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Bénéficiaire</th>
                    <th hidden class="text-center">Action</th>
                    <th hidden class="text-center">Référence</th>
                    <th class="text-left">Type</th> 
                    <th class="text-center">Détails</th>
                    {{-- <th class="text-center">Status</th> --}}
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Flexible table width  Ends-->
    </div>
    <!-- Container-fluid Ends-->
  </div>
  <div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        {{-- <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Détails de la transaction</h5>
        </div> --}}
        <div class="modal-body">
          <div class="row">
          <div class="col-sm-12 col-xl-12">
            <div class="card">
              <div class="card-body bg-black">
                <div class="text-center mb-2"><i class="fa fa-info-circle fa-4x" style="color: dodgerblue"></i></div>
                <h4 style="font-weight:900 bold" class="sub-title text-center">Détails De La Transaction</h4>
                <div class="email-general">
                  <ul class="mt-5">
                    <li>Description <div class=" pull-right"><input style="border: none;text-align:right; text-transform:capitalize" class="font-primary" id="m_type" value=""></div></li>
                    <li class="mt-3">Expéditeur<div class=" pull-right"><input style="border: none;text-align:right;" class="font-primary" id="m_sender" value=""></div></li>
                    <li class="mt-3">Bénéficiaire <div class=" pull-right"><input style="border: none;text-align:right;" class="font-primary" id="m_receiver" value=""></div></li>
                    <li class="mt-3">Référence <div class=" pull-right"><input style="border: none;text-align:right;" class="font-primary" id="m_reference" value=""></div></li>
                    <li class="mt-3">Devise <div class=" pull-right"><input style="border: none;text-align:right;" class="font-primary" id="m_currency" value=""></div></li>
                    <li class="mt-3">Montant <div class=" pull-right "><input style="border: none;text-align:right;" class="font-primary" id="m_amount" value=""></div></li>
                    <li class="mt-3">Frais <div class=" pull-right"><input style="border: none;text-align:right;" class="font-primary" id="m_fees" value=""></div></li>
                    <li class="mt-3">Date <div class=" pull-right"><input style="border: none;text-align:right;" class="font-primary" id="m_date" value=""></div></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="delete_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="text-center mt-4">
                <h3>Supprimer l'utilisateur</h3>
                <p>Etes-vous sûre de vouloir supprimer?</p>
            </div>
        </div>
        <div class="modal-btn">
            <form action="" method="POST">
                @csrf
                <input type="hidden" name="id" class="e_id" value="">
                <div class="row">
                    <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-secondary" type="submit">Supprimer</button>
                      </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
  @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_amount').val(_this.find('.amount').text());
        });
    </script>

<script>
  $(document).on('click','.details',function()
  {
      var _this = $(this).parents('tr');
      $('#e_id').val(_this.find('.id').text());
      $('#m_amount').val(_this.find('.amount').text());
      $('#m_currency').val(_this.find('.currency').text());
      $('#m_sender').val(_this.find('.sender').text());
      $('#m_receiver').val(_this.find('.receiver').text());
      $('#m_date').val(_this.find('.date').text());
      $('#m_type').val(_this.find('.type').text());
      $('#m_reference').val(_this.find('.reference').text());
      $('#m_fees').val(_this.find('.fees').text());
  });
</script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.ids').text());
            $('.e_avatar').val(_this.find('.image').text());
        });
    </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection