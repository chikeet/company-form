<?php declare(strict_types = 1);

namespace App\Forms\CompanyIn;

use Nette;
use Nette\Application\UI\Form;

class CompanyInForm extends Form
{
    private const REQUIRED_MESSAGE = 'Vyplňte prosím pole %label.';

    public function __construct(?Nette\ComponentModel\IContainer $parent = null, ?string $name = null)
    {
        parent::__construct($parent, $name);

        $this->setup();
    }

    public function setup(): void
    {
        $this->addText('in', 'IČO:')
            ->setRequired(self::REQUIRED_MESSAGE)
            ->addRule(self::PATTERN, '%label musí mít 8 čísel.', '^\d{8}$');

        $this->addSubmit('submit', 'Načíst data z ARESu');
    }
}