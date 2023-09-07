<?php

use CliqueTI\Horus\Transports;

require __DIR__.'/../../vendor/autoload.php';

$filters = [
    'COD_EMPRESA' => 1,
    'COD_FILIAL' => 1,
    'COD_PED_VENDA' => null,
    'OFFSET' => null,
    'LIMIT' => null
];

$transports = (new Transports(
    'http://seu_local/Horus/api/TServerB2B',
    'seu-usuario',
    'sua-senha'
))->search($filters);

if($transports->error()){
    var_dump($transports->error());
    die();
}

var_dump($transports->response());