<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Forms\CompanyIn\CompanyInForm;
use App\Forms\CompanyIn\ICompanyInFormFactory;
use App\Forms\CompanyRegistration\CompanyRegistrationData;
use App\Forms\CompanyRegistration\CompanyRegistrationForm;
use App\Forms\CompanyRegistration\ICompanyRegistrationFormFactory;
use App\Services\Ares\AresService;
use App\Services\CompanyRegistration\CompanyRegistrationSessionService;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private ICompanyRegistrationFormFactory $companyRegistrationFormFactory;

    private CompanyRegistrationSessionService $companyRegistrationSessionService;

    private ICompanyInFormFactory $companyInFormFactory;

    private AresService $aresService;


    public function __construct(
        ICompanyRegistrationFormFactory $companyRegistrationFormFactory,
        CompanyRegistrationSessionService $companyRegistrationSessionService,
        ICompanyInFormFactory $companyInFormFactory,
        AresService $aresService
    ) {
        parent::__construct();

        $this->companyRegistrationFormFactory = $companyRegistrationFormFactory;
        $this->companyRegistrationSessionService = $companyRegistrationSessionService;
        $this->companyInFormFactory = $companyInFormFactory;
        $this->aresService = $aresService;
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

    protected function createComponentInForm(): CompanyInForm
    {
        $form = $this->companyInFormFactory->create();

        $form->onSuccess[] = function (CompanyInForm $form, $data): void {
            try {
                $companyAresData = $this->aresService->getCompanyDataByIn($data->in);
                $this['registrationForm']->setDefaults([
                    CompanyRegistrationData::KEY_NAME => $companyAresData->getName(),
                    CompanyRegistrationData::KEY_ADDRESS => $companyAresData->getAddress(),
                    CompanyRegistrationData::KEY_IN => $companyAresData->getIn(),
                    CompanyRegistrationData::KEY_TIN => $companyAresData->getTin(),
                ]);
            } catch (\App\Services\Ares\Exceptions\AresApiNotAvailableException $e) {
                $this->flashMessage('Registr ARES není dostupný.', 'error');
                $this->redirect('this');
            } catch (\App\Services\Ares\Exceptions\CompanyNotFoundByInException $e) {
                $this->flashMessage('Zadané IČO nebylo nalezeno v registru ARES.', 'error');
                $this->redirect('this');
            }
        };

        return $form;
    }
}
