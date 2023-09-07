<?php

namespace App\Models\DataTransferObjects;

class RecipientDto {

    private string $name;
    private string $document01;
    private string $postal_code;
    private string $address;
    private string $number;
    private string $neighborhood;
    private string $city;
    private string $state;
    private string $country;
    private ?string $complement;

    public function __construct(
        string $name,
        string $document01,
        string $postal_code,
        string $address,
        string $number,
        string $neighborhood,
        string $city,
        string $state,
        string $country,
        string $complement = null
    ) {
        $this->name = $name;
        $this->document01 = $document01;
        $this->postal_code = $postal_code;
        $this->address = $address;
        $this->number = $number;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->complement = $complement;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDocument01(): string {
        return $this->document01;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string {
        return $this->postal_code;
    }

    /**
     * @return string
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getNumber(): string {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getNeighborhood(): string {
        return $this->neighborhood;
    }

    /**
     * @return string
     */
    public function getCity(): string {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState(): string {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCountry(): string {
        return $this->country;
    }

    /**
     * @return string|null
     */
    public function getComplement(): ?string {
        return $this->complement;
    }

    /**
     * RETORNA OS DADOS EM ARRAY
     * @return array
     */
    public function toArray(): array {
        return get_object_vars($this);
    }

}
