<?php

namespace App\Models;

use Framework\Core\IIdentity;
use Framework\Core\Model;

class Account extends Model implements IIdentity
{
    protected ?int $id = null;
    protected string $role = 'customer'; // ! v databaze enum
    protected string $first_name;
    protected string $last_name;
    protected string $email;
    protected string $password;
    protected float $credit = 0.0;

    public function __construct(
        string $email = '',
        string $password = '',
        string $first_name = '',
        string $last_name = ''
    ) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
    }


    // ID
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    // Role
    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    // First Name
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    // Last Name
    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    // Email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Password
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // Credit
    public function getCredit(): float
    {
        return $this->credit;
    }

    public function setCredit(float $credit): void
    {
        $this->credit = $credit;
    }

    public function getName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
}
