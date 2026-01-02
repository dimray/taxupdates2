<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\SubmissionsHelper;
use App\Helpers\UploadHelper;
use App\Flash;
use App\Helpers\ForeignPropertyHelper;
use Framework\Controller;

class Uploads extends Controller
{
    public function __construct(private ForeignPropertyHelper $foreign_property_helper) {}

    public function createCumulativeUpload()
    {
        // keep foreign property session in case adding more properties
        if ($_SESSION['type_of_business'] !== "foreign-property") {
            unset($_SESSION['cumulative_data']);
        }

        // but clear foreign property session if cancel has been selected
        $cancel_foreign_property = isset($this->request->get['cancel-foreign-property']) ? true : false;

        if ($cancel_foreign_property) {
            unset($_SESSION['cumulative_data']);
        }

        if (isset($this->request->get['period_start_date'])) {
            $_SESSION['period_start_date'] = $this->request->get['period_start_date'];
        }

        if (isset($this->request->get['period_end_date'])) {
            $_SESSION['period_end_date'] = $this->request->get['period_end_date'];
        }

        $errors = $this->flashErrors();

        $business_details = Helper::setBusinessDetails();

        $business_details['periodStartDate'] = $_SESSION['period_start_date'];
        $business_details['periodEndDate'] = $_SESSION['period_end_date'];

        $heading = "Import Cumulative Summary Data";

        $hide_tax_year = true;

        $type_of_business = $_SESSION['type_of_business'];

        $form_action = "/uploads/process-cumulative-upload";

        $country_codes = [];
        $foreign_properties = [];
        $nino = Helper::getNino();




        if ($type_of_business === "foreign-property") {

            if ($_SESSION['tax_year'] === "2025-26") {
                $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";
            } else {
                if (isset($_SESSION['foreign_properties'])) {
                    $foreign_properties = $_SESSION['foreign_properties'];
                } else {
                    $foreign_properties = $this->foreign_property_helper->getForeignProperties($nino);
                    $_SESSION['foreign_properties'] = $foreign_properties;
                }
            }

            // ********************
            // FOR TESTING remove for production
            $foreign_properties = $this->foreign_property_helper->getForeignProperties($nino);
            $country_codes = [];
            // ****************************
        }


        return $this->view("Uploads/cumulative-upload.php", compact("heading", "business_details", "type_of_business", "errors", "hide_tax_year", "form_action", "country_codes", "foreign_properties"));
    }

