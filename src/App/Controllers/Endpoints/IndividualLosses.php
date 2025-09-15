<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Helpers\Helper;
use App\Helpers\TaxYearHelper;
use App\HmrcApi\Endpoints\ApiIndividualLosses;
use App\Validate;
use App\Flash;
use Framework\Controller;

class IndividualLosses extends Controller
{

    public function __construct(private ApiIndividualLosses $apiIndividualLosses) {}

    // **********************BFWD LOSSES*******************************

    public function broughtForwardLosses()
    {
        $heading = "Pre-Making Tax Digital Losses";

        $business_id_query_string = http_build_query(['business_id' => $_SESSION['business_id']]);

        $business_details = Helper::setBusinessDetails();

        return $this->view("Endpoints/IndividualLosses/bfwd-index.php", compact("heading", "business_details", "business_id_query_string"));
    }

    public function createBroughtForwardLoss()
    {
        // business_id and tax_year must be set in session, otherwise redirect to list_of_businesses
        $business_id = $_SESSION['business_id'] ?? '';
        $type_of_loss = $_SESSION['type_of_business'] ?? '';

        if (empty($business_id) || empty($type_of_loss)) {
            return $this->redirect("/business-details/list-all-businesses");
        }

        $errors = $this->flashErrors();

        $business_details = Helper::setBusinessDetails();

        $heading = "Register A Brought Forward Loss";

        $tax_years = [
            'year_1' => TaxYearHelper::getCurrentTaxYear(-1),
            'year_2' => TaxYearHelper::getCurrentTaxYear(-2),
            'year_3' => TaxYearHelper::getCurrentTaxYear(-3),
            'year_4' => TaxYearHelper::getCurrentTaxYear(-4)
        ];

        return $this->view("Endpoints/IndividualLosses/bfwd-create.php", compact("heading", "business_details", "errors", "tax_years"));
    }

    public function registerBroughtForwardLoss()
    {
        $business_id = $_SESSION['business_id'] ?? '';
        $type_of_business = $_SESSION['type_of_business'] ?? '';

        if (empty($business_id) || empty($type_of_business)) {
            return $this->redirect("/business-details/list-all-businesses");
        }

        $loss_amount = $this->request->get['loss_amount'] ?? '';

        if (!is_numeric($loss_amount) || $loss_amount < 0) {
            $this->addError("Loss amount must be a positive number");
        }

        $loss_amount = round((float) $loss_amount, 2);

        $loss_year = $this->request->get['loss_year'];

        if (!Validate::tax_year($loss_year)) {
            $this->addError("Select a tax year from the dropdown box");
        }

        $loss_type = $this->request->get['loss_type'] ?? $type_of_business;

        // year to which the data applies
        $tax_year = TaxYearHelper::getNextTaxYear($loss_year);

        if (!empty($this->errors)) {
            return $this->redirect("/individual-losses/create-brought-forward-loss");
        }

        $nino = Helper::getNino();

        $response = $this->apiIndividualLosses->createABroughtForwardLoss($nino, $loss_year, $tax_year,  $loss_type, $business_id, $loss_amount);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $loss_id = $response['loss_id'] ?? "";

        $business_id = $_SESSION['business_id'];

        // don't need to send the loss type - get all losses for the business
        $query_string = http_build_query(compact("business_id", "loss_year"));

        return $this->redirect("/individual-losses/list-brought-forward-losses?$query_string");
    }

