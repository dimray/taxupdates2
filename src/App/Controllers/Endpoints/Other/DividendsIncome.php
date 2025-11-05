<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Helpers\DividendsHelper;
use App\Helpers\Helper;
use App\HmrcApi\Endpoints\Other\ApiDividendsIncome;
use Framework\Controller;
use App\Flash;

class DividendsIncome extends Controller
{
    public function __construct(private ApiDividendsIncome $apiDividendsIncome) {}

    public function index()
    {
        $heading = "Dividends";

        return $this->view("Endpoints/Other/DividendsIncome/index.php", compact("heading"));
    }

    // DIVIDENDS INCOME

    public function retrieveDividendsIncome()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDividendsIncome->retrieveDividendsIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $dividends_income = [];

        if ($response['type'] === "success") {
            $dividends_income = $response['response'];
            $_SESSION['dividends_income'] = $dividends_income;
        }

        $heading = "Other Dividends";

        return $this->view("Endpoints/Other/DividendsIncome/other-dividends.php", compact("heading", "dividends_income"));
    }

    public function createAmendDividendsIncome()
    {
        $errors = $this->flashErrors();

        $dividends_income = $_SESSION['dividends_income'] ?? [];
        unset($_SESSION['dividends_income']);

        $heading = "Add Other Dividend Income";
        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $dividends_income = Helper::cleanupEmptySubArrays($dividends_income);

        $foreign_dividends = $dividends_income['foreignDividend'] ?? [[]];
        $dividends_abroad = $dividends_income['dividendIncomeReceivedWhilstAbroad'] ?? [[]];
        $stock_dividend = $dividends_income['stockDividend'] ?? [[]];
        $redeemable_shares = $dividends_income['redeemableShares'] ?? [[]];
        $bonus_issues = $dividends_income['bonusIssuesOfSecurities'] ?? [[]];
        $close_company_loans = $dividends_income['closeCompanyLoansWrittenOff'] ?? [[]];

        return $this->view("Endpoints/Other/DividendsIncome/other-dividends-add-edit.php", compact("errors", "heading", "countries", "foreign_dividends", "dividends_abroad", "stock_dividend", "redeemable_shares", "bonus_issues", "close_company_loans"));
    }

    public function processCreateAmendDividendsIncome()
    {
        $dividends_income = $this->request->post ?? [];

        $validated = DividendsHelper::formatDividendIncome($dividends_income);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['dividends_income'] = $dividends_income;

            return $this->redirect("/dividends-income/create-amend-dividends-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDividendsIncome->createAndAmendDividendsIncome($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Dividends income has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/dividends-income/retrieve-dividends-income");
    }

    public function confirmDeleteDividendsIncome()
    {
        $heading = "Delete Other Dividends";

        return $this->view("Endpoints/Other/DividendsIncome/other-dividends-confirm-delete.php", compact("heading"));
    }

    public function deleteDividendsIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiDividendsIncome->deleteDividendsIncome($nino, $tax_year);

            unset($_SESSION['dividends_income']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Other Dividends income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/dividends-income/retrieve-dividends-income");
    }


    // UK DIVIDENDS

    public function retrieveUkDividendsIncomeAnnualSummary()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDividendsIncome->retrieveUkDividendsAnnualSummary($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $uk_dividends = [];

        if ($response['type'] === "success") {
            $uk_dividends = $response['response'];
            $_SESSION['uk_dividends'] = $uk_dividends;
        }

        $heading = "UK Dividends";

        return $this->view("Endpoints/Other/DividendsIncome/uk-dividends-annual-summary.php", compact("heading", "uk_dividends"));
    }

    public function createAmendUkDividendsAnnualSummary()
    {
        $heading = "Add or Edit UK Dividends";

        $errors = $this->flashErrors();

        $dividends = $_SESSION['uk_dividends'] ?? [];
        unset($_SESSION['uk_dividends']);

        return $this->view("Endpoints/Other/DividendsIncome/uk-dividends-add-edit.php", compact("heading", "dividends", "errors"));
    }

    public function processCreateAmendUkDividendsAnnualSummary()
    {
        $dividends = $this->request->post ?? [];

        $validated = DividendsHelper::validateUkDividends($dividends);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['uk_dividends'] = $dividends;

            return $this->redirect("/dividends-income/create-amend-dividends-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDividendsIncome->createAndAmendUkDividendsIncomeAnnualSummary($nino, $tax_year, $dividends);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("UK Dividends have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/dividends-income/retrieve-uk-dividends-income-annual-summary");
    }

    public function confirmDeleteUkDividendsIncomeAnnualSummary()
    {
        $heading = "Delete UK Dividends";

        return $this->view("Endpoints/Other/DividendsIncome/uk-dividends-delete.php", compact("heading"));
    }

    public function deleteUkDividendsIncomeAnnualSummary()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiDividendsIncome->deleteUkDividendsIncomeAnnualSummary($nino, $tax_year);
        }

        unset($_SESSION['uk_dividends']);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("UK Dividend Income has been deleted", Flash::SUCCESS);
        }

        return $this->redirect("/dividends-income/retrieve-uk-dividends-income-annual-summary");
    }

    // DIRECTORS

    public function retrieveDirectorshipAndDividendInformation()
    {
        $employer_name = ucwords($this->request->get['employer_name'] ?? '');
        $employment_id = ucwords($this->request->get['employment_id'] ?? '');

        if ($employment_id === '') {
            Flash::addMessage("An error has occurred, please try again", Flash::WARNING);
            return $this->redirect("/dividends-income/index");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDividendsIncome->retrieveAdditionalDirectorshipAndDividendInformation($nino, $tax_year, $employment_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $director_dividends = [];

        if ($response['type'] === "success") {
            $director_dividends = $response['response'];
            $_SESSION['director_dividends'] = $director_dividends;
        }

        $heading = "Company Director And Dividend Information";

        return $this->view("Endpoints/Other/DividendsIncome/company-director.php", compact("heading", "director_dividends", "employment_id", "employer_name"));
    }

    public function createAmendDirectorshipAndDividendInformation()
    {
        $employment_id = $this->request->get['employment_id'] ?? '';
        $employer_name = $this->request->get['employer_name'] ?? '';

        if ($employment_id === '') {
            Flash::addMessage("An error has occurred, please try again", Flash::WARNING);
            return $this->redirect("/dividends-income/index");
        }

        $errors = $this->flashErrors();

        $heading = ucwords($employer_name) . ": Add Or Edit Director Information";

        $director_dividends = $_SESSION['director_dividends'] ?? [];
        unset($_SESSION['director_dividends']);

        return $this->view("Endpoints/Other/DividendsIncome/company-director-add-edit.php", compact("heading", "director_dividends", "employment_id", "employer_name", "errors"));
    }

    public function processCreateAmendDirectorshipAndDividendInformation()
    {
        $director_info = $this->request->post;

        $employment_id = $director_info['employment_id'] ?? '';
        $employer_name = $director_info['employer_name'] ?? '';

        $validated = DividendsHelper::validateDirectorInfo($director_info);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['director_dividends'] = $director_info;

            return $this->redirect("/dividends-income/create-amend-directorship-and-dividend-information?employment_id=$employment_id&employer_name=$employer_name");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDividendsIncome->createOrAmendAdditionalDirectorshipAndDividendInformation($nino, $tax_year, $employment_id, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Director Information has been updated", Flash::SUCCESS);
        }

        $query_string = http_build_query(compact("employment_id", "employer_name"));

        return $this->redirect("/dividends-income/retrieve-directorship-and-dividend-information?" . $query_string);
    }

    public function confirmDeleteDirectorshipAndDividendInformation()
    {

        $employment_id = $this->request->get['employment_id'] ?? '';
        $employer_name = $this->request->get['employer_name'] ?? '';

        if ($employment_id === '') {
            Flash::addMessage("An error has occurred, please try again", Flash::WARNING);
            return $this->redirect("/dividends-income/index");
        }

        $heading = "Delete Directorship And Dividend Information For " . ucwords($employer_name);

        $query_string = http_build_query(compact("employment_id", "employer_name"));

        return $this->view("Endpoints/Other/DividendsIncome/company-director-confirm-delete.php", compact("employment_id", "query_string"));
    }

    public function deleteDirectorshipAndDividendInformation()
    {
        if (isset($this->request->post)) {

            $employment_id = $this->request->post['employment_id'];
            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiDividendsIncome->deleteAdditionalDirectorshipAndDividendInformation($nino, $tax_year, $employment_id);
        }

        unset($_SESSION['director_dividends']);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Directorship and Dividend Information has been deleted", Flash::SUCCESS);
        }

        return $this->redirect("/dividends-income/retrieve-uk-dividends-income-annual-summary");
    }
}
