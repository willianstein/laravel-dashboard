<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use CliqueTI\NfeXmlHandler\Nfe;

require __DIR__.'/../vendor/autoload.php';

$nfe = Nfe::arquivoXml(__DIR__.'/xml.xml');

var_dump($nfe->protocolo());