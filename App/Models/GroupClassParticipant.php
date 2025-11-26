<?php

namespace App\Models;

use Framework\Core\Model;

class GroupClassParticipant extends Model
{
    protected ?int $id = null;
    protected ?int $customer_id = null;
    protected ?int $group_class_id = null;
    protected ?string $customer_note = '';

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

    public function getGroupClassId(): ?int
    {
        return $this->group_class_id;
    }

    public function setGroupClassId(int $group_class_id): void
    {
        $this->group_class_id = $group_class_id;
    }

    public function getCustomerNote(): ?string
    {
        return $this->customer_note;
    }

    public function setCustomerNote(string $customer_note): void
    {
        $this->customer_note = $customer_note;
    }
}
