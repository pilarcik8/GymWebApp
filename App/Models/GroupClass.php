<?php

namespace App\Models;

use Framework\Core\Model;

class GroupClass extends Model {
    protected static ?string $tableName = 'group_classes';

    protected ?int $id = null;
    protected string $name;
    protected string $start_datetime;
    protected int $duration_minutes;
    protected int $trainer_id;
    protected int $capacity;
    protected ?string $description;

    public function __construct(
        string $name = '',
        string $start_datetime = '',
        int $duration_minutes = 0,
        int $trainer_id = 0,
        int $capacity = 0,
        ?string $description = null
    ) {
        $this->name = $name;
        $this->start_datetime = $start_datetime;
        $this->duration_minutes = $duration_minutes;
        $this->trainer_id = $trainer_id;
        $this->capacity = $capacity;
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartDatetime(): string
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(string $start_datetime): void
    {
        $this->start_datetime = $start_datetime;
    }

    public function getDurationMinutes(): int
    {
        return $this->duration_minutes;
    }

    public function setDurationMinutes(int $duration_minutes): void
    {
        $this->duration_minutes = $duration_minutes;
    }

    public function getTrainerId(): int
    {
        return $this->trainer_id;
    }

    public function setTrainerId(int $trainer_id): void
    {
        $this->trainer_id = $trainer_id;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
