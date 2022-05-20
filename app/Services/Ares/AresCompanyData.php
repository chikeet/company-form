<?php declare(strict_types = 1);

namespace App\Services\Ares;

class AresCompanyData
{
    private string $in;
    private string $tin;
    private string $name;
    private string $address;

    public function __construct(
        string $in,
        string $tin,
        string $name,
        string $address
    )
    {
        $this->in = $in;
        $this->tin = $tin;
        $this->name = $name;
        $this->address = $address;
    }

    public function getIn(): string
    {
        return $this->in;
    }

    public function getTin(): string
    {
        return $this->tin;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}