<?php declare(strict_types = 1);

namespace App\Services\CompanyRegistration;

use App\Forms\CompanyRegistration\CompanyRegistrationData;
use Nette\Http\Session;

class CompanyRegistrationSessionService
{
    private const REGISTRATION_SESSION_NAME = 'companyRegistration';
    private const REGISTRATION_SESSION_KEY = 'companyRegistrationData';

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isSessionActive(): bool
    {
        return $this->session->hasSection(self::REGISTRATION_SESSION_NAME);
    }

    public function saveCompanyRegistrationDataToSession(CompanyRegistrationData $data): void
    {
        $section = $this->session->getSection(self::REGISTRATION_SESSION_NAME);
        $section->set(self::REGISTRATION_SESSION_KEY, $data->toArray());
        $this->session->close();
    }

    public function getCompanyRegistrationDataFromSession(): CompanyRegistrationData
    {
        $sessionData = $this->session->getSection(self::REGISTRATION_SESSION_NAME)
            ->offsetGet(self::REGISTRATION_SESSION_KEY);

        return CompanyRegistrationData::createFromArray($sessionData);
    }

    public function cleanCompanyRegistrationDataFromSession(): void
    {
        $section = $this->session->getSection(self::REGISTRATION_SESSION_NAME);
        $section->remove(self::REGISTRATION_SESSION_KEY);
        $this->session->close();
    }
}