    public function processCumulativeUpload()
    {
        $country_or_property = "";
        $country_code = "";
        $property_id = "";

        // check whether dealing with countries or properties
        if ($_SESSION['type_of_business'] === "foreign-property") {
            // set country code and foreign tax credit relief
            $country_or_property = $this->request->post['country_or_property'];

            if ($country_or_property === "country") {

                $country_code = $this->request->post['country_code'] ?? '';

                if (empty($country_code)) {
                    $this->addError("Country is required");
                    return $this->redirect("/uploads/create-cumulative-upload");
                }
            } else {

                $property_id = $this->request->post['hmrc_property_id'] ?? '';

                if (empty($property_id)) {
                    $this->addError("A property must be selected");
                    return $this->redirect("/uploads/create-cumulative-upload");
                }
            }

            $foreign_tax_credit_relief = isset($this->request->post['foreign_tax_credit_relief']) ? true : false;
        }

        // keep foreign property session in case adding more properties
        if ($_SESSION['type_of_business'] !== "foreign-property") {
            unset($_SESSION['cumulative_data']);
        }

        // check for no data
        if (
            (empty($this->request->post['pasted_data']) || !isset($this->request->post['pasted_data'])) &&
            (isset($this->request->files['csv_upload']) && $this->request->files['csv_upload']['error'] == UPLOAD_ERR_NO_FILE)
        ) {
            $this->addError("No data submitted");
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        // check for double data
        if (
            !empty($this->request->post['pasted_data']) &&
            (isset($this->request->files['csv_upload']) && $this->request->files['csv_upload']['error'] != UPLOAD_ERR_NO_FILE)
        ) {
            $_SESSION['errors'] = ["Either upload a file or paste your data, not both."];
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        $parsed_data = [];

        // data paste
        if (isset($this->request->post['pasted_data']) && !empty($this->request->post['pasted_data'])) {

            $pasted_data = trim($this->request->post['pasted_data']);

            $errors = UploadHelper::processPasteErrors($pasted_data, 100, 2);

            if (!empty($errors)) {

                $_SESSION['errors'] = $errors;
                return $this->redirect("/uploads/create-cumulative-upload");
            }

            $parsed_data = UploadHelper::parseDataToKeyValueArray($pasted_data, ['name', 'nino']);
        } else {
            // csv upload
            $file = $this->request->files['csv_upload'] ?? null;

            $errors = UploadHelper::processCsvErrors($file, 200, 2);

            if (!empty($errors)) {

                $_SESSION['errors'] = $errors;
                return $this->redirect("/uploads/create-cumulative-upload");
            }

            $parsed_data = UploadHelper::parseKeyValueCsv($file);
        }

        $camel_case_data = SubmissionsHelper::camelCaseArrayKeys($parsed_data);

        $cumulative_data = SubmissionsHelper::buildArrays($camel_case_data, $_SESSION['type_of_business'], "cumulative");

        $cumulative_data = SubmissionsHelper::formatArrayValuesAsFloat($cumulative_data);

        $business_type = $_SESSION['type_of_business'] === "self-employment" ? "self-employment" : "property";

        $errors = SubmissionsHelper::validatePositiveNegativeCumulativeArrays($cumulative_data, $business_type);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        if ($_SESSION['type_of_business'] === "foreign-property") {

            $cumulative_data['foreignTaxCreditRelief'] = $foreign_tax_credit_relief;

            if ($country_or_property === "country") {
                $_SESSION['cumulative_data'][$_SESSION['business_id']][$country_code] = $cumulative_data;

                return $this->redirect("/uploads/add-country");
            } else {
                $_SESSION['cumulative_data'][$_SESSION['business_id']][$property_id] = $cumulative_data;

                return $this->redirect("/uploads/add-property");
            }
        }

        $_SESSION['cumulative_data'][$_SESSION['business_id']] = $cumulative_data;

        return $this->redirect("/uploads/approve-" . $_SESSION['type_of_business']);
    }

    public function approveSelfEmployment()
    {
        $business_details = Helper::setBusinessDetails();
        $business_details['periodStartDate'] = $_SESSION['period_start_date'];
        $business_details['periodEndDate'] = $_SESSION['period_end_date'];

        $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']] ?? "";

        if (empty($cumulative_data)) {
            Flash::addMessage("An error occurred, please try again", Flash::WARNING);
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        $errors = $this->flashErrors();

        $income = $cumulative_data['periodIncome'];
        $expenses = $cumulative_data['periodExpenses'];
        $disallowed = $cumulative_data['periodDisallowableExpenses'];
        $total_income = array_sum($income);
        $total_expenses = array_sum($expenses);
        $total_disallowed = array_sum($disallowed);
        $total_allowed = $total_expenses - $total_disallowed;
        $profit = $total_income - $total_allowed;

        $heading = "Confirm Cumulative Summary";

        $hide_tax_year = true;

        return $this->view(
            "Endpoints/SelfEmployment/approve-cumulative-summary.php",
            compact(
                "heading",
                "business_details",
                "errors",
                "income",
                "expenses",
                "disallowed",
                "total_income",
                "total_expenses",
                "total_disallowed",
                "total_allowed",
                "profit",
                "hide_tax_year"
            )
        );
    }

    public function approveUkProperty()
    {
        $business_details = Helper::setBusinessDetails();
        $business_details['periodStartDate'] = $_SESSION['period_start_date'];
        $business_details['periodEndDate'] = $_SESSION['period_end_date'];

        $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']] ?? "";

        if (empty($cumulative_data)) {
            Flash::addMessage("An error occurred, please try again", Flash::WARNING);
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        $errors = $this->flashErrors();

        $income = $cumulative_data['income'] ?? [];
        $expenses = $cumulative_data['expenses'] ?? [];
        $rentaroom = $cumulative_data['rentARoom'] ?? [];
        $residential_finance = $cumulative_data['residentialFinance'] ?? [];

        if (!empty($rentaroom)) {
            $rentaroom['rentsReceived'] = $rentaroom['rentARoomRentsReceived'] ?? 0;
            $rentaroom['amountClaimed'] = $rentaroom['rentARoomAmountClaimed'] ?? 0;
            unset($rentaroom['rentARoomRentsReceived'], $rentaroom['rentARoomAmountClaimed']);
        }

        $total_income = array_sum($income);
        $total_expenses = array_sum($expenses);
        $rentaroom_profit = (($rentaroom['rentsReceived'] ?? 0) - ($rentaroom['amountClaimed'] ?? 0));
        $profit = $total_income + $rentaroom_profit - $total_expenses;

        $heading = "Confirm Cumulative Summary";

        $hide_tax_year = true;

        return $this->view(
            "Endpoints/PropertyBusiness/approve-cumulative-summary-uk.php",
            compact(
                "heading",
                "business_details",
                "errors",
                "income",
                "expenses",
                "rentaroom",
                "rentaroom_profit",
                "residential_finance",
                "total_income",
                "total_expenses",
                "profit",
                "hide_tax_year"
            )
        );
    }

    public function addCountry()
    {
        $business_details = Helper::setBusinessDetails();
        $business_details['periodStartDate'] = $_SESSION['period_start_date'];
        $business_details['periodEndDate'] = $_SESSION['period_end_date'];

        if ($_SESSION['type_of_business'] !== 'foreign-property' || empty($_SESSION['cumulative_data'][$_SESSION['business_id']])) {
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        $heading = "Cumulative Summary";

        $country_codes = require ROOT_PATH . "config/mappings/country-codes.php";
        $session_country_codes = array_keys($_SESSION['cumulative_data'][$_SESSION['business_id']]);
        $country_names = [];

        foreach ($session_country_codes as $code) {

            foreach ($country_codes as $continent => $countries) {
                if (isset($countries[$code])) {
                    $country_names[] = $countries[$code];
                    break;
                }
            }
        }

        return $this->view("Uploads/cumulative-add-country.php", compact("heading", "business_details", "country_names"));
    }

    public function addProperty()
    {

        $business_details = Helper::setBusinessDetails();
        $business_details['periodStartDate'] = $_SESSION['period_start_date'];
        $business_details['periodEndDate'] = $_SESSION['period_end_date'];

        if ($_SESSION['type_of_business'] !== 'foreign-property' || empty($_SESSION['cumulative_data'][$_SESSION['business_id']])) {
            return $this->redirect("/uploads/create-cumulative-upload");
        }

        $nino = Helper::getNino();

        $foreign_properties = $this->foreign_property_helper->getForeignProperties($nino);
        $selected_properties = [];
        $session_property_ids = array_keys($_SESSION['cumulative_data'][$_SESSION['business_id']]);

        foreach ($foreign_properties as $property) {
            if (in_array($property['propertyId'], $session_property_ids)) {
                $selected_properties[] = $property;
            }
        }

        $heading = "Cumulative Summary";

        return $this->view("Uploads/cumulative-add-property.php", compact("heading", "business_details", "selected_properties", "foreign_properties"));
    }

    public function approveForeignProperty()
    {
        $business_details = Helper::setBusinessDetails();
        $business_details['periodStartDate'] = $_SESSION['period_start_date'];
        $business_details['periodEndDate'] = $_SESSION['period_end_date'];

        $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']] ?? "";

        if (empty($cumulative_data)) {
            Flash::addMessage("An error occurred, please try again", Flash::WARNING);
            return $this->redirect("/uploads/create-cumulative-upload");
        }


        // differentiate between country or property from 26/27
        $country_or_property = "";

        if ($_SESSION['tax_year'] === "2025-26") {
            $country_or_property = "country";
        } else {
            $country_or_property = "property";
        }

        // ********************
        // For TESTING remove at production
        $country_or_property = "property";
        // End for TESTING
        // ********************

        // put the country code or property id inside the array and save this as the session array       

        // set totals
        $totals = [];

        foreach ($cumulative_data as $key => $data) {

            $total_income = array_sum($data['income']);
            $total_expenses = array_sum($data['expenses']);

            $totals[$key] = [
                'total_income' => $total_income,
                'total_expenses' => $total_expenses,
                'profit' => $total_income - $total_expenses
            ];
        }

        // check if consolidated expenses is needed, and also if there are non-consolidated expenses present:
        $consolidated_expenses = false;
        $non_consolidated_expenses = false;
        foreach ($cumulative_data as $entry) {
            if (isset($entry['expenses']['consolidatedExpenses'])) {
                $consolidated_expenses = true;
            } else {
                $non_consolidated_expenses = true;
            }
        }

        // prepare view
        $errors = $this->flashErrors();

        $heading = "Confirm Cumulative Summary";

        $hide_tax_year = true;

        $foreign_property_data = $cumulative_data;

        $nino = Helper::getNino();

        $foreign_properties = $this->foreign_property_helper->getForeignProperties($nino);

        return $this->view(
            "Endpoints/PropertyBusiness/approve-cumulative-summary-foreign.php",
            compact(
                "heading",
                "business_details",
                "errors",
                "foreign_property_data",
                "foreign_properties",
                "totals",
                "consolidated_expenses",
                "non_consolidated_expenses",
                "hide_tax_year",
                "country_or_property"
            )
        );
    }
}
