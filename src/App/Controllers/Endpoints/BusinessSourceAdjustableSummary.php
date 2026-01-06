<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Flash;
use App\Helpers\BsasHelper;
use App\Helpers\ForeignPropertyHelper;
use App\Helpers\Helper;
use App\Helpers\PeriodsOfAccountHelper;
use App\Helpers\SubmissionsHelper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiBusinessSourceAdjustableSummary;
use App\Models\Submission;
use Framework\Controller;
use DateTime;

class BusinessSourceAdjustableSummary extends Controller
{
    public function __construct(private ApiBusinessSourceAdjustableSummary $apiBusinessSourceAdjustableSummary, private Submission $submission, private PeriodsOfAccountHelper $periodsOfAccountHelper, private ForeignPropertyHelper $foreignPropertyHelper) {}

    public function index()
    {
        Helper::clearUpSession();

        $heading = "Accounting Adjustments";

        $business_details = Helper::setBusinessDetails();

        return $this->view("Endpoints/BusinessSourceAdjustableSummary/index.php", compact("heading", "business_details"));
    }

    public function trigger()
    {
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        $start_date = TaxYearHelper::getTaxYearStartDate($tax_year);
        $end_date = TaxYearHelper::getTaxYearEndDate($tax_year);



        if (isset($_SESSION['period_type'])) {
            if ($_SESSION['period_type'] !== "standard") {
                $start_date = (new DateTime($start_date))->modify("-5 days")->format("Y-m-d");
                $end_date = (new DateTime($end_date))->modify("-5 days")->format("Y-m-d");
            }
        } else {
            $periods = $this->periodsOfAccountHelper->getPeriodsOfAccount($nino, $business_id);
            if ($periods === "calendar") {
                $start_date = (new DateTime($start_date))->modify("-5 days")->format("Y-m-d");
                $end_date = (new DateTime($end_date))->modify("-5 days")->format("Y-m-d");
            }
        }

        $accounting_period = [
            'startDate' => $start_date,
            'endDate' => $end_date
        ];

        $type_of_business = $_SESSION['type_of_business'];

        $response = $this->apiBusinessSourceAdjustableSummary->triggerABusinessSourceAdjustableSummary($nino, $business_id, $type_of_business, $accounting_period);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success" && !empty($response['calculation_id'])) {

            $_SESSION['bsas_calc_id'] = $response['calculation_id'];

            return $this->redirect("/business-source-adjustable-summary/create");
        } else {

            return $this->redirect("/business-details/retrieve-business-details");
        }
    }

    public function create()
    {
        // *************TEST ONLY DELETE FOR PRODUCTION
        $_SESSION['tax_year'] = "2026-27";
        // *************TEST ONLY DELETE FOR PRODUCTION

        $add_another = $this->request->get['add_another'] ?? false;

        $type = $_SESSION['type_of_business'];

        if ($type !== "foreign-property" || !$add_another) {

            if (empty($_SESSION['errors'])) {
                unset($_SESSION['bsas']);
            }
        }

        $heading = "Accounting Adjustments";

        $business_details = Helper::setBusinessDetails();

        $errors = $this->flashErrors();

        if ($type !== "foreign-property") {
            // self-employment and uk-property
            // I don't need zeroAdjustments here as there's never an error if zeroAdjustments is set
            $income = $_SESSION['bsas'][$_SESSION['business_id']]['income'] ?? [];
            $expenses = $_SESSION['bsas'][$_SESSION['business_id']]['expenses'] ?? [];
            $additions = $_SESSION['bsas'][$_SESSION['business_id']]['additions'] ?? [];

            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/create-bsas-$type.php",
                compact("heading", "business_details", "errors", "income", "expenses", "additions", "add_country")
            );
        } elseif ($type === "foreign-property") {

            $country_code = "";
            $country_codes = [];
            $country_data = [];
            $property_id = "";
            $foreign_properties = [];
            $property_data = [];
            $country_or_property = "property";


            if ($_SESSION['tax_year'] === "2025-26") {

                $country_or_property = "country";

                if (empty($errors)) {
                    // this clears only the country code, not the annual submission data
                    unset($_SESSION['bsas'][$_SESSION['business_id']]['countryCode']);
                }
                // country code is saved by AnnualSubmissionHelper if there are errors
                $country_code = $_SESSION['bsas'][$_SESSION['business_id']]['countryCode'] ?? '';

                // get current country's data from the submission
                $country_data = $_SESSION['bsas'][$_SESSION['business_id']][$country_code] ?? [];
                $income = $country_data['income'] ?? [];
                $expenses = $country_data['expenses'] ?? [];

                $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";
            } else {


                if (empty($errors)) {
                    // this clears only the property id, not the annual submission data
                    unset($_SESSION['bsas'][$_SESSION['business_id']]['propertyId']);
                }

                // property id is saved by AnnualSubmissionHelper if there are errors
                $property_id = $_SESSION['bsas'][$_SESSION['business_id']]['propertyId'] ?? '';

                // get current property's data from the submission
                $property_data = $_SESSION['bsas'][$_SESSION['business_id']][$property_id] ?? [];
                $income = $property_data['income'] ?? [];
                $expenses = $property_data['expenses'] ?? [];

                $nino = Helper::getNino();
                $business_id = $_SESSION['business_id'];
                $tax_year = $_SESSION['tax_year'];

                $foreign_properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);
            }


            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/create-bsas-$type.php",
                compact("heading", "business_details", "errors", "income", "expenses", "country_code", "country_codes", "property_id", "foreign_properties", "property_data", "add_another", "country_or_property")
            );
        }
    }

    public function process()
    {
        $data = $this->request->post;



        if (isset($data['zeroAdjustments'])) {
            unset($_SESSION['bsas']);
            $_SESSION['bsas'][$_SESSION['business_id']]['zeroAdjustments'] = true;
            return $this->redirect("/business-source-adjustable-summary/finalise");
        }


        if ($_SESSION['type_of_business'] !== "foreign-property") {
            unset($_SESSION['bsas']);
        }

        $errors = BsasHelper::validateBsas($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect("/business-source-adjustable-summary/create");
        }

        if ($_SESSION['type_of_business'] === "foreign-property") {
            if ($_SESSION['tax_year'] === "2025-26") {
                return $this->redirect("/business-source-adjustable-summary/add-country");
            } else {
                return $this->redirect("/business-source-adjustable-summary/add-property");
            }
        } else {
            return $this->redirect("/business-source-adjustable-summary/finalise");
        }
    }

    public function addCountry()
    {
        $business_details = Helper::setBusinessDetails();

        if ($_SESSION['type_of_business'] !== 'foreign-property' || empty($_SESSION['bsas'][$_SESSION['business_id']])) {
            return $this->redirect("/business-source-adjustable-summary/create");
        }

        $heading = "Accounting Adjustments";

        $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";
        $session_country_codes = array_keys($_SESSION['bsas'][$_SESSION['business_id']]);


        $country_names = [];

        foreach ($session_country_codes as $code) {

            foreach ($country_codes as $continent => $countries) {
                if (isset($countries[$code])) {
                    $country_names[] = $countries[$code];
                    break;
                }
            }
        }

        return $this->view("Endpoints/BusinessSourceAdjustableSummary/add-country.php", compact("heading", "business_details", "country_names"));
    }

    public function addProperty()
    {

        $business_details = Helper::setBusinessDetails();

        if ($_SESSION['type_of_business'] !== 'foreign-property' || empty($_SESSION['bsas'][$_SESSION['business_id']])) {
            return $this->redirect("/business-source-adjustable-summary/create");
        }

        $heading = "Accounting Adjustments";

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        $foreign_properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);
        $session_property_ids = array_keys($_SESSION['bsas'][$_SESSION['business_id']]);


        $selected_properties = [];

        foreach ($foreign_properties as $property) {
            if (in_array($property['propertyId'], $session_property_ids)) {
                $selected_properties[] = $property;
            }
        }

        return $this->view("Endpoints/BusinessSourceAdjustableSummary/add-property.php", compact("heading", "business_details", "selected_properties", "foreign_properties"));
    }

    public function finalise()
    {

        $type = $_SESSION['type_of_business'];

        $business_details = Helper::setBusinessDetails();

        $errors = $this->flashErrors();

        $hide_tax_year = true;

        $bsas_data = $_SESSION['bsas'][$_SESSION['business_id']] ?? [];

        if (empty($bsas_data)) {
            Flash::addMessage("Unable to retrieve adjustments. Please try again", Flash::WARNING);
            return $this->redirect("/business-source-adjustable-summary/trigger");
        }

        $heading = "Confirm Accounting Adjustments";

        $zero_adjustments = $bsas_data['zeroAdjustments'] ?? "";

        if ($type !== "foreign-property") {

            $income = $bsas_data['income'] ?? [];
            $expenses = $bsas_data['expenses'] ?? [];
            $additions = $bsas_data['additions'] ?? [];
            $total_income = array_sum($income);
            $total_expenses = array_sum($expenses);
            $total_additions = array_sum($additions);
            $total_allowed = $total_expenses - $total_additions;
            $profit = $total_income - $total_allowed;

            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/finalise-bsas-$type.php",
                compact("errors", "heading", "hide_tax_year", "business_details", "income", "expenses", "additions", "zero_adjustments",  "total_income", "total_expenses", "total_allowed", "total_additions", "profit")
            );
        } elseif ($type === "foreign-property") {

            $country_or_property = $_SESSION['tax_year'] === "2025-26" ? "country" : "property";

            $foreign_property_data = $bsas_data;

            $nino = Helper::getNino();
            $business_id = $_SESSION['business_id'];
            $tax_year = $_SESSION['tax_year'];

            $foreign_properties = $this->foreignPropertyHelper->getForeignProperties($nino, $business_id, $tax_year);

            // unset the country tracker
            unset($foreign_property_data['countryCode']);
            unset($foreign_property_data['propertyId']);



            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/finalise-bsas-$type.php",
                compact("errors", "heading", "hide_tax_year", "business_details", "foreign_property_data", "zero_adjustments", "foreign_properties", "country_or_property")
            );
        }
    }

    public function submit()
    {
        if (!isset($this->request->post['confirm_submit'])) {
            $this->addError("Please tick the confirmation box to proceed");
            return $this->redirect("/business-source-adjustable-summary/finalise");
        }

        $type_of_business = $_SESSION['type_of_business'];

        $zero_adjustments = $_SESSION['bsas'][$_SESSION['business_id']]['zeroAdjustments'] ?? [];

        $bsas_data = [];

        $country_or_property = $_SESSION['tax_year'] === "2025-26" ? "country" : "property";

        if (!empty($zero_adjustments)) {

            $bsas_data['zeroAdjustments'] = $zero_adjustments;
        } elseif ($type_of_business === "foreign-property") {

            $foreign_property_data = $_SESSION['bsas'][$_SESSION['business_id']] ?? [];

            if ($country_or_property === "country") {
                $bsas_data['countryLevelDetail'] = [];

                // removes the top  level countryCode, which is no longer needed
                unset($foreign_property_data['countryCode']);

                foreach ($foreign_property_data as $data) {
                    // skips the countryCode used for identifying current country, if still set 
                    if (!is_array($data) || !isset($data['countryCode'])) {
                        continue;
                    }

                    $bsas_data['countryLevelDetail'][] = [
                        'countryCode' => $data['countryCode'],
                        'income' => $data['income'] ?? [],
                        'expenses' => $data['expenses'] ?? [],
                    ];
                }
            } else {
                $bsas_data['propertyLevelDetail'] = [];

                // removes the top level propertyId, which is no longer needed
                unset($foreign_property_data['propertyId']);

                foreach ($foreign_property_data as $data) {
                    // skips the countryCode used for identifying current country, if still set 
                    if (!is_array($data) || !isset($data['propertyId'])) {
                        continue;
                    }

                    $bsas_data['propertyLevelDetail'][] = [
                        'propertyId' => $data['propertyId'],
                        'income' => $data['income'] ?? [],
                        'expenses' => $data['expenses'] ?? [],
                    ];
                }
            }
        } else {
            // uk-property or self-employment
            $income = $_SESSION['bsas'][$_SESSION['business_id']]['income'] ?? [];
            $expenses = $_SESSION['bsas'][$_SESSION['business_id']]['expenses'] ?? [];
            $additions = $_SESSION['bsas'][$_SESSION['business_id']]['additions'] ?? [];

            if (!empty($income)) {
                $bsas_data['income'] = $income;
            }

            if (!empty($expenses)) {
                $bsas_data['expenses'] = $expenses;
            }

            if (!empty($additions)) {
                $bsas_data['additions'] = $additions;
            }
        }

        $nino = Helper::getNino();
        $calculation_id = $_SESSION['bsas_calc_id'];
        $tax_year = $_SESSION['tax_year'];


        if ($type_of_business === "uk-property") {

            $bsas_data = [
                'ukProperty' => $bsas_data
            ];
        } elseif ($type_of_business === "foreign-property") {

            if (!$zero_adjustments) {

                if ($country_or_property === "country") {

                    foreach ($bsas_data['countryLevelDetail'] as $index => $data) {

                        if (empty($data['income'])) {
                            unset($bsas_data['countryLevelDetail'][$index]['income']);
                        }

                        if (empty($data['expenses'])) {
                            unset($bsas_data['countryLevelDetail'][$index]['expenses']);
                        }
                    }
                } else {

                    foreach ($bsas_data['propertyLevelDetail'] as $index => $data) {

                        if (empty($data['income'])) {
                            unset($bsas_data['propertyLevelDetail'][$index]['income']);
                        }

                        if (empty($data['expenses'])) {
                            unset($bsas_data['propertyLevelDetail'][$index]['expenses']);
                        }
                    }
                }
            }

            $bsas_data = [
                'foreignProperty' => $bsas_data
            ];
        }



        // self-employment is just $bsas_data
        $response = $this->apiBusinessSourceAdjustableSummary->submitAccountingAdjustments($nino, $type_of_business, $calculation_id, $tax_year, $bsas_data);

        unset($_SESSION['bsas']);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success" && !empty($response['submission_id'])) {

            $submission_data = SubmissionsHelper::createSubmission("bsas", $response['submission_id']);

            $submission_data['submission_payload'] = json_encode($bsas_data);

            $this->submission->insert($submission_data);

            Flash::addMessage("Your Accounting Adjustments have been submitted to HMRC", Flash::SUCCESS);
        }

        // failure
        return $this->redirect("/business-source-adjustable-summary/index");
    }

    public function success()
    {

        $heading = "Action Successful";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        return $this->view("Endpoints/BusinessSourceAdjustableSummary/success.php", compact("heading", "hide_tax_year", "business_details"));
    }

    public function list()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $business_id = $_SESSION['business_id'];

        $response = $this->apiBusinessSourceAdjustableSummary->listBusinessSourceAdjustableSummaries($nino, $tax_year, $business_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $calculation_id = "";

        if ($response['type'] === 'success' && !empty($response['data'])) {

            $data = $response['data'];

            $latest_timestamp = null;

            foreach ($data['summaries'] as $summary) {
                if ($summary['summaryStatus'] === 'valid') {
                    $timestamp = strtotime($summary['requestedDateTime']);

                    if (is_null($latest_timestamp) || $timestamp > $latest_timestamp) {
                        $latest_timestamp = $timestamp;
                        $calculation_id = $summary['calculationId'];
                    }
                }
            }
        }


        return $this->redirect("/business-source-adjustable-summary/retrieve?calculation_id=$calculation_id");
    }

    public function retrieve()
    {

        $calculation_id = $this->request->get['calculation_id'] ?? '';

        $heading = "Accounting Adjustments";

        $business_type = $_SESSION['type_of_business'];

        $hide_tax_year = true;

        $business_details = Helper::setBusinessDetails();

        // if calculation id is empty, return view saying no bsas before the next api call
        if (empty($calculation_id)) {
            return $this->view("Endpoints/BusinessSourceAdjustableSummary/show-bsas-" . $business_type . ".php", compact("heading", "business_details", "hide_tax_year", "calculation_id"));
        }

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiBusinessSourceAdjustableSummary->retrieveBusinessSourceAdjustableSummary($nino, $business_type, $calculation_id, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] !== 'success') {
            return $this->redirect("/business-source-adjustable-summary/index");
        }

        $adjustments = $response['summary']['adjustments'] ?? [];

        $zero_adjustments = $adjustments['zeroAdjustments'] ?? false;

        if ($business_type === "self-employment"  || $business_type === "uk-property") {

            $income = $adjustments['income'] ?? [];
            $expenses = $adjustments['expenses'] ?? [];
            $additions = $adjustments['additions'] ?? [];

            $total_income = array_sum($income);
            $total_expenses = array_sum($expenses);
            $total_additions = array_sum($additions);
            $total_allowed = $total_expenses - $total_additions;
            $profit = $total_income - $total_allowed;

            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/show-bsas-" . $business_type . ".php",
                compact("heading", "business_details", "hide_tax_year", "income", "expenses", "additions", "total_income", "total_expenses", "total_additions", "total_allowed", "profit", "zero_adjustments", "calculation_id")
            );
        }

        if ($business_type === "foreign-property") {

            $foreign_property_data = [];

            foreach ($adjustments['countryLevelDetail'] as $data) {

                $country_code = $data['countryCode'];
                unset($data['countryCode']);

                $foreign_property_data[$country_code] = $data;
            }

            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/show-bsas-foreign-property.php",
                compact("heading", "business_details", "hide_tax_year", "foreign_property_data", "zero_adjustments", "calculation_id")
            );
        }
    }
}
