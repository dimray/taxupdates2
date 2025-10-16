<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Helpers\Helper;
use App\HmrcApi\Endpoints\Other\ApiSavings;
use Framework\Controller;
use App\Flash;
use App\Helpers\SavingsHelper;

class Savings extends Controller
{
    public function __construct(private ApiSavings $apiSavings) {}

    // UK SAVINGS ACCOUNTS

    public function listUkSavingsAccounts()
    {
        $nino = Helper::getNino();

        $response = $this->apiSavings->listAllUkSavingsAccounts($nino);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $savings_accounts = [];

        if ($response['type'] === "success") {
            $savings_accounts = $response['response']['savingsAccounts'] ?? [];
        }

        $heading = "UK Savings Accounts";

        return $this->view("Endpoints/Other/SavingsIncome/uk-savings-accounts-list.php", compact("heading", "savings_accounts"));
    }

    public function addUkSavingsAccount()
    {
        $errors = $this->flashErrors();

        $heading = "Add A UK Savings Account";

        return $this->view("Endpoints/Other/SavingsIncome/uk-savings-account-add.php", compact("heading", "errors"));
    }

    public function processAddUkSavingsAccount()
    {
        $account_name = $this->request->post['account_name'] ?? '';

        if (empty($account_name)) {
            $this->addError("Account Name is required");
            return $this->redirect("/savings/addUkSavingsAccount");
        }

        if (!preg_match('#^[A-Za-z0-9 &\'\(\)\*,\-\./@£]{1,32}$#', $account_name)) {
            $this->addError("Account Name cannot be longer than 32 characters and cannot contain invalid characters");
            return $this->redirect("/savings/addUkSavingsAccount");
        }

        $nino = Helper::getNino();

        $response = $this->apiSavings->addAUkSavingsAccount($nino, $account_name);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Savings Account has been added", Flash::SUCCESS);
        }

