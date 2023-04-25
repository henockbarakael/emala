@extends('layouts.caissier')
@section('content')

	<!-- Sidebar -->
    @include('layouts.ca_sidebar')
	<!-- /Sidebar -->
    {!! Toastr::message() !!}
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="welcome-box">
                        <div class="welcome-img">
                            <img alt="" src="{{ URL::to('/assets/img/caisse2.jpg') }}">
                        </div>
                        <div class="welcome-det">
                            <h3 style="font-size: 17px; font-weight:400;"><strong>Ouverture de caisse</strong></h3>
                            <h4 style="font-size: 14px">Bienvenu dans votre espace de travail emalafintech! </h3>
                            <p style="font-size: 13px">{{$todayDate }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body text-center bg-info">
                            <h4 class="holiday-title mb-0 text-left">Fonds de caisse</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="card">
                        <div class="card-body text-center ">
                            <h4 class="holiday-title mb-0 text-left">Dernier solde</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card">
                        <div class="card-body text-center ">
                            <h4 class="holiday-title mb-0 text-right">{{$data_cdf->balance." ".$data_cdf->currency}}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card">
                        <div class="card-body text-center ">
                            <h4 class="holiday-title mb-0 text-right">{{$data_usd->balance." ".$data_usd->currency}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{route('caissier-macaisses')}}" method="POST">
                @csrf
                <div class="row">                    
                    <div class="col-md-4 col-sm-4">
                        <div class="card">
                            <div class="card-body text-center ">
                                <h4 class="holiday-title mb-0 text-left">Nouveau solde</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="card">
                            <div class="card-body text-center ">
                                {{-- <h4 class="holiday-title mb-0 text-left">Ancien solde</h4> --}}
                                <input type="text" name="newsolde1" class="form-control" style="height: 23px" id="sum-out8">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="card">
                            <div class="card-body text-center ">
                                <input type="text" name="newsolde2" class="form-control" style="height: 23px" id="sum-out8">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="request-btn mb-3 float-right">
                    <button type="submit" class="btn btn-info" href="#"><i class="fa fa-check"></i><span class="ml-2">Valider</span></button>
                </div>
                {{-- <div class="request-btn mb-3 float-right mr-2">
                    <a class="btn btn-info" href="{{ route('caissier.dashboard') }}"><i class="fa fa-home"></i><span class="ml-2"> Retour</span></a>
                </div> --}}
            </form>

            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Historique de transactions</h4>
                        <p class="card-text">
                            Le tableau ci-dessous affiche l'historique de toutes les transactions de <code>votre caisse</code>. Il est possible d'exporter le fchier au format désiré.
                        </p>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="example1" class="table table-bordered table-stripped mb-0">
                            <thead>
                                <tr class="bg-success text-white">
                                    <th>#</th>
                                    <th>Expéditeur</th>
                                    <th>Montant</th>
                                    <th>Devise</th>
                                    <th>Destinataire</th>
                                    <th>Type transaction</th>
                                    <th>Date transaction</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $key=>$value)
                                <tr>
                                    <td class="telephone">{{ ++$key }}</td>
                                    <td class="expediteur"><a href="{{ url('caissier/profilesender/'.Crypt::encrypt($value->sender)) }}" class="sender"><span class="badge bg-inverse-warning">{{$value->sender}}</span></a></td>
                                    <td class="montant">{{$value->amount}}</td>
                                    <td class="devise">{{$value->currency}}</td>
                                    <td class="destinataire"><a href="{{ url('caissier/profilereceiver/'.Crypt::encrypt($value->receiver)) }}" class="receiver"><span class="badge bg-inverse-info">{{$value->receiver}}</span></a></td>
                                    <td class="type">{{$value->transaction_type}}</td>
                                    <td class="date">{{$value->created_at}}</td>
                                    <td class="text-center">
                                        <a class="btn btn-info"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- /Page Content -->

    </div>
    @section('script')
    <script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["csv", "excel", "pdf", "print"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
      </script>
    <script>
        // select auto id and email
        $('#trainer').on('change',function()
        {
            $('#trainer_id').val($(this).find(':selected').data('trainer_id'));
        });
        $('#employees').on('change',function()
        {
            $('#employees_id').val($(this).find(':selected').data('employees_id'));
        });
    </script>
    <script>
        // select auto id and email
        $('#e_trainer').on('change',function()
        {
            $('#e_trainer_id').val($(this).find(':selected').data('e_trainer_id'));
        });
        $('#e_employees').on('change',function()
        {
            $('#e_employees_id').val($(this).find(':selected').data('e_employees_id'));
        });
    </script>

    {{-- update js --}}
    <script>
        $(document).on('click','.edit_training',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.e_id').text());
            $('#e_trainer_id').val(_this.find('.trainer_id').text());
            $('#e_employees_id').val(_this.find('.employees_id').text());
            $('#e_training_cost').val(_this.find('.training_cost').text());
            $('#e_start_date').val(_this.find('.start_date').text());
            $('#e_end_date').val(_this.find('.end_date').text());
            $('#e_description').val(_this.find('.description').text());

            // training_type
            var training_type = (_this.find(".training_type").text());
            var _option = '<option selected value="' +training_type+ '">' + _this.find('.training_type').text() + '</option>'
            $( _option).appendTo("#e_training_type");

            // trainer
            var trainer = (_this.find(".trainer").text());
            var _option = '<option selected value="' +trainer+ '">' + _this.find('.trainer').text() + '</option>'
            $( _option).appendTo("#e_trainer");

            // employees
            var employees = (_this.find(".employees").text());
            var _option = '<option selected value="' +employees+ '">' + _this.find('.employees').text() + '</option>'
            $( _option).appendTo("#e_employees");

            // status
            var status = (_this.find(".status").text());
            var _option = '<option selected value="' +status+ '">' + _this.find('.status').text() + '</option>'
            $( _option).appendTo("#e_status");
        });
    </script>

    {{-- delete model --}}
    <script>
        $(document).on('click','.delete_training',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
    @endsection
@endsection
