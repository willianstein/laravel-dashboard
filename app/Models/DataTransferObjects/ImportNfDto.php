<?php

namespace App\Models\DataTransferObjects;

use DateTime;

class ImportNfDto {

    private ?string $third_system_id;
    private ?string $xmlString;
    private ?string $number_nf;
    private ?string $third_system_code_nf;

    public function __construct(
        string $third_system_id = null,
        string $xmlString       = null,
        string $number_nf       = null,
        string $third_system_code_nf = null

    ) {
        $this->third_system_id = $third_system_id;
        $this->xmlString = $xmlString;
        $this->number_nf = $number_nf;
        $this->third_system_code_nf = $third_system_code_nf;
    }

    /**
     * @return string|null
     */
    public function getThirdSystemId(): ?string {
        return $this->third_system_id;
    }

    /**
     * @return string
     */
    public function getXmlString(): string {
        return $this->xmlString;
    }

    /**
     * @return string|null
     */
    public function getNumberNf(): ?string {
        return $this->number_nf;
    }

    /**
     * @return string|null
     */
    public function getThirdSystemCodeNf(): ?string {
        return $this->third_system_code_nf;
    }

    /**
     * @param string|null $third_system_id
     * @return $this
     */
    public function setThirdSystemId(?string $third_system_id): self {
        $this->third_system_id = $third_system_id;
        return $this;
    }

    /**
     * @param string $xmlString
     * @return $this
     */
    public function setXmlString(string $xmlString): self {
        $this->xmlString = $xmlString;
        return $this;
    }

    /**
     * @param string $numberNf
     * @return $this
     */
    public function setNumberNf(string $numberNf): self {
        $this->number_nf = $numberNf;
        return $this;
    }

    /**
     * @param string|null $third_system_code_nf
     * @return $this
     */
    public function setThirdSystemCodeNf(?string $third_system_code_nf): self {
        $this->third_system_code_nf = $third_system_code_nf;
        return $this;
    }



    /**
     * RETORNA OS DADOS EM ARRAY
     * @return array
     */
    public function toArray(): array {
        return get_object_vars($this);
    }

}
