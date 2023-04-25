@extends('layouts.master')
@section('content')
@section('title','Tickets')
@section('page','Tickets')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        {{-- <div class="row mt-2 mb-4">
            <div style="height: 30px">
              <a href="#" class="btn btn-success btn-sm pull-right"  title="Ajouter un caissier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_ticket"><i class="fa fa-plus" style="margin-right: 3px"></i><span >Nouveau Ticket</span> </a>
            </div>
          </div> --}}
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th hidden>#</th>
                    <th>N° Ticket</th>
                    <th class="text-left">Agence</th>
                    <th class="text-left">Sujet</th>
                    <th class="text-left">Message</th>
                    <th class="text-left">Expéditeur</th>
                    <th class="text-left">Pièce jointe</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $key => $value)
                  @php
                      $admi = DB::table('users')->where('id', $value->sender_id)->first();
                      $branche = DB::table('branches')->where('id', $value->branche_id)->first();
                      $dest = $admi->firstname;
                      $bname = $branche->bname;
                  @endphp
                  
                  <tr>
                    <td hidden class="id">{{$value->id}}</td>
                    <td class="firstname">{{$value->ticket_id}}</td>
                    <td class="firstname">{{$bname}}</td>
                    <td class="acnumber">{{$value->subject}}</td>
                    <td class="acnumber">{{$value->message}}</td>
                    <td class="currency text-left">{{$dest}}</td>
                    <td class="currency text-left"><a href="{{url('admin/donwload-file/'.$value->file)}}">{{$value->file}}</a></td>
                    <td class="text-center">
                        <span class="badge badge-success">{{$value->status}}</span>
                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th hidden>#</th>
                    <th>N° Ticket</th>
                    <th class="text-left">Agence</th>
                    <th class="text-left">Sujet</th>
                    <th class="text-left">Message</th>
                    <th class="text-left">Expéditeur</th>
                    <th class="text-left">Pièce jointe</th>
                    <th class="text-center">Status</th>
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
  <div class="modal fade" id="add_ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Nouveau Ticket</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.ticket.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                  <div class="col-md-12 mt-4">
                    <label class="form-label" for="validationCustom01">Assigner à</label>
                    <select name="assigned_id" class="form-select input-air-primary digits @error('assigned_id') is-invalid @enderror" id="exampleFormControlSelect9">
                      <option  selected disabled>Choisir un administrateur</option>
                      @foreach($admins as $cashier)
                        <option value="{{ $cashier->id }}">{{ $cashier->firstname.' '.$cashier->lastname }}</option>
                     @endforeach
                    </select>
                    @error('objet')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom01">Sujet</label>
                    <input class="form-control @error('subject') is-invalid @enderror" name="subject" id="e_subject" type="text" value="" >
                    @error('subject')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom01">Joindre un fichier</label>
                    <input class="form-control @error('file') is-invalid @enderror" name="file" type="file" aria-label="file example">
                    @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom02">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="e_message" type="text" value="" ></textarea>
                    @error('message')
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
  @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.AccountTopUp',function()
        {
            var _this = $(this).parents('tr');
            $('#account_id').val(_this.find('.id').text());
            $('#account_level').val(_this.find('.account_level').text());
            $('#topup_currency').val(_this.find('.currency').text());
        });
    </script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection