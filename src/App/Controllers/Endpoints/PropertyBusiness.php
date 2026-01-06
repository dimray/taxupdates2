<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Helpers\Helper;
use App\Helpers\SubmissionsHelper;
use App\Helpers\AnnualSubmissionHelper;
use App\Helpers\AgentHelper;
use App\HmrcApi\Endpoints\ApiPropertyBusiness;
use App\Models\Submission;
use App\Flash;
use App\Helpers\ForeignPropertyHelper;
use App\Helpers\TaxYearHelper;
use App\Validate;
use Framework\Controller;

class PropertyBusiness extends Controller
{
    public function __construct(private ApiPropertyBusiness $apiPropertyBusiness, private Submission $submission, private ForeignPropertyHelper $foreignPropertyHelper) {}

    public function retrieveCumulativePeriodSummary()
    {
        $location =  $_SESSION['type_of_business'] === "uk-property" ? "uk" : "foreign";
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiPropertyBusiness->RetrieveAPropertyCumulativePeriodSummary($location, $nino, $business_id, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $summary = [];

        if ($response['type'] === 'success') {
            $summary = $response['summary'] ?? [];
        }

        if (empty($summary)) {
            $empty_data = true;
        } else {
            $empty_data = false;
        }

        $heading = "Cumulative Summary";

        $hide_tax_year = true;

        $business_details = Helper::setBusinessDetails();
        $business_details['periodStartDate'] = $summary['fromDate'] ?? "";
        $business_details['periodEndDate'] = $summary['toDate'] ?? "";

        $supporting_agent  = false;
        if (isset($_SESSION['client']['agent_type']) && $_SESSION['client']['agent_type'] === "supporting") {
            $supporting_agent = true;
        }


        if ($empty_data) {
            return $this->view(
                "Endpoints/PropertyBusiness/show-cumulative-summary.php",
                compact("heading", "hide_tax_year", "business_details", "empty_data", "supporting_agent")
            );
        }

        if ($location === "uk") {
            $income = $summary['ukProperty']['income'] ?? [];
            $expenses = $summary['ukProperty']['expenses'] ?? [];

            $rentaroom =  [];

            if (isset($income['rentARoom']['rentsReceived'])) {
                $rentaroom['rentsReceived'] = $income['rentARoom']['rentsReceived'];
                unset($income['rentARoom']);
            }

            if (isset($expenses['rentARoom']['amountClaimed'])) {
                $rentaroom['amountClaimed'] = $expenses['rentARoom']['amountClaimed'];
                unset($expenses['rentARoom']);
            }

            $residential_finance = [];

            if (isset($expenses['residentialFinancialCost'])) {
                $residential_finance['residentialFinancialCost'] = $expenses['residentialFinancialCost'];
                unset($expenses['residentialFinancialCost']);
            }

            if (isset($expenses['residentialFinancialCostsCarriedForward'])) {
                $residential_finance['residentialFinancialCostsCarriedForward'] = $expenses['residentialFinancialCostsCarriedForward'];
                unset($expenses['residentialFinancialCostsCarriedForward']);
            }

            $total_income = array_sum($income);
            $total_expenses = array_sum($expenses);
            $rentaroom_profit = (($rentaroom['rentsReceived'] ?? 0) - ($rentaroom['amountClaimed'] ?? 0));
            $profit = $total_income + $rentaroom_profit - $total_expenses;


            return $this->view(
                "Endpoints/PropertyBusiness/show-cumulative-summary.php",
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "income", "expenses", "rentaroom", "rentaroom_profit", "residential_finance", "total_income", "total_expenses", "profit", "supporting_agent")
            );
        }

        if ($location === "foreign") {

            $foreign_property_data = $summary['foreignProperty'] ?? [];


            // this sorts the inner arrays for display
            foreach ($foreign_property_data as &$entry) {
                if (isset($entry['income']['rentIncome']['rentAmount'])) {
                    $entry['income']['rentAmount'] = $entry['income']['rentIncome']['rentAmount'];
                    unset($entry['income']['rentIncome']);
                }

                $entry['foreignTaxCreditRelief'] = $entry['income']['foreignTaxCreditRelief'] ?? false;
                unset($entry['income']['foreignTaxCreditRelief']);

                $finance_fields = ['residentialFinancialCost', 'broughtFwdResidentialFinancialCost'];
                foreach ($finance_fields as $field) {
                    if (isset($entry['expenses'][$field])) {
                        $entry['residentialFinance'][$field] = $entry['expenses'][$field];
                        unset($entry['expenses'][$field]);
                    }
                }
            }
            unset($entry);

            // get total income, total expenses, profit
            $totals = [];

            foreach ($foreign_property_data as $data) {

                $total_income = array_sum($data['income']);
                $total_expenses = array_sum($data['expenses']);

                $totals[$data['countryCode']] = [
                    'total_income' => $total_income,
                    'total_expenses' => $total_expenses,
                    'profit' => $total_income - $total_expenses
                ];
            }

            // check if consolidated expenses is needed, and also if there are non-consolidated expenses present:
            $consolidated_expenses = false;
            $non_consolidated_expenses = false;
            foreach ($foreign_property_data as $entry) {
                if (isset($entry['expenses']['consolidatedExpenses'])) {
                    $consolidated_expenses = true;
                } else {
                    $non_consolidated_expenses = true;
                }
            }

            $country_or_property = $_SESSION['tax_year'] === "2025-26" ? "country" : "property";

            return $this->view(
                "Endpoints/PropertyBusiness/show-cumulative-summary.php",
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "foreign_property_data", "totals", "consolidated_expenses", "non_consolidated_expenses", "supporting_agent", "country_or_property")
            );
        }
    }

    public function submitCumulativePeriodSummary()
    {

        $confirm_submit = $this->request->get['confirm_submit'] ?? false;

        $location =  ($_SESSION['type_of_business'] === "uk-property") ? "uk" : "foreign";

        if (!$confirm_submit) {
            $this->addError("You must tick the box to confirm you have approved this submission.");

            if ($location === "uk") {
                return $this->redirect("/uploads/approve-uk-property");
            }

            if ($location === "foreign") {
                return $this->redirect("/uploads/approve-foreign-property");
            }
        }


        if ($location === "uk") {
            SubmissionsHelper::finaliseUkPropertyCumulativeSummaryArray();

            $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']];
        }

        if ($location === "foreign") {

            $foreign_property_data = $_SESSION['cumulative_data'][$_SESSION['business_id']];

            $updated_data = [];

            if ($_SESSION['tax_year'] === "2025-26") {

                foreach ($foreign_property_data as $key => $data) {
                    $updated_data[] = array_merge(
                        ['countryCode' => $key],
                        $data
                    );
                }
            } else {

                foreach ($foreign_property_data as $key => $data) {
                    $updated_data[] = array_merge(
                        ['propertyId' => $key],
                        $data
                    );
                }
            }

            // ********************
            // FOR TESTING PROPERTIES
            // REMOVE FOR PRODUCTION
            // $updated_data = [];
            // foreach ($foreign_property_data as $key => $data) {
            //     $updated_data[] = array_merge(
            //         ['propertyId' => $key],
            //         $data
            //     );
            // }
            // END TESTING
            // *********************

            $cumulative_data = SubmissionsHelper::finaliseForeignPropertyCumulativeSummaryArray($updated_data);
        }

        unset($_SESSION['cumulative_data']);

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $business_id = $_SESSION['business_id'];

        $response = $this->apiPropertyBusiness->createAndAmendAPropertyCumulativePeriodSummary($location, $nino, $business_id, $tax_year, $cumulative_data);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $submission_id = "";

        if ($response['type'] === 'success') {
            $submission_id = $response['submission_ref'];
        }

        if ($submission_id !== "") {

            $submission_data = SubmissionsHelper::createSubmission("cumulative", $submission_id);

            $submission_data['submission_payload'] = json_encode($cumulative_data);

            $this->submission->insert($submission_data);

            Flash::addMessage("Your Cumulative Summary has been submitted to HMRC", Flash::SUCCESS);

            if (!AgentHelper::isSupportingAgent()) {

                return $this->redirect("/property-business/success?type=cumulative");
            }
        }

        if ($response['type'] === 'error') {
            Flash::addMessage("The Cumulative Summary has not been submitted to HMRC", Flash::WARNING);
        }

        // failure or supporting agent
        return $this->redirect("/obligations/retrieve-cumulative-obligations");
    }

    public function annualSubmission()
    {
        $heading = "Annual Submission";

        $business_details = Helper::setBusinessDetails();

        $type_of_business = $_SESSION['type_of_business'];

        return $this->view("Endpoints/PropertyBusiness/annual-submission.php", compact("heading", "business_details", "type_of_business"));
    }


    public function createAnnualSubmission()
    {
        // ************FOR TESTING
        // $_SESSION['tax_year'] = "2026-27";
        // ************DELETE FOR PRODUCTION

        $type_of_business = $_SESSION['type_of_business'];

        if ($type_of_business === "uk-property") {
            if (empty($_SESSION['errors'])) {
                unset($_SESSION['annual_submission']);
            }
        }

        // clear the annual submission data if cancelled
        if (isset($this->request->get['cancel'])) {
            unset($_SESSION['annual_submission']);
        }

        $heading = "Annual Submission";

        $business_details = Helper::setBusinessDetails();

        $errors = $this->flashErrors();

        if ($type_of_business === "uk-property") {

            $adjustments =  $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] ?? [];
            $allowances =  $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] ?? [];
            $sba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] ?? [];
            $esba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] ?? [];
            $rentaroom =  $_SESSION['annual_submission'][$_SESSION['business_id']]['rentaroom'] ?? [];

            if (!empty($adjustments && isset($adjustments['nonResidentLandlord']))) {
                $adjustments['nonResidentLandlord'] = $adjustments['nonResidentLandlord'] === true ? "true" : "false";
            }

            if (!empty($rentaroom && isset($rentaroom['jointlyLet']))) {
                $rentaroom['rentARoomClaimed'] = "true";
                $rentaroom['jointlyLet'] = $rentaroom['jointlyLet'] === true ? "true" : "false";
            }

            return $this->view(
                "Endpoints/PropertyBusiness/create-annual-submission-uk.php",
                compact("heading", "business_details", "errors",  "adjustments", "allowances", "sba", "esba", "rentaroom")
            );
        } elseif ($type_of_business === "foreign-property") {

            if (empty($errors)) {
                // this clears only the country code, not the annual submission data
                unset($_SESSION['annual_submission'][$_SESSION['business_id']]['countryCode']);
                unset($_SESSION['annual_submission'][$_SESSION['business_id']]['propertyId']);
            }

            $input_data = [];
            $country_code = '';
            $hmrc_property_id = '';
            $country_codes = [];
            $foreign_properties = [];


            if ($_SESSION['tax_year'] === "2025-26") {

                // country code is saved by AnnualSubmissionHelper in case there are errors
                $country_code = $_SESSION['annual_submission'][$_SESSION['business_id']]['countryCode'] ?? '';

                // this is the data for that country code, to display if there is an error
                if (!empty($country_code)) {
                    $input_data = $_SESSION['annual_submission'][$_SESSION['business_id']][$country_code];
                }

                // get country codes for user to select from
                $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";
            } else {

                // get the specific property from AnnualSubmissionHelper if returning here with an error
                $hmrc_property_id = $_SESSION['annual_submission'][$_SESSION['business_id']]['propertyId'] ?? '';
                // get property data to display on an error
                if (!empty($hmrc_property_id)) {
                    $input_data = $_SESSION['annual_submission'][$_SESSION['business_id']][$hmrc_property_id];
                }

                // get properties from the PropertyHelper api call
                $nino = Helper::getNino();
                $business_id = $_SESSION['business_id'];
                $tax_year = $_SESSION['tax_year'];
                $foreign_properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);
            }


            return $this->view(
                "Endpoints/PropertyBusiness/create-annual-submission-foreign.php",
                compact("heading", "business_details", "errors", "input_data", "country_code", "hmrc_property_id", "country_codes", "foreign_properties")
            );
        }
    }

    public function processAnnualSubmission()
    {
        $data = $this->request->post ?? [];


        if (empty($data)) {
            return $this->redirect("/property-business/create-annual-submission");
        }

        $errors = [];

        $type_of_business = $_SESSION['type_of_business'];

        $errors = AnnualSubmissionHelper::validatePropertyBusinessAnnualSubmission($data);

        if (!isset($_SESSION['annual_submission']) || empty($_SESSION['annual_submission'])) {
            $errors[] = "To create an Annual Submission, add data to at least one field";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect("/property-business/create-annual-submission");
        }



        // add more countries or properties
        if ($type_of_business === "foreign-property") {
            if ($_SESSION['tax_year'] === "2025-26") {
                return $this->redirect("/property-business/add-country");
            } else {
                return $this->redirect("/property-business/add-property");
            }
        }

        return $this->redirect("/property-business/approve-annual-submission");
    }

    public function addCountry()
    {
        $business_details = Helper::setBusinessDetails();

        if ($_SESSION['type_of_business'] !== 'foreign-property' || empty($_SESSION['annual_submission'][$_SESSION['business_id']])) {
            return $this->redirect("/business-details/list-all-businesses?year_end=true");
        }

        $heading = "Annual Submission";

        $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";
        $session_country_codes = array_keys($_SESSION['annual_submission'][$_SESSION['business_id']]);

        $country_names = [];

        foreach ($session_country_codes as $code) {

            foreach ($country_codes as $continent => $countries) {
                if (isset($countries[$code])) {
                    $country_names[] = $countries[$code];
                    break;
                }
            }
        }

        return $this->view("Endpoints/PropertyBusiness/add-country-annual-submission.php", compact("heading", "business_details", "country_names"));
    }

    public function addProperty()
    {

        $business_details = Helper::setBusinessDetails();

        if ($_SESSION['type_of_business'] !== 'foreign-property' || empty($_SESSION['annual_submission'][$_SESSION['business_id']])) {
            return $this->redirect("/business-details/list-all-businesses?year_end=true");
        }

        $heading = "Annual Submission";

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        $foreign_properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);
        $selected_properties = [];
        $session_property_ids = array_keys($_SESSION['annual_submission'][$_SESSION['business_id']]);

        foreach ($foreign_properties as $property) {
            if (in_array($property['propertyId'], $session_property_ids)) {
                $selected_properties[] = $property;
            }
        }

        return $this->view("Endpoints/PropertyBusiness/add-property-annual-submission.php", compact("heading", "business_details", "selected_properties", "foreign_properties"));
    }

    public function approveAnnualSubmission()
    {

        $errors = $this->flashErrors();

        $type_of_business = $_SESSION['type_of_business'];

        $heading = "Annual Submission";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        if ($type_of_business === "uk-property") {

            $adjustments =  $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] ?? [];
            $allowances =  $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] ?? [];
            $sba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] ?? [];
            $esba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] ?? [];
            $rentaroom =  $_SESSION['annual_submission'][$_SESSION['business_id']]['rentaroom'] ?? [];

            return $this->view(
                "Endpoints/PropertyBusiness/approve-annual-submission-uk.php",
                compact("heading", "hide_tax_year", "business_details", "type_of_business",  "adjustments", "allowances", "sba", "esba", "rentaroom", "errors")
            );
        }

        if ($type_of_business === "foreign-property") {



            $country_or_property = "property";
            if ($_SESSION['tax_year'] === "2025-26") {
                $country_or_property = "country";
            }

            $foreign_properties = [];
            if ($_SESSION['tax_year'] !== "2025-26") {
                $nino = Helper::getNino();
                $business_id = $_SESSION['business_id'];
                $tax_year = $_SESSION['tax_year'];
                $foreign_properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);
            }

            // these were to identify the country or property selected if displaying data again on error
            unset($_SESSION['annual_submission'][$_SESSION['business_id']]['countryCode']);
            unset($_SESSION['annual_submission'][$_SESSION['business_id']]['propertyId']);

            $foreign_property_data = $_SESSION['annual_submission'][$_SESSION['business_id']];

            return $this->view("Endpoints/PropertyBusiness/approve-annual-submission-foreign.php", compact("heading", "hide_tax_year", "business_details", "foreign_property_data", "country_or_property", "foreign_properties"));
        }
    }

    public function createAmendAnnualSubmission()
    {
        $confirm_submit = $this->request->post['confirm_submit'] ?? false;

        if (!$confirm_submit) {

            $this->addError("You must tick the confirmation box to proceed");

            return $this->redirect("/property-business/show-finalise-annual-submission");
        }

        $annual_submission = AnnualSubmissionHelper::finalisePropertyBusinessAnnualSubmission();

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        $location = $_SESSION['type_of_business'] === "uk-property" ? "uk" : "foreign";

        $response = $this->apiPropertyBusiness->createAndAmendAPropertyBusinessAnnualSubmission($location, $nino, $business_id, $tax_year, $annual_submission);

        unset($_SESSION['annual_submission']);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $submission_id = "";

        if ($response['type'] === 'success') {
            $submission_id = $response['submission_ref'];
        }

        if ($submission_id !== "") {

            $submission_data = SubmissionsHelper::createSubmission("annual", $submission_id);

            $submission_data['submission_payload'] = json_encode($annual_submission);

            $this->submission->insert($submission_data);

            Flash::addMessage("Your Annual Submission has been submitted to HMRC", Flash::SUCCESS);

            return $this->redirect("/property-business/success?type=annual");
        }

        // failure
        return $this->redirect("/self-employment/annual-submission");
    }

    public function retrieveAnnualSubmission()
    {
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];
        $location = $_SESSION['type_of_business'] === "uk-property" ? "uk" : "foreign";

        $response = $this->apiPropertyBusiness->retrieveAPropertyBusinessAnnualSubmission($location, $nino, $business_id, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $submission = [];

        if ($response['type'] === 'success') {
            $submission = $response['submission'];
        }

        $type_of_business = $_SESSION['type_of_business'];

        $heading = "Annual Submission";
        $business_details = Helper::setBusinessDetails();
        $hide_tax_year = true;

        $empty_data = false;
        if (empty($submission)) {
            $empty_data = true;
            return $this->view(
                "Endpoints/PropertyBusiness/show-annual-submission.php",
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "type_of_business")
            );
        }

        if ($location === "uk") {
            $annual_submission = $submission['ukProperty'] ?? [];

            $rentaroom = $annual_submission['adjustments']['rentARoom']['jointlyLet'] ?? [];

            $sba = $annual_submission['allowances']['structuredBuildingAllowance'] ?? [];
            $esba = $annual_submission['allowances']['enhancedStructuredBuildingAllowance'] ?? [];
            $sba = AnnualSubmissionHelper::flattenSba($sba, "sba");
            $esba = AnnualSubmissionHelper::flattenSba($esba, "esba");

            unset($annual_submission['allowances']['structuredBuildingAllowance'], $annual_submission['allowances']['enhancedStructuredBuildingAllowance'], $annual_submission['adjustments']['rentARoom']['jointlyLet']);

            $adjustments =  $annual_submission['adjustments'] ?? [];
            $allowances = $annual_submission['allowances'] ?? [];

            return $this->view(
                "Endpoints/PropertyBusiness/show-annual-submission.php",
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "type_of_business", "sba", "esba", "adjustments", "allowances", "rentaroom")
            );
        }

        if ($location === "foreign") {

            $data = $submission['foreignProperty'] ?? [];
            $foreign_property_data = [];

            foreach ($data as &$entry) {
                // Flatten SBA
                $entry['sba'] = AnnualSubmissionHelper::flattenSba($entry['allowances']['structuredBuildingAllowance'] ?? [], "sba");

                unset($entry['allowances']['structuredBuildingAllowance']);
            }
            unset($entry);

            // put country codes as head of array, to match create submission code
            foreach ($data as $entry) {
                $country_code = $entry['countryCode'];
                unset($entry['countryCode']);
                $foreign_property_data[$country_code] = $entry;
            }

            return $this->view(
                "Endpoints/PropertyBusiness/show-annual-submission.php",
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "type_of_business", "foreign_property_data")
            );
        }
    }

    public function deleteAnnualSubmission()
    {
        if (isset($this->request->post['delete_annual_submission'])) {

            $nino = Helper::getNino();

            $business_id = $_SESSION['business_id'];

            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiPropertyBusiness->deleteAPropertyBusinessAnnualSubmission($nino, $business_id, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            $deleted = false;

            if ($response['type'] === 'success') {
                $deleted = true;
            }

            if ($deleted) {

                $nino_hash = Helper::getHash($nino);
                $business_id = $_SESSION['business_id'];

                $submission_id = $this->submission->findSubmission($nino_hash, $business_id, $tax_year, "annual");

                if ($submission_id) {

                    $data = [
                        'id' => $submission_id,
                        'deleted_at' => date('Y-m-d H:i:s')
                    ];

                    $this->submission->update($data);
                }

                Flash::addMessage("The Annual Submission has been deleted", Flash::SUCCESS);

                return $this->redirect("/property-business/success?type=annual-deleted");
            }

            // failure
            Flash::addMessage("Unable to delete Annual Submission", Flash::WARNING);
            return $this->redirect("/property-business/annual-submission");
        } else {

            $tax_year = $_SESSION['tax_year'];

            $heading = "Delete Annual Submission";

            $hide_tax_year = true;

            return $this->view("Endpoints/PropertyBusiness/delete-annual-submission.php", compact("tax_year", "hide_tax_year", "heading"));
        }
    }

    public function success()
    {
        $type = $this->request->get['type'] ?? '';

        $heading = "Action Successful";

        $hide_tax_year = true;

        $supporting_agent  = false;
        if (isset($_SESSION['client']['agent_type']) && $_SESSION['client']['agent_type'] === "supporting") {
            $supporting_agent = true;
        }

        $business_details = Helper::setBusinessDetails();

        return $this->view("Endpoints/PropertyBusiness/success.php", compact("heading", "hide_tax_year", "business_details", "type", "supporting_agent"));
    }

    public function retrieveForeignProperties()
    {
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];
        $tax_year = "2026-27";

        $response = $this->apiPropertyBusiness->retrieveForeignPropertyDetails($nino, $business_id, $tax_year);

        var_dump($response['foreign_property_details']);
        exit;
    }

    public function createForeignProperty()
    {
        $source = "";
        if (isset($this->request->get['source'])) {
            $source = $this->request->get['source'];
        }

        $heading = "Add A Foreign Property";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";

        // first and last year must both be at least 2026-27
        $last_tax_year = TaxYearHelper::getCurrentTaxYear();
        $last_tax_year = TaxYearHelper::getLatestTaxYear("2026-27", $last_tax_year);
        $first_tax_year = TaxYearHelper::getCurrentTaxYear(-4);
        $first_tax_year = TaxYearHelper::getLatestTaxYear("2026-27", $first_tax_year);

        $errors = $this->flashErrors();

        return $this->view("Endpoints/PropertyBusiness/foreign-property-add.php", compact("heading", "country_codes", "hide_tax_year", "business_details", "last_tax_year", "first_tax_year", "errors", "source"));
    }

    public function processCreateForeignProperty()
    {

        if (!isset($this->request->post)) {
            $this->addError("Please fill in all fields");
            return $this->redirect("/property-business/create-foreign-property");
        }

        $property_name = $this->request->post['property_name'] ?? '';
        $country_code = $this->request->post['country_code'] ?? '';
        $tax_year = $this->request->post['tax_year'] ?? '';
        $source = $this->request->post['source'] ?? '';

        if (!Validate::string($property_name)) {
            $this->addError("Property name is required (max 105 characters)");
        }

        if (!Validate::countryCode($country_code)) {
            $this->addError("Select a country from the drop-down list");
        }

        if (!Validate::tax_year($tax_year)) {
            $tax_year = TaxYearHelper::getCurrentTaxYear();
        }

        if (!empty($this->errors)) {
            return $this->redirect("/property-business/create-foreign-property");
        }

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $property_data = [
            'propertyName' => $property_name,
            'countryCode' => $country_code
        ];

        $response = $this->apiPropertyBusiness->createForeignPropertyDetails($nino, $business_id, $tax_year, $property_data);

        unset($_SESSION[$nino]['cache']['foreign_properties']);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {

            $property_id = $response['property_id'];

            if (empty($property_id)) {

                $this->addError("An error occurred and HMRC did not return a property ID. Please try again");
                return $this->redirect("/property-business/create-foreign-property");
            }

            Flash::addMessage("Property has been saved", Flash::SUCCESS);
            if (empty($source)) {
                return $this->redirect("/business-details/retrieve-business-details");
            } else {
                return $this->redirect($source);
            }
        }
        // if not success
        return $this->redirect("/property-business/create-foreign-property");
    }

    public function viewForeignProperties()
    {
        $heading = "Foreign Properties";

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        // helper makes the api call because i also need them in the submit cumulative update view
        $properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);

        $hide_tax_year = true;

        $business_details = Helper::setBusinessDetails();

        return $this->view("Endpoints/PropertyBusiness/foreign-property-view.php", compact("heading", "hide_tax_year", "business_details", "properties"));
    }

    public function updateForeignProperty()
    {
        $property_id = $this->request->post['property_id'] ?? '';
        $property_name = $this->request->post['property_name'] ?? '';

        if (empty($property_id) || empty($property_name)) {
            return $this->redirect("/property-business/view-foreign-properties");
        }


        $heading = "Update Foreign Property";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        $errors = $this->flashErrors();

        return $this->view("Endpoints/propertyBusiness/foreign-property-update.php", compact("heading", "property_id", "property_name", "business_details", "hide_tax_year", "errors"));
    }

    public function processUpdateForeignProperty()
    {
        $property_id = $this->request->post['property_id'] ?? '';
        $property_name = $this->request->post['property_name'] ?? '';
        $end_date = $this->request->post['end_date'] ?? '';
        $end_reason = $this->request->post['end_reason'] ?? '';

        if (empty($property_id)) {
            return $this->redirect("/property-business/view-foreign-properties");
        }

        if (empty($property_name)) {
            $this->addError("Property name is required");
        }

        if (empty($end_date)) {
            $end_reason = "";
        }

        if (empty($end_reason) && !empty($end_date)) {
            $this->addError("End reason is required  if an end date is given");
        }

        if (!empty($this->errors)) {
            return $this->redirect("/property-business/update-foreign-property");
        }

        $property_data = [
            'propertyName' => $property_name
        ];

        if (!empty($end_date)) {
            $property_data['endDate'] = $end_date;
            $property_data['endReason'] = $end_reason;
        }

        $nino = Helper::getNino();


        if (!empty($end_date)) {
            $tax_year = TaxYearHelper::getTaxYearFromDate($end_date);
        } else {
            $tax_year = TaxYearHelper::getLatestTaxYear(TaxYearHelper::getCurrentTaxYear(), "2026-27");
        }

        $response = $this->apiPropertyBusiness->updateForeignPropertyDetails($nino, $property_id, $tax_year, $property_data);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("Property has been updated", Flash::SUCCESS);
        }
        return $this->redirect("/business-details/retrieve-business-details");
    }
}