    public function listBroughtForwardLosses()
    {
        $loss_year = $this->request->get['loss_year'] ?? TaxYearHelper::getCurrentTaxYear(-1);

        $nino = Helper::getNino();

        $query_params = [];


        $response = $this->apiIndividualLosses->listBroughtForwardLosses($nino, $loss_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $losses = $response['losses'] ?? [];

        $heading = "Pre-MTD Brought Forward Losses";

        $hide_tax_year = true;

        Helper::unsetBusinessSessionInfo();

        // options to change the year of loss
        $tax_years = [
            'year_1' => TaxYearHelper::getCurrentTaxYear(-1),
            'year_2' => TaxYearHelper::getCurrentTaxYear(-2),
            'year_3' => TaxYearHelper::getCurrentTaxYear(-3),
            'year_4' => TaxYearHelper::getCurrentTaxYear(-4)
        ];


        return $this->view("Endpoints/IndividualLosses/bfwd-list.php", compact("losses", "heading", "loss_year", "hide_tax_year", "tax_years"));
    }

    public function editBroughtForwardLoss()
    {
        $loss_id = $this->request->get['loss_id'] ?? '';
        $loss_amount = $this->request->get['loss_amount'] ?? '';
        $loss_year = $this->request->get['loss_year'];

        $query_string = http_build_query(compact("loss_year"));

        if (empty($loss_id) || empty($loss_amount)) {
            Flash::addMessage("Something went wrong. Unable to edit loss", Flash::WARNING);

            return $this->redirect("/individual-losses/list-brought-forward-losses?$query_string");
        }

        $heading = "Edit Brought Forward Loss";

        $errors = $this->flashErrors();

        $hide_tax_year = true;

        return $this->view("Endpoints/IndividualLosses/bfwd-edit.php", compact("heading", "loss_id", "loss_amount", "loss_year",  "errors", "hide_tax_year", "query_string"));
    }

    public function updateBroughtForwardLoss()
    {
        $loss_id = $this->request->get['loss_id'] ?? '';
        $loss_amount = $this->request->get['loss_amount'] ?? '';
        $loss_year = $this->request->get['loss_year'] ?? '';

        if (empty($loss_id) || empty($loss_amount) || empty($loss_year)) {
            Flash::addMessage("Something went wrong. Please try again", Flash::WARNING);
            return $this->redirect("/individual-losses/brought-forward-losses");
        }

        if (!is_numeric($loss_amount)) {
            $this->addError("Loss amount must be a number");
        } elseif ($loss_amount < 0) {
            $this->addError("Loss amount must be 0 or higher");
        }

        if (!empty($this->errors)) {

            $query_string = http_build_query(compact("loss_id", "loss_amount", "loss_year"));
            return $this->redirect("/individual-losses/edit-brought-forward-loss" . "?" . $query_string);
        }

        $nino = Helper::getNino();
        $loss_amount = round((float) $loss_amount, 2);

        // year to which the data applies
        $tax_year = TaxYearHelper::getNextTaxYear($loss_year);

        $response = $this->apiIndividualLosses->amendABroughtForwardLossAmount($nino, $loss_id, $loss_amount, $tax_year);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("Loss amount has been updated", Flash::SUCCESS);
        }

        $query_string = http_build_query(compact("loss_year"));

        return $this->redirect("/individual-losses/list-brought-forward-losses?" . $query_string);
    }

    public function deleteBroughtForwardLoss()
    {
        if (isset($this->request->get['loss_id'])) {

            $loss_id = $this->request->get['loss_id'];
            $loss_year = $this->request->get['loss_year'];

            $heading = "Delete Brought Forward Loss";

            $query_string = http_build_query(compact("loss_year"));

            $hide_tax_year = true;

            return $this->view("Endpoints/IndividualLosses/bfwd-delete.php", compact("heading", "loss_id", "loss_year", "hide_tax_year", "query_string"));
        }

        if (isset($this->request->post['loss_id'])) {

            $loss_id = $this->request->post['loss_id'];
            $loss_year = $this->request->post['loss_year'];

            $nino = Helper::getNino();

            $response = $this->apiIndividualLosses->deleteABroughtForwardLoss($nino, $loss_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === 'success') {
                Flash::addMessage("Loss brought forward has been deleted", Flash::SUCCESS);
            }
        }


        $query_string = http_build_query(compact("loss_year"));

        return $this->redirect("/individual-losses/list-brought-forward-losses?$query_string");
    }

    // ************** end of bfwd losses **************************************

    public function lossClaims()
    {

        $heading = "Losses";

        $business_details = Helper::setBusinessDetails();

        $type_of_business = $_SESSION['type_of_business'] === "self-employment" ? "self-employment" : "property";

        return $this->view("Endpoints/IndividualLosses/loss-claim-index.php", compact("heading", "business_details", "type_of_business"));
    }

    public function createLossClaim()
    {
        $type_of_business = $_SESSION['type_of_business'] === "self-employment" ? "self-employment" : "property";

        $heading = ucwords($type_of_business) . " Loss Claim";

        $business_details = Helper::setBusinessDetails();

        $loss_year = $_SESSION['tax_year'];

        return $this->view("Endpoints/IndividualLosses/loss-claim-create.php", compact("heading", "business_details", "type_of_business", "loss_year"));
    }

    public function processLossClaim()
    {
        $year_claimed_for = $_SESSION['tax_year'];
        $type_of_claim = $this->request->get['type_of_claim'];

        $nino = Helper::getNino();
        $type_of_loss = $_SESSION['type_of_business'];
        $business_id = $_SESSION['business_id'];


        $response = $this->apiIndividualLosses->createALossClaim($nino, $year_claimed_for, $type_of_loss, $type_of_claim, $business_id);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $claim_id = $response['claim_id'] ?? "";

        if (!empty($claim_id)) {
            Flash::addMessage("Loss claim has been registered", Flash::SUCCESS);
        }

        return $this->redirect("/individual-losses/list-loss-claims");
    }

    public function listLossClaims()
    {
        $nino = Helper::getNino();
        $year_claimed_for = $_SESSION['tax_year'];

        $response = $this->apiIndividualLosses->listLossClaims($nino, $year_claimed_for);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $claims = $response['claims'] ?? [];

        $claims_string = json_encode($claims);

        $heading = "Loss Claims";

        $sideways_claim_count = 0;

        foreach ($claims as $claim) {
            if ($claim['typeOfClaim'] === "carry-sideways") {
                $sideways_claim_count += 1;
            }
        }

        return $this->view("Endpoints/IndividualLosses/loss-claims-list.php", compact("heading", "claims", "claims_string", "sideways_claim_count"));
    }

