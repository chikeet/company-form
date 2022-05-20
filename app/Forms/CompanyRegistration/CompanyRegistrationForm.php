<?php declare(strict_types = 1);

namespace App\Forms\CompanyRegistration;

use Nette;
use Nette\Application\UI\Form;

class CompanyRegistrationForm extends Form
{
    private const REQUIRED_MESSAGE = 'Vyplňte prosím pole %label.';

    public function __construct(?Nette\ComponentModel\IContainer $parent = null, ?string $name = null)
    {
        parent::__construct($parent, $name);

        $this->setup();
    }

    public function setup(): void
    {
        $this->addText(CompanyRegistrationData::KEY_NAME, 'Název firmy:')
            ->setRequired(self::REQUIRED_MESSAGE)
            ->addRule(self::MIN_LENGTH, '%label musí mít alespoň %d znaky.', 3);

        $this->addTextArea(CompanyRegistrationData::KEY_ADDRESS, 'Adresa sídla:', 40, 5)
            ->setRequired(self::REQUIRED_MESSAGE)
            ->addRule(self::MIN_LENGTH, '%label musí mít alespoň %d znaků.', 10);

        $this->addText(CompanyRegistrationData::KEY_IN, 'IČO:')
            ->setRequired(self::REQUIRED_MESSAGE)
            ->addRule(self::PATTERN, '%label musí mít 8 čísel.', '^\d{8}$');

        $this->addText(CompanyRegistrationData::KEY_TIN, 'DIČ:')
            ->setRequired(self::REQUIRED_MESSAGE)
            ->addRule(self::PATTERN, '%label musí mít 2 velká písmena a 8 - 10 čísel.', '^[A-Z]{2}\d{8,10}$');

        $this->addText(CompanyRegistrationData::KEY_STATUTORY_REPRESENTATIVE_NAME, 'Jméno statutárního zástupce:')
            ->setRequired(self::REQUIRED_MESSAGE)
            ->addRule(self::MIN_LENGTH, '%label musí mít alespoň %d znaků.', 5);

        $this->addText(CompanyRegistrationData::KEY_STATUTORY_REPRESENTATIVE_EMAIL, 'E-mailová adresa statutárního zástupce:')
            ->addRule(self::EMAIL, '%label musí být e-mail v platném formátu.');

        $this->addSubmit('submit', 'Registrovat');
    }
}