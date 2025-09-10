<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use App\Helpers\Helper;
use App\Models\Agent;
use App\Models\AgentFirm;
use App\Models\Client;
use App\Models\ClientAgent;
use App\Models\User;
use App\Flash;
use App\Email;
use Exception;

class Firm extends Controller
{

    public function __construct(private User $user, private Client $client, private ClientAgent $client_agent, private Agent $agent, private AgentFirm $agent_firm) {}

    // any agent can view firm
    // agents can already edit their own profiles
    // admin can delete agents
    // admin can transfer admin to somebody else
    // admin can delete firm

    public function showFirm()
    {
        unset($_SESSION['client']);

        $is_admin = $this->agent->isAdmin($_SESSION['user_id']);

        $firm_id = $_SESSION['firm_id'] ?? '';

        if (empty($firm_id)) {
            return $this->redirect("/logout");
        }

        // counts only active agents
        $total_agents = $this->agent->countAgentsFromFirm($firm_id);

        $agents_per_page = 15;

        $pagination = Helper::paginate($total_agents, $agents_per_page, $this->request->get ?? []);

        $offset = $pagination['offset'];

        $agents = $this->agent->getAgentsArray($firm_id, $agents_per_page, $offset);

        $heading = "Your Firm";

        return $this->view("Firm/show.php", compact("heading", "is_admin", "agents", "pagination"));
    }

    public function deleteAgent()
    {
        // pass to confirm delete if comes from get request
        if (isset($this->request->get['agent_user_id'])) {

            $query_string = http_build_query(["agent_user_id" => $this->request->get['agent_user_id']]);

            return $this->redirect("/firm/confirm-delete-agent?$query_string");
        }

        // delete if comes from post request
        if (isset($this->request->post['agent_user_id'])) {

            $agent_user_id = (int)$this->request->post['agent_user_id'];

            $password = $this->request->post['password'] ?? '';

            $user = $this->user->find($_SESSION['user_id']);

            if (password_verify($password, $user['password_hash'])) {

                $agent_to_delete = $this->user->find($agent_user_id);

                $this->user->delete($agent_to_delete['id']);

                $name = $agent_to_delete['name'] ?? 'agent';

                $email = $agent_to_delete['email'] ?? "email@example.com";

                $this->sendAgentDeletedEmail($name, $email);

                Flash::addMessage("$name has been deleted", Flash::SUCCESS);
                return $this->redirect("/firm/show-firm");
            } else {

                $this->addError("Password is not correct");

                $query_string = http_build_query(["agent_user_id" => $agent_user_id]);

                return $this->redirect("/firm/confirm-delete-agent?$query_string");
            }
        }

        return $this->redirect("/firm/show-firm");
    }

    public function confirmDeleteAgent()
    {

        if (isset($this->request->get['agent_user_id'])) {
            $agent_user_id = $this->request->get['agent_user_id'];

            $agent_to_delete = $this->user->find((int) $agent_user_id);

            $name = $agent_to_delete['name'] ?? $agent_user_id;

            $heading = "Remove $name";

            $errors = $this->flashErrors();

            return $this->view("Firm/delete-agent.php", compact("heading", "name", "agent_user_id", "errors"));
        }

        return $this->redirect("/firm/show-firm");
    }

    public function transferAdmin()
    {

        // pass to confirm transfer if comes from get request
        if (isset($this->request->get['agent_user_id'])) {

            $query_string = http_build_query(["agent_user_id" => $this->request->get['agent_user_id']]);

            return $this->redirect("/firm/confirm-transfer-admin?$query_string");
        }

        // transfer admin if comes from post request
        if (isset($this->request->post['agent_user_id'])) {

            $password = $this->request->post['password'] ?? '';

            $user = $this->user->find($_SESSION['user_id']);

            if (password_verify($password, $user['password_hash'])) {

                $agent_to_make_admin = (int) $this->request->post['agent_user_id'];

                $agent_to_remove_admin = $_SESSION['user_id'];

                $remove_admin_array = [
                    "id" => $agent_to_remove_admin,
                    "agent_admin" => 0
                ];

                $add_admin_array = [
                    "id" => $agent_to_make_admin,
                    "agent_admin" => 1
                ];

                $this->agent->beginTransaction();

                try {

                    $this->agent->update($remove_admin_array);

                    $this->agent->update($add_admin_array);

                    $this->agent->commit();
                } catch (Exception $e) {

                    $this->agent->rollBack();

                    error_log("Admin transfer failed" . $e->getMessage());

                    Flash::addMessage("Admin transfer failed. Please try again", Flash::WARNING);

                    return $this->redirect("/firm/show-firm");
                }

                $new_admin = $this->user->find($agent_to_make_admin);

                $name = $new_admin['name'];
                $email = $new_admin['email'];

                $this->sendNewAdminEmail($name, $email);

                Flash::addMessage("Agent role has been updated", Flash::SUCCESS);
            } else {
                $this->addError("Password is not correct");

                $query_string = http_build_query(["agent_user_id" => $this->request->post['agent_user_id']]);

                return $this->redirect("/firm/confirm-transfer-admin?$query_string");
            }

            return $this->redirect("/firm/show-firm");
        }

        return $this->redirect("/firm/show-firm");
    }

