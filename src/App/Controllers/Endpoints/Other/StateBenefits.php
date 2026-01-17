<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiStateBenefits;
use App\Helpers\Helper;
use App\Flash;
use Framework\Controller;

class StateBenefits extends Controller
{
    public function __construct(private ApiStateBenefits $apiStateBenefits) {}

    public function createStateBenefit()
    {
        $heading = "Add A State Benefit";

        return $this->view("Endpoints/Other/StateBenefits/create-state-benefit.php", compact("heading"));
    }

    public function processCreateStateBenefit()
    {
        $state_benefit = $this->request->post ?? [];

        $benefit = $state_benefit['benefitType'] ?? "";
        $start_date = $state_benefit['startDate'] ?? "";
        $end_date = $state_benefit['endDate'] ?? "";

        $allowed_benefits = ["incapacityBenefit", "statePension", "statePensionLumpSum", "employmentSupportAllowance", "jobSeekersAllowance", "bereavementAllowance", "otherStateBenefits"];

        if (!in_array($benefit, $allowed_benefits)) {
            $this->addError("Please select a benefit from the drop-down list");
        }

        if (empty($start_date)) {
            $this->addError("Start date is required");
        }

        if (empty($end_date)) {
            unset($state_benefit['endDate']);
        }

        if (!empty($this->errors)) {
            return $this->redirect("/state-benefits/create-state-benefit");
        }

        $nino = Helper::getNino();

        $response = $this->apiStateBenefits->createStateBenefit($nino, $_SESSION['tax_year'], $state_benefit);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("State Benefit has been added", Flash::SUCCESS);
        }

        return $this->redirect("/state-benefits/list-state-benefits");
    }

    public function listStateBenefits()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiStateBenefits->listStateBenefits($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $state_benefits = [];
        $customer_added_state_benefits = [];

        if ($response['type'] === "success") {

            $state_benefits = $response['response']['stateBenefits'] ?? [];
            $customer_added_state_benefits = $response['response']['customerAddedStateBenefits'] ?? [];

            $_SESSION['state_benefits']['benefits'] = $state_benefits;
            $_SESSION['state_benefits']['customer_added'] = $customer_added_state_benefits;
        }

        $heading = "Other Income";

        return $this->view("Endpoints/Other/StateBenefits/list-state-benefits.php", compact("heading", "state_benefits", "customer_added_state_benefits"));
    }

    public function confirmDeleteStateBenefit()
    {
        $benefit_type = $this->request->get['benefit_type'] ?? "";
        $benefit_id = $this->request->get['benefit_id'] ?? "";

        if (empty($benefit_id) || empty($benefit_type)) {
            return $this->redirect("/state-benefits/list-state-benefits");
        }

        $heading = "Delete " . Helper::formatCamelCase($benefit_type);

        return $this->view("Endpoints/Other/StateBenefits/confirm-delete-state-benefit.php", compact("heading", "benefit_id", "benefit_type"));
    }

    public function deleteStateBenefit()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $benefit_id = $this->request->post['benefit_id'];
            $benefit_type = $this->request->post['benefit_type'];

            $response = $this->apiStateBenefits->deleteStateBenefit($nino, $tax_year, $benefit_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {

                Flash::addMessage(Helper::formatCamelCase($benefit_type) . " has been deleted", Flash::SUCCESS);
            }
        }

        unset($_SESSION['employment_income']);

        return $this->redirect("/state-benefits/list-state-benefits");
    }

    public function amendStateBenefitAmounts()
    {
        $benefit_type = $this->request->get['benefit_type'] ?? "";
        $benefit_id = $this->request->get['benefit_id'] ?? "";

        if (empty($benefit_id) || empty($benefit_type)) {
            return $this->redirect("/state-benefits/list-state-benefits");
        }

        $heading = "Enter Amount";

        return $this->view("Endpoints/Other/StateBenefits/amend-state-benefit-amount.php", compact("heading", "benefit_type", "benefit_id"));
    }

    public function processAmendStateBenefitAmounts()
    {
        if (isset($this->request->post)) {
            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $benefit_id = $this->request->post['benefit_id'] ?? '';
            $benefit_type = $this->request->post['benefit_type'] ?? '';
            $amount = $this->request->post['amount'];
            $tax_paid = $this->request->post['tax_paid'];

            if (empty($amount)) {
                $this->addError("Amount is required");
            }

            if (is_numeric($amount)) {
                $amount = (float) $amount;
            } else {
                $this->addError("Amount must be a number between 0 and 99999999999.99");
            }

            if (!empty($tax_paid)) {

                if (is_numeric($tax_paid)) {
                    $tax_paid = (float) $tax_paid;
                } else {
                    $this->addError("Tax Paid must be a number between 0 and 99999999999.99");
                }
            }

            if (!empty($this->errors)) {
                return $this->redirect("/state-benefits/amend-state-benefit-amounts");
            }

            if ($benefit_id === '' || $benefit_type === '') {
                return $this->redirect("/state-benefits/list-state-benefits");
            }

            $amounts = ['amount' => $amount];
            if (!empty($tax_paid)) {
                $amounts['taxPaid'] = $tax_paid;
            }

            $response = $this->apiStateBenefits->amendStateBenefitAmounts($nino, $tax_year, $benefit_id, $amounts);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("State Benefit " . Helper::formatCamelCase($benefit_type) . " has been updated", Flash::SUCCESS);
            }
        }

        return $this->redirect("/state-benefits/list-state-benefits");
    }

    public function confirmIgnoreStateBenefit()
    {
        $benefit_type = $this->request->get['benefit_type'] ?? "";
        $benefit_id = $this->request->get['benefit_id'] ?? "";

        if (empty($benefit_id) || empty($benefit_type)) {
            return $this->redirect("/state-benefits/list-state-benefits");
        }

        $heading = "Ignore " . Helper::formatCamelCase($benefit_type);

        return $this->view("Endpoints/Other/StateBenefits/confirm-ignore-state-benefit.php", compact("heading", "benefit_type", "benefit_id"));
    }

    public function ignoreStateBenefit()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $benefit_id = $this->request->post['benefit_id'] ?? '';
            $benefit_type = $this->request->post['benefit_type'] ?? '';

            if ($benefit_id === '' || $benefit_type === '') {
                return $this->redirect("/state-benefits/list-state-benefits");
            }

            $response = $this->apiStateBenefits->ignoreStateBenefit($nino, $tax_year, $benefit_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("State Benefit " . Helper::formatCamelCase($benefit_type) . " is ignored", Flash::SUCCESS);
            }
        }

        return $this->redirect("/state-benefits/list-state-benefits");
    }

    public function unignoreStateBenefit()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];
            $benefit_id = $this->request->post['benefit_id'] ?? '';
            $benefit_type = $this->request->post['benefit_type'] ?? '';

            if ($benefit_id === '' || $benefit_type === '') {
                return $this->redirect("/state-benefits/list-state-benefits");
            }

            $response = $this->apiStateBenefits->unIgnoreStateBenefit($nino, $tax_year, $benefit_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("State Benefit " . Helper::formatCamelCase($benefit_type) . " is ignored", Flash::SUCCESS);
            }
        }

        return $this->redirect("/state-benefits/list-state-benefits");
    }
}
