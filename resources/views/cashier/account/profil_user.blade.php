@extends('layouts.master')
@section('content')
@section('title','Compte Utilisateur')
@section('page','Compte Caissier')
{!! Toastr::message() !!}

    <div class="page-body">
        @include('layouts.page-title')
        @include('sweetalert::alert')
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="user-profile">
                <div class="row">
                  <!-- user profile first-style start-->
                  <div class="col-sm-12">
                    <div class="card hovercard text-center">
                      <div class="cardheader"></div>
                      <div class="user-image">
                        <div class="avatar"><img alt="" src="https://frontend.emalafintech.net/assets/profil/{{$avatar}}" width="86px" height="86px"></div>
                        {{-- <div class="icon-wrapper"><i class="icofont icofont-pencil-alt-5"></i></div> --}}
                      </div>
                      <div class="info">
                        <div class="row">
                          <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="ttl-info text-start">
                                  <h6><i class="fa fa-envelope"></i>   Email</h6><span>{{$email}}</span>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="ttl-info text-start">
                                  <h6><i class="fa fa-calendar"></i>   Anniversaire</h6><span></span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
                            <div class="user-designation">
                              <div class="title"><a target="_blank" href="">{{$firstname." ".$lastname}}</a></div>
                              <div class="desc">CAISSIER</div>
                            </div>
                          </div>
                          <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="ttl-info text-start">
                                  <h6><i class="fa fa-phone"></i>   Téléphone</h6><span>{{$phone_number}}</span>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="ttl-info text-start">
                                  <h6><i class="fa fa-location-arrow"></i>   Adresse</h6><span>{{$city}}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <div class="follow">
                          <div class="row">
                            <div class="col-6 text-md-end border-right">
                                <span>COMPTE CDF</span><div class="follow-num counter"> {{$acnumber_1}}</div><span>BALANCE ACTUELLE</span>
                              <h6>{{$account_1}}</h6>
                            </div>
                            <div class="col-6 text-md-start">
                                <span>COMPTE USD</span><div class="follow-num counter"> {{$acnumber_2}}</div><span>BALANCE ACTUELLE</span>
                              <h6>{{$account_2}}</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="display" id="orderingById">
                                <thead>
                                  <tr>
                                    <th>Date transaction</th>
                                    <th hidden>Id</th>
                                    <th class="text-left">Expéditeur</th>
                                    <th class="text-left">Montant</th>
                                    
                                    <th class="text-left">Devise</th>
                                    {{-- <th class="text-left">Frais</th> --}}
                                    <th class="text-left">Bénéficiaire</th>
                                    <th class="text-center">Reference</th>
                                    {{-- <th class="text-center">Agence</th> --}}
                                    <th class="text-center">Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach ($transaction as $key => $value)
                                @php
                                    if($value->currency_id == 1){
                                      $currency = "CDF";
                                    }
                                    elseif($value->currency_id == 2){
                                      $currency = "USD";
                                    }
                                @endphp
                                  <tr>
                                    <td class="level text-center">{{$value->created_at}}</td>
                                    <td hidden class="id">{{$value->id}}</td>
                                    <td class="type text-center"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->sender_phone)) }}">{{$value->sender_phone}}</a></td>
                                    <td class="balance_cdf text-center">{{$value->amount}}</td>
                                    <td class="balance_usd text-center">{{$currency}}</td>
                                    {{-- <td class="balance_usd text-center">{{$value->fees}}</td> --}}
                                    <td class="type text-center"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->receiver_phone)) }}">{{$value->receiver_phone}}</a></td>
                                    <td class="level text-center">{{$value->reference}}</td>
                                    {{-- <td class="level text-center">{{$value->btownship}}</td> --}}
                                    <td class="level text-center">{{$value->status}}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th>Date transaction</th>
                                    <th hidden>Id</th>
                                    <th class="text-left">Expéditeur</th>
                                    <th class="text-left">Montant</th>
                                    
                                    <th class="text-left">Devise</th>
                                    {{-- <th class="text-left">Frais</th> --}}
                                    <th class="text-left">Bénéficiaire</th>
                                    <th class="text-center">Reference</th>
                                    {{-- <th class="text-center">Agence</th> --}}
                                    <th class="text-center">Status</th>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <div class="modal fade" id="cash_register" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-warning text-warning ml-4"></i> Attention!!!</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Votre caisse est fermée! Veuillez ouvrir votre caisse avant d'effectuer une opération liée à celle-ci, merci!</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>
@endsection