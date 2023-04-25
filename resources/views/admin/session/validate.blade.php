<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Google font-->
    <link href="{{ asset('dist/css-2?family=Work+Sans:100,200,300,400,500,600,700,800,900')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-3?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-4?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i')}}" rel="stylesheet">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css')}}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
	<script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css')}}">
    <script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}">

    <style type="text/css">
      body{
      text-align: center;
      margin: 0 auto;
      width: 650px;
      font-family: work-Sans, sans-serif;
      background-color: #f6f7fb;
      display: block;
      }
      ul{
      margin:0;
      padding: 0;
      }
      li{
      display: inline-block;
      text-decoration: unset;
      }
      a{
      text-decoration: none;
      }
      p{
      margin: 15px 0;
      }
      h5{
      color:#444;
      text-align:left;
      font-weight:400;
      }
      .text-center{
      text-align: center
      }
      .main-bg-light{
      background-color: #fafafa;
      box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);
      }
      .title{
      color: #444444;
      font-size: 22px;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 10px;
      padding-bottom: 0;
      text-transform: uppercase;
      display: inline-block;
      line-height: 1;
      }
      /* table{
      margin-top:30px
      }
      table.top-0{
      margin-top:0;
      }
      table.order-detail , .order-detail th , .order-detail td {
      border: 1px solid #ddd;
      border-collapse: collapse;
      }
      .order-detail th{
      font-size:16px;
      padding:15px;
      text-align:center;
      }
      .footer-social-icon tr td img{
      margin-left:5px;
      margin-right:5px;
      } */
    </style>
  </head>
  <body style="margin: 20px auto;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" style="padding: 0 30px;background-color: #fff; -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);width: 100%;">
      <tbody>
        <tr>
          <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td><img src="{{ URL::to('assets/images/logo/mpay.png') }}" alt="" style="margin-top: 20px;margin-bottom: 30px;"></td>
                </tr>
                <tr>
                <tr>
                  <td>
                    <h2 class="title" style="text-transform: capitalize">Bonjour {{Auth::user()->firstname}} !</h2>
                  </td>
                </tr>
                <tr>
                  <td>
                    <p>Votre dernière session n'a pas été clôtuée, <br>voudriez-vous continuer avec ou rouvrir une nouvelle session de caisse?</p>
                    <p>Dernière session: {{$last_session}}</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="text-end mt-3">
                        <button type="button" data-bs-toggle="modal" id="#open_cash_register" data-original-title="test" data-bs-target="#open_cash_register" class="btn btn-success btn-block w-100" type="submit">Nouvelle session</button>
                    </div>
                    <div class="text-end mt-3 mb-5">
                        <a href="{{route('session.stay.in')}}" class="btn btn-primary btn-block w-100" type="submit">Continuer</a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

          </td>
        </tr>
      </tbody>
    </table>

    <div class="modal fade" id="open_cash_register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><strong>Ouverture de caisse</strong></h5>
            </div>
            <div class="modal-body">
                <form action="{{route('start.new.session')}}" method="POST">
                    @csrf
                    <input type="hidden" name="wallet_id" id="e_id" value="">
                    {{-- Début Fond de caisse précédent --}}
                    <div class="row g-3">
                      <label  class="col-sm-4 col-form-label input-air-primary">Fond de caisse précédente</label>
                      <div class="col-sm-2">
                        <input disabled style="background-color: white"  class="form-control text-center input-air-primary @error('report_usd_on_o') is-invalid @enderror" name="report_usd_on_o" style="text-align:center;" type="number" value="{{$report_usd_on_c}}" data-bs-original-title="" title="USD">
                        @error('report_usd_on_o')
                              <span class="invalid-feedback" role="alert">
                                <strong>Champs obligatoire</strong>
                            </span>
                        @enderror
                      </div>
                      <label  class="col-sm-2 col-form-label input-air-primary">USD</label>
                      <div class="col-sm-2">
                        <input disabled style="background-color: white" class="form-control text-center input-air-primary @error('report_cdf_on_o') is-invalid @enderror" name="report_cdf_on_o" style="text-align:center;" type="number" value="{{$report_cdf_on_c}}" data-bs-original-title="" title="CDF">
                        @error('report_cdf_on_o')
                              <span class="invalid-feedback" role="alert">
                                <strong>Champs obligatoire</strong>
                            </span>
                        @enderror         
                      </div>
                      <label  class="col-sm-2 col-form-label input-air-primary">CDF</label>
                    </div>
                    {{-- Fin Fond de caisse précédent --}}
                    {{-- Début Fond de caisse actuel --}}
                    <div class="row g-3 mt-3">
                        <label  class="col-sm-4 col-form-label input-air-primary">Nouveau fond de caisse</label>
                        <div class="col-sm-2">
                          <input  class="form-control text-center input-air-primary @error('fund_usd_on_o') is-invalid @enderror" name="fund_usd_on_o" style="text-align:right;" type="number" value="" data-bs-original-title="" title="USD">
                          @error('fund_usd_on_o')
                                <span class="invalid-feedback" role="alert">
                                  <strong>Champs obligatoire</strong>
                              </span>
                          @enderror
                        </div>
                        <label  class="col-sm-2 col-form-label input-air-primary">USD</label>
                        <div class="col-sm-2">
                          <input  class="form-control text-center input-air-primary @error('fund_cdf_on_o') is-invalid @enderror" name="fund_cdf_on_o" style="text-align:right;" type="number" value="" data-bs-original-title="" title="CDF">
                          @error('fund_cdf_on_o')
                                <span class="invalid-feedback" role="alert">
                                  <strong>Champs obligatoire</strong>
                              </span>
                          @enderror         
                        </div>
                        <label  class="col-sm-2 col-form-label input-air-primary">CDF</label>
                    </div>
                    <div class="modal-footer mt-5">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-primary" type="submit">Confirmer</button>
                      </div>
                  </form>
              </div>
              
          </div>
        </div>
      </div>
  </body>
  <!-- Bootstrap js-->
  <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
</html>