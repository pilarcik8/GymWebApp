<?php

namespace App\Models;

use Framework\Core\Model;

class Pass extends Model
{
    protected ?int $id = null;
    protected ?int $user_id = null;
    protected ?string $purchase_date = '';
    protected ?string $expiration_date = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getPurchaseDate(): ?string
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(string $purchase_date): void
    {
        $this->purchase_date = $purchase_date;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(string $expiration_date): void
    {
        $this->expiration_date = $expiration_date;
    }
}
