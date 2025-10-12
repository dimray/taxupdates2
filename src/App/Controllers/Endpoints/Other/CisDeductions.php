<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Helpers\CisHelper;
use App\HmrcApi\Endpoints\Other\ApiCisDeductions;
use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use Framework\Controller;
use App\Flash;

class CisDeductions extends Controller
{
    public function __construct(private ApiCisDeductions $apiCisDeductions) {}

    public function retrieveCisDeductions()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCisDeductions->retrieveCisDeductionsForSubcontractor($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $cis_totals = [];
        $cis_deductions = [];

        if ($response['type'] === "success") {

            // totals
            $cis_totals = $response['response'];
            // amounts by contractor
            $cis_deductions = $cis_totals['cisDeductions'] ?? [];
            unset($cis_totals['cisDeductions']);
        }

        $heading = "CIS Deductions in " . $_SESSION['tax_year'];

        return $this->view("Endpoints/Other/CisDeductions/list-deductions.php", compact("heading", "cis_totals", "cis_deductions"));
    }

    public function createCisDeductions()
    {
        $errors = $this->flashErrors();

        $cis_deductions = $_SESSION['cis_deductions'] ?? [];
        unset($_SESSION['cis_deductions']);

        $heading = "Add CIS Deductions";

        $monthly_periods = TaxYearHelper::getMonthsInTaxYear($_SESSION['tax_year']);

        return $this->view("Endpoints/Other/CisDeductions/add-deductions.php", compact("heading", "monthly_periods", "cis_deductions", "errors"));
    }

    public function processCreateCisDeductions()
    {
        $cis_deductions = $this->request->post;

        $validated = CisHelper::formatCreateCisDeductionsArray($cis_deductions);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['cis_deductions'] = $cis_deductions;

            return $this->redirect("/cis-deductions/create-cis-deductions");
        }

        $nino = Helper::getNino();

        $response = $this->apiCisDeductions->createCisDeductionsForSubcontractor($nino, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("CIS Deductions have been added", Flash::SUCCESS);
        }

        return $this->redirect("/cis-deductions/retrieve-cis-deductions");
    }

    public function amendCisDeductions()
    {
        $submission_id = $this->request->post['submission_id'] ?? "";

        $contractor = json_decode($this->request->post['contractor'] ?? "", true) ?? [];
        $contractor['submissionId'] = $submission_id;

        $period_data = json_decode($this->request->post['period_data'] ?? "", true) ?? $_SESSION['cis_deductions'] ?? [];

        $errors = $this->flashErrors();

        $monthly_periods = TaxYearHelper::getMonthsInTaxYear($_SESSION['tax_year']);

        if (empty($contractor['submissionId']) || empty($contractor['contractorName'] || empty($contractor['employerRef']) || empty($period_data))) {
            Flash::addMessage("Unable to edit this submission", Flash::WARNING);
            return $this->redirect("/cis-deductions/retrieve-cis-deductions");
        }

        $heading = "Edit CIS Deductions";

        return $this->view("Endpoints/Other/CisDeductions/edit-deductions.php", compact("heading", "contractor", "period_data", "monthly_periods", "errors"));
    }

    public function processAmendCisDeductions()
    {
        $cis_deductions = $this->request->post;

        $validated = CisHelper::formatCreateCisDeductionsArray($cis_deductions);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['cis_deductions'] = $cis_deductions;

            return $this->redirect("/cis-deductions/amend-cis-deductions");
        }

        $nino = Helper::getNino();

        $submission_id = $validated['submissionId'] ?? "";

        $period_data = $validated['periodData'];

        $response = $this->apiCisDeductions->amendCisDeductionsForSubcontractor($nino, $submission_id, $period_data);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("CIS Deductions for this contractor have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/cis-deductions/retrieve-cis-deductions");
    }

    public function confirmDeleteCisDeductions()
    {
        $submission_id = $this->request->post['submission_id'] ?? "";
        $contractor_name = $this->request->post['contractor'] ?? "";
        $tax_year = $_SESSION['tax_year'];

        $hide_tax_year = true;

        $heading = "Confirm Delete CIS Deductions";

        return $this->view("Endpoints/Other/CisDeductions/confirm-delete-deductions.php", compact("heading", "tax_year", "hide_tax_year", "submission_id", "contractor_name"));
    }

    public function deleteCisDeductions()
    {
        if (isset($this->request->post)) {
            $submission_id = $this->request->post['submission_id'] ?? "";
            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiCisDeductions->deleteCisDeductionsForSubcontractor($nino, $tax_year, $submission_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("CIS Deductions have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/cis-deductions/retrieve-cis-deductions");
    }
}
