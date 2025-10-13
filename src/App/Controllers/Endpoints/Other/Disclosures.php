<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints\Other;

use App\HmrcApi\Endpoints\Other\ApiDisclosures;
use Framework\Controller;
use App\Flash;
use App\Helpers\DisclosuresHelper;
use App\Helpers\Helper;

class Disclosures extends Controller
{
    public function __construct(private ApiDisclosures $apiDisclosures) {}

    public function createMarriageAllowance()
    {
        $heading = "Create Marriage Allowance";

        $errors = $this->flashErrors();

        $marriage_allowance = $_SESSION['disclosures']['marriage_allowance'] ?? [];
        unset($_SESSION['disclosures']);

        $hide_tax_year = true;

        return $this->view("Endpoints/Other/Disclosures/marriage-allowance-create.php", compact("heading", "errors", "marriage_allowance", "hide_tax_year"));
    }

    public function processCreateMarriageAllowance()
    {
        $marriage_allowance = $this->request->post;
        $marriage_allowance['spouseOrCivilPartnerNino'] = strtoupper($marriage_allowance['spouseOrCivilPartnerNino']) ?? '';
        $validated = DisclosuresHelper::validateCreateMarriageAllowance($marriage_allowance);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['disclosures']['marriage_allowance'] = $marriage_allowance;
            return $this->redirect("/disclosures/create-marriage-allowance");
        }

        $nino = Helper::getNino();

        $response = $this->apiDisclosures->createMarriageAllowance($nino, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Marriage Allowance election has been created", Flash::SUCCESS);
        }

        $heading = "Marriage Allowance";

        return $this->view("Endpoints/Other/Disclosures/marriage-allowance-result.php", compact("heading"));
    }

    public function retrieveDisclosures()
    {
        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDisclosures->retrieveDisclosures($nino, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $disclosures = [];

        if ($response['type'] === "success") {
            $disclosures = $response['response'];
            $_SESSION['disclosures'] = $disclosures;
        }

        $heading = "Disclosures";

        return $this->view("Endpoints/Other/Disclosures/disclosures-retrieve.php", compact("heading", "disclosures"));
    }

    public function createAndAmendDisclosures()
    {
        $heading = "Disclosures";

        $errors = $this->flashErrors();

        $disclosures = $_SESSION['disclosures'] ?? [];
        unset($_SESSION['disclosures']);

        $tax_avoidance = $disclosures['taxAvoidance'] ?? [[]];
        $class_2_nics = $disclosures['class2Nics'] ?? null;

        return $this->view("Endpoints/Other/Disclosures/disclosures-create-amend.php", compact("heading", "errors", "tax_avoidance", "class_2_nics"));
    }

    public function processCreateAndAmendDisclosures()
    {
        $disclosures = $this->request->post ?? [];

        $validated = DisclosuresHelper::validateAndFormatDisclosures($disclosures);

        if (!empty($_SESSION['errors'])) {
            $_SESSION['disclosures'] = $disclosures;

            return $this->redirect("/disclosures/create-and-amend-disclosures");
        }

        $nino = Helper::getNino();
        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiDisclosures->createAndAmendDisclosures($nino, $tax_year, $validated);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === "success") {
            Flash::addMessage("Disclosures have been updated", Flash::SUCCESS);
        }

        return $this->redirect("/disclosures/retrieve-disclosures");
    }

    public function confirmDeleteDisclosures()
    {
        $heading = "Delete Disclosures";

        return $this->view("Endpoints/Other/Disclosures/disclosures-confirm-delete.php", compact("heading"));
    }

    public function deleteDisclosures()
    {
        if (isset($this->request->post)) {

            $nino = Helper::getNino();
            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiDisclosures->deleteDisclosures($nino, $tax_year);

            unset($_SESSION['disclosures']);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === "success") {
                Flash::addMessage("Disclosures have been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/disclosures/retrieve-disclosures");
    }
}
