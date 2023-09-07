<?php

namespace CliqueTI\NfeXmlHandler;

use Exception;

/**
 * COMPONENTE PARA MANIPULAÇÃO DE ARQUIVO XML DA NFe
 */
class Nfe {

    use Versoes;

    public static $fail;

    /**
     * @param string|null $file
     * @return Contracts\Nfe|null
     */
    public static function arquivoXml(string $file = null) {

        if(empty($file) || !$conteudo = file_get_contents($file)){
            return null;
        }

        return self::conteudoXml($conteudo);

    }

    /**
     * @param string|null $conteudo
     * @return Contracts\Nfe|null
     */
    public static function conteudoXml(string $conteudo = null) {

        if (empty($conteudo)){
            return null;
        }

        return self::carregaXml(simplexml_load_string($conteudo));
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name) {
        $method = $this->toCamelCase($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        if (method_exists($this, $name)) {
            return $this->$name();
        }

        return ($this->$name ?? null);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function toCamelCase(string $string): string {
        $camelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $camelCase[0] = strtolower($camelCase[0]);
        return $camelCase;
    }

}