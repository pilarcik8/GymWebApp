<?php

namespace App\Models;

use Framework\Core\Model;

class TrainerInfo extends Model
{
    protected static ?string $tableName = 'trainer_info';

    protected ?int $id = null;
    protected string $short = '';
    protected string $description = '';
    protected int $trainer_id;
    protected ?int $image_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShort(): string
    {
        return $this->short;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTrainerId(): int
    {
        return $this->trainer_id;
    }

    public function getImageId(): ?int
    {
        return $this->image_id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setShort(string $short): void
    {
        $this->short = $short;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setTrainerId(int $trainer_id): void
    {
        $this->trainer_id = $trainer_id;
    }

    public function setImageId(?int $image_id): void
    {
        $this->image_id = $image_id;
    }
}

