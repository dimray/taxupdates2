<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Flash;
use App\Helpers\AgentHelper;
use App\Helpers\AnnualSubmissionHelper;
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

                return $this->redirect("/self-employment/success?type=cumulative");
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

        return $this->view("Endpoints/SelfEmployment/annual-submission.php", compact("heading", "business_details"));
    }

    public function createAnnualSubmission()
    {
        if (empty($_SESSION['errors'])) {
            unset($_SESSION['annual_submission']);
        }

        $heading = "Annual Submission";

        $business_details = Helper::setBusinessDetails();

        $errors = $this->flashErrors();

        $adjustments =  $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] ?? [];
        $allowances =  $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] ?? [];
        $sba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] ?? [];
        $esba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] ?? [];
        $non_financials =  $_SESSION['annual_submission'][$_SESSION['business_id']]['non_financials'] ?? [];

        return $this->view(
            "Endpoints/SelfEmployment/create-annual-submission.php",
            compact("heading", "errors", "business_details", "adjustments", "allowances", "sba", "esba", "non_financials")
        );
    }

    public function processAnnualSubmission()
    {
        unset($_SESSION['annual_submission']);

        $data = $this->request->post ?? [];

        $errors = AnnualSubmissionHelper::validateSelfEmploymentAnnualSubmission($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect("/self-employment/create-annual-submission");
        }

        return $this->redirect("/self-employment/approve-annual-submission");
    }

    public function approveAnnualSubmission()
    {
        $errors = $this->flashErrors();

        $adjustments =  $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] ?? [];
        $allowances =  $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] ?? [];
        $sba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] ?? [];
        $esba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] ?? [];
        $non_financials =  $_SESSION['annual_submission'][$_SESSION['business_id']]['non_financials'] ?? [];

        $heading = "Annual Submission";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        return $this->view(
            "Endpoints/SelfEmployment/approve-annual-submission.php",
            compact("heading", "hide_tax_year", "business_details", "adjustments", "allowances", "sba", "esba", "non_financials", "errors")
        );
    }

    public function createAmendAnnualSubmission()
    {
        $confirm_submit = $this->request->post['confirm_submit'] ?? false;

        if (!$confirm_submit) {

            $this->addError("You must tick the confirmation box to proceed");

            return $this->redirect("/self-employment/show-finalise-annual-submission");
        }

        $annual_submission = AnnualSubmissionHelper::finaliseSelfEmploymentAnnualSubmission();

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSelfEmployment->createAndAmendSelfEmploymentAnnualSubmission($nino, $business_id, $tax_year, $annual_submission);

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

            return $this->redirect("/self-employment/success?type=annual");
        }

        // failure
        return $this->redirect("/self-employment/annual-submission");
    }

    public function retrieveAnnualSubmission()
    {
        $nino = Helper::getNino();

        $business_id = $_SESSION['business_id'];

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSelfEmployment->retrieveASelfEmploymentAnnualSubmission($nino, $business_id, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $annual_submission = [];

        if ($response['type'] === 'success') {
            $annual_submission = $response['submission'] ?? [];
        }

        $sba = $annual_submission['allowances']['structuredBuildingAllowance'] ?? [];
        $esba = $annual_submission['allowances']['enhancedStructuredBuildingAllowance'] ?? [];
        $sba = AnnualSubmissionHelper::flattenSba($sba, "sba");
        $esba = AnnualSubmissionHelper::flattenSba($esba, "esba");

        unset($annual_submission['allowances']['structuredBuildingAllowance'], $annual_submission['allowances']['enhancedStructuredBuildingAllowance']);

        $adjustments =  $annual_submission['adjustments'] ?? [];
        $allowances = $annual_submission['allowances'] ?? [];
        $non_financials = $annual_submission['nonFinancials'] ?? [];

        $heading = "Annual Submission";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        return $this->view(
            "Endpoints/SelfEmployment/show-annual-submission.php",
            compact("heading", "business_details", "hide_tax_year", "sba", "esba", "adjustments", "allowances", "non_financials")
        );
    }

    public function deleteAnnualSubmission()
    {
        if (isset($this->request->post['delete_annual_submission'])) {

            $nino = Helper::getNino();

            $business_id = $_SESSION['business_id'];

            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiSelfEmployment->deleteASelfEmploymentAnnualSubmission($nino, $business_id, $tax_year);

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

                Flash::addMessage("The Annual Summary has been deleted", Flash::SUCCESS);

                return $this->redirect("/self-employment/success?type=annual-deleted");
            }

            // failure
            Flash::addMessage("Unable to delete Annual Submission", Flash::WARNING);
            return $this->redirect("/self-employment/annual-submission");
        } else {

            $heading = "Delete Annual Submission";

            $hide_tax_year = true;

            $tax_year = $_SESSION['tax_year'];

            return $this->view("Endpoints/SelfEmployment/delete-annual-submission.php", compact("heading", "tax_year", "hide_tax_year"));
        }
    }



    public function success()
    {
        $type = $this->request->get['type'] ?? '';

        $heading = "Action Successful";

        $business_details = Helper::setBusinessDetails();

        $hide_tax_year = true;

        return $this->view("Endpoints/SelfEmployment/success.php", compact("heading", "hide_tax_year", "business_details", "type"));
    }
}
