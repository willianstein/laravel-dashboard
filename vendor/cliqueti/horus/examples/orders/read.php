<?php

use CliqueTI\Horus\Orders;

require __DIR__.'/../../vendor/autoload.php';

$filters = [
    'OFFSET'        => 1,
    'LIMIT'         => 25,
    'COD_EMPRESA'   => 1,
    'COD_FILIAL'    => 1,
];

$orders = (new Orders(
    'http://seu_local/Horus/api/TServerB2B',
    'meu-usuario',
    'minha-senha'
))->search($filters);

if($orders->error()){
    var_dump($orders->error());
    die();
}

var_dump($orders->response());