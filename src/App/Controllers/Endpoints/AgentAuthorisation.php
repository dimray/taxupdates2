<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\Flash;
use Framework\Controller;
use App\Validate;
use App\Models\ClientAgent;
use App\HmrcApi\Endpoints\ApiAgentAuthorisation;

class AgentAuthorisation extends Controller
{

    public function __construct(private ApiAgentAuthorisation $apiAgentAuthorisation, private ClientAgent $client_agent) {}

    public function unauthorised()
    {
        if (isset($_SESSION['client']['name'])) {
            $heading = "HMRC Agent Authorisation For " . $_SESSION['client']['name'];
        } else {
            $heading = "HMRC Agent Authorisation";
        }

        return $this->view("Endpoints/AgentAuthorisation/unauthorised.php", compact("heading"));
    }

    public function requestNewAuthorisation()
    {
        $client_name = $_SESSION['client']['name'];
        $heading = "Request Authorisation for " . $client_name;

        $errors = $this->flashErrors();

        return $this->view(
            "Endpoints/AgentAuthorisation/request-new-authorisation.php",
            compact("heading", "errors")
        );
    }

    public function createNewAuthorisation()
    {
        $postcode = $this->request->get['postcode'] ?? "";
        $postcode = strtoupper((string) $postcode);

        if (!Validate::postcode($postcode)) {
            $this->addError("Postcode format is not correct");
        }

        $agent_type = $this->request->get['agent_type'] ?? "";

        if (empty($agent_type)) {
            $this->addError("An agent type must be selected");
        }

        if (!empty($this->errors)) {
            return $this->redirect("/agent-authorisation/request-new-authorisation");
        }

        $arn = $_SESSION['arn'];

        $nino = $_SESSION['client']['nino'];

        $response = $this->apiAgentAuthorisation->createNewAuthorisation($arn, $nino, $postcode, $agent_type);

        if ($response['type'] === 'success' && !empty($response['location'])) {

            $location = $response['location'];

            $parts = explode('/', $location);
            $invitation_id = end($parts);

            $query_string = http_build_query(compact("invitation_id"));

            return $this->redirect("/agent-authorisation/get-invitation-by-id?$query_string");
        }

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        // if response type is error
        return $this->redirect("/agent-authorisation/request-new-authorisation");
    }

    public function getInvitationById()
    {
        $invitation_id = $this->request->get['invitation_id'] ?? '';

        if (empty($invitation_id)) {
            Flash::addMessage("An error occurred, please try again", Flash::WARNING);
            return $this->redirect("/agent-authorisation/request-new-authorisation");
        }

        $arn = $_SESSION['arn'];

        $response = $this->apiAgentAuthorisation->getAnInvitationById($arn, $invitation_id);

        if ($response['type'] === 'success' && !empty($response['url'])) {

            $invitation_url = $response['url'];

            $query_string = http_build_query(compact("invitation_url", "invitation_id"));

            return $this->redirect("/agent-authorisation/show-invitation-url?" . $query_string);
        }

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        // if error or url is empty
        return $this->redirect("/agent-authorisation/list-authorisation-requests");
    }

    public function showInvitationUrl()
    {
        $invitation_id = $this->request->get['invitation_id'] ?? '';
        $invitation_url = $this->request->get['invitation_url'] ?? '';

        if (empty($invitation_id) || empty($invitation_url)) {
            return $this->redirect("/agent-authorisation/list-authorisation-requests");
        }

        $heading = "Client Link for " . $_SESSION['client']['name'];

        return $this->view("Endpoints/AgentAuthorisation/show-client-link.php", compact("heading", "invitation_id", "invitation_url"));
    }