    public function deleteLossClaim()
    {
        if (isset($this->request->get['claim_id'])) {

            $claim_id = $this->request->get['claim_id'] ?? '';
            $type_of_claim = $this->request->get['type_of_claim'] ?? '';
            $business_id = $this->request->get['business_id'] ?? '';
            $type_of_loss = $this->request->get['type_of_loss'] ?? '';
            $tax_year_claimed_for = $this->request->get['tax_year_claimed_for'] ?? '';

            $heading = "Delete Loss Claim";

            $claim_details = [
                'businessId' => $business_id,
                'claimId' => $claim_id,
                'typeOfLoss' => $type_of_loss,
                'typeOfClaim' => $type_of_claim,
                'taxYearClaimedFor' => $tax_year_claimed_for
            ];

            $hide_tax_year = true;

            return $this->view("Endpoints/IndividualLosses/loss-claim-delete.php", compact("heading", "claim_id", "claim_details", "hide_tax_year"));
        }

        if (isset($this->request->post['claim_id'])) {

            $claim_id = $this->request->post['claim_id'];

            $nino = Helper::getNino();

            $tax_year = $_SESSION['tax_year'];

            $response = $this->apiIndividualLosses->deleteALossClaim($nino, $tax_year, $claim_id);

            if ($response['type'] === 'redirect') {
                return $this->redirect($response['location']);
            }

            if ($response['type'] === 'success') {

                Flash::addMessage("Loss claim has been deleted", Flash::SUCCESS);
            }
        }

        return $this->redirect("/individual-losses/list-loss-claims");
    }

    public function editLossClaim()
    {
        $claim_id = $this->request->get['claim_id'] ?? '';
        $type_of_claim = $this->request->get['type_of_claim'] ?? '';
        $business_id = $this->request->get['business_id'] ?? '';
        $type_of_loss = $this->request->get['type_of_loss'] ?? '';
        $tax_year_claimed_for = $this->request->get['tax_year_claimed_for'] ?? '';

        $claim_details = [
            'businessId' => $business_id,
            'claimId' => $claim_id,
            'typeOfLoss' => $type_of_loss,
            'typeOfClaim' => $type_of_claim,
            'taxYearClaimedFor' => $tax_year_claimed_for
        ];

        $heading = "Edit Loss Claim";

        $hide_tax_year = true;

        return $this->view("Endpoints/IndividualLosses/loss-claim-edit.php", compact("heading", "type_of_claim",  "claim_details", "hide_tax_year"));
    }

    public function updateLossClaim()
    {
        $type_of_claim = $this->request->get['type_of_claim'];
        $claim_id = $this->request->get['claim_id'];

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiIndividualLosses->amendALossClaimType($nino, $tax_year, $claim_id, $type_of_claim);

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("Loss claim has been updated", Flash::SUCCESS);
        }

        return $this->redirect("/individual-losses/list-loss-claims");
    }

    public function editLossClaimsSequence()
    {

        $claims = json_decode($this->request->post['claims_string'] ?? '', true);

        // get from session if nothing in post
        if (empty($claims)) {
            $claims =  $_SESSION['loss_claims'] ?? [];
        }

        // redirect if nothing in post or session
        if (empty($claims)) {
            return $this->redirect("/individual-losses/loss-claims");
        }

        // save in session for redirect with errors
        $_SESSION['loss_claims'] = $claims;

        $sideways_claim_count = 0;

        foreach ($claims as $claim) {
            if ($claim['typeOfClaim'] === "carry-sideways") {
                $sideways_claim_count += 1;
            }
        }

        $heading = "Change The Order Of Sideways Loss Claims";

        $errors = $this->flashErrors();

        $hide_tax_year = true;

        return $this->view("Endpoints/IndividualLosses/loss-claims-edit-sequence.php", compact("heading", "claims", "errors", "sideways_claim_count", "hide_tax_year"));
    }

    public function updateLossClaimsSequence()
    {
        $claims = $this->request->post['claims'] ?? [];

        $valid_sequence = Helper::validateSequence($claims);

        if (!$valid_sequence) {
            $this->addError("The sequence must start with 1 and have no gaps.");
            return $this->redirect("/individual-losses/edit-loss-claims-sequence");
        }

        unset($_SESSION['loss_claims']);

        // re-index claims as the index has gaps because it excludes non sideways-relief claims
        $claims = array_values($claims);

        foreach ($claims as &$claim) {
            if (isset($claim['sequence'])) {
                $claim['sequence'] = (int) $claim['sequence'];
            }
        }
        unset($claim);

        $type_of_claim = "carry-sideways";

        $nino = Helper::getNino();

        $tax_year = $_SESSION['tax_year'];

        $response = $this->apiIndividualLosses->amendLossClaimsOrder($nino, $tax_year, $type_of_claim, $claims);


        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        if ($response['type'] === 'success') {
            Flash::addMessage("Loss Claims sequence has been updated", Flash::SUCCESS);
        } else {
            Flash::addMessage("Unable to update sequence", Flash::WARNING);
        }

        return $this->redirect("/individual-losses/list-loss-claims");
    }
}
