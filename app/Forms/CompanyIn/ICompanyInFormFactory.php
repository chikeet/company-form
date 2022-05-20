<?php declare(strict_types = 1);

namespace App\Forms\CompanyIn;

interface ICompanyInFormFactory
{
    public function create(): CompanyInForm;
}