    public function listAuthorisationRequests()
    {
        $arn = $_SESSION['arn'];

        $response = $this->apiAgentAuthorisation->getAllAuthorisationRequests($arn);

        $requests = [];

        if ($response['code'] === 204) {
            Flash::addMessage("No authorisation requests found from the last 30 days", Flash::INFO);
        } elseif ($response['type'] === "success" && $response['code'] === 200) {

            if (!empty($response['requests'])) {
                foreach ($response['requests'] as $request) {
                    if (strtolower($request['status']) !== "cancelled") {

                        $href = $request['_links']['self']['href'] ?? '';
                        $parts = explode('/', $href) ?? '';
                        // get the last part of the URL
                        $request_id = end($parts) ?? '';

                        $requests[] = [
                            'created' => $request['created'] ?? '',
                            'expires' => $request['expiresOn'] ?? '',
                            'agent_type' => $request['agentType'] ?? '',
                            'request_id' => $request_id,
                            'client_url' => $request['clientActionUrl'] ?? '',
                            'status' => $request['status']
                        ];
                    }
                }
            }
        } elseif ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $heading = "Agent Authorisation Requests";

        return $this->view("Endpoints/AgentAuthorisation/show-all-requests.php", compact("heading", "requests"));
    }

    public function confirmCancelInvitation()
    {
        $invitation_id = $this->request->get['invitation_id'];

        $heading = "Cancel Client Invitation";

        $hide_tax_year = true;

        return $this->view("Endpoints/AgentAuthorisation/cancel-request.php", compact("heading", "invitation_id", "hide_tax_year"));
    }

    public function cancelInvitation()
    {
        $invitation_id = $this->request->get['invitation_id'] ?? "";

        if (empty($invitation_id)) {
            return $this->redirect("/agent-authorisation/list-authorisation-requests");
        }

        $arn = $_SESSION['arn'];

        $response = $this->apiAgentAuthorisation->cancelAnInvitationById($arn, $invitation_id);

        if ($response['type'] === "success") {
            Flash::addMessage("Invitation $invitation_id has been cancelled", Flash::SUCCESS);
        }

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        return $this->redirect("/agent-authorisation/list-authorisation-requests");
    }

    public function requestStatusOfRelationship()
    {
        $nino = $_SESSION['client']['nino'] ?? "";
        $client_name = $_SESSION['client']['name'] ?? "";

        if (empty($nino) || empty($client_name)) {
            return $this->redirect("/clients/show-clients");
        }

        $heading = "Get Client Relationship Status for " . $client_name;

        $errors = $this->flashErrors();

        return $this->view("Endpoints/AgentAuthorisation/request-status.php", compact("heading", "nino", "errors"));
    }

    public function getStatusOfRelationship()
    {
        $postcode = $this->request->get['postcode'] ?? "";

        $postcode = strtoupper((string) $postcode);

        if (!Validate::postcode($postcode)) {
            $this->addError("Postcode is not in the correct format");
        }

        $agent_type = $this->request->get['agent_type'] ?? "";

        if (empty($agent_type)) {
            $this->addError("Agent type must be selected");
        }

        if (!empty($this->errors)) {
            return $this->redirect("/agent-authorisation/request-status-of-relationship");
        }

        $arn = $_SESSION['arn'];
        $nino = $_SESSION['client']['nino'];

        $response = $this->apiAgentAuthorisation->getStatusOfRelationship($arn, $nino, $postcode, $agent_type);

        $authorised = false;

        if ($response['type'] === 'success') {

            $authorised = true;

            $agent_id = (int) $_SESSION['firm_id'];
            $client_id = (int) $_SESSION['client']['id'];

            $this->client_agent->updateAuthorisation($agent_type, $agent_id, $client_id);

            Flash::addMessage("You are authorised to act for " . $_SESSION['client_name'] . " as " . $agent_type . " agent.", Flash::SUCCESS);
        }

        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $heading = "Relationship status for " . $_SESSION['client']['name'];

        return $this->view("Endpoints/AgentAuthorisation/show-status.php", compact("heading", "authorised"));
    }

    public function updateAuthorisationOnError()
    {
        // on api call giving 'client_or_agent_not_authorised' response:
        // update status
        // return to let agent request status

        $agent_type = null;
        $agent_id = (int)$_SESSION['firm_id'] ?? "";
        $client_id = (int)$_SESSION['client']['id'] ?? "";

        if (empty($agent_id) || empty($client_id)) {
            return $this->redirect("/clients/show-clients");
        }

        $this->client_agent->updateAuthorisation($agent_type, $agent_id, $client_id);

        return $this->redirect("/agent-authorisation/request-status-of-relationship");
    }
}
