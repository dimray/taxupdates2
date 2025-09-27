<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiObligations;
use Framework\Controller;

class Obligations extends Controller
{
    public function __construct(private ApiObligations $apiObligations) {}

    public function retrieveCumulativeObligations()
    {
        // what is this?
        unset($_SESSION['cumulative_data']);

        if (isset($this->request->get['business_id'])) {
            $_SESSION['business_id'] = $this->request->get['business_id'];
        }

        if (isset($this->request->get['type_of_business'])) {
            $_SESSION['type_of_business'] = $this->request->get['type_of_business'];
        }

        if (isset($this->request->get['trading_name'])) {
            $_SESSION['trading_name'] = $this->request->get['trading_name'];
        } else {
            unset($_SESSION['trading_name']);
        }

        $business_id = $_SESSION['business_id'];

        // test foreign property
        // $business_id = "XFIS12345678901";

        $type_of_business = $_SESSION['type_of_business'];

        // first year for cumulative updates is 2025-26
        // NEED TO CHANGE TO 2025-26 AFTER TESTING
        $first_tax_year = "2022-23";

        // sets session to $first_tax_year if it was earlier than that.
        $tax_year =  $_SESSION['tax_year'];

        if ((int) substr($tax_year, 0, 4) < substr($first_tax_year, 0, 4)) {

            $_SESSION['tax_year'] = $first_tax_year;
        }

        $from_date = TaxYearHelper::getTaxYearStartDate($tax_year);
        $to_date = TaxYearHelper::getTaxYearEndDate($tax_year);

        $nino = Helper::getNino();

        $response = $this->apiObligations->retrieveIncomeTaxIncomeAndExpenditureObligations($nino, $business_id, $type_of_business, $from_date, $to_date);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $obligations = $response['obligations'] ?? [];

        $business_details = Helper::setBusinessDetails();

        $heading = "Cumulative Summary";

        $controller = $_SESSION['type_of_business'] === "self-employment" ? "self-employment" : "property-business";

        return $this->view("Endpoints/Obligations/cumulative.php", compact("heading", "first_tax_year", "business_details", "obligations", "controller"));
    }

    public function finalDeclaration()
    {
        $tax_year = $_SESSION['tax_year'];

        $nino = Helper::getNino();

        $response = $this->apiObligations->retrieveIncomeTaxFinalDeclarationObligations($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $obligations = $response['obligations'] ?? [];

        $heading = "Final Declaration";

        $deadlines = TaxYearHelper::getDeadlinesFromTaxYear($tax_year);
        $before_filing_deadline = time() <= $deadlines['filing_deadline'];
        $before_amendment_deadline = time() <= $deadlines['amendment_deadline'];
        $before_current_tax_year = TaxYearHelper::beforeCurrentYear($tax_year);

        $first_tax_year = "2024-25";

        return $this->view("Endpoints/Obligations/final-declaration.php", compact("heading", "tax_year", "obligations", "before_amendment_deadline", "before_current_tax_year", "first_tax_year"));
    }
}
