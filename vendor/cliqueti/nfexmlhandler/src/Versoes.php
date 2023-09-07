<?php

namespace CliqueTI\NfeXmlHandler;

use Exception;
use SimpleXMLElement;

trait Versoes {

    private static $versoes = [
        '4.00' => \CliqueTI\NfeXmlHandler\Nfe40\LeitorXml::class,
    ];

    /**
     * @param SimpleXMLElement | bool $xml
     * @return \CliqueTI\NfeXmlHandler\Contracts\Nfe
     */
    private static function carregaXml($xml) {

        if(empty($xml->attributes())){
            self::$fail = 'Não foi possivel verificar a versão da NFe.';
            return null;
        }

        if(!$versao = strval($xml->attributes()->versao)){
            self::$fail = 'Não foi possivel verificar a versão da NFe.';
            return null;
        }

        if(!key_exists($versao,self::$versoes)){
            self::$fail = "A versão {$versao} não é suportada";
            return null;
        }

        $class = self::$versoes[$versao];
        return (new $class($xml));

    }

}