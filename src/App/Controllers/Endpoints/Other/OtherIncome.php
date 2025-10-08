<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiOtherIncome;
use Framework\Controller;
use App\Helpers\Helper;
use App\Helpers\OtherIncomeHelper;
use App\Flash;

class OtherIncome extends Controller
{
    public function __construct(private ApiOtherIncome $apiOtherIncome) {}

    public function retrieveOtherIncome()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiOtherIncome->retrieveOtherIncome($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $other_income = [];

        if ($response['type'] === "success") {
            $other_income = $response['response'];
            $_SESSION['other_income'] = $other_income;
        }

        $heading = "Other Income";

        return $this->view("Endpoints/Other/OtherIncome/retrieve-other-income.php", compact("heading", "other_income"));
    }

    public function createAndAmendOtherIncome()
    {
        $heading = "Other Income";

        $errors = $this->flashErrors();

        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $other_income = $_SESSION['other_income'] ?? [];
        unset($_SESSION['other_income']);

        $post_cessation_receipts = $other_income['postCessationReceipts'] ?? [[]];
        $business_receipts = $other_income['businessReceipts'] ?? [[]];
        $all_other_income_received_whilst_abroad = $other_income['allOtherIncomeReceivedWhilstAbroad'] ?? [[]];
        $overseas_income_and_gains = $other_income['overseasIncomeAndGains'] ?? [[]];
        $chargeable_foreign_benefits_and_gifts = $other_income['chargeableForeignBenefitsAndGifts'] ?? [[]];
        $omitted_foreign_income = $other_income['omittedForeignIncome'] ?? [[]];

        return $this->view("Endpoints/Other/OtherIncome/create-amend-other-income.php", compact("heading", "errors", "countries", "post_cessation_receipts", "business_receipts", "all_other_income_received_whilst_abroad", "overseas_income_and_gains", "chargeable_foreign_benefits_and_gifts", "omitted_foreign_income"));
    }

    public function processCreateAndAmendOtherIncome()
    {
        $other_income = $this->request->post ?? [];

        $validated = OtherIncomeHelper::validateAndFormatOtherIncome($other_income);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['other_income'] = $other_income;

            return $this->redirect("/other-income/create-and-amend-other-income");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiOtherIncome->createAndAmendOtherIncome($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Other Income has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/other-income/retrieve-other-income");
    }

    public function confirmDeleteOtherIncome()
    {
        $heading = "Delete Other Income";

        return $this->view("Endpoints/Other/OtherIncome/confirm-delete-other-income.php", compact("heading"));
    }

    public function deleteOtherIncome()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiOtherIncome->deleteOtherIncome($nino, $tax_year);

            unset($_SESSION['other_income']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Other income has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/other-income/retrieve-other-income");
    }
}
