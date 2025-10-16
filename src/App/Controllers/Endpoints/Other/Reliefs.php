<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiReliefs;
use Framework\Controller;
use App\Helpers\Helper;
use App\Flash;
use App\Helpers\ReliefsHelper;

class Reliefs extends Controller
{
    public function __construct(private ApiReliefs $apiReliefs) {}

    // INVESTMENTS
    public function retrieveReliefInvestments()
    {
        unset($_SESSION['reliefs']);
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->retrieveReliefInvestments($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $investment_reliefs = [];

        if ($response['type'] === "success") {
            $investment_reliefs = $response['response'];
            $_SESSION['reliefs']['investments'] = $investment_reliefs;
        }

        $heading = "Investment Reliefs";

        return $this->view("Endpoints/Other/Reliefs/investment-reliefs-retrieve.php", compact("heading", "investment_reliefs"));
    }

    public function createAndAmendReliefInvestments()
    {
        $heading = "Investment Reliefs";

        $errors = $this->flashErrors();

        $investment_reliefs = $_SESSION['reliefs']['investments'] ?? [];
        unset($_SESSION['reliefs']);

        $vct_subscription = $investment_reliefs['vctSubscription'] ?? [[]];
        $eis_subscription = $investment_reliefs['eisSubscription'] ?? [[]];
        $community_investment = $investment_reliefs['communityInvestment'] ?? [[]];
        $seed_enterprise_investment = $investment_reliefs['seedEnterpriseInvestment'] ?? [[]];

        return $this->view("Endpoints/Other/Reliefs/investment-reliefs-create-amend.php", compact("heading", "errors", "vct_subscription", "eis_subscription", "community_investment", "seed_enterprise_investment"));
    }

    public function processCreateAndAmendReliefInvestments()
    {
        $investment_reliefs = $this->request->post ?? [];

        $validated = ReliefsHelper::validateAndFormatInvestmentReliefs($investment_reliefs);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['reliefs']['investments'] = $investment_reliefs;

            return $this->redirect("/reliefs/create-and-amend-relief-investments");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->createAndAmendReliefInvestments($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Investment Reliefs have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/reliefs/retrieve-relief-investments");
    }

    public function confirmDeleteReliefInvestments()
    {
        $heading = "Delete Investment Reliefs";

        return $this->view("Endpoints/Other/Reliefs/investment-reliefs-confirm-delete.php", compact("heading"));
    }

    public function deleteReliefInvestments()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiReliefs->deleteReliefInvestments($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['reliefs']);

            if ($response['type'] === "success") {
                Flash::addMessage("Investment Reliefs have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/reliefs/retrieve-relief-investments");
    }

    // OTHER RELIEFS

    public function retrieveOtherReliefs()
    {
        unset($_SESSION['reliefs']);
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->retrieveOtherReliefs($nino, $tax_year);

        $other_reliefs = [];

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            $other_reliefs = $response['response'];
            $_SESSION['reliefs']['other'] = $other_reliefs;
        }

        $heading = "Other Reliefs";

        return $this->view("Endpoints/Other/Reliefs/other-reliefs-retrieve.php", compact("heading", "other_reliefs"));
    }

    public function createAndAmendOtherReliefs()
    {
        $heading = "Other Reliefs";

        $errors = $this->flashErrors();

        $other_reliefs = $_SESSION['reliefs']['other'] ?? [];
        unset($_SESSION['reliefs']);

        $non_deductible_loan_interest = $other_reliefs['nonDeductibleLoanInterest'] ?? [];
        $payroll_giving = $other_reliefs['payrollGiving'] ?? [];
        $qualifying_distribution = $other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities'] ?? [];
        $maintenance_payments = $other_reliefs['maintenancePayments'] ?? [[]];
        $post_cessation_trade_relief = $other_reliefs['postCessationTradeReliefAndCertainOtherLosses'] ?? [[]];
        $annual_payments_made = $other_reliefs['annualPaymentsMade'] ?? [];
        $qualifying_loan_interest_payments = $other_reliefs['qualifyingLoanInterestPayments'] ?? [[]];


        return $this->view("Endpoints/Other/Reliefs/other-reliefs-create-amend.php", compact(
            "heading",
            "errors",
            "non_deductible_loan_interest",
            "payroll_giving",
            "qualifying_distribution",
            "maintenance_payments",
            "post_cessation_trade_relief",
            "annual_payments_made",
            "qualifying_loan_interest_payments"
        ));
    }

    public function processCreateAndAmendOtherReliefs()
    {
        $other_reliefs = $this->request->post ?? [];

        $validated = ReliefsHelper::validateAndFormatOtherReliefs($other_reliefs);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['reliefs']['other'] = $other_reliefs;

            return $this->redirect("/reliefs/create-and-amend-other-reliefs");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->createAndAmendOtherReliefs($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Other Reliefs have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/reliefs/retrieve-other-reliefs");
    }

    public function confirmDeleteOtherReliefs()
    {
        $heading = "Delete Other Reliefs";

        return $this->view("Endpoints/Other/Reliefs/other-reliefs-confirm-delete.php", compact("heading"));
    }

    public function deleteOtherReliefs()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiReliefs->deleteOtherReliefs($nino, $tax_year);

            unset($_SESSION['reliefs']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Other Reliefs have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/reliefs/retrieve-other-reliefs");
    }

    // FOREIGN
    public function retrieveForeignReliefs()
    {
        unset($_SESSION['reliefs']);
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->retrieveForeignReliefs($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $foreign_reliefs = [];

        if ($response['type'] === "success") {
            $foreign_reliefs = $response['response'];
            $_SESSION['reliefs']['foreign'] = $foreign_reliefs;
        }

        $heading = "Foreign Reliefs";

        return $this->view("Endpoints/Other/Reliefs/foreign-reliefs-retrieve.php", compact("heading", "foreign_reliefs"));
    }

    public function createAndAmendForeignReliefs()
    {
        $heading = "Foreign Reliefs";

        $errors = $this->flashErrors();

        $countries = require ROOT_PATH . "config/mappings/country-codes.php";

        $foreign_reliefs = $_SESSION['reliefs']['foreign'] ?? [];
        unset($_SESSION['reliefs']);

        $foreign_tax_credit_relief = $foreign_reliefs['foreignTaxCreditRelief'] ?? [];
        $foreign_income_tax_credit_relief = $foreign_reliefs['foreignIncomeTaxCreditRelief'] ?? [[]];
        $ftcr_not_claimed = $foreign_reliefs['foreignTaxForFtcrNotClaimed'] ?? [];

        return $this->view("Endpoints/Other/Reliefs/foreign-reliefs-create-amend.php", compact(
            "heading",
            "errors",
            "countries",
            "foreign_tax_credit_relief",
            "foreign_income_tax_credit_relief",
            "ftcr_not_claimed"
        ));
    }

    public function processCreateAndAmendForeignReliefs()
    {
        $foreign_reliefs = $this->request->post ?? [];

        $validated = ReliefsHelper::validateAndFormatForeignReliefs($foreign_reliefs);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['reliefs']['foreign'] = $foreign_reliefs;

            return $this->redirect("/reliefs/create-and-amend-foreign-reliefs");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->createAndAmendForeignReliefs($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Foreign Reliefs have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/reliefs/retrieve-foreign-reliefs");
    }

    public function confirmDeleteForeignReliefs()
    {
        $heading = "Delete Foreign Reliefs";

        return $this->view("Endpoints/Other/Reliefs/foreign-reliefs-confirm-delete.php", compact("heading"));
    }

    public function deleteForeignReliefs()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiReliefs->deleteForeignReliefs($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['reliefs']);

            if ($response['type'] === "success") {
                Flash::addMessage("Foreign Reliefs have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/reliefs/retrieve-foreign-reliefs");
    }

    // PENSIONS

    public function retrievePensionsReliefs()
    {
        unset($_SESSION['reliefs']);
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->retrievePensionsReliefs($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $pensions_reliefs = [];

        if ($response['type'] === "success") {
            $pensions_reliefs = $response['response'];
            $_SESSION['reliefs']['pensions'] = $pensions_reliefs;
        }

        $heading = "Pensions Reliefs";

        return $this->view("Endpoints/Other/Reliefs/pensions-reliefs-retrieve.php", compact("heading", "pensions_reliefs"));
    }

    public function createAndAmendPensionsReliefs()
    {
        $heading = "Pensions Reliefs";

        $errors = $this->flashErrors();

        $pensions_reliefs = $_SESSION['reliefs']['pensions'] ?? [];
        unset($_SESSION['reliefs']);

        $pension_reliefs = $pensions_reliefs['pensionReliefs'] ?? [];

        return $this->view("Endpoints/Other/Reliefs/pensions-reliefs-create-amend.php", compact(
            "heading",
            "errors",
            "pension_reliefs"
        ));
    }

    public function processCreateAndAmendPensionsReliefs()
    {
        $pensions_reliefs = $this->request->post ?? [];

        $validated = ReliefsHelper::validateAndFormatPensionsReliefs($pensions_reliefs);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['reliefs']['pensions'] = $pensions_reliefs;

            return $this->redirect("/reliefs/create-and-amend-pensions-reliefs");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->createAndAmendPensionsReliefs($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Pensions Reliefs have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/reliefs/retrieve-pensions-reliefs");
    }

    public function confirmDeletePensionsReliefs()
    {
        $heading = "Delete Pensions Reliefs";

        return $this->view("Endpoints/Other/Reliefs/pensions-reliefs-confirm-delete.php", compact("heading"));
    }

    public function deletePensionsReliefs()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiReliefs->deletePensionsReliefs($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['reliefs']);

            if ($response['type'] === "success") {
                Flash::addMessage("Pensions Reliefs have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/reliefs/retrieve-pensions-reliefs");
    }

    // CHARITABLE GIVING

    public function retrieveCharitableGivingTaxRelief()
    {
        unset($_SESSION['reliefs']);
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->retrieveCharitableGivingTaxRelief($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $charitable_giving = [];

        if ($response['type'] === "success") {
            $charitable_giving = $response['response'];
            $_SESSION['reliefs']['charitable'] = $charitable_giving;
        }

        // change confusing hmrc array - add heading for uk gifts
        if (isset($charitable_giving['giftAidPayments']['totalAmount'])) {
            $uk_giving = $charitable_giving['giftAidPayments']['totalAmount'];
            $charitable_giving['giftAidPayments']['ukCharities']['totalAmount'] = $uk_giving;
            unset($charitable_giving['giftAidPayments']['totalAmount']);
        }

        if (isset($charitable_giving['gifts']['landAndBuildings']) || isset($charitable_giving['gifts']['sharesOrSecurities'])) {
            $land = $charitable_giving['gifts']['landAndBuildings'] ?? '';
            $shares = $charitable_giving['gifts']['sharesOrSecurities'] ?? '';

            if (!empty($land)) {
                $charitable_giving['gifts']['ukCharities']['landAndBuildings'] = $land;
                unset($charitable_giving['gifts']['landAndBuildings']);
            }

            if (!empty($shares)) {
                $charitable_giving['gifts']['ukCharities']['sharesOrSecurities'] = $land;
                unset($charitable_giving['gifts']['sharesOrSecurities']);
            }
        }


        $heading = "Charitable Giving Tax Reliefs";

        return $this->view("Endpoints/Other/Reliefs/charitable-giving-retrieve.php", compact("heading", "charitable_giving"));
    }

    public function createAndAmendCharitableGivingTaxRelief()
    {
        $heading = "Charitable Giving Tax Reliefs";

        $errors = $this->flashErrors();

        $charitable_giving = $_SESSION['reliefs']['charitable'] ?? [];
        unset($_SESSION['reliefs']);

        $gift_aid_payments = $charitable_giving['giftAidPayments'] ?? [];
        $gifts = $charitable_giving['gifts'] ?? [];

        return $this->view("Endpoints/Other/Reliefs/charitable-giving-create-amend.php", compact(
            "heading",
            "errors",
            "gift_aid_payments",
            "gifts"
        ));
    }

    public function processCreateAndAmendCharitableGivingTaxRelief()
    {
        $charitable_giving = $this->request->post ?? [];

        $validated = ReliefsHelper::validateAndFormatCharitableGiving($charitable_giving);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['reliefs']['charitable'] = $charitable_giving;

            return $this->redirect("/reliefs/create-and-amend-charitable-giving-tax-relief");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiReliefs->createAndAmendCharitableGivingTaxRelief($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Charitable Giving TaxReliefs have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/reliefs/retrieve-charitable-giving-tax-relief");
    }

    public function confirmDeleteCharitableGivingTaxRelief()
    {
        $heading = "Delete Charitable Giving";

        return $this->view("Endpoints/Other/Reliefs/charitable-giving-confirm-delete.php", compact("heading"));
    }

    public function deleteCharitableGivingTaxRelief()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiReliefs->deleteCharitableGivingTaxRelief($nino, $tax_year);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            unset($_SESSION['reliefs']);

            if ($response['type'] === "success") {
                Flash::addMessage("Charitable Giving has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/reliefs/retrieve-charitable-giving-tax-relief");
    }
}
