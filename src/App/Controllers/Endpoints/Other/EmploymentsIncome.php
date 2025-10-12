<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Flash;
use App\Helpers\EmploymentsHelper;
use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\Other\ApiEmploymentsIncome;
use App\Validate;
use Framework\Controller;

class EmploymentsIncome extends Controller
{
    public function __construct(private ApiEmploymentsIncome $apiEmploymentsIncome) {}

    public function index()
    {
        $heading = "Employment Income";

        unset($_SESSION['employment_income']);

        return $this->view("Endpoints/Other/EmploymentsIncome/index.php", compact("heading"));
    }

    public function listEmployments()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'] ?? TaxYearHelper::getCurrentTaxYear(-1);

        $before_current_tax_year = TaxYearHelper::beforeCurrentYear($tax_year);

        $response = $this->apiEmploymentsIncome->listEmployments($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $employments = [];
        $custom_employments = [];

        if ($response['type'] === 'success') {
            $employments = $response['response']['employments'] ?? [];
            $custom_employments = $response['response']['customEmployments'] ?? [];
        }

        $heading = "Employments";

        return $this->view("/Endpoints/Other/EmploymentsIncome/list-employments.php", compact("heading", "employments", "custom_employments", "before_current_tax_year"));
    }

    public function addCustomEmployment()
    {
        $heading = "Add Employment Details";

        $errors = $this->flashErrors();

        $employer_data = $_SESSION['employment_income']['employer_data'] ?? "";

        unset($_SESSION['employment_income']);

        return $this->view("/Endpoints/Other/EmploymentsIncome/add-custom-employment.php", compact("heading", "errors", "employer_data"));
    }

    public function processAddCustomEmployment()
    {
        $employer_data = $this->request->post ?? [];

        $employer_name = $employer_data['employer_name'] ?? "";
        $start_date = $employer_data['start_date'] ?? null;

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        if (!TaxYearHelper::beforeCurrentYear($tax_year)) {
            $this->addError("An employment can only be added for a tax year which has ended.");
        }

        if (!Validate::string($employer_name, 1, 73)) {
            $this->addError("Employer name is required");
        }

        if (empty($start_date)) {
            $this->addError("Start date is required");
        }

        if (!empty($this->errors)) {
            $_SESSION['employment_income']['employer_data'] = $employer_data;
            return $this->redirect("/employments-income/add-custom-employment");
        }

        $occupational_pension = isset($employer_data['occupational_pension']) ? true : false;

        $response = $this->apiEmploymentsIncome->addCustomEmployment($nino, $tax_year, $employer_name, $start_date, $occupational_pension);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("Employment has been added", Flash::SUCCESS);
        }

