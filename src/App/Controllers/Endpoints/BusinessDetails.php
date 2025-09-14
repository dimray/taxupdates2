<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Flash;
use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiBusinessDetails;
use Framework\Controller;

class BusinessDetails extends Controller
{
    public function __construct(private ApiBusinessDetails $apiBusinessDetails) {}

    public function listAllBusinesses()
    {
        $updates = $this->request->get['updates'] ?? "";

        $nino = Helper::getNino();

        $response = $this->apiBusinessDetails->listAllBusinesses($nino);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $businesses  = [];

        if ($response['type'] === 'success') {
            $businesses = $response['businesses'] ?? [];
        }

        $heading = "Your Businesses";

        $hide_tax_year = true;

        return $this->view("/Endpoints/BusinessDetails/index.php", compact("heading", "businesses", "hide_tax_year", "updates"));
    }

    public function retrieveBusinessDetails()
    {
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

        // TEST SELF EMPLOYMENT ***********************
        if ($_SESSION['type_of_business'] === "self-employment") {
            $_SESSION['business_id'] = "XBIS12345678901";
            $test_headers = "";
        }

        // TEST UK PROPERTY ***************************
        if ($_SESSION['type_of_business'] === "uk-property") {
            $_SESSION['business_id'] = "XPIS12345678901";
            $test_headers = "PROPERTY";
        }

        // TEST FOREIGN PROPERTY ***********************
        if ($_SESSION['type_of_business'] === "foreign-property") {
            $_SESSION['business_id'] = "XFIS12345678901";
            $test_headers = "FOREIGN_PROPERTY";
        }

        $nino = Helper::getNino();

        $response = $this->apiBusinessDetails->retrieveBusinessDetails($nino, $_SESSION['business_id'], $test_headers);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $business_details = [];

        $current_period = "";

        if ($response['type'] === 'success') {
            $business = $response['business'] ?? [];

            if (!empty($business['businessId'])) {
                $business_details['businessId'] = $business['businessId'];
            }
            if (!empty($business['typeOfBusiness'])) {
                $business_details['typeOfBusiness'] = $business['typeOfBusiness'];
            }
            if (!empty($business['tradingName'])) {
                $business_details['tradingName'] = $business['tradingName'];
            }
            if (!empty($business['businessAddressPostcode'])) {
                $business_details['postcode'] = $business['businessAddressPostcode'];
            }
            if (!empty($business['commencementDate'])) {
                $business_details['commencementDate'] = $business['commencementDate'];
            }
            if (!empty($business['cessationDate'])) {
                $business_details['cessationDate'] = $business['cessationDate'];
            }
            if (!empty($business['accountingType'])) {
                $business_details['accountingType'] = $business['accountingType'];
            }

            $current_period = $business['quarterlyTypeChoice']['quarterlyPeriodType'] ?? "standard";

            $_SESSION['period_type'] = $current_period;

            if ($current_period === 'standard') {

                $business_details['quarterlyPeriods'] = ucfirst($current_period) . " (6 April to 5 April)";
            } else {

                $business_details['quarterlyPeriods'] = ucfirst($current_period) . " (1 April to 31 March)";
            }
        }

        $type_of_business = $_SESSION['type_of_business'] === "self-employment" ? "self-employment" : "property-business";

        $heading = "Business Details";

        $tax_year = $_SESSION['tax_year'];
        $current_tax_year = TaxYearHelper::getCurrentTaxYear();

        return $this->view(
            "Endpoints/BusinessDetails/show.php",
            compact("heading", "current_period", "business_details", "type_of_business")
        );
    }

    public function changeReportingPeriod()
    {
        $current_period = $this->request->get['current_period'];

        $new_period = $current_period === "standard" ? "calendar" : "standard";

        $previous_year = TaxYearHelper::getCurrentTaxYear(-1);
        $tax_year = TaxYearHelper::getCurrentTaxYear();
        $next_year = TaxYearHelper::getCurrentTaxYear(+1);

        $heading = "Change Reporting Period";

        $hide_tax_year = true;

        $business_id = $_SESSION['business_id'];

        return $this->view(
            "Endpoints/BusinessDetails/change-reporting-period.php",
            compact("heading", "previous_year", "tax_year", "next_year", "current_period", "new_period", "hide_tax_year")
        );
    }

    public function updateReportingPeriod()
    {
        $tax_year = $this->request->get['tax_year'] ?? null;
        $new_period = $this->request->get['new_period'] ?? null;

        if (!$tax_year || !$new_period) {
            Flash::addMessage("Unable to update period", Flash::WARNING);
            return $this->redirect("/business-details/retrieve-business-details");
        }

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'] ?? '';


        if (!$business_id) {
            Flash::addMessage("Unable to update period", Flash::WARNING);
            return $this->redirect("/business-details/retrieve-business-details");
        }

        $response = $this->apiBusinessDetails->createAmendPeriodTypeForBusiness($nino, $business_id, $tax_year, $new_period);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Quarterly Period Type has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/business-details/retrieve-business-details");
    }
}
