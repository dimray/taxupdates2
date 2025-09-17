<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiBusinessSourceAdjustableSummary;
use Framework\Controller;
use DateTime;

class BusinessSourceAdjustableSummary extends Controller
{
    public function __construct(private ApiBusinessSourceAdjustableSummary $apiBusinessSourceAdjustableSummary) {}

    public function index()
    {
        $heading = "Accounting Adjustments";

        $business_details = Helper::setBusinessDetails();

        return $this->view("Endpoints/BusinessSourceAdjustableSummary/index.php", compact("heading", "business_details"));
    }

    public function trigger()
    {
        $start_date = TaxYearHelper::getTaxYearStartDate($_SESSION['tax_year']);
        $end_date = TaxYearHelper::getTaxYearEndDate($_SESSION['tax_year']);

        if ($_SESSION['period_type'] !== "standard") {
            $start_date = (new DateTime($start_date))->modify("-5 days")->format("Y-m-d");
            $end_date = (new DateTime($end_date))->modify("-5 days")->format("Y-m-d");
        }

        $accounting_period = [
            'startDate' => $start_date,
            'endDate' => $end_date
        ];

        $nino = Helper::getNino();

        $business_id = $_SESSION['business_id'];

        $type_of_business = $_SESSION['type_of_business'];

        $response = $this->apiBusinessSourceAdjustableSummary->triggerABusinessSourceAdjustableSummary($nino, $business_id, $type_of_business, $accounting_period);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success" && !empty($response['calculation_id'])) {

            $_SESSION['bsas_calc_id'] = $response['calculation_id'];

            return $this->redirect("/business-source-adjustable-summary/create");
        } else {

            return $this->redirect("/business-details/retrieve-business-details");
        }
    }

    public function create()
    {
        if (empty($_SESSION['errors'])) {
            unset($_SESSION['bsas']);
        }

        $heading = "Accounting Adjustments";

        $business_details = Helper::setBusinessDetails();

        $type = $_SESSION['type_of_business'];

        $errors = $this->flashErrors();

        if ($_SESSION['type_of_business'] !== "foreign-property") {
            // self-employment and uk-property
            // I don't need zeroAdjustments here as there's never an error if zeroAdjustments is set
            $income = $_SESSION['bsas'][$_SESSION['business_id']]['income'] ?? [];
            $expenses = $_SESSION['bsas'][$_SESSION['business_id']]['expenses'] ?? [];
            $additions = $_SESSION['bsas'][$_SESSION['business_id']]['additions'] ?? [];


            return $this->view(
                "Endpoints/BusinessSourceAdjustableSummary/create-bsas-$type.php",
                compact("heading", "business_details", "errors", "income", "expenses", "additions")
            );
        } elseif ($_SESSION['type_of_business'] === "foreign-property") {
        }
    }
}