    public function confirmTransferAdmin()
    {
        if (isset($this->request->get['agent_user_id'])) {

            $agent_user_id = $this->request->get['agent_user_id'];

            $agent_to_make_admin = $this->user->find((int) $agent_user_id);

            $name = $agent_to_make_admin['name'];

            $heading = "Change Admin";

            $errors = $this->flashErrors();

            return $this->view("Firm/transfer-admin.php", compact("heading", "name", "agent_user_id", "errors"));
        }

        return $this->redirect("/firm/show-firm");
    }

    public function confirmDeleteFirm()
    {

        $user_id = $_SESSION['user_id'];

        if (!$this->agent->isAdmin($user_id)) {
            Flash::addMessage("Only the Firm Admin can delete the firm", FLASH::INFO);
            return $this->redirect("/firm/show-firm");
        }

        $firm_id = (int) $this->agent->getFirmId($user_id);

        // only count active users ***
        if ($this->agent->countAgentsFromFirm($firm_id) > 1) {
            Flash::addMessage("Firm cannot be deleted while other agents are associated with it. Please delete all other agents apart from the Admin before deleting the firm.");
            return $this->redirect("/firm/show-firm");
        }

        $heading = "Delete Firm";

        $arn = $_SESSION['arn'];

        $errors = $this->flashErrors();

        return $this->view("/firm/delete-firm.php", compact("heading", "arn", "errors"));
    }


    public function deleteFirm()
    {


        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $password = $this->request->post['password'] ?? '';

            $user = $this->user->find($_SESSION['user_id']);

            if (password_verify($password, $user['password_hash'])) {

                $user_id = $_SESSION['user_id'];
                $firm_id = (int) $this->agent->getFirmId($user_id);

                // delete any inactive agents associated with the firm.
                $this->agent->deleteInactiveAgents($firm_id);

                $this->user->beginTransaction();

                try {

                    // delete the firm first, as deleting user deletes the last agent, and causes SQL issues in agent_firm.
                    $this->agent_firm->delete($firm_id);

                    $this->user->delete($user_id);

                    $this->user->commit();
                } catch (Exception $e) {

                    $this->user->rollBack();

                    error_log("Firm delete failed" . $e->getMessage());

                    Flash::addMessage("Firm delete failed. Please try again", Flash::WARNING);

                    return $this->redirect("/firm/show-firm");
                }

                $this->client->deleteOrphanedClients();

                return $this->redirect("/logout");
            } else {
                $this->addError("Password is not correct");

                return $this->redirect("/firm/confirm-delete-firm");
            }
        }

        return $this->redirect("/firm/show-firm");
    }

    private function sendAgentDeletedEmail(string $name, string $email)
    {
        $subject = "TaxUpdates Profile Removed";
        $html = $this->viewer->renderEmail("Firm/agent-deleted-email.html", compact("name"));
        $text = $this->viewer->renderTextEmail("Firm/agent-deleted-email.txt", compact("name"));

        Email::send($subject, $email, $html, $text);
    }

    private function sendNewAdminEmail(string $name, string $email)
    {
        $subject = "TaxUpdates Account - You Are Admin";
        $html = $this->viewer->renderEmail("Firm/new-admin-email.html", compact("name"));
        $text = $this->viewer->renderTextEmail("Firm/new-admin-email.txt", compact("name"));

        Email::send($subject, $email, $html, $text);
    }
}
