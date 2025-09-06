<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\SubmissionsHelper;
use App\Helpers\UploadHelper;
use App\Flash;
use Framework\Controller;

class Uploads extends Controller
{

    public function createCumulativeUpload()
    {
        unset($_SESSION['cumulative_data']);

        // period dates are set if coming from obligations, 
        // but also redirects here if there is an error after dates have already been set
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

        $form_action = "/uploads/process-cumulative-upload";

        return $this->view("Uploads/cumulative-upload.php", compact("heading", "business_details", "errors", "hide_tax_year", "form_action"));
    }

    public function processCumulativeUpload()
    {
        if (empty($this->request->post['pasted_data']) && !isset($this->request->files['csv_upload'])) {
            return $this->redirect("/cumulative-upload/create");
        }

        $parsed_data = [];

        // csv upload
        if (isset($this->request->files['csv_upload'])) {

            $file = $this->request->files['csv_upload'] ?? null;

            $errors = UploadHelper::processCsvErrors($file, 200, 2);

            if (!empty($errors)) {

                $_SESSION['errors'] = $errors;
                return $this->redirect("/cumulative-upload/create");
            }

            $parsed_data = UploadHelper::parseKeyValueCsv($file);
        }

        // data paste
        if (isset($this->request->post['pasted_data']) && !empty($this->request->post['pasted_data'])) {

            $pasted_data = trim($this->request->post['pasted_data']);

            $errors = UploadHelper::processPasteErrors($pasted_data, 100, 2);

            if (!empty($errors)) {

                $_SESSION['errors'] = $errors;
                return $this->redirect("/cumulative-upload/create");
            }

            $parsed_data = UploadHelper::parseDataToKeyValueArray($pasted_data, ['name', 'nino']);
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
            return $this->redirect("/cumulative-upload/create");
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
}