        return $this->redirect("/savings/list-uk-savings-accounts");
    }

    public function editUkSavingsAccountName()
    {
        $account_id = $this->request->get['account_id'] ?? '';
        $account_name = $this->request->get['account_name'] ?? '';

        $errors = $this->flashErrors();

        $heading = "Edit Account Name";

        return $this->view("Endpoints/Other/SavingsIncome/uk-savings-edit-account-name.php", compact("account_id", "account_name"));
    }

    public function processEditUkSavingsAccountName()
    {
        $account_id = $this->request->post['account_id'] ?? '';
        $account_name = $this->request->post['account_name'] ?? '';


        if (empty($account_name)) {
            $this->addError("Account Name is required");
            return $this->redirect("/savings/addUkSavingsAccount");
        }

        if (!preg_match('#^[A-Za-z0-9 &\'\(\)\*,\-\./@£]{1,32}$#', $account_name)) {
            $this->addError("Account Name cannot be longer than 32 characters and cannot contain invalid characters");
            return $this->redirect("/savings/addUkSavingsAccount");
        }

        $nino = Helper::getNino();

        $response = $this->apiSavings->updateUkSavingsAccountName($nino, $account_id, $account_name);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Account name has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/savings/list-uk-savings-accounts");
    }

    public function retrieveUkSavingsAccountAnnualSummary()
    {
        $account_id = $this->request->get['account_id'] ?? '';
        $account_name = $this->request->get['account_name'] ?? '';
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSavings->retrieveUkSavingsAccountAnnualSummary($nino, $tax_year, $account_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $account_interest = [];

        if ($response['type'] === "success") {
            $account_interest = $response['response'];
            $_SESSION['savings']['uk_interest'] = $account_interest;
        }

        if ($account_name !== "") {
            $heading = $account_name;
        } else {
            $heading = "Account id: " . $account_id;
        }

        $hide_tax_year = true;

        return $this->view("Endpoints/Other/SavingsIncome/uk-savings-account-annual-summary.php", compact("heading", "account_interest", "hide_tax_year", "tax_year", "account_name", "account_id"));
    }

    public function createAmendUkSavingsAccountAnnualSummary()
    {
        $account_id = $this->request->get['account_id'] ?? '';
        $account_name = $this->request->get['account_name'] ?? '';
        $taxed_interest = $this->request->get['taxed_interest'] ?? 0;
        $untaxed_interest = $this->request->get['untaxed_interest'] ?? 0;

        $uk_interest = [
            'taxedUkInterest' => $taxed_interest,
            'untaxedUkInterest' => $untaxed_interest
        ];

        $errors = $this->flashErrors();

        if ($account_name !== '') {
            $heading = "Add Interest: " . $account_name;
        } else {
            $heading = "Add Interest Account: " . $account_id;
        }

        return $this->view("Endpoints/Other/SavingsIncome/uk-savings-create-amend-annual-summary.php", compact("heading", "errors", "uk_interest", "account_id", "account_name"));
    }

    public function processCreateAmendUkSavingsAccountAnnualSummary()
    {

        $account_id = $this->request->post['account_id'] ?? '';
        $account_name = $this->request->post['account_name'] ?? '';
        $taxed_interest = $this->request->post['taxed_interest'] ?? 0;
        $untaxed_interest = $this->request->post['untaxed_interest'] ?? 0;

        $uk_interest = SavingsHelper::validateUkSavingsAccountAnnualSummary(['taxedUkInterest' => $taxed_interest, 'untaxedUkInterest' => $untaxed_interest]);


        if (!empty($_SESSION['errors'])) {
            $query_string = http_build_query([
                'account_id' => $account_id,
                'account_name' => $account_name,
                'taxed_interest' => $taxed_interest,
                'untaxed_interest' => $untaxed_interest
            ]);

            return $this->redirect("/savings/create-amend-uk-savings-account-annual-summary?" . $query_string);
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSavings->createAndAmendAUkSavingsAccountAnnualSummary($nino, $tax_year, $account_id, $uk_interest);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Savings Account Interest has been added", Flash::SUCCESS);
        }

        return $this->redirect("/savings/list-uk-savings-accounts");
    }

    // SAVINGS INCOME
    public function retrieveSavingsIncome()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSavings->retrieveSavingsIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $savings_income = [];

        if ($response['type'] === "success") {
            $savings_income = $response['response'];
            $_SESSION['savings']['savings_income'] = $savings_income;
        }

        $heading = "Savings Income";

        return $this->view("Endpoints/Other/SavingsIncome/savings-income-retrieve.php", compact("heading", "savings_income"));
    }

    public function createAmendSavingsIncome()
    {
        $heading = "Savings Income";

        $errors = $this->flashErrors();

        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $savings_income = $_SESSION['savings']['savings_income'] ?? [];
        unset($_SESSION['savings']);

        $securities = $savings_income['securities'] ?? [[]];
        $foreign_interest = $savings_income['foreignInterest'] ?? [[]];

        return $this->view("Endpoints/Other/SavingsIncome/savings-income-create-amend.php", compact("heading", "errors", "countries", "securities", "foreign_interest"));
    }

    public function processCreateAmendSavingsIncome()
    {
        $savings_income = $this->request->post ?? [];

        $validated = SavingsHelper::validateAndFormatSavingsIncome($savings_income);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['savings']['savings_income'] = $savings_income;

            return $this->redirect("/savings/create-amend-savings-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiSavings->createAndAmendSavingsIncome($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Savings Income has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/savings/retrieve-savings-income");
    }

    public function confirmDeleteSavingsIncome()
    {
        $heading = "Delete Savings Income";

        return $this->view("Endpoints/Other/SavingsIncome/savings-income-confirm-delete.php", compact("heading"));
    }

    public function deleteSavingsIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            

            unset($_SESSION['savings_income']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Savings income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/savings/retrieve-savings-income");
    }
}