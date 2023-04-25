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

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>GESTION DE CAISSE</h5><span>Session de caisse du {{$todayDate}}</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if ($authorization['success'] == false)
                                @if ($last_solde == null)
                                <div class="col-xl-6 col-sm-6 box-col-6">
                                    <div class="card ecommerce-widget">
                                    <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                                        <div class="row">
                                        <div class="col-12"><span class="text-white">FOND DE CAISSE</span>
                                            <ul >
                                                <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$last_solde_ouverture_cdf}}</span></li>
                                                <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$last_solde_ouverture_usd}}</span></li>
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
                                        <div class="col-12"><span class="text-white" style="text-transform: uppercase">fonds de caisse précédent</span>
                                            <ul >
                                                <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$last_solde_ouverture_cdf}}</span></li>
                                                <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$last_solde_ouverture_usd}}</span></li>
                                                <li style="font-size: 14px;" class="text-dark">Dernière clôture:<span class="texte-white ms-2">{{$created_at}}</span></li>
                                            </ul>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                @endif
                                @elseif ($authorization['success'] == true)
                                <div class="col-xl-6 col-sm-6 box-col-6">
                                    <div class="card ecommerce-widget">
                                    <div class="card-body shadow shadow-showcase support-ticket-font" style="background-color: #0990ff">
                                        <div class="row">
                                        <div class="col-12"><span class="text-white">SOLDE OUVERTURE</span>
                                            <ul >
                                                <li style="font-size: 14px; font-weight: bold">CDF<span class="ms-2 text-dark">{{$openingSoldeFc}}</span></li>
                                                <li style="font-size: 14px; font-weight: bold">USD<span class="ms-2 text-dark">{{$openingSoldeUs}}</span></li>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow shadow-showcase">
                        <div class="card-body">
                            <form action="#">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12 col-sm-12 box-col-12">
                                        <div class="card ecommerce-widget shadow shadow-showcase">
                                            <div class="card-body support-ticket-font">
                                                <div class="row">
                                                <div class="col-5"><span>Solde Théorique</span>
                                                    <h3 class="total-num counter" style="font-size: 12px">(Solde à l'ouverture + Credit) - Debit</h3>
                                                </div>
                                                <div class="col-7">
                                                    <div class="text-end">
                                                    <ul>
                                                        <li>CDF<span class="product-stts txt-danger ms-2">{{$solde_theorique_cdf}}<i class="icon-angle-up f-12 ms-1"></i></span></li>
                                                        <li>USD<span class="product-stts txt-danger ms-2">{{$solde_theorique_usd}}<i class="icon-angle-up f-12 ms-1"></i></span></li>
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
                                    <div class="col-md-6">
                                        <div class="table-responsive shadow shadow-showcase">
                                            <table class="table table-bordered review-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Unité(Fc)</th>
                                                        <th>Nombre</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>50,00</td>
                                                        <td><input id="n1" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>100,00</td>
                                                        <td><input id="n2" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out2"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>200,00</td>
                                                        <td><input id="n3" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>500,00</td>
                                                        <td><input id="n4" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out4"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>1000,00</td>
                                                        <td><input id="n5" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out5"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>5000,00</td>
                                                        <td><input id="n6" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out6"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>10000,00</td>
                                                        <td><input id="n7" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out7"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>20000,00</td>
                                                        <td><input id="n8" oninput="add_number()" type="text" class="form-control" ></td>
                                                        <td><input type="text" class="form-control" readonly id="sum-out8"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Solde réel en espèces</b></td>
                                                        <td colspan="1" class="text-center"><input type="text" id="resultat_fc" class="form-control" readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Ecart caisse</b></td>
                                                        <td colspan="1" class="text-center"><input type="text" id="ecart_fc" class="form-control" readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Transfert en banque</b></td>
                                                        <td colspan="1" class="text-center"><input id="transfert_fc" oninput="add_number()" type="text" value="0" class="form-control"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Montant à reporter</b></td>
                                                        <td colspan="1" class="text-center"><input type="text" id="report_fc" class="form-control" readonly></td>
                                                    </tr>
                                                    

                                                    {{-- <tr>
                                                        <td colspan="3" class="text-center">
                                                            <div class="grade-span">
                                                                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#add_promotion"><i class="fa fa-plus"></i> Valider</a>
                                                            </div>
                                                        </td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive shadow shadow-showcase">
                                            <table class="table table-bordered review-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Unité($)</th>
                                                        <th>Nombre</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1,00</td>
                                                        <td><input id="s1" oninput="add()" type="text" class="form-control" ></td>
                                                        <td><input type="text" id="mum-out1" class="form-control" readonly value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>5,00</td>
                                                        <td><input id="s2" oninput="add()" type="text" class="form-control" ></td>
                                                        <td><input type="text" id="mum-out2" class="form-control" readonly value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>10,00</td>
                                                        <td><input id="s3" oninput="add()" type="text" class="form-control" ></td>
                                                        <td><input type="text" id="mum-out3" class="form-control" readonly value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>20,00</td>
                                                        <td><input id="s4" oninput="add()" type="text" class="form-control" ></td>
                                                        <td><input type="text" id="mum-out4" class="form-control" readonly value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>50,00</td>
                                                        <td><input id="s5" oninput="add()" type="text" class="form-control" ></td>
                                                        <td><input type="text" id="mum-out5" class="form-control" readonly value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td>100,00</td>
                                                        <td><input id="s6" oninput="add()" type="text" class="form-control" ></td>
                                                        <td><input type="text" id="mum-out6" class="form-control" readonly value=""></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Solde réel en espèces</b></td>
                                                        <td colspan="1" class="text-center"><input type="text" id="resultat_usd" class="form-control" readonly ></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Ecart caisse</b></td>
                                                        <td colspan="1" class="text-center"><input type="text" id="ecart_usd" class="form-control" readonly ></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-left" style="font-size: 14px;"><b>Transfert en banque</b></td>
                                                        <td colspan="1" class="text-center"><input id="transfert_usd" oninput="add()" type="text" value="0"  class="form-control"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"  class="text-left" style="font-size: 14px;"><b>Montant à reporter</b></td>
                                                        <td colspan="1" class="text-center"><input hidden type="text" name="IDdernierSoldeFc" class="form-control" value="{{$IDdernierSoldeFc}}">
                                                            <input hidden type="text" name="IDdernierSoldeUs" class="form-control" value="{{$IDdernierSoldeUs}}"><input type="text" id="report_usd" class="form-control" readonly></td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td colspan="3" class="text-center">
                                                            <div class="grade-span">
                                                                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#add_promotion"><i class="fa fa-plus"></i> Valider</a>
                                                            </div>
                                                        </td>
                                                    </tr> --}}
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <button type="submit"  class="btn btn-danger confirmCloture shadow shadow-showcase mt-4">CLÔTURER LA CAISSE</button>

                                    </div>
                                </div>
                            </form>
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
            <form action="{{route('admin.fondcaisse.ouverture')}}" method="POST">
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

    <script type="text/javascript">
    $(".confirmCloture").click(function(event){
        event.preventDefault();
        var soldeFC = document.getElementById("resultat_fc").value;
        var ecartFC = document.getElementById("ecart_fc").value;
        var banqueFC = document.getElementById("transfert_fc").value;
        var reportFC = document.getElementById("report_fc").value;
        var soldeUSD = document.getElementById("resultat_usd").value;
        var ecartUSD = document.getElementById("ecart_usd").value;
        var banqueUSD = document.getElementById("transfert_usd").value;
        var reportUSD = document.getElementById("report_usd").value;
        var IDdernierSoldeFc = $("input[name=IDdernierSoldeFc]").val();
        var IDdernierSoldeUs = $("input[name=IDdernierSoldeUs]").val();
        var url = 'cloture-caisse';
        var redirect = 'ouverture-caisse';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            type:"POST",
            data:{
              soldeFC:soldeFC,
              ecartFC:ecartFC,
              banqueFC:banqueFC,
              reportFC:reportFC,
              soldeUSD:soldeUSD,
              ecartUSD:ecartUSD,
              banqueUSD:banqueUSD,
              reportUSD:reportUSD,
              IDdernierSoldeFc:IDdernierSoldeFc,
              IDdernierSoldeUs:IDdernierSoldeUs,
            },
            success:function(response){
                if(response.status == true) {
                    toastr.success(response.message, 'Parfait!');
                    setTimeout(() => {
                     window.location = redirect;
                },3000)
              }
              else if(response.status == false) {
                toastr.error(response.message, 'Erreur!');
                setTimeout(() => {
                    window.location.href = redirect;     
                },3000);
                
              }
            }
        });
    });
    </script>

    <script type="text/javascript">

        var n1 = document.getElementById("n1");
        var n2 = document.getElementById("n2");
        var n3 = document.getElementById("n3");
        var n4 = document.getElementById("n4");
        var n5 = document.getElementById("n5");
        var n6 = document.getElementById("n6");
        var n7 = document.getElementById("n7");
        var n8 = document.getElementById("n8");
        var tr_fc = document.getElementById("transfert_fc");

        var ca1 = {{$solde_theorique_cdf}};

            function add_number() {
                var num1 = parseFloat(n1.value*50);
                if (isNaN(num1)) num1 = 0;
                var num2 = parseFloat(n2.value*100);
                if (isNaN(num2)) num2 = 0;
                var num3= parseFloat(n3.value*200);
                if (isNaN(num3)) num3 = 0;
                var num4 = parseFloat(n4.value*500);
                if (isNaN(num4)) num4 = 0;
                var num5 = parseFloat(n5.value*1000);
                if (isNaN(num5)) num5 = 0;
                var num6 = parseFloat(n6.value*5000);
                if (isNaN(num6)) num6 = 0;
                var num7 = parseFloat(n7.value*10000);
                if (isNaN(num7)) num7 = 0;
                var num8 = parseFloat(n8.value*20000);
                if (isNaN(num8)) num8 = 0;

                var trxfc = parseFloat(tr_fc.value*1);
                if (isNaN(trxfc)) trxfc = 0;

                var result1 = num1 ;
                var result2 = num2 ;
                var result3 = num3;
                var result4 = num4;
                var result5 = num5;
                var result6 = num6;
                var result7 = num7;
                var result8 = num8;
                var somme_fc = num1 + num2 + num3 + num4 + num5 + num6 + num7 + num8;
                var ecart_1 = ca1 - somme_fc;
                var report_1 = somme_fc - trxfc;
                document.getElementById("sum-out1").value = result1;
                document.getElementById("sum-out2").value = result2;
                document.getElementById("sum-out3").value = result3;
                document.getElementById("sum-out4").value = result4;
                document.getElementById("sum-out5").value = result5;
                document.getElementById("sum-out6").value = result6;
                document.getElementById("sum-out7").value = result7;
                document.getElementById("sum-out8").value = result8;
                document.getElementById("resultat_fc").value = somme_fc;
                document.getElementById("ecart_fc").value = ecart_1;
                document.getElementById("report_fc").value = report_1;
            }
    </script>

    <script type="text/javascript">

        var s1 = document.getElementById("s1");
        var s2 = document.getElementById("s2");
        var s3 = document.getElementById("s3");
        var s4 = document.getElementById("s4");
        var s5 = document.getElementById("s5");
        var s6 = document.getElementById("s6");
        var tr_usd = document.getElementById("transfert_usd");
        var ca2 = {{$solde_theorique_usd}};

            function add() {
                var no1 = parseFloat(s1.value*1);
                if (isNaN(no1)) no1 = 0;
                var no2 = parseFloat(s2.value*5);
                if (isNaN(no2)) no2 = 0;
                var no3= parseFloat(s3.value*10);
                if (isNaN(no3)) no3 = 0;
                var no4 = parseFloat(s4.value*20);
                if (isNaN(no4)) no4 = 0;
                var no5 = parseFloat(s5.value*50);
                if (isNaN(no5)) no5 = 0;
                var no6 = parseFloat(s6.value*100);
                if (isNaN(no6)) no6 = 0;
                var trxusd = parseFloat(tr_usd.value*1);
                if (isNaN(trxusd)) trxusd = 0;
                var res1 = no1 ;
                var res2 = no2 ;
                var res3 = no3;
                var res4 = no4;
                var res5 = no5;
                var res6 = no6;
                var somme_usd = no1 + no2 + no3 + no4 + no5 + no6;
                var ecart_2 = ca2 - somme_usd;
                var report_2 = somme_usd - trxusd;
                document.getElementById("resultat_usd").value = somme_usd;
                document.getElementById("mum-out1").value = res1;
                document.getElementById("mum-out2").value = res2;
                document.getElementById("mum-out3").value = res3;
                document.getElementById("mum-out4").value = res4;
                document.getElementById("mum-out5").value = res5;
                document.getElementById("mum-out6").value = res6;

                document.getElementById("ecart_usd").value = ca2 - somme_usd;
                document.getElementById("report_usd").value = report_2;
            }
    </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection