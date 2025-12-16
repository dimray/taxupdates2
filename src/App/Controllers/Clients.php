<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use App\Helpers\AgentHelper;
use App\Helpers\Helper;
use App\Flash;
use App\Helpers\UploadHelper;
use App\Models\Client;
use App\Models\ClientAgent;
use Framework\Encryption;
use App\Validate;

class Clients extends Controller
{
    public function __construct(private Client $client, private ClientAgent $client_agent) {}

    private const CLIENTS_PER_PAGE = 15;

    public function index()
    {
        //update status, view submissions, select clients all come here.
        // this function sets the client session, then redirects to the appropriate place


        unset($_SESSION['client']);

        if (isset($this->request->post)) {
            // set client session
            if (!AgentHelper::setClientSession($this->request->post)) {
                return $this->redirect("/clients/show-clients");
            }
        }

        if (isset($this->request->post['select_client'])) {

            $authorisation = $this->request->post['authorisation'] ?? null;

            $_SESSION['client']['agent_type'] = $authorisation;

            return $this->redirect("/business-details/list-all-businesses?updates=true");
        } elseif (isset($this->request->post['show_submissions'])) {

            return $this->redirect("/submissions/get-submissions");
        } elseif (isset($this->request->post['update_status'])) {

            return $this->redirect("/clients/update-status");
        }

        return $this->redirect("/clients/show-clients");
    }

    public function showClients()
    {
        Helper::clearUpSession();
        unset($_SESSION['client']);

        $firm_id = $_SESSION['firm_id'];
        $clients = [];
        $pagination = [];
        $search_result = false;
        $heading = "Clients";
        $errors = $this->flashErrors();

        if (!empty($_SESSION['client_search_result'])) {
            $clients = [$_SESSION['client_search_result']];
            unset($_SESSION['client_search_result']);
            $search_result = true;
        }

        if (!$search_result) {

            // if no search result, return all clients
            $total_clients = $this->client_agent->countClientsForAgent($firm_id);

            if ($total_clients > 0) {

                $pagination = Helper::paginate($total_clients, self::CLIENTS_PER_PAGE, $this->request->get ?? []);

                $offset = $pagination['offset'];

                $clients = $this->client_agent->findClientsForAgent($firm_id, self::CLIENTS_PER_PAGE, $offset);
            }
        }

        foreach ($clients as &$client) {
            $client['nino'] = Encryption::decrypt($client['nino']);
        }


        return $this->view("Clients/show.php", compact("heading", "clients", "pagination", "search_result", "errors"));
    }

    public function findClient()
    // links to search form in show view
    {
        $search_nino = $this->request->post['search_nino'] ?? null;

        $firm_id = $_SESSION['firm_id'];

        if ($search_nino) {
            $search_nino = trim(strtoupper($search_nino));
            $nino_hash = Helper::getHash($search_nino);

            $search_result = $this->client_agent->findClientByNino($nino_hash, $firm_id);

            if ($search_result) {
                $_SESSION['client_search_result'] = $search_result;
            } else {
                $this->addError("No match found");
            }
        }

        return $this->redirect("/clients/show-clients");
    }

    public function addClients()
    {
        $errors = $this->flashErrors();

        $heading = "Add Clients";

        return $this->view("Clients/add.php", compact("heading", "errors"));
    }

    public function uploadClients()
    {
        unset($_SESSION['client']);

        $errors = $this->flashErrors();

        $heading  = "Upload Clients";

        return $this->view("Clients/client-upload.php", compact("heading", "errors"));
    }

    // processed here instead of in uploads to avoid having to save long client list in session
    public function processUpload()
    {
        $clients = [];

        if (isset($this->request->post['pasted_data'])) {

            $pasted_data = trim($this->request->post['pasted_data']);

            $errors = UploadHelper::processPasteErrors($pasted_data, 100, 2);

            if (!empty($errors)) {

                $_SESSION['errors'] = $errors;
                return $this->redirect("/clients/upload-clients");
            }

            $clients = UploadHelper::parseDataToStructuredArray($pasted_data, ['name', 'nino']);
        }

        if (isset($this->request->files['csv_upload'])) {

            $file = $this->request->files['csv_upload'] ?? null;

            $errors = UploadHelper::processCsvErrors($file, 100, 2);

            if (!empty($errors)) {

                $_SESSION['errors'] = $errors;
                return $this->redirect("/clients/upload-clients");
            }

            $clients = UploadHelper::parseClientCsv($file);
        }


        if (empty($clients)) {
            $this->addError("No valid data found, please check your file and try again");
            return $this->redirect("/clients/upload-clients");
        };

        // delete first line if nino doesn't validate (it's probably a heading)
        if (!Validate::nino($clients[0]['nino'])) {
            array_shift($clients);
        }



        $valid_clients = $this->validateClients($clients);


        if (empty($valid_clients)) {
            return $this->redirect("/clients/upload-clients");
        }

        return $this->processClients($valid_clients);
    }

    public function updateClients()
    {
        // ensure post array has been submitted
        if (!isset($this->request->post['clients'])) {
            $this->addError("Enter client details before submitting");
            return $this->redirect("/clients/add-clients");
        }
        // array_values normalises the array sequencing, if rows have been deleted
        $submitted_clients = array_values($this->request->post['clients']);

        // Filter out any rows where both name and nino are empty
        $clients = array_filter($submitted_clients, function ($client) {
            return !empty($client['name']) || !empty($client['nino']);
        });

        // Now, check if the filtered array is empty
        if (empty($clients)) {
            $this->addError("Enter client details before submitting");
            return $this->redirect("/clients/add-clients");
        }

        $valid_clients = $this->validateClients($clients);

        if (empty($valid_clients)) {
            return $this->redirect("/clients/add-clients");
        }

        return $this->processClients($valid_clients);
    }

    private function processClients(array $clients)
    {
        // client names not encrypted, as if encrypted they can't be sorted for display

        $firm_id = $_SESSION['firm_id'];

        foreach ($clients as $client) {

            $nino_hash = Helper::getHash($client['nino']);
            $nino = Encryption::encrypt($client['nino']);

            // check client table to make sure nino remains unique
            $existing = $this->client->findUserBy('nino_hash', $nino_hash);

            if ($existing) {
                $client_id = (int) $existing['id'];
            } else {

                $client_array = ['nino' => $nino, 'nino_hash' => $nino_hash];
                $this->client->insert($client_array);
                $client_id = (int) $this->client->getLastId();
            }



            // if client is already linked to this firm, update details if they don't match
            if ($this->client_agent->checkDuplicate($client_id, $firm_id)) {

                $existing_client = $this->client_agent->findClientForAgent($firm_id, $client_id);

                if ($existing_client) {

                    $stored_name = $existing_client['client_name'];
                    $submitted_name = trim(ucwords($client['name']));

                    if ($stored_name !== $submitted_name) {
                        $this->client_agent->updateClientDetails($firm_id, $client_id, [
                            'client_name' => $submitted_name
                        ]);
                    }
                }

                continue;
            }

            // if client doesn't already exist, insert details

            $client_name = trim(ucwords($client['name']));

            $client_agent_data = [
                'client_id' => (int) $client_id,
                'agent_firm_id' => $firm_id,
                'client_name' => $client_name
            ];

            $this->client_agent->insert($client_agent_data);
        }

        return $this->redirect("/clients/show-clients");
    }

    public function confirmDelete()
    {
        $client_id = $this->request->post['client_id'] ?? '';
        $client_name = $this->request->post['client_name'] ?? '';

        if (empty($client_id) || empty($client_name)) {
            return $this->redirect("/clients/show-clients");
        }

        $heading = "Delete Client";


        return $this->view("Clients/confirm-delete.php", compact("heading", "client_id", "client_name"));
    }

    public function delete()
    {
        if (isset($this->request->post['client_id'])) {

            $client_id = $this->request->post['client_id'];

            $firm_id = $_SESSION['firm_id'];

            // delete client_agent link
            $this->client_agent->deleteClientForAgent($firm_id, $client_id);

            // check if client is linked with any other agents.
            $count = $this->client_agent->countClientAgentLinks($client_id);

            // If no other links, delete client completely
            if ($count === 0) {
                $this->client->delete($client_id);
            }
        } else {
            Flash::addMessage("Unable to delete client", Flash::WARNING);
        }

        return $this->redirect("/clients/show-clients");
    }

    private function validateClients(array $clients): array
    {
        $valid_clients = [];

        foreach ($clients as  $client) {

            $name = trim(ucwords($client['name'] ?? ''));
            $nino = trim(strtoupper($client['nino'] ?? ''));

            if (empty($name) && empty($nino)) {
                continue;
            }

            if (empty($name)) {
                $this->addError("Name is required");
                continue;
            }

            if (empty($nino)) {
                $this->addError("Client '$name' not saved. NI Number is required.");
                continue;
            }

            if (!Validate::nino($nino)) {
                $this->addError("Client '$name' not saved. NI Number $nino is not valid.");
                continue;
            }

            if (!Validate::string($name, 1, 70)) {
                $this->addError("Client '$name' not saved. Name must be between 1 and 70 characters.");
                continue;
            }

            $valid_clients[] = [
                'name' => $name,
                'nino' => $nino
            ];
        }

        return $valid_clients;
    }

    public function updateStatus()
    {
        $heading = "Check Or Update Client Status";
        return $this->view("Clients/update-status.php", compact("heading"));
    }
}
