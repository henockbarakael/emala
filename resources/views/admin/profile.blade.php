@extends('layouts.caissier')
@section('content')

	<!-- Sidebar -->
    @include('layouts.ca_sidebar')
	<!-- /Sidebar -->

    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Mon Profil</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('caissier.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profil</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <!-- /Page Header -->
            <div class="card mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap">
                                    <div class="profile-img">
                                        <a href="#">
                                            <img alt="" src="{{ URL::to('/assets/images/'. $users->avatar) }}" alt="{{ $users->name }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0"><span style="text-transform: uppercase">{{ $users->firstname." ".$users->name }}</span></h3>
                                                <h6 class="text-muted">FONCTION : <span style="text-transform: uppercase">{{ $users->role_name }}</span></h6>
                                                {{-- <small class="text-muted">{{ $users->position }}</small> --}}
                                                {{-- <div class="staff-id">Compte epargne : {{ $compte_epargne }}</div> --}}
                                                {{-- <div class="staff-id">Compte courant : {{ $compte_courant }}</div> --}}
                                                <div class="small doj text-muted">Date d'inscription : {{ $users->current_date }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Téléphone:</div>
                                                    <div class="text"><a href="">{{ $users->telephone }}</a></div>
                                                </li>
                                                <li>
                                                    <div class="title">E-mail:</div>
                                                    <div class="text"><a href="">{{ $users->email }}</a></div>
                                                </li>
                                                @if(!empty($information))
                                                    <li>
                                                        <div class="title">Anniversaire:</div>
                                                        <div class="text">{{$information->birth_date }}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Adresse:</div>
                                                        <div class="text">{{ $information->address }}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Sexe:</div>
                                                        <div class="text">{{ $information->gender }}</div>
                                                    </li>
                                                @else
                                                    <li>
                                                        <div class="title">Anniversaire:</div>
                                                        <div class="text">Aucune information</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Adresse:</div>
                                                        <div class="text">Aucune information</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Sexe:</div>
                                                        <div class="text">Aucune information</div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="pro-edit"><a data-target="#profile_info" data-toggle="modal" class="edit-icon" href="#"><i class="fa fa-edit"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        @if(!empty($information))
         <!-- Profile Modal -->
         <div id="profile_info" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations sur le profil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile/information/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="profile-img-wrap edit-img">
                                        <img class="inline-block" src="{{ URL::to('/assets/images/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                        <div class="fileupload btn">
                                            <span class="btn-text">Modifier</span>
                                            <input class="upload" type="file" id="image" name="images">
                                            <input type="hidden" name="hidden_image" id="e_image" value="{{ Auth::user()->avatar }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                                                <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Prénom</label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ Auth::user()->firstname }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date d'anniversaire</label>
                                                <div class="cal-icon">
                                                    <input class="form-control datetimepicker" type="text" id="birthDate" name="birthDate" value="{{ $information->birth_date }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sexe</label>
                                                <select class="select form-control" id="gender" name="gender">
                                                    <option value="{{ $information->gender }}" {{ ( $information->gender == $information->gender) ? 'selected' : '' }}>{{ $information->gender }} </option>
                                                    <option value="Masculin">Masculin</option>
                                                    <option value="Féminin">Féminin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" class="form-control" id="adress" name="address" value="{{ $information->address }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" class="form-control" id="state" name="state" value="{{ $information->state }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pays</label>
                                        <select class="select @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}">
                                            <option value="{{ $information->country }}" {{ ( $information->country == $information->country) ? 'selected' : '' }}>{{ $information->country }} </option>
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
                                            <option value="COD">Congo, République démocratique du Congo</option>
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
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Code Pin</label>
                                        <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $information->pin_code }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label>Numéro de téléphone</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $information->phone_number }}">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Profile Modal -->
        @else
         <!-- Profile Modal -->
         <div id="profile_info" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Mettre à jour ses informations</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profile/information/save') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="profile-img-wrap edit-img">
                                        <img class="inline-block" src="{{ URL::to('/assets/images/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                        <div class="fileupload btn">
                                            <span class="btn-text">Modifier</span>
                                            <input class="upload" type="file" id="upload" name="upload">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                                                <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Prénom</label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ Auth::user()->firstname }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date d'anniversaire</label>
                                                <div class="cal-icon">
                                                    <input class="form-control datetimepicker" type="text" id="birthDate" name="birthDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sexe</label>
                                                <select class="select" id="gender" name="gender">
                                                    <option selected disabled>Sélectionner un genre</option>
                                                    <option value="Masculin">Masculin</option>
                                                    <option value="Féminin">Féminin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label>Numéro de téléphone</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" class="form-control" id="address" name="address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ville</label>
                                        <input type="text" class="form-control" id="state" name="state" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pays</label>
                                        <select class="select @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}">
                                            <option selected disabled>
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
                                            <option value="COD">Congo, République démocratique du Congo</option>
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
                                    </div>
                                </div>

                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Profile Modal -->
        @endif

        @if(!empty($personnal_information))
        <!-- Personal Info Modal -->
        <div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations personnelles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('personnal/information/save') }}" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pièce d'identité</label>
                                        <select class="select form-control @error('pi') is-invalid @enderror" name="pi">
                                            <option>-</option>
                                            <option value="PP">Passeport</option>
                                            <option value="PC">Permis de conduire</option>
                                            <option value="CE">Carte d'electeur</option>
                                        </select>
                                        @error('pi')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>N° de passeport</label>
                                        <input type="text" name="num_p" class="form-control" value="{{ old('num_p') }}">
                                        @error('num_p')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date d'expiration</label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker @error('date_exp') is-invalid @enderror" value="{{ old('date_p') }}" name="date_exp" type="text">
                                            @error('date_p')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Commune</label>
                                        <input class="form-control @error('commune') is-invalid @enderror" value="{{ old('commune') }}" name="commune" type="text">
                                        @error('commune')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nationalité <span class="text-danger">*</span></label>
                                        <input class="form-control @error('pays') is-invalid @enderror" value="{{ old('pays') }}" name="pays" type="text">
                                        @error('pays')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Etat civil <span class="text-danger">*</span></label>
                                        <select class="select form-control @error('civil') is-invalid @enderror" value="{{ old('civil') }}" name="civil">
                                            <option>-</option>
                                            <option value="Célibataire">Célibataire</option>
                                            <option value="Marié">Marié</option>
                                            <option value="Divorcé">Divorcé</option>
                                        </select>
                                        @error('civil')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre d'enfants </label>
                                        <input class="form-control @error('enfant') is-invalid @enderror" value="{{ old('enfant') }}" type="text" name="enfant">
                                        @error('enfant')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations personnelles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('personnal/information/save') }}" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pièce d'identité</label>
                                        <select class="select form-control @error('pi') is-invalid @enderror" name="pi">
                                            <option>-</option>
                                            <option value="PP">Passeport</option>
                                            <option value="PC">Permis de conduire</option>
                                            <option value="CE">Carte d'electeur</option>
                                        </select>
                                        @error('pi')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>N° de passeport</label>
                                        <input type="text" name="num_p" class="form-control" value="{{ old('num_p') }}">
                                        @error('num_p')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date d'expiration</label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker @error('date_exp') is-invalid @enderror" value="{{ old('date_p') }}" name="date_exp" type="text">
                                            @error('date_p')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Commune</label>
                                        <input class="form-control @error('commune') is-invalid @enderror" value="{{ old('commune') }}" name="commune" type="text">
                                        @error('commune')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nationalité <span class="text-danger">*</span></label>
                                        <input class="form-control @error('pays') is-invalid @enderror" value="{{ old('pays') }}" name="pays" type="text">
                                        @error('pays')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Etat civil <span class="text-danger">*</span></label>
                                        <select class="select form-control @error('civil') is-invalid @enderror" value="{{ old('civil') }}" name="civil">
                                            <option>-</option>
                                            <option value="Célibataire">Célibataire</option>
                                            <option value="Marié">Marié</option>
                                            <option value="Divorcé">Divorcé</option>
                                        </select>
                                        @error('civil')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre d'enfants </label>
                                        <input class="form-control @error('enfant') is-invalid @enderror" value="{{ old('enfant') }}" type="text" name="enfant">
                                        @error('enfant')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- /Personal Info Modal -->
        @if(!empty($bank_information))
        <!-- Bank Info Modal -->
        <div id="bank_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations bancaires</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('bank/information/save') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>N° de passeport</label>
                                        <input type="text" class="form-control">
                                        <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                        <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date d'expiration</label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nationalité <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Etat civil <span class="text-danger">*</span></label>
                                        <select class="select form-control">
                                            <option>-</option>
                                            <option value="Célibataire">Célibataire</option>
                                            <option value="Marié">Marié</option>
                                            <option value="Divorcé">Divorcé</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre d'enfants </label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div id="bank_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations personnelles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('bank/information/save') }}" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>N° de passeport</label>
                                        <input type="text" class="form-control">
                                        <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                        <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date d'expiration</label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nationalité <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Etat civil <span class="text-danger">*</span></label>
                                        <select class="select form-control">
                                            <option>-</option>
                                            <option value="Célibataire">Célibataire</option>
                                            <option value="Marié">Marié</option>
                                            <option value="Divorcé">Divorcé</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre d'enfants </label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- /Bank Info Modal -->

        <!-- Family Info Modal -->
        @if(!empty($family_information))
        <div id="family_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Informations sur la famille</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('family/information/save') }}" method="POST">
                            <div class="form-scroll">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Membre de famille <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom complet <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lien de parenté <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date de naissance <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Téléhone <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lien de parenté <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date of birth <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phone <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-more">
                                            <a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div id="family_info_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Informations sur la famille</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('family/information/save') }}" method="POST">
                            <div class="form-scroll">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Membre de famille <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom complet <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lien de parenté <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date de naissance <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Téléhone <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Education Informations <a href="javascript:void(0);" class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lien de parenté <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date of birth <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phone <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-more">
                                            <a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- /Family Info Modal -->

        <!-- Contact d'urgence Modal -->
        @if(!empty($emergency_information))
        <div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Contacts d'urgence</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('emergency/information/save') }}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Contact principal</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom complet <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="emergency_fullname1">
                                                <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lien de parenté <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="relationship1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléphone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléphone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Deuxième Contact</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom complet <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="emergency_fullname2">
                                                <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lien de parenté <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="relationship2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléhone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone3">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléhone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informations personnelles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('emergency/information/save') }}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Contact principal</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom complet <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="emergency_fullname1">
                                                <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lien de parenté <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="relationship1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléphone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléphone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Deuxième Contact</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom complet <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="emergency_fullname2">
                                                <input type="hidden" class="form-control" id="rec_id" name="rec_id" value="{{ Auth::user()->rec_id }}">
                                                <input type="hidden" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lien de parenté <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="relationship2">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléhone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone3">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Téléhone 1<span class="text-danger">*</span></label>
                                                <input class="form-control" type="phone" name="emergency_phone4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- /Contact d'urgence Modal -->

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