        return $this->redirect("/employments-income/list-employments");
    }

    public function retrieveEmployment()
    {
        $employment_id = $this->request->get['employment_id'] ?? $_SESSION['employment_income']['employment_id'];
        $employment_type = $this->request->get['employment_type'] ?? "";

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiEmploymentsIncome->retrieveAnEmployment($nino, $tax_year, $employment_id);

        if ($response['type'] === 'success') {

            $employment_data = $response['response'];
            $employer_name = $employment_data['employerName'] ?? null;

            $_SESSION['employment_income']['employer_name'] = $employer_name;
            $_SESSION['employment_income']['employment_id'] = $employment_id;

            $heading = "Employment Details";

            if (!empty($employer_name)) {
                $heading = $heading . ": " . $employer_name;
            }

            return $this->view("Endpoints/Other/EmploymentsIncome/employment-details.php", compact("employment_data", "heading", "employment_type", "employer_name", "employment_id"));
        }

        return $this->redirect("/employments-income/list-employments");
    }

    public function retrieveEmploymentAndFinancialDetails()
    {
        $employment_id = $this->request->get['employment_id'] ?? "";
        $employment_type = $this->request->get['employment_type'] ?? "";
        $_SESSION['employment_income']['employment_id'] = $employment_id;

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiEmploymentsIncome->retrieveAnEmploymentAndItsFinancialDetails($nino, $tax_year, $employment_id);

        // REDIRECT TO retrieve-employment
        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location'] . "?employment_type=" . $employment_type);
        }

        // if type is success:

        if ($response['type'] === 'success') {

            $employment_data = $response['response'];
            $employer_name = $employment_data['employment']['employer']['employerName'] ?? null;
            $_SESSION['employment_income']['employer_name'] = $employer_name;

            $heading = "Employment Details";

            if (!empty($employer_name)) {
                $heading = $heading . ": " . $employer_name;
            }

            return $this->view("Endpoints/Other/EmploymentsIncome/employment-and-financial-details.php", compact("employment_data", "heading", "employment_type", "employer_name", "employment_id"));
        }

        return $this->redirect("/employments-income/list-employments");
    }

    public function createAmendEmploymentFinancialDetails()
    {
        $employer_name = $_SESSION['employment_income']['employer_name'] ?? null;

        $employment_id = $_SESSION['employment_income']['employment_id'] ?? null;

        $employment_data = json_decode($this->request->post['employment_data'] ?? "", true) ?? $_SESSION['employment_income']['financial_details'] ?? null;

        unset($_SESSION['employment_income']['financial_details']);

        if (!$employment_id) {
            return $this->redirect("/employments/list-employments");
        }

        $errors = $this->flashErrors();

        $pay = $employment_data['pay'] ?? [];
        $deductions = $employment_data['deductions'] ?? [];
        $benefits_in_kind = $employment_data['benefitsInKind'] ?? [];

        $heading = $employer_name . " " . $_SESSION['tax_year'];

        return $this->view("Endpoints/Other/EmploymentsIncome/create-edit-financial-details.php", compact("heading", "pay", "deductions", "benefits_in_kind", "errors"));
    }

    public function processCreateAmendEmploymentFinancialDetails()
    {
        $financial_details = $this->request->post ?? [];

        $employer_name = $_SESSION['employment_income']['employer_name'] ?? null;

        $employment = EmploymentsHelper::validateAndFormatEmploymentFinancialDetails($financial_details);

        if (!empty($_SESSION['errors'])) {

            $_SESSION['employment_income']['financial_details'] = $financial_details;

            return $this->redirect("/employments-income/create-amend-employment-financial-details");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];
        $employment_id = $_SESSION['employment_income']['employment_id'];

        $response = $this->apiEmploymentsIncome->createAndAmendEmploymentFinancialDetails($nino, $tax_year, $employment_id, $employment);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            $employer_name = $_SESSION['employment_income']['employer_name'];
            Flash::addMessage("Employment income from $employer_name has been updated", Flash::SUCCESS);
        }

        unset($_SESSION['employment_income']);

        return $this->redirect("/employments-income/list-employments");
    }

    public function confirmDeleteEmploymentFinancialDetails()
    {

        $heading = "Delete Edits To Financial Details for " . $_SESSION['employment_income']['employer_name'];

        return $this->view("Endpoints/Other/EmploymentsIncome/confirm-delete-edits.php", compact("heading"));
    }

    public function deleteEmploymentFinancialDetails()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $employment_id = $_SESSION['employment_income']['employment_id'];

            $response = $this->apiEmploymentsIncome->deleteEmploymentFinancialDetails($nino, $tax_year, $employment_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                $employer_name = $_SESSION['employment_income']['employer_name'];
                Flash::addMessage("Edits for $employer_name have been deleted", Flash::SUCCESS);
            }
        }

        unset($_SESSION['employment_income']);

        return $this->redirect("/employments-income/list-employments");
    }

    public function confirmDeleteCustomEmployment()
    {
        $employer_name = $this->request->get['employer_name'] ?? '';

        $heading = "Delete Employment: " . $employer_name;

        return $this->view("Endpoints/Other/EmploymentsIncome/confirm-delete-custom-employment.php", compact("heading", "employer_name"));
    }

    public function deleteCustomEmployment()
    {
        if (isset($this->request->post)) {

            $employment_id = $_SESSION['employment_income']['employment_id'] ?? "";
            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiEmploymentsIncome->deleteCustomEmployment($nino, $tax_year, $employment_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {

                Flash::addMessage("Employment has been deleted", Flash::SUCCESS);
            }
        }

        unset($_SESSION['employment_income']);

        return $this->redirect("/employments-income/list-employments");
    }

    public function confirmIgnoreEmployment()
    {
        $heading = "Ignore Employment " . $_SESSION['employment_income']['employer_name'];

        return $this->view("Endpoints/Other/EmploymentsIncome/confirm-ignore-employment.php", compact("heading"));
    }

    public function ignoreEmployment()
    {

        if (!TaxYearHelper::beforeCurrentYear($_SESSION['tax_year'])) {

            Flash::addMessage("An employment can only be 'ignored' for a tax year which has ended", Flash::WARNING);
        } elseif (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $employment_id = $_SESSION['employment_income']['employment_id'] ?? '';

            $response = $this->apiEmploymentsIncome->ignoreEmployment($nino, $tax_year, $employment_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Employment is now ignored", Flash::SUCCESS);
            }
        }

        unset($_SESSION['employment_income']);

        return $this->redirect("/employments-income/list-employments");
    }

    public function confirmUnignoreEmployment()
    {
        $heading = "Unignore employment " . $_SESSION['employment_income']['employer_name'];

        return $this->view("Endpoints/Other/EmploymentsIncome/confirm-unignore-employment.php", compact("heading"));
    }


    public function unignoreEmployment()
    {
        if (!TaxYearHelper::beforeCurrentYear($_SESSION['tax_year'])) {

            Flash::addMessage("An employment can only be 'unignored' for a tax year which has ended", Flash::WARNING);
        } elseif (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $employment_id = $_SESSION['employment_income']['employment_id'];

            $response = $this->apiEmploymentsIncome->unignoreEmployment($nino, $tax_year, $employment_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                $employer_name = $_SESSION['employment_income']['employer_name'];
                Flash::addMessage("Employment $employer_name has been unignored", Flash::SUCCESS);
            }
        }

        unset($_SESSION['employment_income']);

        return $this->redirect("/employments-income/list-employments");
    }

    // ********* NON-PAYE EMPLOYMENT INCOME ************

    public function retrieveNonPayeEmploymentIncome()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiEmploymentsIncome->retrieveNonPayeEmploymentIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $non_paye_income = [];

        if ($response['type'] === "success") {
            $non_paye_income = $response['response']['nonPayeIncome'];
        }

        $_SESSION['employment_income']['tips'] = $non_paye_income['tips'] ?? "";

        $heading = "Non PAYE Employment Income";

        return $this->view("Endpoints/Other/EmploymentsIncome/non-paye-employment-income.php", compact("heading", "non_paye_income"));
    }


    public function createAmendNonPayeEmploymentIncome()
    {
        $heading = "Enter Non PAYE Employment Income";

        $errors = $this->flashErrors();

        $tips = $_SESSION['employment_income']['tips'] ?? "";

        return $this->view("Endpoints/Other/EmploymentsIncome/non-paye-employment-income-create.php", compact("heading", "tips", "errors"));
    }

    public function processCreateAmendNonPayeEmploymentIncome()
    {

        $tips = $this->request->post['tips'];

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        if (!TaxYearHelper::beforeCurrentYear($tax_year)) {
            $this->addError("Amounts can only be input/updated for a tax year which has ended.");
        }

        if ($tips === '' || !is_numeric($tips)) {
            $this->addError("An amount is required");
        }

        $tips = round((float)$tips, 2);

        if (empty($tips)) {
            $this->addError("An amount is required");
        }

        if ($tips < 0 || $tips > 99999999999.99) {
            $this->addError("Tips must be a number between 0 and 99999999999.99");
        }

        if (!empty($this->errors)) {
            $_SESSION['employment_income']['tips'] = $tips;
            return $this->redirect("/employments/create-amend-non-paye-employment-income");
        }

        $response = $this->apiEmploymentsIncome->createAndAmendNonPayeEmploymentIncome($nino, $tax_year, $tips);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {

            Flash::addMessage("Amount updated", Flash::SUCCESS);
        }

        return $this->redirect("/employments-income/retrieve-non-paye-employment-income");
    }

    public function confirmDeleteNonPayeEmploymentIncome()
    {
        $heading = "Delete Non PAYE Employment Income";

        return $this->view("Endpoints/Other/EmploymentsIncome/non-paye-employment-income-delete.php", compact("heading"));
    }

    public function deleteNonPayeEmploymentIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();

            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiEmploymentsIncome->deleteNonPayeEmploymentIncome($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Non PAYE Income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/employments-income/index");
    }

    // ********* OTHER EMPLOYMENT INCOME ************

    public function retrieveOtherEmploymentIncome()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiEmploymentsIncome->retrieveOtherEmploymentIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $other_income = [];

        if ($response['type'] === "success") {
            $other_income = $response['response'];
            $_SESSION['employment_income']['other_employment_income'] = $response['response'];
        }

        $heading = "Other Employment Income";

        return $this->view("Endpoints/Other/EmploymentsIncome/other-employment-income.php", compact("heading", "other_income"));
    }

    public function createAmendOtherEmploymentIncome()
    {
        $errors = $this->flashErrors();

        $other_employment_income = $_SESSION['employment_income']['other_employment_income'] ?? [];
        unset($_SESSION['employment_income']);

        $heading = "Other Employment Income";

        $other_employment_income = Helper::cleanupEmptySubArrays($other_employment_income);

        $share_options = $other_employment_income['shareOption'] ?? [[]];
        $share_awards = $other_employment_income['sharesAwardedOrReceived'] ?? [[]];
        $lump_sums = $other_employment_income['lumpSums'] ?? [[]];
        $disability = $other_employment_income['disability'] ?? [];
        $foreign_service = $other_employment_income['foreignService'] ?? [];

        return $this->view("Endpoints/Other/EmploymentsIncome/other-employment-income-add.php", compact("heading", "share_options", "share_awards", "lump_sums", "disability", "foreign_service", "errors"));
    }

    public function processCreateAmendOtherEmploymentIncome()
    {
        $other_employment_income = $this->request->post;

        $validated = EmploymentsHelper::formatOtherEmploymentsArrays($other_employment_income);


        if (!empty($_SESSION['errors'])) {
            $_SESSION['employment_income']['other_employment_income'] = $other_employment_income;

            return $this->redirect("/employments-income/create-amend-other-employment-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $payload_data = [];
        if (is_array($validated)) {
            foreach (['shareOption', 'sharesAwardedOrReceived', 'disability', 'foreignService', 'lumpSums'] as $key) {
                if (!empty($validated[$key])) {
                    $payload_data[$key] = $validated[$key];
                }
            }
        }

        $response = $this->apiEmploymentsIncome->createAndAmendOtherEmploymentIncome($nino, $tax_year, $payload_data);

        if ($response['type'] === "success") {
            Flash::addMessage("Other Employment Income has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/employments-income/retrieve-other-employment-income");
    }

    public function deleteOtherEmploymentIncome()
    {
        $heading = "Delete Other Employment Income";

        return $this->view("Endpoints/Other/EmploymentsIncome/other-employment-income-delete.php", compact("heading"));
    }

    public function confirmDeleteOtherEmploymentIncome()
    {


        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiEmploymentsIncome->deleteOtherEmploymentIncome($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {

                unset($_SESSION['employment_income']);
                Flash::addMessage("Other Employment Income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/employments-income/retrieve-other-employment-income");
    }
}
