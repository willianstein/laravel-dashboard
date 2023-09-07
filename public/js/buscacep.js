/**
 *  BIBLIOTECA DE BUSCA DE CEP
 *  FONTE: Via CEP
 *  By: Paulo Brandeburski - paulo@cliqueti.com.br
 *  Depends: JQuery
 * */

/* Endereço do WebService */
const url = 'https://viacep.com.br/ws'; // ApiURL

/* Tipo de Captura (Trigger) */
const captureType = 'blur'; // blur or click

/* Campos (Fields) */
var $endereco   = 'address';        // Address
var numero      = 'number';         // Number
var $bairro     = 'neighborhood';   // Neighborhood
var $cidade     = 'city';           // City
var $estado     = 'state';          // State
var $cep        = 'postal_code';    // Postal Code

/* Prepara Campos (Prepare) */
$endereco   = $('#'+$endereco);
$bairro     = $('#'+$bairro);
$cidade     = $('#'+$cidade);
$estado     = $('#'+$estado);
$cep        = $('#'+$cep);

/* Desabilita Campos (Disable Fields) */
function disableFields(disabled = false) {
    $endereco.attr('disabled',disabled);
    $bairro.attr('disabled',disabled);
    $cidade.attr('disabled',disabled);
    $estado.attr('disabled',disabled);
    $cep.attr('disabled',disabled);
}

/* Busca Endereço (Get Address) */
$cep.on(captureType, function() {

    disableFields(true);
    let cep = $cep.val().replace(/\D/g, '');

    if($cep.val().length < 8){
        disableFields();
        return;
    }

    $.getJSON(url+"/"+cep+"/json/", function(data,status){
        if(status === 'success'){
            $endereco.val(data.logradouro);
            $bairro.val(data.bairro);
            $cidade.val(data.localidade);
            $estado.val(data.uf);
            $('#'+numero).focus();
        }
        disableFields(false);
    });
});




