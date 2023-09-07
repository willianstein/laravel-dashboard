<?php

namespace App\Models\DataTransferObjects;

use DateTime;

class OrderDto {

    private string $type;
    private int $office_id;
    private int $partner_id;
    private string $status;
    private ?int $id;
    private ?int $recipient_id;
    private ?int $transport_id;
    private ?string $invoice;
    private ?string $content_declaration;
    private ?DateTime $forecast;
    private ?string $third_system;
    private ?string $third_system_id;
    private ?string $observations;

    public function __construct(
        string      $type,
        int         $office_id,
        int         $partner_id,
        string      $status,
        int         $id = null,
        int         $recipient_id           = null,
        int         $transport_id           = null,
        string      $invoice                = null,
        string      $content_declaration    = null,
        DateTime    $forecast               = null,
        string      $third_system           = null,
        string      $third_system_id        = null,
        string      $observations           = null
    ) {
        $this->type                     = $type;
        $this->office_id                = $office_id;
        $this->partner_id               = $partner_id;
        $this->status                   = $status;
        $this->id                       = $id;
        $this->recipient_id             = $recipient_id;
        $this->transport_id             = $transport_id;
        $this->invoice                  = $invoice;
        $this->content_declaration      = $content_declaration;
        $this->forecast                 = $forecast;
        $this->third_system             = $third_system;
        $this->third_system_id          = $third_system_id;
        $this->observations             = $observations;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getOfficeId(): int {
        return $this->office_id;
    }

    /**
     * @return int
     */
    public function getPartnerId(): int {
        return $this->partner_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getRecipientId(): ?int {
        return $this->recipient_id;
    }

    /**
     * @return int|null
     */
    public function getTransportId(): ?int {
        return $this->transport_id;
    }

    /**
     * @return string|null
     */
    public function getInvoice(): ?string {
        return $this->invoice;
    }

    /**
     * @return string|null
     */
    public function getContentDeclaration(): ?string {
        return $this->content_declaration;
    }

    /**
     * @return DateTime|null
     */
    public function getForecast(): ?DateTime {
        return $this->forecast;
    }

    /**
     * @return string|null
     */
    public function getThirdSystem(): ?string {
        return $this->third_system;
    }

    /**
     * @return string|null
     */
    public function getThirdSystemId(): ?string {
        return $this->third_system_id;
    }

    /**
     * @return string|null
     */
    public function getObservations(): ?string {
        return $this->observations;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    /**
     * @param int $office_id
     * @return $this
     */
    public function setOfficeId(int $office_id): self {
        $this->office_id = $office_id;
        return $this;
    }

    /**
     * @param int $partner_id
     * @return $this
     */
    public function setPartnerId(int $partner_id): self {
        $this->partner_id = $partner_id;
        return $this;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int|null $recipient_id
     * @return $this
     */
    public function setRecipientId(?int $recipient_id): self {
        $this->recipient_id = $recipient_id;
        return $this;
    }

    /**
     * @param int|null $transport_id
     * @return $this
     */
    public function setTransportId(?int $transport_id): self {
        $this->transport_id = $transport_id;
        return $this;
    }

    /**
     * @param string|null $invoice
     * @return $this
     */
    public function setInvoice(?string $invoice): self {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @param string|null $content_declaration
     * @return $this
     */
    public function setContentDeclaration(?string $content_declaration): self {
        $this->content_declaration = $content_declaration;
        return $this;
    }

    /**
     * @param DateTime|null $forecast
     * @return $this
     */
    public function setForecast(?DateTime $forecast): self {
        $this->forecast = $forecast;
        return $this;
    }

    /**
     * @param string|null $third_system
     * @return $this
     */
    public function setThirdSystem(?string $third_system): self {
        $this->third_system = $third_system;
        return $this;
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
     * @param string|null $observations
     * @return $this
     */
    public function setObservations(?string $observations): self {
        $this->observations = $observations;
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
