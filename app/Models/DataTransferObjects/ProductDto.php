<?php

namespace App\Models\DataTransferObjects;

class ProductDto {

    private string $isbn;
    private string $title;
    private int $height;
    private int $width;
    private int $length;
    private int $weight;
    private ?string $publisher;
    private ?string $category;
    private ?string $synopsis;
    private ?string $cover;
    private ?int $id;

    public function __construct(
        string $isbn,
        string $title,
        int $height,
        int $width,
        int $length,
        int $weight,
        string $publisher = null,
        string $category = null,
        string $synopsis = null,
        string $cover = null,
        int $id = null
    ) {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
        $this->weight = $weight;
        $this->publisher = $publisher;
        $this->category = $category;
        $this->synopsis = $synopsis;
        $this->cover = $cover;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIsbn(): string {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getHeight(): int {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getLength(): int {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getWeight(): int {
        return $this->weight;
    }

    /**
     * @return string|null
     */
    public function getPublisher(): ?string {
        return $this->publisher;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string {
        return $this->category;
    }

    /**
     * @return string|null
     */
    public function getSynopsis(): ?string {
        return $this->synopsis;
    }

    /**
     * @return string|null
     */
    public function getCover(): ?string {
        return $this->cover;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * RETORNA OS DADOS EM ARRAY
     * @return array
     */
    public function toArray(): array {
        return get_object_vars($this);
    }

}
