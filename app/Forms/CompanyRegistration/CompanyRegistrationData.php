<?php declare(strict_types = 1);

namespace App\Forms\CompanyRegistration;

class CompanyRegistrationData
{
    public const KEY_NAME = 'name';
    public const KEY_ADDRESS = 'address';
    public const KEY_IN = 'in';
    public const KEY_TIN = 'tin';
    public const KEY_STATUTORY_REPRESENTATIVE_NAME = 'statutoryRepresentativeName';
    public const KEY_STATUTORY_REPRESENTATIVE_EMAIL = 'statutoryRepresentativeEmail';

    public string $name;
    public string $address;
    public string $in;
    public string $tin;
    public string $statutoryRepresentativeName;
    public ?string $statutoryRepresentativeEmail;

    /**
     * @param array<string, string|null> $values
     * @return self
     */
    public static function createFromArray(array $values): self
    {
        $instance = new self();

        $instance->name = $values[self::KEY_NAME];
        $instance->address = $values[self::KEY_ADDRESS];
        $instance->in = $values[self::KEY_IN];
        $instance->tin = $values[self::KEY_TIN];
        $instance->statutoryRepresentativeName = $values[self::KEY_STATUTORY_REPRESENTATIVE_NAME];
        $instance->statutoryRepresentativeEmail = $values[self::KEY_STATUTORY_REPRESENTATIVE_EMAIL];

        return $instance;
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            self::KEY_NAME => $this->name,
            self::KEY_ADDRESS => $this->address,
            self::KEY_IN => $this->in,
            self::KEY_TIN => $this->tin,
            self::KEY_STATUTORY_REPRESENTATIVE_NAME => $this->statutoryRepresentativeName,
            self::KEY_STATUTORY_REPRESENTATIVE_EMAIL => $this->statutoryRepresentativeEmail,
        ];
    }
}