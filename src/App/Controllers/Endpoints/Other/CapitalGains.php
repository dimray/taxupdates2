<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiCapitalGains;
use Framework\Controller;
use App\Helpers\Helper;
use App\Flash;
use App\Helpers\CapitalGainsHelper;

class CapitalGains extends Controller
{
    public function __construct(private ApiCapitalGains $apiCapitalGains) {}

    // RESIDENTIAL PROPERTY
    public function retrieveAllResidentialPropertyDisposals()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        unset($_SESSION['capital_gains']);

        $response = $this->apiCapitalGains->retrieveAllCgtResidentialPropertyDisposalsAndOverrides($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $customer_added_disposals = [];
        $real_time_disposals = [];

        if ($response['type'] === "success") {
            $customer_added_disposals = $response['response']['customerAddedDisposals'] ?? [];
            $real_time_disposals = $response['response']['ppdService'] ?? [];
            $_SESSION['capital_gains']['customer_added_disposals'] = $customer_added_disposals;
            $_SESSION['capital_gains']['real_time_disposals'] = $real_time_disposals;
        }

        $heading = "Residential Property Disposals";

        return $this->view("Endpoints/Other/CapitalGains/retrieve-residential-property-disposals.php", compact("heading", "customer_added_disposals", "real_time_disposals"));
    }

    // CUSTOMER ADDED
    public function createAmendCustomerAddedResidentialPropertyDisposals()
    {
        $heading = "Customer Added Residential Property Disposals";

        $errors = $this->flashErrors();

        $customer_added_disposals = $_SESSION['capital_gains']['customer_added_disposals'] ?? [];

        $disposals = $customer_added_disposals['disposals'] ?? [[]];

        unset($_SESSION['capital_gains']);

        return $this->view("Endpoints/Other/CapitalGains/create-amend-customer-created-residential-property-disposals.php", compact("heading", "errors", "disposals"));
    }

    public function processCreateAmendCustomerAddedResidentialPropertyDisposals()
    {
        $customer_added_disposals = $this->request->post ?? [];

        $validated = CapitalGainsHelper::validateAndFormatCustomerAddedResidentialPropertyDisposals($customer_added_disposals);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['capital_gains']['customer_added_disposals'] = $customer_added_disposals;

            return $this->redirect("/capital-gains/create-amend-customer-added-residential-property-disposals");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCapitalGains->createAndAmendCgtResidentialPropertyDisposals($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Residential Property Disposals have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/capital-gains/retrieve-all-residential-property-disposals");
    }

    public function confirmDeleteCustomerAddedResidentialPropertyDisposals()
    {
        $heading = "Delete Customer Added Residential Property Disposals";

        return $this->view("Endpoints/Other/CapitalGains/confirm-delete-customer-created-residential-property-disposals.php", compact("heading"));
    }

    public function deleteCustomerAddedResidentialPropertyDisposals()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiCapitalGains->deleteCgtResidentialPropertyDisposals($nino, $tax_year);

            unset($_SESSION['capital_gains']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Customer Added Disposals have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/capital-gains/retrieve-all-residential-property-disposals");
    }

    // REAL TIME RETURNS
    public function createAmendCgtOnResidentialPropertyOverrides()
    {
        $heading = "Override Reported Residential Property Disposals";

        $errors = $this->flashErrors();

        $reported_disposals = $_SESSION['capital_gains']['real_time_disposals'] ?? [];

        $multiple_property_disposals = $reported_disposals['multiplePropertyDisposals'] ?? [[]];
        $single_property_disposals = $reported_disposals['singlePropertyDisposals'] ?? [[]];

        unset($_SESSION['capital_gains']);

        return $this->view("Endpoints/Other/CapitalGains/create-amend-residential-property-overrides.php", compact("heading", "errors", "multiple_property_disposals", "single_property_disposals"));
    }

    public function processCreateAmendCgtOnResidentialPropertyOverrides()
    {
        $reported_disposals = $this->request->post ?? [];

        $validated = CapitalGainsHelper::validateAndFormatCgtOnResidentialPropertyOverrides($reported_disposals);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['capital_gains']['real_time_disposals'] = $reported_disposals;

            return $this->redirect("/capital-gains/create-amend-cgt-on-residential-property-overrides");
        }


        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCapitalGains->createAndAmendReportAndPayCapitalGainsTaxOnResidentialPropertyOverrides($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Reported Property Disposals have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/capital-gains/retrieve-all-residential-property-disposals");
    }

    public function confirmDeleteCgtOnResidentialPropertyOverrides()
    {
        $heading = "Delete Reported Residential Propery Disposal Overrides";

        return $this->view("Endpoints/Other/CapitalGains/confirm-delete-residential-property-overrides.php", compact("heading"));
    }

    public function deleteCgtOnResidentialPropertyOverrides()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiCapitalGains->deleteReportAndPayCapitalGainsTaxOnResidentialPropertyOverrides($nino, $tax_year);

            unset($_SESSION['capital_gains']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Reported Residential Propery Disposal Overrides have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/capital-gains/retrieve-all-residential-property-disposals");
    }

    // OTHER CAPITAL GAINS
    public function retrieveOtherCapitalGains()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCapitalGains->retrieveOtherCapitalGainsAndDisposals($nino, $tax_year);

        $other_capital_gains = [];

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            $other_capital_gains = $response['response'];
            $_SESSION['capital_gains']['other_capital_gains'] = $other_capital_gains;
        }

        $heading = "Capital Gains";

        return $this->view("Endpoints/Other/CapitalGains/OtherGains/retrieve-other-capital-gains.php", compact("heading", "other_capital_gains"));
    }

    public function createAndAmendOtherCapitalGains()
    {
        $heading = "Capital Gains";

        $errors = $this->flashErrors();

        $other_capital_gains = $_SESSION['capital_gains']['other_capital_gains'] ?? [];
        unset($_SESSION['capital_gains']);

        $disposals = $other_capital_gains['disposals'] ?? [[]];
        $non_standard_gains = $other_capital_gains['nonStandardGains'] ?? [[]];
        $losses = $other_capital_gains['losses'] ?? [[]];
        $adjustments = $other_capital_gains['adjustments'] ?? "";

        return $this->view("Endpoints/Other/CapitalGains/OtherGains/create-amend-other-capital-gains.php", compact("heading", "errors", "disposals", "non_standard_gains", "losses", "adjustments"));
    }

    public function processCreateAndAmendOtherCapitalGains()
    {
        $other_capital_gains = $this->request->post ?? [];


        $validated = CapitalGainshelper::validateAndFormatOtherCapitalGains($other_capital_gains);


        if (!empty($_SESSION['errors'])) {
            $_SESSION['capital_gains']['other_capital_gains'] = $other_capital_gains;

            return $this->redirect("/capital-gains/create-and-amend-other-capital-gains");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiCapitalGains->createAndAmendOtherCapitalGainsAndDisposals($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Other Capital Gains have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/capital-gains/retrieve-other-capital-gains");
    }

    public function confirmDeleteOtherCapitalGains()
    {
        $heading = "Delete Capital Gains";

        return $this->view("Endpoints/Other/CapitalGains/OtherGains/confirm-delete-other-capital-gains.php", compact("heading"));
    }

    public function deleteOtherCapitalGains()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiCapitalGains->deleteOtherCapitalGainsAndDisposals($nino, $tax_year);

            unset($_SESSION['capital_gains']['other_capital_gains']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Other Capital Gains has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/capital-gains/retrieve-other-capital-gains");
    }
}