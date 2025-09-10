<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Helpers\Helper;
use App\Helpers\SubmissionsHelper;
use App\Helpers\AgentHelper;
use App\HmrcApi\Endpoints\ApiPropertyBusiness;
use App\Models\Submission;
use App\Flash;
use Framework\Controller;

class PropertyBusiness extends Controller
{
    public function __construct(private ApiPropertyBusiness $apiPropertyBusiness, private Submission $submission) {}

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


        if ($empty_data) {
            return $this->view(
                "Endpoints/PropertyBusiness/show-cumulative-summary.php",
                compact("heading", "hide_tax_year", "business_details", "empty_data")
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
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "income", "expenses", "rentaroom", "rentaroom_profit", "residential_finance", "total_income", "total_expenses", "profit")
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

                $finance_fields = ['residentialFinancialCost', 'broughtFwdResidentialFinancialCost'];
                foreach ($finance_fields as $field) {
                    if (isset($entry['expenses'][$field])) {
                        $entry['financeCosts'][$field] = $entry['expenses'][$field];
                        unset($entry['expenses'][$field]);
                    }
                }
            }
            unset($entry);

            // var_dump($foreign_property_data);
            // exit;

            // $foreign_property_fields = require ROOT_PATH . "config/mappings/foreign-property.php";

            // $income_fields = $foreign_property_fields['cumulative']['income'];
            // $expense_fields = $foreign_property_fields['cumulative']['expenses'];
            // $finance_fields = $foreign_property_fields['cumulative']['residentialFinance'];

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


            // This just filters income, expense and finance fields to get rid of unused fields. The data from the api is not filtered. It's needed to make sure all tables are using the same fields if there are multiple countries.
            // $income_fields = array_filter($income_fields, function ($field) use ($foreign_property_data) {
            //     foreach ($foreign_property_data as $entry) {
            //         if (isset($entry['income'][$field])) return true;
            //     }
            //     return false;
            // });

            // $expense_fields = array_filter($expense_fields, function ($field) use ($foreign_property_data) {
            //     foreach ($foreign_property_data as $entry) {
            //         if (isset($entry['expenses'][$field])) return true;
            //     }
            //     return false;
            // });

            // $finance_fields = array_filter($finance_fields, function ($field) use ($foreign_property_data) {
            //     foreach ($foreign_property_data as $entry) {
            //         if (isset($entry['financeCosts'][$field])) return true;
            //     }
            //     return false;
            // });

            return $this->view(
                "Endpoints/PropertyBusiness/show-cumulative-summary.php",
                compact("empty_data", "location", "heading", "hide_tax_year", "business_details", "foreign_property_data", "consolidated_expenses", "non_consolidated_expenses")
            );
        }
    }

    public function submitCumulativePeriodSummary()
    {

        $confirm_submit = $this->request->get['confirm_submit'] ?? false;

        if (!$confirm_submit) {
            $this->addError("You must tick the box to confirm you have approved this submission.");

            return $this->redirect("/uploads/approve-uk-property");
        }

        SubmissionsHelper::finaliseUkPropertyCumulativeSummaryArray();

        $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']];

        unset($_SESSION['cumulative_data']);

        $location =  ($_SESSION['type_of_business'] === "uk-property") ? "uk" : "foreign";

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

            $submission_data['submission_payload'] = json_encode($final_array);

            $this->submission->insert($submission_data);

            Flash::addMessage("Your Cumulative Summary has been submitted to HMRC", Flash::SUCCESS);

            if (!AgentHelper::isSupportingAgent()) {
                exit("success");
                // return $this->redirect("/individual-calculations/trigger-calculation");
            }
        }

        if ($response['type'] === 'error') {
            Flash::addMessage("The Cumulative Summary has not been submitted to HMRC", Flash::WARNING);
        }

        // failure or supporting agent
        return $this->redirect("/obligations/retrieve-cumulative-obligations");
    }
}
