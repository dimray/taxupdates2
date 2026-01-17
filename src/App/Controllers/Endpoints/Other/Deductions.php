<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Helpers\DeductionsHelper;
use App\HmrcApi\Endpoints\Other\ApiDeductions;
use Framework\Controller;
use App\Flash;

class Deductions extends Controller
{

    public function __construct(private ApiDeductions $apiDeductions, private DeductionsHelper $deductionsHelper) {}

    public function retrieveDeductions()
    {

        $nino = $_SESSION['nino'];

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDeductions->retrieveDeductions($nino, $tax_year);

        $deductions = [];

        if ($response['type'] === "success") {
            $deductions = $response['response'];
            $_SESSION['deductions'] = $deductions;
        }

        $heading = "Seafarers Earnings Deduction";

        return $this->view("Endpoints/Other/Deductions/retrieve-deductions.php", compact("heading", "deductions"));
    }

    public function createAndAmendDeductions()
    {

        $heading = "Seafarers Earnings Deduction";

        $errors = $this->flashErrors();

        $deductions = $_SESSION['deductions'] ?? [];
        unset($_SESSION['deductions']);

        $seafarers = $deductions['seafarers'] ?? [[]];

        return $this->view("Endpoints/Other/Deductions/add-edit-deductions.php", compact("heading", "errors", "seafarers"));
    }

    public function processCreateAndAmendDeductions()
    {
        $deductions = $this->request->post ?? [];


        $validated = $this->deductionsHelper->validateAndFormatDeductions($deductions);


        if (!empty($_SESSION['errors'])) {
            $_SESSION['deductions'] = $deductions;

            return $this->redirect("/deductions/create-and-amend-deductions");
        }

        $nino = $_SESSION['nino'];
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDeductions->createAndAmendDeductions($nino, $tax_year, $validated);

        if ($response['type'] === "success") {
            Flash::addMessage("Seafarers Earnings Deduction has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/deductions/retrieve-deductions");
    }

    public function confirmDeleteDeductions()
    {

        $heading = "Delete Seafarers Earnings Deductions";

        return $this->view("Endpoints/Other/Deductions/confirm-delete-deductions.php", compact("heading"));
    }

    public function deleteDeductions()
    {
        if (isset($this->request->post)) {

            $nino = $_SESSION['nino'];
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiDeductions->deleteDeductions($nino, $tax_year);

            unset($_SESSION['deductions']);

            if ($response['type'] === "success") {
                Flash::addMessage("Seafarers Earnings Deductions have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/deductions/retrieve-deductions");
    }
}
