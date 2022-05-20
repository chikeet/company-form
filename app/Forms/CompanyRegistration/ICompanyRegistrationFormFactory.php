<?php declare(strict_types = 1);

namespace App\Forms\CompanyRegistration;

interface ICompanyRegistrationFormFactory
{
    public function create(): CompanyRegistrationForm;
}