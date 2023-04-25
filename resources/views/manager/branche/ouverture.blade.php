@extends('layouts.master')
@section('content')
@section('title','Ouverture-Clôture de caisse')
@section('page','Ouverture-Clôture de caisse')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')
      <!-- Container-fluid starts-->
      <div class="container-fluid">
        <div class="feature-products mb-4">
            <div class="row">
                <div class="col-md-12 text-end pull-right">
                    <div class="select2-drpdwn-product select-options d-inline-block">
                        @if ($authorization['success'] == false)
                        <a href="" class="btn btn-success shadow shadow-showcase" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#open">OUVRIR CAISSE</a>
                        @else
                        <a href="{{route('manager.cloture_caisse')}}" class="btn btn-danger shadow shadow-showcase" type="button">CLÔTURER CAISSE</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @php
            
            // if($dernierSoldeFc == null && $dernierSoldeUs == null){
            //     $dernierSoldeFc = 0;
            //     $dernierSoldeUs = 0;
            //     $dateCloture ="-";
            // }
            // else {
            //     // dd($dernierSoldeFc->created_at);
            //     $dernierSoldeFc = $dernierSoldeFc;
            //     $dernierSoldeUs = $dernierSoldeUs;
            //     $dateCloture = $dernierSoldeFc->created_at;
            // }
            @endphp
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>GESTION DE CAISSE</h5><span>Session de caisse du {{$todayDate}}</span>
              </div>
              <div class="card-body">
                <div class="row">
                  {{-- False == Fermée --}}
                    @if ($authorization['success'] == false)
                      <div class="col-xl-6 col-sm-6 box-col-6">
                        <div class="card ecommerce-widget">
                          <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                            <div class="row">
                              <div class="col-12"><span class="text-white" style="text-transform: uppercase">fonds de caisse précédent</span>
                                <ul >
                                    <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$dernierSoldeFc}}</span></li>
                                    <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$dernierSoldeUs}}</span></li>
                                    <li style="font-size: 14px;" class="text-dark">Dernière clôture:<span class="texte-white ms-2">{{$dateCloture}}</span></li>
                                  </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      {{-- @if ($last_solde == null)
                      <div class="col-xl-6 col-sm-6 box-col-6">
                        <div class="card ecommerce-widget">
                          <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                            <div class="row">
                              <div class="col-12"><span class="text-white">FOND DE CAISSE</span>
                                <ul >
                                    <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$dernierSoldeFc}}</span></li>
                                    <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$dernierSoldeUs}}</span></li>
                                    <li style="font-size: 14px;" class="text-dark">Dernière clôture:<span class="texte-white ms-2">-</span></li>
                                  </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      @else
                      <div class="col-xl-6 col-sm-6 box-col-6">
                        <div class="card ecommerce-widget">
                          <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                            <div class="row">
                              <div class="col-12"><span class="text-white">DERNIERE CLOTURE</span>
                                <ul >
                                    <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$dernierSoldeFc}}</span></li>
                                    <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$dernierSoldeUs}}</span></li>
                                    <li style="font-size: 14px;" class="text-dark">Dernière clôture:<span class="texte-white ms-2">{{$dateCloture}}</span></li>
                                  </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div> --}}
                      {{-- @endif --}}
                    @elseif ($authorization['success'] == true)
                      <div class="col-xl-6 col-sm-6 box-col-6">
                        <div class="card ecommerce-widget">
                          <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                            <div class="row">
                              <div class="col-12"><span class="text-white">SOLDE OUVERTURE</span>
                                <ul >
                                    <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$dernierSoldeFc}}</span></li>
                                    <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$dernierSoldeUs}}</span></li>
                                    <li style="font-size: 14px;" class="text-dark">Date d'ouverture:<span class="texte-white ms-2">{{$dateOuverture}}</span></li>
                                  </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  
                  <div class="col-xl-6 col-sm-6 box-col-6">
                    <div class="card ecommerce-widget">
                      <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                        <div class="row">
                          <div class="col-5"><span class="text-white">E/S CAISSE</span>
                            <h3 class="total-num counter">Nbre. Trx. {{$total_trx}}</h3>
                          </div>
                          <div class="col-7">
                            <div class="text-end">
                              <ul>
                                <li style="font-size: 14px; font-weight: bold">{{$credit_cdf}} CDF <span class="product-stts text-warning ms-2"><i class="icon-angle-down f-12 ms-1"></i></span></li>
                                <li style="font-size: 14px; font-weight: bold">{{$credit_usd}} USD <span class="product-stts text-warning ms-2"><i class="icon-angle-down f-12 ms-1"></i></span></li>
                                {{-- <li></li> --}}
                                <li style="font-size: 14px; font-weight: bold">{{$debit_cdf}} CDF <span class="product-stts text-white ms-2"><i class="icon-angle-up f-12 ms-1"></i></span></li>
                                <li style="font-size: 14px; font-weight: bold">{{$debit_usd}} USD <span class="product-stts text-white ms-2"><i class="icon-angle-up f-12 ms-1"></i></span></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-6 col-sm-6 box-col-6">
                    <div class="card ecommerce-widget shadow shadow-showcase">
                      <div class="card-body support-ticket-font">
                        <div class="row">
                          <div class="col-5"><span>Balance actuelle</span>
                            <h3 class="total-num counter"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png')}}" alt="" width="60px" height="60px"></h3>
                          </div>
                          <div class="col-7">
                            <div class="text-end">
                              <ul>
                                <li>CDF<span class="product-stts txt-success ms-2">{{$agence_cdf}}<i class="icon-angle-down f-12 ms-1"></i></span></li>
                                <li>USD<span class="product-stts txt-success ms-2">{{$agence_usd}}<i class="icon-angle-down f-12 ms-1"></i></span></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-sm-6 box-col-6">
                    <div class="card ecommerce-widget shadow shadow-showcase">
                      <div class="card-body support-ticket-font">
                        <div class="row">
                          <div class="col-5"><span>Revenue</span>
                            <h3 class="total-num counter"><img class="img-fluid" src="{{ asset('assets/images/logo/logo.png')}}" alt="" width="60px" height="60px"></h3>
                          </div>
                          <div class="col-7">
                            <div class="text-end">
                              <ul>
                                <li>CDF<span class="product-stts txt-success ms-2">{{$fees_cdf}}<i class="icon-angle-down f-12 ms-1"></i></span></li>
                                <li>USD<span class="product-stts txt-success ms-2">{{$fees_usd}}<i class="icon-angle-down f-12 ms-1"></i></span></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-6 col-sm-6 box-col-6">
                    <div class="card ecommerce-widget shadow shadow-showcase">
                      <div class="card-body support-ticket-font">
                        <div class="row">
                          <div class="col-5"><span>Entrée</span>
                            <h3 class="total-num counter">{{$total_credit}}</h3>
                          </div>
                          <div class="col-7">
                            <div class="text-end">
                              <ul>
                                <li>CDF<span class="product-stts txt-success ms-2">{{$credit_cdf}}<i class="icon-angle-down f-12 ms-1"></i></span></li>
                                <li>USD<span class="product-stts txt-success ms-2">{{$credit_usd}}<i class="icon-angle-down f-12 ms-1"></i></span></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <div class="progress-showcase">
                          <div class="progress sm-progress-bar">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-sm-6 box-col-6">
                    <div class="card ecommerce-widget shadow shadow-showcase">
                      <div class="card-body support-ticket-font">
                        <div class="row">
                          <div class="col-5"><span>Sortie</span>
                            <h3 class="total-num counter">{{$total_debit}}</h3>
                          </div>
                          <div class="col-7">
                            <div class="text-end">
                              <ul>
                                <li>CDF<span class="product-stts txt-danger ms-2">{{$debit_cdf}}<i class="icon-angle-up f-12 ms-1"></i></span></li>
                                <li>USD<span class="product-stts txt-danger ms-2">{{$debit_usd}}<i class="icon-angle-up f-12 ms-1"></i></span></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <div class="progress-showcase">
                          <div class="progress sm-progress-bar">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <div class="mt-3 mb-4">
                    <h5>Liste Des Transactions</h5><span>Cette liste affiche toutes <code>les transactions(opérations) journalières</code> effectuées par la caissière.</span>
                  </div>
                    <table class="display shadow shadow-showcase table-bordered" id="basic-8">
                      <thead class="table-primary">
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
                      @endphp
                        <tr>
                          <td class="date text-left">{{$value->created_at}}</td>
                          <td hidden class="id">{{$value->id}}</td>
                          <td class="sender text-left"><a href="{{ url('manager/compte-client/'.Crypt::encrypt($value->id)) }}">{{$value->sender_phone}}</a></td>
                          <td class="amount text-left">{{$value->amount}}</td>
                          
                          <td hidden class="fees text-center">{{$value->fees}}</td>
                          <td hidden class="reference text-center">{{$value->reference}}</td>
                          <td class="currency text-left">{{$currency}}</td>
                          <td class="receiver text-left"><a href="{{ url('manager/compte-client/'.Crypt::encrypt($value->id)) }}">{{$value->receiver_phone}}</a></td>
                          <td hidden class="action text-center"><span style="text-transform: capitalize">{{$value->action}}</span></td>
                          <td class="type text-left"><span style="text-transform: capitalize">{{$value->type}}</span></td> 
                          <td class="text-center">
                            <a href="#" class="btn btn-primary btn-xs details" title="Détails" data-bs-toggle="modal" data-original-title="test" data-bs-target="#details">Détails</a>
                        </td>
                          {{-- <td class="level text-center">{{$value->status}}</td> --}}
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
        </div>
      </div>
      <!-- Container-fluid Ends-->
  </div>
  <div class="modal fade" id="open" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">OUVERTURE DE CAISSE {{$todayDate}}</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('manager.fondcaisse.ouverture')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="e_id" value="">
                
                <div class="row g-3">
                    <div class="card-header">
                        <h5>ETAT DE CAISSE</h5>
                        <span>
                            Enregistrez ici la somme actuelle en franc congolais et dollar américain présente en caisse
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
                    <input readonly class="form-control input-air-primary @error('last_solde_cdf') is-invalid @enderror" name="last_solde_cdf" id="e_last_solde_cdf" type="tel" value="{{$account_cdf->balance}}" >
                    @error('last_solde_cdf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6" style="margin-top: -10px">
                    <label class="form-label" for="validationCustom01"></label>
                    <input readonly class="form-control input-air-primary @error('last_solde_usd') is-invalid @enderror" name="last_solde_usd" id="e_last_solde_usd" type="tel" value="{{$account_usd->balance}}" >
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
                <div class="text-center mb-2"><i class="fa fa-info-circle fa-2x" style="color: dodgerblue"></i></div>
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
  @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.assignUser',function()
        {
            var _this = $(this).parents('tr');
            $('#brancheID').val(_this.find('.id').text());
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
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection