<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Helpers\ChargesHelper;
use App\HmrcApi\Endpoints\Other\ApiCharges;
use App\Helpers\Helper;
use Framework\Controller;
use App\Flash;

class Charges extends Controller
{
    public function __construct(private ApiCharges $apiCharges) {}

    // PENSION CHARGES 

    public function retrievePensionCharges()
    {
        unset($_SESSION['charges']);

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCharges->retrievePensionCharges($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $pension_charges = [];

        if ($response['type'] === "success") {
            $pension_charges = $response['response'];
            $_SESSION['charges']['pension_charges'] = $pension_charges;
        }

        $heading = "Pension Charges";

        return $this->view("Endpoints/Other/Charges/pension-charges-retrieve.php", compact("heading", "pension_charges"));
    }

    public function createAndAmendPensionCharges()
    {
        $heading = "Pension Charges";

        $errors = $this->flashErrors();

        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $pension_charges = $_SESSION['charges']['pension_charges'] ?? [];
        unset($_SESSION['charges']);

        // variables
        $pension_scheme_overseas_transfers = $pension_charges['pensionSchemeOverseasTransfers'] ?? [];
        $pension_scheme_overseas_transfers_overseas_scheme_provider = $pension_scheme_overseas_transfers['overseasSchemeProvider'] ?? [[]];

        $pension_scheme_unauthorised_payments = $pension_charges['pensionSchemeUnauthorisedPayments'] ?? [];
        $pension_contributions = $pension_charges['pensionContributions'] ?? [];

        $overseas_pension_contributions = $pension_charges['overseasPensionContributions'] ?? [];
        $overseas_pension_contributions_overseas_scheme_provider = $overseas_pension_contributions['overseasSchemeProvider'] ?? [[]];

        return $this->view("Endpoints/Other/Charges/pension-charges-create-amend.php", compact(
            "heading",
            "errors",
            "countries",
            "pension_scheme_overseas_transfers",
            "pension_scheme_overseas_transfers_overseas_scheme_provider",
            "pension_scheme_unauthorised_payments",
            "pension_contributions",
            "overseas_pension_contributions",
            "overseas_pension_contributions_overseas_scheme_provider"
        ));
    }

    public function processCreateAndAmendPensionCharges()
    {
        $pension_charges = $this->request->post ?? [];

        $validated = ChargesHelper::validateAndFormatPensionCharges($pension_charges);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['charges']['pension_charges'] = $pension_charges;

            return $this->redirect("/charges/create-and-amend-pension-charges");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCharges->createAndAmendPensionCharges($nino, $tax_year, $validated);

        if ($response['type'] === "success") {
            Flash::addMessage("Pension Charges have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/charges/retrieve-pension-charges");
    }

    public function confirmDeletePensionCharges()
    {
        $heading = "Delete Pension Charges";

        return $this->view("Endpoints/Other/Charges/pension-charges-confirm-delete.php", compact("heading"));
    }

    public function deletePensionCharges()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiCharges->deletePensionCharges($nino, $tax_year);

            unset($_SESSION['charges']);

            if ($response['type'] === "success") {
                Flash::addMessage("Pension Charges have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/charges/retrieve-pension-charges");
    }

    // HICBC

    public function retrieveHighIncomeChildBenefitChargeSubmission()
    {
        unset($_SESSION['charges']);

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCharges->retrieveHighIncomeChildBenefitChargeSubmission($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $hicbc = [];

        if ($response['type'] === "success") {
            $hicbc = $response['response'];
            $_SESSION['charges']['hicbc'] = $hicbc;
        }

        $heading = "High Income Child Benefit Charge";

        return $this->view("Endpoints/Other/Charges/hicbc-retrieve.php", compact("heading", "hicbc"));
    }

    public function createOrAmendHighIncomeChildBenefitChargeSubmission()
    {
        $heading = "High Income Child Benefit Charge";

        $errors = $this->flashErrors();

        $hicbc = $_SESSION['charges']['hicbc'] ?? [];
        unset($_SESSION['charges']);

        // variables
        $amount_of_child_benefit_received = $hicbc['amountOfChildBenefitReceived'] ?? '';
        $number_of_children = $hicbc['numberOfChildren'] ?? '';
        $date_ceased = $hicbc['dateCeased'] ?? '';

        return $this->view("Endpoints/Other/Charges/hicbc-create-amend.php", compact(
            "heading",
            "errors",
            "amount_of_child_benefit_received",
            "number_of_children",
            "date_ceased"
        ));
    }

    public function processCreateOrAmendHighIncomeChildBenefitChargeSubmission()
    {
        $hicbc = $this->request->post ?? [];

        $validated = ChargesHelper::validateHicbc($hicbc);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['charges']['hicbc'] = $hicbc;

            return $this->redirect("/charges/create-or-amend-high-income-benefit-charge-submission");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCharges->createOrAmendHighIncomeChildBenefitChargeSubmission($nino, $tax_year, $validated);

        if ($response['type'] === "success") {
            Flash::addMessage("Child Benefit Charge has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/charges/retrieve-high-income-child-benefit-charge-submission");
    }

    public function confirmDeleteHighIncomeChildBenefitChargeSubmission()
    {
        $heading = "Delete High Income Child Benefit Charge";

        return $this->view("Endpoints/Other/Charges/hicbc-confirm-delete.php", compact("heading"));
    }

    public function deleteHighIncomeChildBenefitChargeSubmission()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiCharges->deleteHighIncomeChildBenefitChargeSubmission($nino, $tax_year);

            unset($_SESSION['charges']);

            if ($response['type'] === "success") {
                Flash::addMessage("High Income Child Benefit Charge has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/charges/retrieve-high-income-child-benefit-charge-submission");
    }
}
