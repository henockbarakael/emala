@extends('layouts.master')
@section('content')
@section('title','Gestion de caisse')
@section('page','Clôture de caisse')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="invoice">
                  <div>
                    <div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="media">
                            <div class="media-left"><img class="media-object img-60" src="{{ asset('assets/images/logo/mpay.png')}}" alt=""></div>
                            <div class="media-body m-l-20 text-right">
                              <h4 class="media-heading">Lumumba & Partners</h4>
                              {{-- <p>hello@Cuba.in<br><span>289-335-6503</span></p> --}}
                            </div>
                          </div>
                          <!-- End Info-->
                        </div>
                        <div class="col-sm-6">
                          <div class="text-md-end text-xs-center">
                            <h3>Caisse #<span class="counter">1069</span></h3>
                            <p>Date ouverture: May<span> 27, 2015</span><br>                                                            
                              Date clôture: June <span>27, 2015</span></p>
                          </div>
                          <!-- End Title-->
                        </div>
                      </div>
                    </div>
                    <hr>
                    <!-- End InvoiceTop-->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="media">
                          <div class="media-left"><img class="media-object rounded-circle img-60" src="{{ URL::to('/assets/images/user/'. Auth::user()->avatar) }}" alt=""></div>
                          <div class="media-body m-l-20">
                            <h4 class="media-heading">{{Auth::user()->firstname." ".Auth::user()->lastname}}</h4>
                            <p>{{Auth::user()->phone_number}}<br><span>{{Auth::user()->role_name}}</span></p>
                          </div>
                        </div>
                      </div>
                      {{-- <div class="col-md-6">
                        <div class="text-md-end" id="project">
                          <h6>Project Description</h6>
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                        </div>
                      </div> --}}
                    </div>
                    <!-- End Invoice Mid-->
                      <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered input-air-primary review-table mb-0 table-success">
                                    <thead>
                                        <tr class="table-success">
                                            <th colspan="2">Solde théorique</th>
                                            <th colspan="2">Report à nouveau</th>
                                            <th colspan="2">Fond ajouté</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$solde_theorique_cdf}} Fc</td>
                                            <td class="text-right">{{$solde_theorique_usd}} $</td>
                                            <td> Fc</td>
                                            <td class="text-right"> $</td>
                                            <td> Fc</td>
                                            <td class="text-right"> $</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
        
                    </div>
                    <div class="row">
                      <div class="col-md-6 table-responsive invoice-table" id="table">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr class=" table-primary">
                                <th>Unité</th>
                                <th>Nombre</th>
                                <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                                <td>50,00 Fc</td>
                                <td><input id="n1" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out1"></td>
                            </tr>
                            <tr>
                                <td>100,00 Fc</td>
                                <td><input id="n2" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out2"></td>
                            </tr>
                            <tr>
                                <td>200,00 Fc</td>
                                <td><input id="n3" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out3"></td>
                            </tr>
                            <tr>
                                <td>500,00 Fc</td>
                                <td><input id="n4" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out4"></td>
                            </tr>
                            <tr>
                                <td>1000,00 Fc</td>
                                <td><input id="n5" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out5"></td>
                            </tr>
                            <tr>
                                <td>5000,00 Fc</td>
                                <td><input id="n6" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out6"></td>
                            </tr>
                            <tr>
                                <td>10000,00 Fc</td>
                                <td><input id="n7" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out7"></td>
                            </tr>
                            <tr>
                                <td>20000,00 Fc</td>
                                <td><input id="n8" oninput="add_number()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" class="form-control input-air-primary" disabled id="sum-out8"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase"><label><b>Solde réel en espèces</b></label></td>
                                <td colspan="1" class="text-center"><input  type="text" id="resultat_fc" class="form-control input-air-primary" disabled></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><label><b>Ecart caisse</b></label></td>
                                <td colspan="1" class="text-center"><input type="text" id="ecart_fc" class="form-control input-air-primary" disabled></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><label><b>Transfert en banque</b></label></td>
                                <td colspan="1" class="text-center"><input id="transfert_fc" oninput="add_number()" type="text"  class="form-control input-air-primary"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><label><b>Montant à reporter</b></label></td>
                                <td colspan="1" class="text-center"><input type="text" id="report_fc" class="form-control input-air-primary" disabled></td>
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

                      <div class="col-md-6  table-responsive invoice-table" id="table">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr class="table-danger">
                                <th>Unité</th>
                                <th>Nombre</th>
                                <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                                <td>1,00 $</td>
                                <td><input id="s1" oninput="add()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" id="mum-out1" class="form-control input-air-primary" disabled value=""></td>
                            </tr>
                            <tr>
                                <td>5,00 $</td>
                                <td><input id="s2" oninput="add()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" id="mum-out2" class="form-control input-air-primary" disabled value=""></td>
                            </tr>
                            <tr>
                                <td>10,00 $</td>
                                <td><input id="s3" oninput="add()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" id="mum-out3" class="form-control input-air-primary" disabled value=""></td>
                            </tr>
                            <tr>
                                <td>20,00 $</td>
                                <td><input id="s4" oninput="add()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" id="mum-out4" class="form-control input-air-primary" disabled value=""></td>
                            </tr>
                            <tr>
                                <td>50,00 $</td>
                                <td><input id="s5" oninput="add()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" id="mum-out5" class="form-control input-air-primary" disabled value=""></td>
                            </tr>
                            <tr>
                                <td>100,00 $</td>
                                <td><input id="s6" oninput="add()" type="text" class="form-control input-air-primary" ></td>
                                <td><input type="text" id="mum-out6" class="form-control input-air-primary" disabled value=""></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase"><b>Solde réel en espèces</b></td>
                                <td colspan="1" class="text-center"><input type="text" id="resultat_usd" class="form-control input-air-primary" disabled ></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Ecart caisse</b></td>
                                <td colspan="1" class="text-center"><input type="text" id="ecart_usd" class="form-control input-air-primary" disabled ></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Transfert en banque</b></td>
                                <td colspan="1" class="text-center"><input id="transfert_usd" oninput="add()" type="text"  class="form-control input-air-primary"></td>
                            </tr>
                            <tr>
                                <td colspan="2"  class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Montant à reporter</b></td>
                                <td colspan="1" class="text-center"><input type="text" id="report_usd" class="form-control input-air-primary" disabled></td>
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
                      <!-- End Table-->
                      <div class="row">
                        <div class="col-md-12">
                          <div>
                            <p class="legal"><strong>Attention!</strong>  Le solde théorique et le solde réel doivent être similaire. En cas d'ecart, il est impératif de le constater et d’en élucider les raisons.</p>
                          </div>
                        </div>
                        {{-- <div class="col-md-4">
                          <form class="text-end">
                            <input type="image" src="../assets/images/other-images/paypal.png" name="submit" alt="PayPal - The safer, easier way to pay online!">
                          </form>
                        </div> --}}
                      </div>
                    </div>
                    <!-- End InvoiceBot-->
                  </div>
                  <div class="col-sm-12 text-center mt-3">
                    <button class="btn btn btn-primary me-2" type="button" onclick="myFunction()">Clôturer ma caisse</button>
                    {{-- <button class="btn btn-secondary" type="button">Cancel</button> --}}
                  </div>
                  <!-- End Invoice-->
                  <!-- End Invoice Holder-->
                  <!-- Container-fluid Ends-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Container-fluid Ends-->
  </div>
  @section('script')
  <script type="text/javascript">
    var n1 = document.getElementById("n1");
    var n2 = document.getElementById("n2");
    var n3 = document.getElementById("n3");
    var n4 = document.getElementById("n4");
    var n5 = document.getElementById("n5");
    var n6 = document.getElementById("n6");
    var n7 = document.getElementById("n7");
    var n8 = document.getElementById("n8");
    var ca1 = "<?php echo $solde_theorique_cdf ?>";
    var s1 = document.getElementById("s1");
    var s2 = document.getElementById("s2");
    var s3 = document.getElementById("s3");
    var s4 = document.getElementById("s4");
    var s5 = document.getElementById("s5");
    var s6 = document.getElementById("s6");
    var ca2 = "<?php echo $solde_theorique_usd ?>";

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

            var x = parseFloat(ca1);
            if (isNaN(ca1)) ca1 = 0;


            var result1 = num1 ;
            var result2 = num2 ;
            var result3 = num3;
            var result4 = num4;
            var result5 = num5;
            var result6 = num6;
            var result7 = num7;
            var result8 = num8;
            var somme_fc = num1 + num2 + num3 + num4 + num5 + num6 + num7 + num8;
            var ecart_1 = somme_fc - x;
            var report_1 = x + ecart_1 ;
            document.getElementById("resultat_fc").value = somme_fc;
            document.getElementById("sum-out1").value = result1;
            document.getElementById("sum-out2").value = result2;
            document.getElementById("sum-out3").value = result3;
            document.getElementById("sum-out4").value = result4;
            document.getElementById("sum-out5").value = result5;
            document.getElementById("sum-out6").value = result6;
            document.getElementById("sum-out7").value = result7;
            document.getElementById("sum-out8").value = result8;
            document.getElementById("ecart_fc").value = ecart_1;
            document.getElementById("report_fc").value = report_1;
        }


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
            var y= parseFloat(ca2);
            if (isNaN(ca2)) ca2 = 0;
            var res1 = no1 ;
            var res2 = no2 ;
            var res3 = no3;
            var res4 = no4;
            var res5 = no5;
            var res6 = no6;
            var somme_usd = no1 + no2 + no3 + no4 + no5 + no6;
            var ecart_2 = somme_usd - y;
            var report_2 = y + ecart_2 ;
            document.getElementById("resultat_usd").value = somme_usd;
            document.getElementById("mum-out1").value = res1;
            document.getElementById("mum-out2").value = res2;
            document.getElementById("mum-out3").value = res3;
            document.getElementById("mum-out4").value = res4;
            document.getElementById("mum-out5").value = res5;
            document.getElementById("mum-out6").value = res6;
            document.getElementById("ecart_usd").value = ecart_2;
            document.getElementById("report_usd").value = report_2;
        }

</script>
<script>
    function ajaxReq(){
        var url = '';
        var redirect = '';
        var csrf_token = '{{csrf_token()}}';
        var solde_reel_fc =  document.getElementById("resultat_fc").value;
        var ecart_fc =  document.getElementById("ecart_fc").value;
        var report_fc = document.getElementById("report_fc").value;
        var solde_reel_usd =  document.getElementById("resultat_usd").value;
        var ecart_usd =  document.getElementById("ecart_usd").value;
        var report_usd = document.getElementById("report_usd").value;
        var formdata = new FormData();
        formdata.append("solde_reel_fc", solde_reel_fc);
        formdata.append("ecart_fc", ecart_fc);
        formdata.append("report_fc", report_fc);
        formdata.append("solde_reel_usd", solde_reel_usd);
        formdata.append("ecart_usd", ecart_usd);
        formdata.append("report_usd", report_usd);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:url,
            data:formdata,
            processData: false,
            contentType: false,
            // success: function(data) {
            //     console.log('success: '+data);
            // }

            success:function(data){

                console.log(data);
                // $('.alert-success').html(data.success).fadeIn('slow');
                // $('.alert-success').delay(3000).fadeOut('slow');
                //$('#menu_table_data').html(data);
                window.location = 'http://127.0.0.1:8000/caissier/thanks';
                //location.reload();
            },
            error: function(response) {
            }
        });

    }

    $('#btn_ajax').on('click', ajaxReq);

</script>
  @endsection
@endsection