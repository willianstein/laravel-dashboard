@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastros Parceiros</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
{{-- {{dd($partner);}} --}}
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#main-data" role="tab">Dados Principais</a>
                                    </li>
                                    @if(!empty($partner->toArray()))
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#address-data" role="tab">Endereços</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#contact-data" role="tab">Contatos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#service-data" role="tab">Serviços</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <!-- MAIN DATA -->
                                    <div class="tab-pane fade show active" id="main-data" role="tabpanel">
                                        <form method="post" class="mt-2">
                                            <div class="form-row">

                                                <div class="form-group col-md-5">
                                                    <div class="input-group">
                                                        <input type="text" name="name" id="name" value="<?=($partner->name??null)?>" placeholder="Razão Social ou Nome" data-toggle="tooltip" class="form-control">
                                                        <div class="input-group-append">
                                                            <button type="button" class="input-group-text search-partner" data-toggle="modal" data-target="#search"><i class="fas fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-5">
                                                    <input type="text" name="trade_name" id="trade_name" value="<?=($partner->trade_name??null)?>" placeholder="Nome Fantasia" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <div class="input-group">
                                                        <input type="text" name="document01" id="document01" value="<?=(str_convert_to_document($partner->document01)??null)?>" placeholder="CNPJ/CPF" data-toggle="tooltip" class="form-control">
                                                        <div class="input-group-append">
                                                            <button type="button" class="input-group-text search-partner" data-toggle="modal" data-target="#search"><i class="fas fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <input type="text" name="document02" id="document02" value="<?=($partner->document02??null)?>" placeholder="IE/RG" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <input type="text" name="phone" id="phone" value="<?=(str_convert_to_phone($partner->phone)??null)?>" placeholder="Telefone" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <input type="text" name="email" id="email" value="<?=($partner->email??null)?>" placeholder="E-mail" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2 d-flex justify-content-center">
                                                    <div class="custom-control mt-2 custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" name="type" id="type" value="Fornecedor" class="custom-control-input" {{($partner->type == 'Fornecedor'? 'checked' : null)}}>
                                                        <label class="custom-control-label" for="type">Fornecedor?</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <?php
                                                        $dropSegments = ['0'=>'Segmento','1'=>'Transportadora'];
                                                        echo form_select(
                                                            'segment',
                                                            $dropSegments,
                                                            $partner->segment,
                                                            ['class' => 'custom-select']
                                                        );
                                                    ?>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <textarea name="obs" id="obs" placeholder="Observações" data-toggle="tooltip" class="form-control"><?=$partner->obs?></textarea>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    @csrf
                                                    <input type="hidden" name="partner_id" id="partner_id" value="<?=($partner->id??null)?>">
                                                    <button class="btn btn-outline-info float-right">Salvar Dados</button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <!-- ADDRESS DATA -->
                                    <div class="tab-pane fade" id="address-data" role="tabpanel">
                                        <form method="post" id="addressForm" action="{{route('adm.partner.saveAddress',['partner'=>$partner->id])}}" class="mt-2" >
                                            <div class="form-row">

                                                <div class="form-group col-md-2">
                                                    <select name="type" id="type" class="custom-select" placeholder="Tipo" data-toggle="tooltip">
                                                        <option value="Comercial">Comercial</option>
                                                        <option value="Fiscal">Fiscal</option>
                                                        <option value="Residencial">Residencial</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <input type="text" name="postal_code" id="postal_code" placeholder="Cep" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <input type="text" name="address" id="address" placeholder="Logradouro" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <input type="text" name="number" id="number" placeholder="Número" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="complement" id="complement" placeholder="Compemento" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="neighborhood" id="neighborhood" placeholder="Bairro" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="city" id="city" placeholder="Cidade" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-1">
                                                    <input type="text" name="state" id="state" placeholder="Estado" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <select name="country" id="country" class="custom-select" placeholder="País" data-toggle="tooltip">
                                                        <option value="Brasil" selected="selected">Brasil</option>
                                                        <option value="Afeganistão">Afeganistão</option>
                                                        <option value="África do Sul">África do Sul</option>
                                                        <option value="Albânia">Albânia</option>
                                                        <option value="Alemanha">Alemanha</option>
                                                        <option value="Andorra">Andorra</option>
                                                        <option value="Angola">Angola</option>
                                                        <option value="Anguilla">Anguilla</option>
                                                        <option value="Antilhas Holandesas">Antilhas Holandesas</option>
                                                        <option value="Antárctida">Antárctida</option>
                                                        <option value="Antígua e Barbuda">Antígua e Barbuda</option>
                                                        <option value="Argentina">Argentina</option>
                                                        <option value="Argélia">Argélia</option>
                                                        <option value="Armênia">Armênia</option>
                                                        <option value="Aruba">Aruba</option>
                                                        <option value="Arábia Saudita">Arábia Saudita</option>
                                                        <option value="Austrália">Austrália</option>
                                                        <option value="Áustria">Áustria</option>
                                                        <option value="Azerbaijão">Azerbaijão</option>
                                                        <option value="Bahamas">Bahamas</option>
                                                        <option value="Bahrein">Bahrein</option>
                                                        <option value="Bangladesh">Bangladesh</option>
                                                        <option value="Barbados">Barbados</option>
                                                        <option value="Belize">Belize</option>
                                                        <option value="Benim">Benim</option>
                                                        <option value="Bermudas">Bermudas</option>
                                                        <option value="Bielorrússia">Bielorrússia</option>
                                                        <option value="Bolívia">Bolívia</option>
                                                        <option value="Botswana">Botswana</option>
                                                        <option value="Brunei">Brunei</option>
                                                        <option value="Bulgária">Bulgária</option>
                                                        <option value="Burkina Faso">Burkina Faso</option>
                                                        <option value="Burundi">Burundi</option>
                                                        <option value="Butão">Butão</option>
                                                        <option value="Bélgica">Bélgica</option>
                                                        <option value="Bósnia e Herzegovina">Bósnia e Herzegovina</option>
                                                        <option value="Cabo Verde">Cabo Verde</option>
                                                        <option value="Camarões">Camarões</option>
                                                        <option value="Camboja">Camboja</option>
                                                        <option value="Canadá">Canadá</option>
                                                        <option value="Catar">Catar</option>
                                                        <option value="Cazaquistão">Cazaquistão</option>
                                                        <option value="Chade">Chade</option>
                                                        <option value="Chile">Chile</option>
                                                        <option value="China">China</option>
                                                        <option value="Chipre">Chipre</option>
                                                        <option value="Colômbia">Colômbia</option>
                                                        <option value="Comores">Comores</option>
                                                        <option value="Coreia do Norte">Coreia do Norte</option>
                                                        <option value="Coreia do Sul">Coreia do Sul</option>
                                                        <option value="Costa do Marfim">Costa do Marfim</option>
                                                        <option value="Costa Rica">Costa Rica</option>
                                                        <option value="Croácia">Croácia</option>
                                                        <option value="Cuba">Cuba</option>
                                                        <option value="Dinamarca">Dinamarca</option>
                                                        <option value="Djibouti">Djibouti</option>
                                                        <option value="Dominica">Dominica</option>
                                                        <option value="Egito">Egito</option>
                                                        <option value="El Salvador">El Salvador</option>
                                                        <option value="Emirados Árabes Unidos">Emirados Árabes Unidos</option>
                                                        <option value="Equador">Equador</option>
                                                        <option value="Eritreia">Eritreia</option>
                                                        <option value="Escócia">Escócia</option>
                                                        <option value="Eslováquia">Eslováquia</option>
                                                        <option value="Eslovênia">Eslovênia</option>
                                                        <option value="Espanha">Espanha</option>
                                                        <option value="Estados Federados da Micronésia">Estados Federados da Micronésia</option>
                                                        <option value="Estados Unidos">Estados Unidos</option>
                                                        <option value="Estônia">Estônia</option>
                                                        <option value="Etiópia">Etiópia</option>
                                                        <option value="Fiji">Fiji</option>
                                                        <option value="Filipinas">Filipinas</option>
                                                        <option value="Finlândia">Finlândia</option>
                                                        <option value="França">França</option>
                                                        <option value="Gabão">Gabão</option>
                                                        <option value="Gana">Gana</option>
                                                        <option value="Geórgia">Geórgia</option>
                                                        <option value="Gibraltar">Gibraltar</option>
                                                        <option value="Granada">Granada</option>
                                                        <option value="Gronelândia">Gronelândia</option>
                                                        <option value="Grécia">Grécia</option>
                                                        <option value="Guadalupe">Guadalupe</option>
                                                        <option value="Guam">Guam</option>
                                                        <option value="Guatemala">Guatemala</option>
                                                        <option value="Guernesei">Guernesei</option>
                                                        <option value="Guiana">Guiana</option>
                                                        <option value="Guiana Francesa">Guiana Francesa</option>
                                                        <option value="Guiné">Guiné</option>
                                                        <option value="Guiné Equatorial">Guiné Equatorial</option>
                                                        <option value="Guiné-Bissau">Guiné-Bissau</option>
                                                        <option value="Gâmbia">Gâmbia</option>
                                                        <option value="Haiti">Haiti</option>
                                                        <option value="Honduras">Honduras</option>
                                                        <option value="Hong Kong">Hong Kong</option>
                                                        <option value="Hungria">Hungria</option>
                                                        <option value="Ilha Bouvet">Ilha Bouvet</option>
                                                        <option value="Ilha de Man">Ilha de Man</option>
                                                        <option value="Ilha do Natal">Ilha do Natal</option>
                                                        <option value="Ilha Heard e Ilhas McDonald">Ilha Heard e Ilhas McDonald</option>
                                                        <option value="Ilha Norfolk">Ilha Norfolk</option>
                                                        <option value="Ilhas Cayman">Ilhas Cayman</option>
                                                        <option value="Ilhas Cocos (Keeling)">Ilhas Cocos (Keeling)</option>
                                                        <option value="Ilhas Cook">Ilhas Cook</option>
                                                        <option value="Ilhas Feroé">Ilhas Feroé</option>
                                                        <option value="Ilhas Geórgia do Sul e Sandwich do Sul">Ilhas Geórgia do Sul e Sandwich do Sul</option>
                                                        <option value="Ilhas Malvinas">Ilhas Malvinas</option>
                                                        <option value="Ilhas Marshall">Ilhas Marshall</option>
                                                        <option value="Ilhas Menores Distantes dos Estados Unidos">Ilhas Menores Distantes dos Estados Unidos</option>
                                                        <option value="Ilhas Salomão">Ilhas Salomão</option>
                                                        <option value="Ilhas Virgens Americanas">Ilhas Virgens Americanas</option>
                                                        <option value="Ilhas Virgens Britânicas">Ilhas Virgens Britânicas</option>
                                                        <option value="Ilhas Åland">Ilhas Åland</option>
                                                        <option value="Indonésia">Indonésia</option>
                                                        <option value="Inglaterra">Inglaterra</option>
                                                        <option value="Índia">Índia</option>
                                                        <option value="Iraque">Iraque</option>
                                                        <option value="Irlanda do Norte">Irlanda do Norte</option>
                                                        <option value="Irlanda">Irlanda</option>
                                                        <option value="Irã">Irã</option>
                                                        <option value="Islândia">Islândia</option>
                                                        <option value="Israel">Israel</option>
                                                        <option value="Itália">Itália</option>
                                                        <option value="Iêmen">Iêmen</option>
                                                        <option value="Jamaica">Jamaica</option>
                                                        <option value="Japão">Japão</option>
                                                        <option value="Jersey">Jersey</option>
                                                        <option value="Jordânia">Jordânia</option>
                                                        <option value="Kiribati">Kiribati</option>
                                                        <option value="Kuwait">Kuwait</option>
                                                        <option value="Laos">Laos</option>
                                                        <option value="Lesoto">Lesoto</option>
                                                        <option value="Letônia">Letônia</option>
                                                        <option value="Libéria">Libéria</option>
                                                        <option value="Liechtenstein">Liechtenstein</option>
                                                        <option value="Lituânia">Lituânia</option>
                                                        <option value="Luxemburgo">Luxemburgo</option>
                                                        <option value="Líbano">Líbano</option>
                                                        <option value="Líbia">Líbia</option>
                                                        <option value="Macau">Macau</option>
                                                        <option value="Macedônia">Macedônia</option>
                                                        <option value="Madagáscar">Madagáscar</option>
                                                        <option value="Malawi">Malawi</option>
                                                        <option value="Maldivas">Maldivas</option>
                                                        <option value="Mali">Mali</option>
                                                        <option value="Malta">Malta</option>
                                                        <option value="Malásia">Malásia</option>
                                                        <option value="Marianas Setentrionais">Marianas Setentrionais</option>
                                                        <option value="Marrocos">Marrocos</option>
                                                        <option value="Martinica">Martinica</option>
                                                        <option value="Mauritânia">Mauritânia</option>
                                                        <option value="Maurícia">Maurícia</option>
                                                        <option value="Mayotte">Mayotte</option>
                                                        <option value="Moldávia">Moldávia</option>
                                                        <option value="Mongólia">Mongólia</option>
                                                        <option value="Montenegro">Montenegro</option>
                                                        <option value="Montserrat">Montserrat</option>
                                                        <option value="Moçambique">Moçambique</option>
                                                        <option value="Myanmar">Myanmar</option>
                                                        <option value="México">México</option>
                                                        <option value="Mônaco">Mônaco</option>
                                                        <option value="Namíbia">Namíbia</option>
                                                        <option value="Nauru">Nauru</option>
                                                        <option value="Nepal">Nepal</option>
                                                        <option value="Nicarágua">Nicarágua</option>
                                                        <option value="Nigéria">Nigéria</option>
                                                        <option value="Niue">Niue</option>
                                                        <option value="Noruega">Noruega</option>
                                                        <option value="Nova Caledônia">Nova Caledônia</option>
                                                        <option value="Nova Zelândia">Nova Zelândia</option>
                                                        <option value="Níger">Níger</option>
                                                        <option value="Omã">Omã</option>
                                                        <option value="Palau">Palau</option>
                                                        <option value="Palestina">Palestina</option>
                                                        <option value="Panamá">Panamá</option>
                                                        <option value="Papua-Nova Guiné">Papua-Nova Guiné</option>
                                                        <option value="Paquistão">Paquistão</option>
                                                        <option value="Paraguai">Paraguai</option>
                                                        <option value="País de Gales">País de Gales</option>
                                                        <option value="Países Baixos">Países Baixos</option>
                                                        <option value="Peru">Peru</option>
                                                        <option value="Pitcairn">Pitcairn</option>
                                                        <option value="Polinésia Francesa">Polinésia Francesa</option>
                                                        <option value="Polônia">Polônia</option>
                                                        <option value="Porto Rico">Porto Rico</option>
                                                        <option value="Portugal">Portugal</option>
                                                        <option value="Quirguistão">Quirguistão</option>
                                                        <option value="Quênia">Quênia</option>
                                                        <option value="Reino Unido">Reino Unido</option>
                                                        <option value="República Centro-Africana">República Centro-Africana</option>
                                                        <option value="República Checa">República Checa</option>
                                                        <option value="República Democrática do Congo">República Democrática do Congo</option>
                                                        <option value="República do Congo">República do Congo</option>
                                                        <option value="República Dominicana">República Dominicana</option>
                                                        <option value="Reunião">Reunião</option>
                                                        <option value="Romênia">Romênia</option>
                                                        <option value="Ruanda">Ruanda</option>
                                                        <option value="Rússia">Rússia</option>
                                                        <option value="Saara Ocidental">Saara Ocidental</option>
                                                        <option value="Saint Martin">Saint Martin</option>
                                                        <option value="Saint-Barthélemy">Saint-Barthélemy</option>
                                                        <option value="Saint-Pierre e Miquelon">Saint-Pierre e Miquelon</option>
                                                        <option value="Samoa Americana">Samoa Americana</option>
                                                        <option value="Samoa">Samoa</option>
                                                        <option value="Santa Helena, Ascensão e Tristão da Cunha">Santa Helena, Ascensão e Tristão da Cunha</option>
                                                        <option value="Santa Lúcia">Santa Lúcia</option>
                                                        <option value="Senegal">Senegal</option>
                                                        <option value="Serra Leoa">Serra Leoa</option>
                                                        <option value="Seychelles">Seychelles</option>
                                                        <option value="Singapura">Singapura</option>
                                                        <option value="Somália">Somália</option>
                                                        <option value="Sri Lanka">Sri Lanka</option>
                                                        <option value="Suazilândia">Suazilândia</option>
                                                        <option value="Sudão">Sudão</option>
                                                        <option value="Suriname">Suriname</option>
                                                        <option value="Suécia">Suécia</option>
                                                        <option value="Suíça">Suíça</option>
                                                        <option value="Svalbard e Jan Mayen">Svalbard e Jan Mayen</option>
                                                        <option value="São Cristóvão e Nevis">São Cristóvão e Nevis</option>
                                                        <option value="São Marino">São Marino</option>
                                                        <option value="São Tomé e Príncipe">São Tomé e Príncipe</option>
                                                        <option value="São Vicente e Granadinas">São Vicente e Granadinas</option>
                                                        <option value="Sérvia">Sérvia</option>
                                                        <option value="Síria">Síria</option>
                                                        <option value="Tadjiquistão">Tadjiquistão</option>
                                                        <option value="Tailândia">Tailândia</option>
                                                        <option value="Taiwan">Taiwan</option>
                                                        <option value="Tanzânia">Tanzânia</option>
                                                        <option value="Terras Austrais e Antárticas Francesas">Terras Austrais e Antárticas Francesas</option>
                                                        <option value="Território Britânico do Oceano Índico">Território Britânico do Oceano Índico</option>
                                                        <option value="Timor-Leste">Timor-Leste</option>
                                                        <option value="Togo">Togo</option>
                                                        <option value="Tonga">Tonga</option>
                                                        <option value="Toquelau">Toquelau</option>
                                                        <option value="Trinidad e Tobago">Trinidad e Tobago</option>
                                                        <option value="Tunísia">Tunísia</option>
                                                        <option value="Turcas e Caicos">Turcas e Caicos</option>
                                                        <option value="Turquemenistão">Turquemenistão</option>
                                                        <option value="Turquia">Turquia</option>
                                                        <option value="Tuvalu">Tuvalu</option>
                                                        <option value="Ucrânia">Ucrânia</option>
                                                        <option value="Uganda">Uganda</option>
                                                        <option value="Uruguai">Uruguai</option>
                                                        <option value="Uzbequistão">Uzbequistão</option>
                                                        <option value="Vanuatu">Vanuatu</option>
                                                        <option value="Vaticano">Vaticano</option>
                                                        <option value="Venezuela">Venezuela</option>
                                                        <option value="Vietname">Vietname</option>
                                                        <option value="Wallis e Futuna">Wallis e Futuna</option>
                                                        <option value="Zimbabwe">Zimbabwe</option>
                                                        <option value="Zâmbia">Zâmbia</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    @csrf
                                                    <input type="hidden" name="address_id" id="address_id">
                                                    <button class="btn btn-outline-info float-right">Salvar Dados</button>
                                                </div>

                                            </div>
                                        </form>
                                        <hr>
                                        <h5>Endereços Cadastrados</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="addressesTable" class="table table-striped dataTable dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th>Endereço</th>
                                                        <th>Bairro</th>
                                                        <th>Cidade</th>
                                                        <th>Estado</th>
                                                        <th>CEP</th>
                                                        <th>Ativo</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CONTACT DATA -->
                                    <div class="tab-pane fade" id="contact-data" role="tabpanel">
                                        <form method="post" id="contactForm" action="{{route('adm.partner.saveContact',['partner'=>$partner->id])}}" class="mt-2">
                                            <div class="form-row">

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="name" id="name" placeholder="Nome" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="cellphone" id="cellphone" placeholder="Celular" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="email" id="email" placeholder="E-mail" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="position" id="position" placeholder="Cargo" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    @csrf
                                                    <input type="hidden" name="contact_id" id="contact_id">
                                                    <button class="btn btn-outline-info float-right">Salvar Dados</button>
                                                </div>

                                            </div>
                                        </form>
                                        <hr>
                                        <h5>Contatos Cadastrados</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="contactsTable" class="table table-striped dataTable dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Celular</th>
                                                        <th>E-mail</th>
                                                        <th>Cargo</th>
                                                        <th>Ativo</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SERVICE DATA -->
                                    <div class="tab-pane fade" id="service-data" role="tabpanel">
                                        <form method="post" id="serviceForm" action="{{route('adm.partner.saveService',['partner'=>$partner->id])}}" class="mt-2">
                                            <div class="form-row">

                                                <div class="form-group col-md-7">
                                                    <?=form_select('service_id',($dropServices??null),null,['id'=>'service_id','placeholder'=>'Serviço','data-toggle'=>'tooltip','class'=>'select2'],'Selecione o Serviço')?>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">R$</span>
                                                        </div>
                                                        <input type="text" name="price" id="price" placeholder="Preço" pattern="^\d*(\,\d{0,2})?$"  data-toggle="tooltip" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    @csrf
                                                    <input type="hidden" name="partner_service_id" id="partner_service_id">
                                                    <button class="btn btn-block btn-outline-info">Salvar Dados</button>
                                                </div>

                                            </div>
                                        </form>
                                        <hr>
                                        <h5>Servicos Cadastrados</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="servicesTable" class="table table-striped dataTable dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        <th>Preço Padrão</th>
                                                        <th>Preço Negociado</th>
                                                        <th>Ativo</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="search">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Resultado da Busca</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="row">
                        <div class="col-12 p-3">
                            <table id="search-table" class="table table-striped w-100">
                                <thead>
                                <tr>
                                    <th>Razão ou Nome</th>
                                    <th>CNPJ/CPF</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('js/buscacep.js')}}"></script>
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#search-table').DataTable({
                "language": {
                    "url": base_url+"/vendor/datatables/locales/pt-br.json"
                },
                "responsive": true
            });

            if({{($partner->id??0)}} > 0){
                loadDataTable("{{route('adm.partner.getAddresses',['partner'=>($partner->id??null)])}}", 'addressesTable');
                loadDataTable("{{route('adm.partner.getContacts',['partner'=>($partner->id??null)])}}", 'contactsTable');
                loadDataTable("{{route('adm.partner.getServices',['partner'=>($partner->id??null)])}}", 'servicesTable');
            }

        });
        $('.search-partner').click(function(){
            let $dv = $($(this).parents().get(1));
            let $field = $dv.find('input');
            let field = $field.attr('name');
            let value = $field.val();
            $.post("{{route('adm.partner.search')}}",{[field]:value, _token:'{{csrf_token()}}' },function(data, status){
                data = JSON.parse(data);
                if(status === 'success'){
                    var table = $('#search-table').DataTable();
                    table.clear();
                    table.rows.add(data.data).draw();
                }
            });
        });
    </script>
@endsection
