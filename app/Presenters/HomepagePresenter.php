<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Forms\CompanyRegistration\CompanyRegistrationData;
use App\Forms\CompanyRegistration\CompanyRegistrationForm;
use App\Forms\CompanyRegistration\ICompanyRegistrationFormFactory;
use App\Services\CompanyRegistration\CompanyRegistrationSessionService;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private ICompanyRegistrationFormFactory $companyRegistrationFormFactory;

    private CompanyRegistrationSessionService $companyRegistrationSessionService;


    public function __construct(
        ICompanyRegistrationFormFactory $companyRegistrationFormFactory,
        CompanyRegistrationSessionService $companyRegistrationSessionService
    ) {
        parent::__construct();

        $this->companyRegistrationFormFactory = $companyRegistrationFormFactory;
        $this->companyRegistrationSessionService = $companyRegistrationSessionService;
    }

    /* Actions ********************************************************************************************************/

    public function actionDefault()
    {
        $this->companyRegistrationSessionService->cleanCompanyRegistrationDataFromSession();
    }

    public function actionResult()
    {
        if (!$this->companyRegistrationSessionService->isSessionActive()) {
            $this->flashMessage('Vyplňte prosím údaje o firmě.', 'error');
            $this->redirect('Homepage:default');
        }
    }

    /* Renders ********************************************************************************************************/

    public function renderResult()
    {
        $this->template->companyRegistrationData = $this->companyRegistrationSessionService->getCompanyRegistrationDataFromSession();
    }

    /* Components *****************************************************************************************************/

    protected function createComponentRegistrationForm(): CompanyRegistrationForm
    {
        $form = $this->companyRegistrationFormFactory->create();

        $form->onSuccess[] = function (CompanyRegistrationData $data): void {
            $this->companyRegistrationSessionService->saveCompanyRegistrationDataToSession($data);

            $this->flashMessage('Firma byla úspěšně zaregistrována.', 'success');
            $this->redirect('Homepage:result');
        };

        return $form;
    }
}
