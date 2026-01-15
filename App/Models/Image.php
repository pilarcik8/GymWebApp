<?php

namespace App\Models;

use Framework\Core\Model;

class Image extends Model
{
    protected ?int $id = null;
    protected string $filename = '';
    protected ?string $title = null;
    protected ?string $alt = null;
    protected ?int $created_by = null;
    protected ?string $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    public function setCreatedBy(?int $created_by): void
    {
        $this->created_by = $created_by;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }
}
