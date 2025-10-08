<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiPensionsIncome;
use Framework\Controller;
use App\Helpers\Helper;
use App\Helpers\PensionIncomeHelper;
use App\Flash;

class PensionsIncome extends Controller
{
    public function __construct(private ApiPensionsIncome $apiPensionsIncome) {}

    public function retrievePensionsIncome()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiPensionsIncome->retrievePensionsIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $pensions_income = [];

        if ($response['type'] === "success") {
            $pensions_income = $response['response'];
            $_SESSION['pensions_income'] = $pensions_income;
        }

        $heading = "Foreign Pension Income And Overseas Contributions";

        return $this->view("Endpoints/Other/PensionsIncome/retrieve-pensions-income.php", compact("heading", "pensions_income"));
    }

    public function createAndAmendPensionsIncome()
    {
        $heading = "Foreign Pension Income And Overseas Contributions";

        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $errors = $this->flashErrors();

        $pensions_income = $_SESSION['pensions_income'] ?? [];
        unset($_SESSION['pensions_income']);

        $foreign_pensions = $pensions_income['foreignPensions'] ?? [[]];
        $overseas_contributions = $pensions_income['overseasPensionContributions'] ?? [[]];

        return $this->view("Endpoints/Other/PensionsIncome/create-amend-pensions-income.php", compact("heading", "errors", "countries", "foreign_pensions", "overseas_contributions"));
    }

    public function processCreateAndAmendPensionsIncome()
    {
        $pensions_income = $this->request->post ?? [];

        $validated = PensionIncomeHelper::validateAndFormatPensionsIncome($pensions_income);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['pensions_income'] = $pensions_income;

            return $this->redirect("/pensions-income/create-and-amend-pensions-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiPensionsIncome->createAndAmendPensionsIncome($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Pensions Income has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/pensions-income/retrieve-pensions-income");
    }

    public function confirmDeletePensionsIncome()
    {
        $heading = "Delete Pensions Income";

        return $this->view("Endpoints/Other/PensionsIncome/confirm-delete-pensions-income.php", compact("heading"));
    }

    public function deletePensionsIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiPensionsIncome->deletePensionsIncome($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['pensions_income']);

            if ($response['type'] === "success") {
                Flash::addMessage("Pensions income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/pensions-income/retrieve-pensions-income");
    }
}
