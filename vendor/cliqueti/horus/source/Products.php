<?php

namespace CliqueTI\Horus;

/**
 *  PRODUCT CLASS
 */
class Products extends Horus {

    /**
     * CONSTRUCT
     * @param string $apiUrl
     * @param string $user
     * @param string $password
     * @param bool $sslVerifypeer
     */
    public function __construct(string $apiUrl, string $user, string $password, bool $sslVerifypeer = true) {
        parent::__construct($apiUrl, $user, $password, $sslVerifypeer);
    }

    /**
     * SEARCH (SEE EXAMPLES FOLDER FOR EXAMPLE)
     * @param array|null $fields
     * @return $this
     */
    public function search(array $fields=null): self {

        $this->fields($fields, 'query');

        $this->request('Busca_Acervo','GET');

        return $this;
    }
}