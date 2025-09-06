<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Flash;
use App\Helpers\AgentHelper;
use App\Helpers\Helper;
use App\Helpers\SubmissionsHelper;
use App\HmrcApi\Endpoints\ApiSelfEmployment;
use App\Models\Submission;
use Framework\Controller;

class SelfEmployment extends Controller
{
    public function __construct(private ApiSelfEmployment $apiSelfEmployment, private Submission $submission) {}

    public function retrieveCumulativePeriodSummary()
    {

        $tax_year = $_SESSION['tax_year'];

        $nino = Helper::getNino();

        $business_id = $_SESSION['business_id'] ?? "";

        if (empty($business_id) || empty($nino)) {
            return $this->redirect("/business-details/list-all-businesses");
        }

        $response = $this->apiSelfEmployment->retrieveASelfEmploymentCumulativePeriodSummary($nino, $business_id, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $summary = [];

        if ($response['type'] === 'success') {
            $summary = $response['summary'];
        }

        $heading = "Cumulative Summary";

        $business_details = Helper::setBusinessDetails();

        $period_dates = $summary['periodDates'] ?? [];
        $period_start_date = $period_dates['periodStartDate'] ?? "";
        $period_end_date = $period_dates['periodEndDate'] ?? "";

        $business_details['periodStartDate'] = $period_start_date;
        $business_details['periodEndDate'] = $period_end_date;

        $income = $summary['periodIncome'] ?? [];
        $expenses = $summary['periodExpenses'] ?? [];
        $disallowed = $summary['periodDisallowableExpenses'] ?? [];

        $total_income = array_sum($income);
        $total_expenses = array_sum($expenses);
        $total_disallowed = array_sum($disallowed);
        $total_allowed = $total_expenses - $total_disallowed;

        $profit = $total_income - $total_allowed;

        if (!empty($summary)) {
            $response = true;
        }

        $hide_tax_year = true;

        return $this->view(
            "Endpoints/SelfEmployment/show-cumulative-summary.php",
            compact("heading", "hide_tax_year", "business_details", "response", "income", "expenses", "disallowed", "total_income", "total_expenses", "total_disallowed", "total_allowed", "profit")
        );
    }

    public function submitCumulativePeriodSummary()
    {
        $confirm_submit = $this->request->get['confirm_submit'] ?? false;

        if (!$confirm_submit) {
            $this->addError("You must tick the box to confirm you have approved this submission.");

            return $this->redirect("/uploads/approve-self-employment");
        }

        $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']];

        unset($_SESSION['cumulative_data']);

        $period_dates['periodStartDate'] = $_SESSION['period_start_date'];
        $period_dates['periodEndDate'] = $_SESSION['period_end_date'];

        $final_array = ['periodDates' => $period_dates] + $cumulative_data;
        $tax_year = $_SESSION['tax_year'];
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];

        $response = $this->apiSelfEmployment->createAndAmendASelfEmploymentCumulativePeriodSummary($nino, $business_id, $tax_year, $final_array);

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
