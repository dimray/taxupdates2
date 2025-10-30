<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use DateTime;
use App\Flash;
use App\Helpers\Helper;
use App\Helpers\SubmissionsHelper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiIndividualCalculations;
use App\Models\Submission;
use Framework\Controller;

class IndividualCalculations extends Controller
{

    public function __construct(private ApiIndividualCalculations $apiIndividualCalculations, private Submission $submission) {}


    public function triggerCalculation()
    {

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



            // ********** VIEW SUBMITTED CALC **********
            if ($calculation_type === "final-declaration" || $calculation_type === "confirm-amendment") {

                $heading = "Final HMRC Tax Calculation";

                $hide_tax_year = true;

                $hide_calculation_options = true;

                return $this->view(
                    "Endpoints/IndividualCalculations/show.php",
                    compact("heading", "hide_tax_year",  "calculation_details", "summary", "inputs", "calculation", "messages", "hide_calculation_options", "calculation_id")
                );
            }

            // ***************** FINAL CALC FOR SUBMISSION ***********************

            //*** this has final declaration included and form to submit final calc. Needs to pass through calc id and type for the submission function ******
            if ($calculation_type === "intent-to-finalise" || $calculation_type === "intent-to-amend") {

                $heading = "Final HMRC Tax Calculation";

                $hide_tax_year = true;

                $hide_calculation_options = true;

                $show_submit = true;

                $errors = $this->flashErrors();

                return $this->view(
                    "Endpoints/IndividualCalculations/show.php",
                    compact("heading", "hide_tax_year", "calculation_details", "summary", "inputs", "calculation", "messages", "errors", "show_submit", "hide_calculation_options", "calculation_id", "calculation_type")
                );
            }

            // ********************* IN YEAR CALC ************************

            $heading = "HMRC Tax Calculation";

            $hide_tax_year = true;

            return $this->view(
                "Endpoints/IndividualCalculations/show.php",
                compact("heading", "hide_tax_year", "calculation_details", "summary", "inputs", "calculation", "messages", "calculation_id")
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

    // ********************************************************************************

    // FINAL DECLARATION

    // ********************************************************************************

    public function retrieveFinalCalculation()
    {
        $tax_year = $_SESSION['tax_year'];

        $nino = Helper::getNino();

        $calculation_type = "";

        $response = $this->apiIndividualCalculations->listSelfAssessmentTaxCalculations($nino, $tax_year, $calculation_type);

        $final_calculation = $this->findFinalCalculation($response['calculations'] ?? []);

        // return flash message if no final calculation
        if (empty($final_calculation)) {

            Flash::addMessage("Unable to display Final Calculation for the selected year", Flash::WARNING);
            return $this->redirect("/obligations/final-declaration");
        }

        $calculation_id = $final_calculation['calculationId'] ?? "";
        $calculation_type = $final_calculation['calculationType'] ?? "";

        $query_string = http_build_query(compact("calculation_id", "calculation_type"));

        return $this->redirect("/individual-calculations/retrieve-calculation?" . $query_string);
    }

    public function prepareFinalDeclaration()
    {
        $calculation_type = $this->request->get['calculation_type'];

        $tax_year = $_SESSION['tax_year'];

        $hide_tax_year = true;

        $heading = "Prepare Your Final Declaration for {$tax_year}";

        $errors = $this->flashErrors();

        return $this->view(
            "Endpoints/IndividualCalculations/prepare-final-declaration.php",
            compact("heading", "calculation_type", "errors", "hide_tax_year")
        );
    }

    public function confirmPrepareFinalDeclaration()
    {
        $data = $this->request->post;

        $confirm_statements = $data['confirm_statements'] ?? false;

        $calculation_type = $this->request->post['calculation_type'];

        $query_string = http_build_query(compact("calculation_type"));

        if (!$confirm_statements) {

            $this->addError("Please confirm all your tax information is complete before preparing the Final Declaration");

            return $this->redirect("/individual-calculations/prepare-final-declaration?" . $query_string);
        }

        return $this->redirect("/individual-calculations/trigger-calculation?" . $query_string);
    }

    public function submitFinalDeclaration()
    {
        $confirm_submit = $this->request->post['confirm_submit'] ?? null;
        $calculation_id = $this->request->post['calculation_id'] ?? null;
        $calculation_type = $this->request->post['calculation_type'] ?? null;

        if (!$calculation_type || !$calculation_id) {

            Flash::addMessage("Unable to retrieve final tax calculation. Please try again.");
            return $this->redirect("/obligations/final-declaration");
        }

        if (!$confirm_submit) {

            $this->addError("You must tick the box to confirm the statement before your Final Declaration can be submitted.");

            $query_string = http_build_query([
                "calculation_type" => $calculation_type,
                "calculation_id" => $calculation_id
            ]);

            return $this->redirect("/individual-calculations/retrieve-calculation?" . $query_string);
        }

        if ($calculation_type === "intent-to-finalise") {
            $calculation_type = "final-declaration";
        }

        if ($calculation_type === "intent-to-amend") {
            $calculation_type = "confirm-amendment";
        }

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiIndividualCalculations->submitASelfAssessmentFinalDeclaration($nino, $tax_year, $calculation_id, $calculation_type);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['submission_id'] !== "") {

            $submission_data = SubmissionsHelper::createSubmission("final_declaration", $response['submission_id']);

            $submission_data['submission_payload'] = null;

            $this->submission->insert($submission_data);

            Flash::addMessage("Your Final Declaration has been submitted to HMRC", Flash::SUCCESS);
        }

        $query_string = http_build_query(compact("calculation_id", "calculation_type"));

        return $this->redirect("/individual-calculations/retrieve-calculation" . "?" .  $query_string);
    }

    private function findFinalCalculation(array $calculations): array
    {
        $amended_calculations = [];

        foreach ($calculations as $calculation) {
            if (
                strtolower($calculation['calculationOutcome']) === "processed" && strtolower($calculation['calculationType']) === "confirm-amendment"
            ) {
                $amended_calculations[] = $calculation;
            }
        }

        if (!empty($amended_calculations)) {
            if (count($amended_calculations) === 1) {
                return $amended_calculations[0];
            } else {
                return $this->getLatestTaxCalculation($amended_calculations);
            }
        }

        $final_calculations = [];

        foreach ($calculations as $calculation) {
            if (strtolower($calculation['calculationOutcome']) === "processed" && strtolower($calculation['calculationType']) === "final-declaration") {
                $final_calculations[] = $calculation;
            }
        }

        if (!empty($final_calculations)) {
            if (count($final_calculations) === 1) {
                return $final_calculations[0];
            } else {
                return $this->getLatestTaxCalculation($final_calculations);
            }
        }

        return [];
    }

    private function getLatestTaxCalculation(array $calculations): ?array
    {
        if (empty($calculations)) {
            return null;
        }

        $latestCalculation = null;
        $latestTimestamp = null;

        foreach ($calculations as $calculation) {
            if (isset($calculation["calculationTimestamp"])) {

                $currentTimestamp = new DateTime($calculation["calculationTimestamp"]);
                if ($latestTimestamp === null || $currentTimestamp > $latestTimestamp) {
                    $latestTimestamp = $currentTimestamp;
                    $latestCalculation = $calculation;
                }
            }
        }

        return $latestCalculation;
    }
}
