<?php

namespace App\Models\DataTransferObjects;

class TransportDto {

    private string $modality;
    private ?string $carrier_name;
    private ?string $packaging;
    private ?string $driver;
    private ?string $driver_document;
    private ?string $car_model;
    private ?string $car_type;
    private ?string $car_plate;

    public function __construct(
        string $modality,
        string $carrier_name = null,
        string $packaging = null,
        string $driver = null,
        string $driver_document = null,
        string $car_model = null,
        string $car_type = null,
        string $car_plate = null
    ) {
        $this->modality = $modality;
        $this->carrier_name = $carrier_name;
        $this->packaging = $packaging;
        $this->driver = $driver;
        $this->driver_document = $driver_document;
        $this->car_model = $car_model;
        $this->car_type = $car_type;
        $this->car_plate = $car_plate;
    }

    /**
     * @return string
     */
    public function getModality(): string {
        return $this->modality;
    }

    /**
     * @return string|null
     */
    public function getCarrierName(): ?string {
        return $this->carrier_name;
    }

    /**
     * @return string|null
     */
    public function getPackaging(): ?string {
        return $this->packaging;
    }

    /**
     * @return string|null
     */
    public function getDriver(): ?string {
        return $this->driver;
    }

    /**
     * @return string|null
     */
    public function getDriverDocument(): ?string {
        return $this->driver_document;
    }

    /**
     * @return string|null
     */
    public function getCarModel(): ?string {
        return $this->car_model;
    }

    /**
     * @return string|null
     */
    public function getCarType(): ?string {
        return $this->car_type;
    }

    /**
     * @return string|null
     */
    public function getCarPlate(): ?string {
        return $this->car_plate;
    }

    /**
     * RETORNA OS DADOS EM ARRAY
     * @return array
     */
    public function toArray(): array {
        return get_object_vars($this);
    }

}
