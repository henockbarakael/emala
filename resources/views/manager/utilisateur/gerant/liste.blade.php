@extends('layouts.master')
@section('content')
@section('title','Gestion des gérants')
@section('page','Liste des gérants')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="col-sm-12" style="padding: 10px">
                    <span class="pull-right">
                        <a href="#" class="btn btn-success" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_user">
                        <span><i class="fa fa-plus text-white"></i></span> Ajouter un gérant
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">

          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Avatar</th>
                    <th hidden>ID</th>
                    <th hidden>Email</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th hidden>City</th>
                    <th hidden>Pays</th>
                    <th hidden>Adresse</th>
                    <th>Téléphone</th>
                    <th class="text-center">Agence de</th>
                    <th class="text-center">Code agence</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($users as $key => $value)
                  <tr>
                    <td class="avatar"><div class="d-inline-block align-middle"><img class="img-40 m-r-15 rounded-circle align-top" src="{{ URL::to('/assets/images/'. $value->avatar) }}" alt=""></td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td hidden class="email">{{$value->email}}</td>
                    <td hidden class="address">{{$value->address}}</td>
                    <td class="firstname">{{$value->firstname}}</td>
                    <td hidden class="city">{{$value->city}}</td>
                    <td hidden class="pays">{{$value->country}}</td>
                    <td class="lastname">{{$value->lastname}}</td>
                    <td class="phone_number"><a href="{{url('manager/profil-utilisateur/'.Crypt::encrypt($value->phone_number))}}">{{$value->phone_number}}</a></td>
                    <td class="btownship text-center">{{$value->btownship}}</td>
                    <td class="bname text-center">{{$value->bname}}</td>
                    <td>
                      <a href="" class="btn btn-success btn-xs userUpdate" title="Edit manager" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-edit"></i></a>
                      <a href="" class="btn btn-danger btn-xs userDelete" title="Delete manager" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                  </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Avatar</th>
                    <th hidden>ID</th>
                    <th hidden>Email</th>
                    <th>Prénom</th>
                    <th hidden>City</th>
                    <th hidden>Pays</th>
                    <th hidden>Adresse</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th class="text-center">Agence de</th>
                    <th class="text-center">Code agence</th>
                    <th class="text-center">Action</th>
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
  <div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Ajouter un gérant</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('manager.creation_gerant')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="e_id" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Prénom</label>
                    <input class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="e_firstname" type="text" value="" >
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Nom</label>
                    <input class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="e_lastname" type="text" value="" >
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Téléphone</label>
                    <input class="form-control input-air-primary @error('phone_number') is-invalid @enderror" name="phone_number" id="e_phone_number" type="tel" value="" >
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Ville</label>
                    <input class="form-control input-air-primary @error('ville') is-invalid @enderror" name="ville" id="e_ville" type="text" value="" >
                    @error('ville')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="pays">Pays</label>
                    <select name="pays" value="{{ old('pays') }}" class="form-select input-air-primary digits @error('pays') is-invalid @enderror" id="pays">
                      <option value="COD" selected>République démocratique du Congo</option>
                      <option value="AFG">Afghanistan</option>
                      <option value="ALA">Åland Islands</option>
                      <option value="ALB">Albanie</option>
                      <option value="DZA">Algérie</option>
                      <option value="ASM">Samoa</option>
                      <option value="AND">Andorre</option>
                      <option value="AGO">Angola</option>
                      <option value="AIA">Anguilla</option>
                      <option value="ATA">Antarctique</option>
                      <option value="ATG">Antigua et Barbuda</option>
                      <option value="ARG">Argentine</option>
                      <option value="ARM">Arménie</option>
                      <option value="ABW">Aruba</option>
                      <option value="AUS">Australie</option>
                      <option value="AUT">Autriche</option>
                      <option value="AZE">Azerbaïdjan</option>
                      <option value="BHS">Bahamas</option>
                      <option value="BHR">Bahrain</option>
                      <option value="BGD">Bangladesh</option>
                      <option value="BRB">Barbade</option>
                      <option value="BLR">Belarus</option>
                      <option value="BEL">Belgique</option>
                      <option value="BLZ">Belize</option>
                      <option value="BEN">Bénin</option>
                      <option value="BMU">Bermuda</option>
                      <option value="BTN">Bhutan</option>
                      <option value="BOL">Bolivie</option>
                      <option value="BES">Bonaire, Saint-Eustache et Saba</option>
                      <option value="BIH">Bosnie-Herzégovine</option>
                      <option value="BWA">Botswana</option>
                      <option value="BVT">Île Bouvet</option>
                      <option value="BRA">Brésil</option>
                      <option value="IOT">Territoire britannique de l'océan Indien</option>
                      <option value="BRN">Brunéi Darussalam</option>
                      <option value="BGR">Bulgarie</option>
                      <option value="BFA">Burkina Faso</option>
                      <option value="BDI">Burundi</option>
                      <option value="KHM">Cambodge</option>
                      <option value="CMR">Cameroun</option>
                      <option value="CAN">Canada</option>
                      <option value="CPV">Cap-Vert</option>
                      <option value="CYM">Îles Caïmans</option>
                      <option value="CAF">République centrafricaine</option>
                      <option value="TCD">Tchad</option>
                      <option value="CHL">Chili</option>
                      <option value="CHN">Chine</option>
                      <option value="CXR">Île Christmas</option>
                      <option value="CCK">Îles Cocos (Keeling)</option>
                      <option value="COL">Colombie</option>
                      <option value="COM">Comores</option>
                      <option value="COG">Congo</option>
                      <option value="COD">République démocratique du Congo</option>
                      <option value="COK">Îles Cook</option>
                      <option value="CRI">Costa Rica</option>
                      <option value="CIV">Côte d'Ivoire</option>
                      <option value="HRV">Croatie</option>
                      <option value="CUB">Cuba</option>
                      <option value="CUW">Curaçao</option>
                      <option value="CYP">Chypre</option>
                      <option value="CZE">République tchèque</option>
                      <option value="DNK">Danemark</option>
                      <option value="DJI">Djibouti</option>
                      <option value="DMA">Dominique</option>
                      <option value="DOM">République dominicaine</option>
                      <option value="ECU">Équateur</option>
                      <option value="EGY">Égypte</option>
                      <option value="SLV">El Salvador</option>
                      <option value="GNQ">Guinée équatoriale</option>
                      <option value="ERI">Érythrée</option>
                      <option value="EST">Estonie</option>
                      <option value="ETH">Éthiopie</option>
                      <option value="FLK">Îles Falkland (Malvinas)</option>
                      <option value="FRO">Îles Féroé</option>
                      <option value="FJI">Fidji</option>
                      <option value="FIN">Finlande</option>
                      <option value="FRA">France</option>
                      <option value="GUF">Guyane française</option>
                      <option value="PYF">Polynésie française</option>
                      <option value="ATF">Terres australes françaises</option>
                      <option value="GAB">Gabon</option>
                      <option value="GMB">Gambie</option>
                      <option value="GEO">Géorgie</option>
                      <option value="DEU">Allemagne</option>
                      <option value="GHA">Ghana</option>
                      <option value="GIB">Gibraltar</option>
                      <option value="GRC">Grèce</option>
                      <option value="GRL">Groenland</option>
                      <option value="GRD">Grenade</option>
                      <option value="GLP">Guadeloupe</option>
                      <option value="GUM">Guam</option>
                      <option value="GTM">Guatemala</option>
                      <option value="GGY">Guernesey</option>
                      <option value="GIN">Guinée</option>
                      <option value="GNB">Guinée-Bissau</option>
                      <option value="GUY">Guyane</option>
                      <option value="HTI">Haïti</option>
                      <option value="HMD">Île Heard et îles McDonald</option>
                      <option value="VAT">Saint-Siège (État de la Cité du Vatican)</option>
                      <option value="HND">Honduras</option>
                      <option value="HKG">Hong Kong</option>
                      <option value="HUN">Hongrie</option>
                      <option value="ISL">Islande</option>
                      <option value="IND">Inde</option>
                      <option value="IDN">Indonésie</option>
                      <option value="IRN">Iran, République islamique d Iran'</option>
                      <option value="IRQ">Irak</option>
                      <option value="IRL">Irlande</option>
                      <option value="IMN">Île de Man</option>
                      <option value="ISR">Israël</option>
                      <option value="ITA">Italie</option>
                      <option value="JAM">Jamaïque</option>
                      <option value="JPN">Japon</option>
                      <option value="JEY">Jersey</option>
                      <option value="JOR">Jordanie</option>
                      <option value="KAZ">Kazakhstan</option>
                      <option value="KEN">Kenya</option>
                      <option value="KIR">Kiribati</option>
                      <option value="PRK">Corée, République populaire démocratique de Corée</option>
                      <option value="KOR">Corée, République de Corée</option>
                      <option value="KWT">Koweït</option>
                      <option value="KGZ">Kirghizistan</option>
                      <option value="LAO">République démocratique populaire lao</option>
                      <option value="LVA">Lettonie</option>
                      <option value="LBN">Liban</option>
                      <option value="LSO">Lesotho</option>
                      <option value="LBR">Liberia</option>
                      <option value="LBY">Libye</option>
                      <option value="LIE">Liechtenstein</option>
                      <option value="LTU">Lituanie</option>
                      <option value="LUX">Luxembourg</option>
                      <option value="MAC">Macao</option>
                      <option value="MKD">Macédoine, ancienne République de Yougoslavie</option>
                      <option value="MDG">Madagascar</option>
                      <option value="MWI">Malawi</option>
                      <option value="MYS">Malaisie</option>
                      <option value="MDV">Maldives</option>
                      <option value="MLI">Mali</option>
                      <option value="MLT">Malte</option>
                      <option value="MHL">Îles Marshall</option>
                      <option value="MTQ">Martinique</option>
                      <option value="MRT">Mauritanie</option>
                      <option value="MUS">Maurice</option>
                      <option value="MYT">Mayotte</option>
                      <option value="MEX">Mexique</option>
                      <option value="FSM">Micronésie, États fédérés de Micronésie</option>
                      <option value="MDA">Moldavie, République de Moldavie</option>
                      <option value="MCO">Monaco</option>
                      <option value="MNG">Mongolie</option>
                      <option value="MNE">Monténégro</option>
                      <option value="MSR">Montserrat</option>
                      <option value="MAR">Maroc</option>
                      <option value="MOZ">Mozambique</option>
                      <option value="MMR">Myanmar</option>
                      <option value="NAM">Namibie</option>
                      <option value="NRU">Nauru</option>
                      <option value="NPL">Népal</option>
                      <option value="NLD">Pays-Bas</option>
                      <option value="NCL">Nouvelle-Calédonie</option>
                      <option value="NZL">Nouvelle-Zélande</option>
                      <option value="NIC">Nicaragua</option>
                      <option value="NER">Niger</option>
                      <option value="NGA">Nigéria</option>
                      <option value="NIU">Niue</option>
                      <option value="NFK">Île Norfolk</option>
                      <option value="MNP">Îles Mariannes du Nord</option>
                      <option value="NOR">Norvège</option>
                      <option value="OMN">Oman</option>
                      <option value="PAK">Pakistan</option>
                      <option value="PLW">Palau</option>
                      <option value="PSE">Territoire palestinien occupé</option>
                      <option value="PAN">Panama</option>
                      <option value="PNG">Papouasie-Nouvelle-Guinée</option>
                      <option value="PRY">Paraguay</option>
                      <option value="PER">Pérou</option>
                      <option value="PHL">Philippines</option>
                      <option value="PCN">Pitcairn</option>
                      <option value="POL">Pologne</option>
                      <option value="PRT">Portugal</option>
                      <option value="PRI">Porto Rico</option>
                      <option value="QAT">Qatar</option>
                      <option value="REU">Réunion</option>
                      <option value="ROU">Roumanie</option>
                      <option value="RUS">Fédération de Russie</option>
                      <option value="RWA">Rwanda</option>
                      <option value="BLM">Saint Barthélemy</option>
                      <option value="SHN">Sainte-Hélène, Ascension et Tristan da Cunha</option>
                      <option value="KNA">Saint-Kitts-et-Nevis</option>
                      <option value="LCA">Sainte-Lucie</option>
                      <option value="MAF">Saint-Martin (partie française)</option>
                      <option value="SPM">Saint-Pierre-et-Miquelon</option>
                      <option value="VCT">Saint-Vincent-et-les Grenadines</option>
                      <option value="WSM">Samoa</option>
                      <option value="SMR">Saint-Marin</option>
                      <option value="STP">Sao Tomé-et-Principe</option>
                      <option value="SAU">Arabie saoudite</option>
                      <option value="SEN">Sénégal</option>
                      <option value="SRB">Serbie</option>
                      <option value="SYC">Seychelles</option>
                      <option value="SLE">Sierra Leone</option>
                      <option value="SGP">Singapour</option>
                      <option value="SXM">Sint Maarten (partie néerlandaise)</option>
                      <option value="SVK">Slovaquie</option>
                      <option value="SVN">Slovénie</option>
                      <option value="SLB">Îles Salomon</option>
                      <option value="SOM">Somalie</option>
                      <option value="ZAF">Afrique du Sud</option>
                      <option value="SGS">Géorgie du Sud et îles Sandwich du Sud</option>
                      <option value="SSD">Soudan du Sud</option>
                      <option value="ESP">Espagne</option>
                      <option value="LKA">Sri Lanka</option>
                      <option value="SDN">Soudan</option>
                      <option value="SUR">Suriname</option>
                      <option value="SJM">Svalbard et Jan Mayen</option>
                      <option value="SWZ">Swaziland</option>
                      <option value="SWE">Suède</option>
                      <option value="CHE">Suisse</option>
                      <option value="SYR">République arabe syrienne</option>
                      <option value="TWN">Taïwan, province de Chine</option>
                      <option value="TJK">Tadjikistan</option>
                      <option value="TZA">Tanzanie, République-Unie de Tanzanie</option>
                      <option value="THA">Thaïlande</option>
                      <option value="TLS">Timor-Leste</option>
                      <option value="TGO">Togo</option>
                      <option value="TKL">Tokelau</option>
                      <option value="TON">Tonga</option>
                      <option value="TTO">Trinité-et-Tobago</option>
                      <option value="TUN">Tunisie</option>
                      <option value="TUR">Turquie</option>
                      <option value="TKM">Turkménistan</option>
                      <option value="TCA">Îles Turques et Caïques</option>
                      <option value="TUV">Tuvalu</option>
                      <option value="UGA">Ouganda</option>
                      <option value="UKR">Ukraine</option>
                      <option value="ARE">Émirats arabes unis</option>
                      <option value="GBR">Royaume-Uni</option>
                      <option value="USA">États-Unis</option>
                      <option value="UMI">Îles mineures éloignées des États-Unis</option>
                      <option value="URY">Uruguay</option>
                      <option value="UZB">Ouzbékistan</option>
                      <option value="VUT">Vanuatu</option>
                      <option value="VEN">Venezuela, République bolivarienne</option>
                      <option value="VNM">Viet Nam</option>
                      <option value="VGB">Îles Vierges britanniques</option>
                      <option value="VIR">Îles Vierges américaines.</option>
                      <option value="WLF">Wallis et Futuna</option>
                      <option value="ESH">Sahara occidental</option>
                      <option value="YEM">Yémen</option>
                      <option value="ZMB">Zambie</option>
                      <option value="ZWE">Zimbabwe</option>
                    </select>
                    @error('pays')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom02">Adresse physique</label>
                    <textarea class="form-control input-air-primary @error('adresse') is-invalid @enderror" name="adresse" id="e_adresse" type="text" value="" ></textarea>
                    @error('adresse')
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
  <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Modifier gérant</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('manager.modification_gerant')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="e_iduser" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Prénom</label>
                    <input class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="e_prenom" type="text" value="" >
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Nom</label>
                    <input class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="e_nom" type="text" value="" >
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Téléphone</label>
                    <input readonly class="form-control input-air-primary @error('phone_number') is-invalid @enderror" name="phone_number" id="e_telephone" type="tel" value="" >
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Ville</label>
                    <input class="form-control input-air-primary @error('ville') is-invalid @enderror" name="ville" id="e_city" type="text" value="" >
                    @error('ville')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="pays">Pays</label>
                    <select name="pays" value="" class="form-select input-air-primary digits @error('pays') is-invalid @enderror" id="e_pays">
                      <option value="" selected>{{ old('pays') }}</option>
                      <option value="AFG">Afghanistan</option>
                      <option value="ALA">Åland Islands</option>
                      <option value="ALB">Albanie</option>
                      <option value="DZA">Algérie</option>
                      <option value="ASM">Samoa</option>
                      <option value="AND">Andorre</option>
                      <option value="AGO">Angola</option>
                      <option value="AIA">Anguilla</option>
                      <option value="ATA">Antarctique</option>
                      <option value="ATG">Antigua et Barbuda</option>
                      <option value="ARG">Argentine</option>
                      <option value="ARM">Arménie</option>
                      <option value="ABW">Aruba</option>
                      <option value="AUS">Australie</option>
                      <option value="AUT">Autriche</option>
                      <option value="AZE">Azerbaïdjan</option>
                      <option value="BHS">Bahamas</option>
                      <option value="BHR">Bahrain</option>
                      <option value="BGD">Bangladesh</option>
                      <option value="BRB">Barbade</option>
                      <option value="BLR">Belarus</option>
                      <option value="BEL">Belgique</option>
                      <option value="BLZ">Belize</option>
                      <option value="BEN">Bénin</option>
                      <option value="BMU">Bermuda</option>
                      <option value="BTN">Bhutan</option>
                      <option value="BOL">Bolivie</option>
                      <option value="BES">Bonaire, Saint-Eustache et Saba</option>
                      <option value="BIH">Bosnie-Herzégovine</option>
                      <option value="BWA">Botswana</option>
                      <option value="BVT">Île Bouvet</option>
                      <option value="BRA">Brésil</option>
                      <option value="IOT">Territoire britannique de l'océan Indien</option>
                      <option value="BRN">Brunéi Darussalam</option>
                      <option value="BGR">Bulgarie</option>
                      <option value="BFA">Burkina Faso</option>
                      <option value="BDI">Burundi</option>
                      <option value="KHM">Cambodge</option>
                      <option value="CMR">Cameroun</option>
                      <option value="CAN">Canada</option>
                      <option value="CPV">Cap-Vert</option>
                      <option value="CYM">Îles Caïmans</option>
                      <option value="CAF">République centrafricaine</option>
                      <option value="TCD">Tchad</option>
                      <option value="CHL">Chili</option>
                      <option value="CHN">Chine</option>
                      <option value="CXR">Île Christmas</option>
                      <option value="CCK">Îles Cocos (Keeling)</option>
                      <option value="COL">Colombie</option>
                      <option value="COM">Comores</option>
                      <option value="COG">Congo</option>
                      <option value="COD">République démocratique du Congo</option>
                      <option value="COK">Îles Cook</option>
                      <option value="CRI">Costa Rica</option>
                      <option value="CIV">Côte d'Ivoire</option>
                      <option value="HRV">Croatie</option>
                      <option value="CUB">Cuba</option>
                      <option value="CUW">Curaçao</option>
                      <option value="CYP">Chypre</option>
                      <option value="CZE">République tchèque</option>
                      <option value="DNK">Danemark</option>
                      <option value="DJI">Djibouti</option>
                      <option value="DMA">Dominique</option>
                      <option value="DOM">République dominicaine</option>
                      <option value="ECU">Équateur</option>
                      <option value="EGY">Égypte</option>
                      <option value="SLV">El Salvador</option>
                      <option value="GNQ">Guinée équatoriale</option>
                      <option value="ERI">Érythrée</option>
                      <option value="EST">Estonie</option>
                      <option value="ETH">Éthiopie</option>
                      <option value="FLK">Îles Falkland (Malvinas)</option>
                      <option value="FRO">Îles Féroé</option>
                      <option value="FJI">Fidji</option>
                      <option value="FIN">Finlande</option>
                      <option value="FRA">France</option>
                      <option value="GUF">Guyane française</option>
                      <option value="PYF">Polynésie française</option>
                      <option value="ATF">Terres australes françaises</option>
                      <option value="GAB">Gabon</option>
                      <option value="GMB">Gambie</option>
                      <option value="GEO">Géorgie</option>
                      <option value="DEU">Allemagne</option>
                      <option value="GHA">Ghana</option>
                      <option value="GIB">Gibraltar</option>
                      <option value="GRC">Grèce</option>
                      <option value="GRL">Groenland</option>
                      <option value="GRD">Grenade</option>
                      <option value="GLP">Guadeloupe</option>
                      <option value="GUM">Guam</option>
                      <option value="GTM">Guatemala</option>
                      <option value="GGY">Guernesey</option>
                      <option value="GIN">Guinée</option>
                      <option value="GNB">Guinée-Bissau</option>
                      <option value="GUY">Guyane</option>
                      <option value="HTI">Haïti</option>
                      <option value="HMD">Île Heard et îles McDonald</option>
                      <option value="VAT">Saint-Siège (État de la Cité du Vatican)</option>
                      <option value="HND">Honduras</option>
                      <option value="HKG">Hong Kong</option>
                      <option value="HUN">Hongrie</option>
                      <option value="ISL">Islande</option>
                      <option value="IND">Inde</option>
                      <option value="IDN">Indonésie</option>
                      <option value="IRN">Iran, République islamique d Iran'</option>
                      <option value="IRQ">Irak</option>
                      <option value="IRL">Irlande</option>
                      <option value="IMN">Île de Man</option>
                      <option value="ISR">Israël</option>
                      <option value="ITA">Italie</option>
                      <option value="JAM">Jamaïque</option>
                      <option value="JPN">Japon</option>
                      <option value="JEY">Jersey</option>
                      <option value="JOR">Jordanie</option>
                      <option value="KAZ">Kazakhstan</option>
                      <option value="KEN">Kenya</option>
                      <option value="KIR">Kiribati</option>
                      <option value="PRK">Corée, République populaire démocratique de Corée</option>
                      <option value="KOR">Corée, République de Corée</option>
                      <option value="KWT">Koweït</option>
                      <option value="KGZ">Kirghizistan</option>
                      <option value="LAO">République démocratique populaire lao</option>
                      <option value="LVA">Lettonie</option>
                      <option value="LBN">Liban</option>
                      <option value="LSO">Lesotho</option>
                      <option value="LBR">Liberia</option>
                      <option value="LBY">Libye</option>
                      <option value="LIE">Liechtenstein</option>
                      <option value="LTU">Lituanie</option>
                      <option value="LUX">Luxembourg</option>
                      <option value="MAC">Macao</option>
                      <option value="MKD">Macédoine, ancienne République de Yougoslavie</option>
                      <option value="MDG">Madagascar</option>
                      <option value="MWI">Malawi</option>
                      <option value="MYS">Malaisie</option>
                      <option value="MDV">Maldives</option>
                      <option value="MLI">Mali</option>
                      <option value="MLT">Malte</option>
                      <option value="MHL">Îles Marshall</option>
                      <option value="MTQ">Martinique</option>
                      <option value="MRT">Mauritanie</option>
                      <option value="MUS">Maurice</option>
                      <option value="MYT">Mayotte</option>
                      <option value="MEX">Mexique</option>
                      <option value="FSM">Micronésie, États fédérés de Micronésie</option>
                      <option value="MDA">Moldavie, République de Moldavie</option>
                      <option value="MCO">Monaco</option>
                      <option value="MNG">Mongolie</option>
                      <option value="MNE">Monténégro</option>
                      <option value="MSR">Montserrat</option>
                      <option value="MAR">Maroc</option>
                      <option value="MOZ">Mozambique</option>
                      <option value="MMR">Myanmar</option>
                      <option value="NAM">Namibie</option>
                      <option value="NRU">Nauru</option>
                      <option value="NPL">Népal</option>
                      <option value="NLD">Pays-Bas</option>
                      <option value="NCL">Nouvelle-Calédonie</option>
                      <option value="NZL">Nouvelle-Zélande</option>
                      <option value="NIC">Nicaragua</option>
                      <option value="NER">Niger</option>
                      <option value="NGA">Nigéria</option>
                      <option value="NIU">Niue</option>
                      <option value="NFK">Île Norfolk</option>
                      <option value="MNP">Îles Mariannes du Nord</option>
                      <option value="NOR">Norvège</option>
                      <option value="OMN">Oman</option>
                      <option value="PAK">Pakistan</option>
                      <option value="PLW">Palau</option>
                      <option value="PSE">Territoire palestinien occupé</option>
                      <option value="PAN">Panama</option>
                      <option value="PNG">Papouasie-Nouvelle-Guinée</option>
                      <option value="PRY">Paraguay</option>
                      <option value="PER">Pérou</option>
                      <option value="PHL">Philippines</option>
                      <option value="PCN">Pitcairn</option>
                      <option value="POL">Pologne</option>
                      <option value="PRT">Portugal</option>
                      <option value="PRI">Porto Rico</option>
                      <option value="QAT">Qatar</option>
                      <option value="REU">Réunion</option>
                      <option value="ROU">Roumanie</option>
                      <option value="RUS">Fédération de Russie</option>
                      <option value="RWA">Rwanda</option>
                      <option value="BLM">Saint Barthélemy</option>
                      <option value="SHN">Sainte-Hélène, Ascension et Tristan da Cunha</option>
                      <option value="KNA">Saint-Kitts-et-Nevis</option>
                      <option value="LCA">Sainte-Lucie</option>
                      <option value="MAF">Saint-Martin (partie française)</option>
                      <option value="SPM">Saint-Pierre-et-Miquelon</option>
                      <option value="VCT">Saint-Vincent-et-les Grenadines</option>
                      <option value="WSM">Samoa</option>
                      <option value="SMR">Saint-Marin</option>
                      <option value="STP">Sao Tomé-et-Principe</option>
                      <option value="SAU">Arabie saoudite</option>
                      <option value="SEN">Sénégal</option>
                      <option value="SRB">Serbie</option>
                      <option value="SYC">Seychelles</option>
                      <option value="SLE">Sierra Leone</option>
                      <option value="SGP">Singapour</option>
                      <option value="SXM">Sint Maarten (partie néerlandaise)</option>
                      <option value="SVK">Slovaquie</option>
                      <option value="SVN">Slovénie</option>
                      <option value="SLB">Îles Salomon</option>
                      <option value="SOM">Somalie</option>
                      <option value="ZAF">Afrique du Sud</option>
                      <option value="SGS">Géorgie du Sud et îles Sandwich du Sud</option>
                      <option value="SSD">Soudan du Sud</option>
                      <option value="ESP">Espagne</option>
                      <option value="LKA">Sri Lanka</option>
                      <option value="SDN">Soudan</option>
                      <option value="SUR">Suriname</option>
                      <option value="SJM">Svalbard et Jan Mayen</option>
                      <option value="SWZ">Swaziland</option>
                      <option value="SWE">Suède</option>
                      <option value="CHE">Suisse</option>
                      <option value="SYR">République arabe syrienne</option>
                      <option value="TWN">Taïwan, province de Chine</option>
                      <option value="TJK">Tadjikistan</option>
                      <option value="TZA">Tanzanie, République-Unie de Tanzanie</option>
                      <option value="THA">Thaïlande</option>
                      <option value="TLS">Timor-Leste</option>
                      <option value="TGO">Togo</option>
                      <option value="TKL">Tokelau</option>
                      <option value="TON">Tonga</option>
                      <option value="TTO">Trinité-et-Tobago</option>
                      <option value="TUN">Tunisie</option>
                      <option value="TUR">Turquie</option>
                      <option value="TKM">Turkménistan</option>
                      <option value="TCA">Îles Turques et Caïques</option>
                      <option value="TUV">Tuvalu</option>
                      <option value="UGA">Ouganda</option>
                      <option value="UKR">Ukraine</option>
                      <option value="ARE">Émirats arabes unis</option>
                      <option value="GBR">Royaume-Uni</option>
                      <option value="USA">États-Unis</option>
                      <option value="UMI">Îles mineures éloignées des États-Unis</option>
                      <option value="URY">Uruguay</option>
                      <option value="UZB">Ouzbékistan</option>
                      <option value="VUT">Vanuatu</option>
                      <option value="VEN">Venezuela, République bolivarienne</option>
                      <option value="VNM">Viet Nam</option>
                      <option value="VGB">Îles Vierges britanniques</option>
                      <option value="VIR">Îles Vierges américaines.</option>
                      <option value="WLF">Wallis et Futuna</option>
                      <option value="ESH">Sahara occidental</option>
                      <option value="YEM">Yémen</option>
                      <option value="ZMB">Zambie</option>
                      <option value="ZWE">Zimbabwe</option>
                    </select>
                    @error('pays')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom02">Adresse physique</label>
                    <textarea class="form-control input-air-primary @error('adresse') is-invalid @enderror" name="adresse" id="e_adres" type="text" value="" ></textarea>
                    @error('adresse')
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
            <form action="{{route('manager.supprimer_gerant')}}" method="POST">
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
            $('#e_iduser').val(_this.find('.id').text());
            $('#e_nom').val(_this.find('.lastname').text());
            $('#e_prenom').val(_this.find('.firstname').text());
            $('#e_telephone').val(_this.find('.phone_number').text());
            $('#e_city').val(_this.find('.city').text());
            $('#e_pays').val(_this.find('.pays').text());
            $('#e_adres').val(_this.find('.address').text());
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