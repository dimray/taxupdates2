<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Flash;
use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiIndividualCalculations;
use App\Models\Submission;
use Framework\Controller;

class IndividualCalculations extends Controller
{

    public function __construct(private ApiIndividualCalculations $apiIndividualCalculations, private Submission $submission) {}


    public function triggerCalculation()
    {
        Helper::unsetBusinessSessionInfo();

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'] ?? TaxYearHelper::getCurrentTaxYear();

        $calculation_type = $this->request->get['calculation_type'] ?? 'in-year';

        $response = $this->apiIndividualCalculations->triggerASelfAssessmentTaxCalculation($nino, $tax_year, $calculation_type);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $calculation_id = $response['calculation_id'] ?? "";

        if (empty($calculation_id)) {
            Flash::addMessage("An error has occurred, please try again", Flash::WARNING);
            return $this->redirect("/");
        }


        $heading = "Tax Calculation";

        $hide_tax_year = true;

        return $this->view("Endpoints/IndividualCalculations/index.php", compact("heading", "hide_tax_year", "calculation_type", "calculation_id"));
    }



    public function retrieveCalculation()
    {
        Helper::unsetBusinessSessionInfo();

        $calculation_id = $this->request->get['calculation_id'] ?? '';

        // needed for redirect if calculation isn't ready yet
        $_SESSION['calculation_id'] = $calculation_id;

        if (empty($calculation_id)) {
            return $this->redirect("/individual-calculations/trigger-calculation");
        }

        $calculation_type = $this->request->get['calculation_type'] ?? 'in-year';

        $tax_year = $_SESSION['tax_year'] ?? TaxYearHelper::getCurrentTaxYear();

        $nino = Helper::getNino();

        $response = $this->apiIndividualCalculations->retrieveASelfAssessmentTaxCalculation($nino, $tax_year, $calculation_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            // this was just set for wait-for-calculation function
            unset($_SESSION['calculation_id']);
        }

        $calc_response = $response['calculation'] ?? [];

        $metadata = $calc_response['metadata'] ?? [];
        $inputs = $calc_response['inputs'] ?? [];
        $calculation = $calc_response['calculation'] ?? [];
        $messages  = $calc_response['messages'] ?? [];

        $summary =  [];

        if (isset($calculation['taxCalculation']['incomeTax']['totalTaxableIncome'])) {
            $summary['totalTaxableIncome'] = $calculation['taxCalculation']['incomeTax']['totalTaxableIncome'];
        }

        if (isset($calculation['taxCalculation']['capitalGainsTax']['totalTaxableGains'])) {
            $summary['totalTaxableGains'] = $calculation['taxCalculation']['capitalGainsTax']['totalTaxableGains'];
        }

        if (isset($calculation['taxCalculation']['totalIncomeTaxAndNicsDue'])) {
            $summary['totalIncomeTaxAndNationalInsurance'] = $calculation['taxCalculation']['totalIncomeTaxAndNicsDue'];
        }

        if (isset($calculation['taxCalculation']['capitalGainsTax']['capitalGainsTaxDue'])) {
            $summary['totalCapitalGainsTax'] = $calculation['taxCalculation']['capitalGainsTax']['capitalGainsTaxDue'];
        }

        if (isset($calculation['taxCalculation']['totalIncomeTaxAndNicsAndCgt'])) {
            $summary['totalForTheYear'] = $calculation['taxCalculation']['totalIncomeTaxAndNicsAndCgt'];
        }

        $calculation_details = [];

        if (!empty($metadata)) {

            $calculation_details['taxYear'] = $metadata['taxYear'] ?? '';
            $calculation_details['calculationFrom'] = $metadata['periodFrom'] ?? '';
            $calculation_details['calculationTo'] = $metadata['periodTo'] ?? '';
            $calculation_details['calculationId'] = $metadata['calculationId'] ?? '';
            $calculation_details['calculationType'] = $metadata['calculationType'] ?? '';

            // **************************TEST ONLY********************
            // $calculation_details['calculationType'] = $calculation_type ?? '';
            // *******************************************************



            // ********** this is submitted calculation. Not used at the moment. **********
            if ($calculation_type === "final-declaration" || $calculation_type === "confirm-amendment") {
                $heading = "Final HMRC Tax Calculation";

                $hide_tax_year = true;

                return $this->view(
                    "Endpoints/IndividualCalculations/show-submitted-final-calculation.php",
                    compact("heading", "hide_tax_year", "calculation_details", "summary", "inputs", "calculation", "messages", "calculation_type", "calculation_id")
                );
            }


            //*** this has final declaration included and form to submit final calc. Not yet used ******
            if ($calculation_type === "intent-to-finalise" || $calculation_type === "intent-to-amend") {

                $heading = "Final HMRC Tax Calculation";

                $errors = $this->flashErrors();

                $hide_tax_year = true;

                return $this->view(
                    "Endpoints/IndividualCalculations/show-final-calculation.php",
                    compact("heading", "hide_tax_year", "calculation_details", "summary", "inputs", "calculation", "messages", "calculation_type", "calculation_id", "errors")
                );
            }


            $heading = "HMRC Tax Calculation";

            $hide_tax_year = true;

            return $this->view(
                "Endpoints/IndividualCalculations/show.php",
                compact("heading", "hide_tax_year", "calculation_details", "summary", "inputs", "calculation", "messages")
            );
        }
    }

    // redirects here if retrieve-calculation returns 404 
    public function waitForCalculation()
    {
        $heading = "Awaiting Tax Calculation";

        $calculation_id = $_SESSION['calculation_id'] ?? '';

        unset($_SESSION['calculation_id']);

        // if empty it's already been used, don't wait again
        if (empty($calculation_id)) {
            Flash::addMessage("HMRC is taking too long to generate your tax calculation, please wait a while then try again", Flash::WARNING);
            return $this->redirect("/businesses/list-all-businesses");
        }

        return $this->view("Endpoints/individual-calculations/wait-for-calculation.php", compact("heading", "calculation_id"));
    }
}
