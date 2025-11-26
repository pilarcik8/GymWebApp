<?php

namespace App\Models;

use Framework\Core\Model;

class Training extends Model
{
    protected ?int $id = null;
    protected ?int $customer_id = null;
    protected ?int $trainer_id = null;
    protected ?string $purchase_date = null;
    protected ?string $start_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    public function getTrainerId(): ?int
    {
        return $this->trainer_id;
    }

    public function setTrainerId(int $trainer_id): void
    {
        $this->trainer_id = $trainer_id;
    }

    public function getPurchaseDate(): ?string
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(string $purchase_date): void
    {
        $this->purchase_date = $purchase_date;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function setStartDate(string $start_date): void
    {
        $this->start_date = $start_date;
    }
}
