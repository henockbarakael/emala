@extends('layouts.master')
@section('content')
@section('title','Création de compte | Epargne')
@section('page','Création de compte')
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
              <h5 style="text-transform: none">Création compte epargne</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.saving.store')}}" novalidate=""method="POST">
                @csrf
                <div class="row g-3">
                  <div class="col-md-4 mt-4">
                    <label class="form-label" for="validationCustom01">Téléphone Client</label>
                    {{-- <input value="" onkeyup="GetDetail(this.value)" class="typeahead form-control input-air-primary @error('receiver_phone') is-invalid @enderror" name="receiver_phone" id="search" type="text" required=""> --}}
                    <input value="" data-type="sender_phone" class="typeahead form-control autocomplete_txt input-air-primary @error('sender_phone') is-invalid @enderror" name="sender_phone" id="sender_phone_1" type="text" >
                    @error('sender_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="form-label" for="validationCustom01">Prénom Client</label>
                    <input  data-type="sender_first" class="form-control autocomplete_txt input-air-primary @error('sender_first') is-invalid @enderror" name="sender_first" id="sender_first_1" type="text" >
                    @error('sender_first')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4 mt-4">
                    <label class="form-label" for="validationCustom01">Nom Client</label>
                    <input data-type="sender_last"  class="typeahead form-control autocomplete_txt input-air-primary @error('sender_last') is-invalid @enderror" name="sender_last" id="sender_last_1" type="text" >
                    @error('sender_last')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Date de création</label>
                    <input class="form-control input-air-primary @error('startDate') is-invalid @enderror" name="startDate" id="validationCustom01" type="date" value="{{ old('startDate') }}" required="">
                    {{-- <input hidden class="form-control" name="id"  type="text" value="{{ $user->id }}"> --}}
                    @error('startDate')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom02">Date fin</label>
                    <input class="form-control input-air-primary @error('endDate') is-invalid @enderror" name="endDate" id="validationCustom02" type="date" value="{{ old('endDate') }}" required="">
                    @error('endDate')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="currency">Devise</label>
                    <select name="currency" class="form-select input-air-primary digits" id="currency">
                      <option selected disabled>Choisir une devise</option>
                      <option value="CDF-USD">CDF-USD</option>
                      <option value="CDF">CDF</option>
                      <option value="USD">USD</option>
                    </select>
                    @error('currency')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                </div>
                <button class="btn btn-primary mt-3" type="submit">Valider</button>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
  @section('script')
  <script type="text/javascript">
    //autocomplete script
    $(document).on('focus','.autocomplete_txt',function(){
      type = $(this).data('type');
      
      if(type =='sender_phone' )autoType='phone'; 
      if(type =='sender_first' )autoType='firstname'; 
      if(type =='sender_last' )autoType='lastname'; 
      
       $(this).autocomplete({
           minLength: 0,
           source: function( request, response ) {
                $.ajax({
                    url: "{{ route('admin.search_autocomplete') }}",
                    dataType: "json",
                    data: {
                        term : request.term,
                        type : type,
                    },
                    success: function(data) {
                        var array = $.map(data, function (item) {
                           return {
                               label: item[autoType],
                               value: item[autoType],
                               data : item
                           }
                       });
                        response(array)
                    }
                });
           },
           select: function( event, ui ) {
               var data = ui.item.data;           
               id_arr = $(this).attr('id');
               id = id_arr.split("_");
               elementId = id[id.length-1];
               $('#sender_phone_'+elementId).val(data.phone);
               $('#sender_first_'+elementId).val(data.firstname);
               $('#sender_last_'+elementId).val(data.lastname);
           }
       });
      });
    </script>
@endsection
@endsection