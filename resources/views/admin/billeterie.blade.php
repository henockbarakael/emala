@extends('layouts.billeterie')
@section('content')
{!! Toastr::message() !!}
<!-- Main Wrapper -->
<div class="main-wrapper">

    <div class="account-content">

        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="welcome-box">
                        <div class="welcome-img">
                            <img alt="" src="{{ URL::to('/assets/img/caisse2.jpg') }}">
                        </div>
                        <div class="welcome-det">
                            <h3 style="font-size: 17px; font-weight:400;"><strong>CLOTURE SESSION DE CAISSE</strong></h3>
                            <h4 style="font-size: 14px">Bienvenu dans votre espace de travail emalafintech! </h3>
                            <p style="font-size: 13px"></p>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered review-table mb-0">
                            <thead>
                                <tr class="table-info">
                                    <th colspan="2">Solde théorique</th>
                                    {{-- <th colspan="2">Report à nouveau</th>
                                    <th colspan="2">Fond ajouté</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="soldetcdf"> {{$soldetcdf}} Fc</td>
                                    <td class="text-right">{{$soldetusd}} $</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


            <section class="review-section professional-excellence mt-3">
                {{-- <div class="review-header text-center ">
                    <h3 class="review-title">Professional Excellence</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div> --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr class="table-info">
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
                                        <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase"><b>Solde réel en espèces</b></td>
                                        <td colspan="1" class="text-center"><input type="text" id="resultat_fc" class="form-control" readonly></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Ecart caisse</b></td>
                                        <td colspan="1" class="text-center"><input type="text" id="ecart_fc" class="form-control" readonly></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Montant à reporter</b></td>
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
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr class="table-info">
                                        <th>Unité(USD)</th>
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
                                        <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase"><b>Solde réel en espèces</b></td>
                                        <td colspan="1" class="text-center"><input type="text" id="resultat_usd" class="form-control" readonly ></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Ecart caisse</b></td>
                                        <td colspan="1" class="text-center"><input type="text" id="ecart_usd" class="form-control" readonly ></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"  class="text-left" style="font-size: 14px; text-transform:uppercase;"><b>Montant à reporter</b></td>
                                        <td colspan="1" class="text-center"><input type="text" id="report_usd" class="form-control" readonly></td>
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


                </div>
                <div class="dash-info-list mt-3">
                    <div class="dash-card">
                        <div class="dash-card-container">
                            <div class="dash-card-icon">
                                <i class="fa fa-suitcase"></i>
                            </div>
                            <div class="dash-card-content">
                                <p>Rassurez-vous que toutes les informations reprises ci-haut sont vraies.</p>
                            </div>
                            <div class="dash-card-avatars">
                                <a href="" type="button" class="btn btn-primary btn-lg">Clôturer la caisse</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /Page Content -->
    </div>
</div>
<!-- /Main Wrapper -->
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
        var soldetcdf = document.getElementById("soldetcdf");

        var ca1 = {{$soldetcdf}};

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
            }
    </script>

    <script type="text/javascript">

        var s1 = document.getElementById("s1");
        var s2 = document.getElementById("s2");
        var s3 = document.getElementById("s3");
        var s4 = document.getElementById("s4");
        var s5 = document.getElementById("s5");
        var s6 = document.getElementById("s6");
        var ca2 = {{$soldetusd}};

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
                var res1 = no1 ;
                var res2 = no2 ;
                var res3 = no3;
                var res4 = no4;
                var res5 = no5;
                var res6 = no6;
                var somme_usd = no1 + no2 + no3 + no4 + no5 + no6;
                var ecart_2 = ca2 - somme_usd;
                document.getElementById("resultat_usd").value = somme_usd;
                document.getElementById("mum-out1").value = res1;
                document.getElementById("mum-out2").value = res2;
                document.getElementById("mum-out3").value = res3;
                document.getElementById("mum-out4").value = res4;
                document.getElementById("mum-out5").value = res5;
                document.getElementById("mum-out6").value = res6;

                document.getElementById("ecart_usd").value = ca2 - somme_usd;
            }
    </script>
    @endsection
@endsection
