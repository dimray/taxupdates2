<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiExpenses;
use Framework\Controller;
use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\Flash;
use App\Helpers\ExpensesHelper;

class Expenses extends Controller
{
    public function __construct(private ApiExpenses $apiExpenses) {}

    // EMPLOYMENT EXPENSES
    public function retrieveEmploymentExpenses()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiExpenses->retrieveEmploymentExpenses($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $employment_expenses = [];

        if ($response['type'] === "success") {
            $employment_expenses = $response['response'];
            $_SESSION['expenses']['employment_expenses'] = $employment_expenses;
        }

        $heading = "Employment Expenses";

        $tax_year_ended = TaxYearHelper::hasTaxYearEnded($_SESSION['tax_year']);

        return $this->view("Endpoints/Other/Expenses/employment-expenses-retrieve.php", compact("heading", "employment_expenses", "tax_year_ended"));
    }

    public function createAndAmendEmploymentExpenses()
    {
        if (!TaxYearHelper::hasTaxYearEnded($_SESSION['tax_year'])) {
            Flash::addMessage("Expenses cannot be added until the tax year has ended");
            return $this->redirect("/expenses/retrieve-employment-expenses");
        }

        $heading = "Employment Expenses";

        $errors = $this->flashErrors();

        $employment_expenses = $_SESSION['expenses']['employment_expenses'] ?? [];
        unset($_SESSION['expenses']);

        $expenses = $employment_expenses['expenses'] ?? [];

        return $this->view("Endpoints/Other/Expenses/employment-expenses-create-amend.php", compact("heading", "errors", "expenses"));
    }

    public function processCreateAndAmendEmploymentExpenses()
    {
        $employment_expenses = $this->request->post ?? [];

        $validated = ExpensesHelper::validateAndFormatEmploymentExpenses($employment_expenses);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['expenses']['employment_expenses'] = $employment_expenses;

            return $this->redirect("/expenses/create-and-amend-employment-expenses");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiExpenses->createAndAmendEmploymentExpenses($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Employment Expenses have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/expenses/retrieve-employment-expenses");
    }

    public function confirmDeleteEmploymentExpenses()
    {
        if (!TaxYearHelper::hasTaxYearEnded($_SESSION['tax_year'])) {
            Flash::addMessage("Expenses cannot be deleted until the tax year has ended");
            return $this->redirect("/expenses/retrieve-employment-expenses");
        }

        $heading = "Delete Employment Expenses";

        return $this->view("Endpoints/Other/Expenses/employment-expenses-confirm-delete.php", compact("heading"));
    }

    public function deleteEmploymentExpenses()
    {
        if (!TaxYearHelper::hasTaxYearEnded($_SESSION['tax_year'])) {
            Flash::addMessage("Expenses cannot be deleted until the tax year has ended");
            return $this->redirect("/expenses/retrieve-employment-expenses");
        }

        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiExpenses->deleteEmploymentExpenses($nino, $tax_year);

            unset($_SESSION['expenses']);

            if ($response['type'] === "success") {
                Flash::addMessage("Employment Expenses have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/expenses/retrieve-employment-expenses");
    }

    public function confirmIgnoreEmploymentExpenses()
    {
        if (!TaxYearHelper::hasTaxYearEnded($_SESSION['tax_year'])) {
            Flash::addMessage("Expenses cannot be ignored until the tax year has ended");
            return $this->redirect("/expenses/retrieve-employment-expenses");
        }

        $heading = "Ignore Employment Expenses";

        return $this->view("Endpoints/Other/Expenses/employment-expenses-ignore.php", compact("heading"));
    }

    public function ignoreEmploymentExpenses()
    {
        if (!TaxYearHelper::hasTaxYearEnded($_SESSION['tax_year'])) {
            Flash::addMessage("Expenses cannot be ignored until the tax year has ended");
            return $this->redirect("/expenses/retrieve-employment-expenses");
        }

        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiExpenses->ignoreEmploymentExpenses($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['expenses']);

            if ($response['type'] === "success") {
                Flash::addMessage("Employment Expenses are now ignored", Flash::SUCCESS);
            }
        }

        return $this->redirect("/expenses/retrieve-employment-expenses");
    }

    // OTHER EXPENSES

    public function retrieveOtherExpenses()
    {

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiExpenses->retrieveOtherExpenses($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $other_expenses = [];

        if ($response['type'] === "success") {
            $other_expenses = $response['response'];
            $_SESSION['expenses']['other_expenses'] = $other_expenses;
        }

        $heading = "Other Expenses";

        return $this->view("Endpoints/Other/Expenses/other-expenses-retrieve.php", compact("heading", "other_expenses"));
    }

    public function createAndAmendOtherExpenses()
    {
        $heading = "Other Expenses";

        $errors = $this->flashErrors();

        $other_expenses = $_SESSION['expenses']['other_expenses'] ?? [];
        unset($_SESSION['expenses']);

        $payments_to_trade_unions = $other_expenses['paymentsToTradeUnionsForDeathBenefits'] ?? [];
        $patent_royalties = $other_expenses['patentRoyaltiesPayments'] ?? [];

        return $this->view("Endpoints/Other/Expenses/other-expenses-create-amend.php", compact("heading", "errors", "payments_to_trade_unions", "patent_royalties"));
    }

    public function processCreateAndAmendOtherExpenses()
    {
        $other_expenses = $this->request->post ?? [];

        $validated = ExpensesHelper::validateAndFormatOtherExpenses($other_expenses);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['expenses']['other_expenses'] = $other_expenses;

            return $this->redirect("/expenses/create-and-amend-other-expenses");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiExpenses->createAndAmendOtherExpenses($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Other Expenses have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/expenses/retrieve-other-expenses");
    }

    public function confirmDeleteOtherExpenses()
    {

        $heading = "Delete Other Expenses";

        return $this->view("Endpoints/Other/Expenses/other-expenses-confirm-delete.php", compact("heading"));
    }

    public function deleteOtherExpenses()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiExpenses->deleteOtherExpenses($nino, $tax_year);

            unset($_SESSION['expenses']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Other Expenses have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/expenses/retrieve-other-expenses");
    }
}
