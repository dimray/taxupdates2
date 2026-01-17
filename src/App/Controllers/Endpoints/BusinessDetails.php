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

    // there is a response in support relating to Periods Of Account endpoints

    public function listAllBusinesses()
    {
        Helper::clearUpSession();

        $year_end = $this->request->get['year_end'] ?? "";

        $nino = Helper::getNino();

        if (isset($_SESSION[$nino]['cache']['businesses'])) {
            $businesses = $_SESSION[$nino]['cache']['businesses'];
        } else {

            $response = $this->apiBusinessDetails->listAllBusinesses($nino);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            $businesses  = [];

            if ($response['type'] === 'success') {
                $businesses = $response['businesses'] ?? [];

                $_SESSION[$nino]['cache']['businesses'] = $businesses;
            }
        }


        $heading = "Select Business To Update";

        $hide_tax_year = true;

        $supporting_agent  = false;
        if (isset($_SESSION['client']['agent_type']) && $_SESSION['client']['agent_type'] === "supporting") {
            $supporting_agent = true;
        }

        return $this->view("/Endpoints/BusinessDetails/index.php", compact("heading", "businesses", "hide_tax_year", "year_end", "supporting_agent"));
    }

    public function retrieveBusinessDetails()
    {
        // business dets already set in retrieve cumulative obligations. This is just resetting if necessary
        if (isset($this->request->get['business_id'])) {
            $_SESSION['business_id'] = $this->request->get['business_id'] ?? '';
        }

        if (isset($this->request->get['type_of_business'])) {
            $_SESSION['type_of_business'] = $this->request->get['type_of_business'] ?? '';
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

        $business_id = $_SESSION['business_id'];

        if (isset($_SESSION[$nino]['cache'][$business_id]['business_details'])) {
            $business_details = $_SESSION[$nino]['cache'][$business_id]['business_details'];
            $current_period = $_SESSION[$nino]['cache'][$business_id]['period_type'];
        } else {

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

                if ($current_period === 'standard') {

                    $business_details['quarterlyPeriods'] = ucfirst($current_period) . " (6 April to 5 April)";
                } else {

                    $business_details['quarterlyPeriods'] = ucfirst($current_period) . " (1 April to 31 March)";
                }

                $_SESSION[$nino]['cache'][$business_id]['period_type'] = $current_period;
                $_SESSION[$nino]['cache'][$business_id]['business_details'] = $business_details;
            }
        }

        $type_of_business = $_SESSION['type_of_business'] === "self-employment" ? "self-employment" : "property-business";

        $foreign_property = false;

        if ($_SESSION['type_of_business'] === "foreign-property") {
            $foreign_property = true;
        }

        $heading = "Business Details";

        $tax_year = $_SESSION['tax_year'];
        $current_tax_year = TaxYearHelper::getCurrentTaxYear();

        return $this->view(
            "Endpoints/BusinessDetails/show.php",
            compact("heading", "current_period", "business_details", "type_of_business", "foreign_property")
        );
    }

    public function accountingAdmin()
    {
        $heading = "Check And Update Accounting Periods Or Type";

        $current_period = $this->request->get['current_period'] ?? $_SESSION['period_type'] ?? '';

        if (empty($current_period)) {
            return $this->redirect("/business-details/retrieve-business-details");
        }

        $hide_tax_year = true;

        return $this->view("Endpoints/BusinessDetails/accounting-admin.php", compact("heading", "current_period", "hide_tax_year"));
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
            return $this->redirect("/business-details/list-all-businesses");
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

    public function retrieveAccountingType()
    {
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'];
        $tax_year = $_SESSION['tax_year'];


        if (!$business_id) {
            Flash::addMessage("Unable to retrieve accounting type", Flash::WARNING);
            return $this->redirect("/business-details/list-all-businesses");
        }

        $response = $this->apiBusinessDetails->retrieveAccountingType($nino, $business_id, $tax_year);



        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {

            $accounting_type = strtolower($response['accounting_type']['accountingType'] ?? '');
        } else {
            return $this->redirect("/business-details/accounting-admin");
        }



        $previous_year = TaxYearHelper::getCurrentTaxYear(-1);
        $previous_previous_year = TaxYearHelper::getCurrentTaxYear(-2);

        $new_accounting_type = $accounting_type === "cash" ? "accrual" : "cash";


        $heading = "Accounting Type";

        $hide_tax_year = true;

        return $this->view("Endpoints/BusinessDetails/change-accounting-type.php", compact("heading", "accounting_type", "new_accounting_type", "hide_tax_year", "previous_year", "previous_previous_year",));
    }

    public function updateAccountingType()
    {
        $tax_year = $this->request->get['tax_year'] ?? null;
        $new_accounting_type = strtoupper($this->request->get['new_accounting_type'] ?? '');

        if (!$tax_year || !$new_accounting_type) {
            Flash::addMessage("Unable to update accounting type", Flash::WARNING);
            return $this->redirect("/business-details/retrieve-business-details");
        }

        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'] ?? '';

        if (!$business_id) {
            Flash::addMessage("Unable to update accounting type", Flash::WARNING);
            return $this->redirect("/business-details/list-all-businesses");
        }

        $response = $this->apiBusinessDetails->updateAcccountingType($nino, $business_id, $tax_year, $new_accounting_type);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Accounting Type has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/business-details/retrieve-business-details");
    }

    public function retrievePeriodsOfAccount()
    {
        $nino = Helper::getNino();
        $business_id = $_SESSION['business_id'] ?? '';
        $tax_year = $_SESSION['tax_year'] ?? '';

        $response = $this->apiBusinessDetails->retrievePeriodsOfAccount($nino, $business_id, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $periods_of_account = [];

        if ($response['type'] === "success") {
            if ($response['periods']['periodsOfAccount']) {
                $periods_of_account = $response['periods']['periodsOfAccountDates'];
            }
        } else {
            return $this->redirect("/business-details/accounting-admin");
        }

        $heading = "Periods Of Account";

        $business_details = Helper::setBusinessDetails();

        $periods_of_account_string = "";

        if (!empty($periods_of_account)) {
            $periods_of_account_string = json_encode($periods_of_account);
        }

        $periods_query_string = http_build_query(compact("periods_of_account_string"));

        return $this->view("Endpoints/BusinessDetails/periods-of-account.php", compact("heading", "business_details", "periods_of_account", "periods_query_string"));
    }

    public function createUpdatePeriodsOfAccount()
    {
        $period_query_string = $this->request->get['periods_of_account_string'] ?? '';
        $periods_of_account = json_decode($period_query_string, true);

        $heading = "Update Periods Of Account";

        $business_details = Helper::setBusinessDetails();

        return $this->view("Endpoints/BusinessDetails/periods-of-account-update.php", compact("heading", "business_details", "periods_of_account"));
    }

    public function processCreateUpdatePeriodsOfAccount()
    {
        // TO DO, support request sent 26 sept and follow-up 23 Dec

        return $this->redirect("/business-details/retrieve-periods-of-account");
    }
}
