<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiInsuranceIncome;
use Framework\Controller;
use App\Helpers\Helper;
use App\Flash;
use App\Helpers\InsuranceIncomeHelper;

class InsuranceIncome extends Controller
{
    public function __construct(private ApiInsuranceIncome $apiInsuranceIncome) {}

    public function retrieveInsurancePoliciesIncome()
    {
        unset($_SESSION['insurance_income']);

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiInsuranceIncome->retrieveInsurancePoliciesIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $insurance_income = [];

        if ($response['type'] === "success") {
            $insurance_income = $response['response'];
            $_SESSION['insurance_income'] = $insurance_income;
        }

        $heading = "Insurance Income";

        return $this->view("Endpoints/Other/InsuranceIncome/retrieve-insurance-income.php", compact("heading", "insurance_income"));
    }

    public function createAndAmendInsurancePoliciesIncome()
    {
        $heading = "Insurance Income";

        $errors = $this->flashErrors();

        $insurance_income = $_SESSION['insurance_income'] ?? [];
        unset($_SESSION['insurance_income']);

        $life_insurance = $insurance_income['lifeInsurance'] ?? [[]];
        $capital_redemption = $insurance_income['capitalRedemption'] ?? [[]];
        $life_annuity = $insurance_income['lifeAnnuity'] ?? [[]];
        $voided_isa = $insurance_income['voidedIsa'] ?? [[]];
        $foreign = $insurance_income['foreign'] ?? [[]];

        return $this->view("Endpoints/Other/InsuranceIncome/add-edit-insurance-income.php", compact("heading",  "errors", "life_insurance", "capital_redemption", "life_annuity", "voided_isa", "foreign"));
    }

    public function processCreateAndAmendInsurancePoliciesIncome()
    {
        $insurance_income = $this->request->post ?? [];

        $validated = InsuranceIncomeHelper::validateAndFormatInsuranceIncome($insurance_income);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['insurance_income'] = $insurance_income;

            return $this->redirect("/insurance-income/create-and-amend-insurance-policies-income");
        }

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiInsuranceIncome->createAndAmendInsurancePoliciesIncome($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Insurance Income has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/insurance-income/retrieve-insurance-policies-income");
    }

    public function confirmDeleteInsurancePoliciesIncome()
    {
        $heading = "Delete Insurance Income";

        return $this->view("Endpoints/Other/InsuranceIncome/delete-insurance-income.php", compact("heading"));
    }

    public function deleteInsurancePoliciesIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiInsuranceIncome->deleteInsurancePoliciesIncome($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['insurance_income']);

            if ($response['type'] === "success") {
                Flash::addMessage("Insurance Income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/insurance-income/retrieve-insurance-policies-income");
    }
}
