<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\Helpers\ForeignIncomeHelper;
use App\HmrcApi\Endpoints\Other\ApiForeignIncome;
use Framework\Controller;
use App\Helpers\Helper;
use App\Flash;

class ForeignIncome extends Controller
{

    public function __construct(private ApiForeignIncome $apiForeignIncome) {}

    public function retrieveForeignIncome()
    {
        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiForeignIncome->retrieveForeignIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $foreign_income = [];

        if ($response['type'] === "success") {
            $foreign_income = $response['response'];
            $_SESSION['foreign_income'] = $foreign_income;
        }

        $heading = "Foreign Income";

        return $this->view("Endpoints/Other/ForeignIncome/retrieve-foreign-income.php", compact("heading", "foreign_income"));
    }

    public function createAndAmendForeignIncome()
    {
        $heading = "Foreign Income";

        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $errors = $this->flashErrors();

        $foreign_income = $_SESSION['foreign_income'] ?? [];
        unset($_SESSION['foreign_income']);

        $foreign_earnings = $foreign_income['foreignEarnings'] ?? [[]];
        $unremittable_foreign_income = $foreign_income['unremittableForeignIncome'] ?? [[]];

        return $this->view("Endpoints/Other/ForeignIncome/add-edit-foreign-income.php", compact("heading", "countries", "foreign_earnings", "unremittable_foreign_income", "errors"));
    }

    public function processCreateAndAmendForeignIncome()
    {
        $foreign_income = $this->request->post ?? [];

        $validated = ForeignIncomeHelper::validateAndFormatForeignIncome($foreign_income);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['foreign_income'] = $foreign_income;

            return $this->redirect("/foreign-income/create-and-amend-foreign-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiForeignIncome->createAndAmendForeignIncome($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Foreign Income has been added", Flash::SUCCESS);
        }

        return $this->redirect("/foreign-income/retrieve-foreign-income");
    }

    public function confirmDeleteForeignIncome()
    {
        $heading = "Delete Foreign Income";

        return $this->view("Endpoints/Other/ForeignIncome/delete-foreign-income.php", compact("heading"));
    }

    public function deleteForeignIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiForeignIncome->deleteForeignIncome($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['foreign_income']);

            if ($response['type'] === "success") {
                Flash::addMessage("Foreign income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/foreign-income/retrieve-foreign-income");
    }
}
