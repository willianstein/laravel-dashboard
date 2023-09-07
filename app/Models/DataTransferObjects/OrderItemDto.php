<?php

namespace App\Models\DataTransferObjects;

class OrderItemDto {

    private int     $order_id;
    private int     $product_id;
    private string  $isbn;
    private int     $quantity;
    private string  $status;

    public function __construct(
        int     $order_id,
        int     $product_id,
        string  $isbn,
        int     $quantity,
        string  $status
    ) {
        $this->order_id     = $order_id;
        $this->product_id   = $product_id;
        $this->isbn         = $isbn;
        $this->quantity     = $quantity;
        $this->status       = $status;
    }

    /**
     * @return int
     */
    public function getOrderId(): int {
        return $this->order_id;
    }

    /**
     * @return int
     */
    public function getProductId(): int {
        return $this->product_id;
    }

    /**
     * @return string
     */
    public function getIsbn(): string {
        return $this->isbn;
    }

    /**
     * @return int
     */
    public function getQuantity(): int {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @param int $order_id
     * @return $this
     */
    public function setOrderId(int $order_id): self {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @param int $product_id
     * @return $this
     */
    public function setProductId(int $product_id): self {
        $this->product_id = $product_id;
        return $this;
    }

    /**
     * @param string $isbn
     * @return $this
     */
    public function setIsbn(string $isbn): self {
        $this->isbn = $isbn;
        return $this;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;
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
     * RETORNA OS DADOS EM ARRAY
     * @return array
     */
    public function toArray(): array {
        return get_object_vars($this);
    }

}
