<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use Framework\Encryption;
use App\Helpers\TaxYearHelper;
use App\Helpers\Helper;
use App\Models\Agent;
use App\Models\User;
use App\Models\Submission;
use App\Services\UserMailer;
use App\Services\UserValidator;
use App\Validate;
use App\Flash;

class Profile extends Controller
{

    public function __construct(private Agent $agent, private User $user, private UserMailer $user_mailer, private UserValidator $user_validator, private Submission $submission) {}

    public function showProfile()
    {
        $profile =  $this->getProfile();

        $is_admin = false;

        if ($_SESSION['user_role'] === "agent") {
            unset($_SESSION['nino']);
            $is_admin = $this->agent->isAdmin($_SESSION['user_id']);
        }

        // needed for submissions?
        $_SESSION['tax_year'] = TaxYearHelper::getCurrentTaxYear();

        $heading = "Profile";

        $hide_client_options_in_header = false;

        if ($_SESSION['user_role'] === "agent") {
            $hide_client_options_in_header = true;
        }

        return $this->view("Profile/show.php", compact("heading", "is_admin", "profile", "hide_client_options_in_header"));
    }

    private function getProfile(): array
    {
        $user_id = $_SESSION['user_id'];

        $profile = [];

        $user = $this->user->find($user_id);

        if ($user) {

            $profile['name'] = Encryption::decrypt($user['name']);
            $profile['email'] = Encryption::decrypt($user['email']);

            if (isset($_SESSION['nino'])) {
                $profile['nino'] = $_SESSION['nino'];
            } elseif (isset($_SESSION['arn'])) {
                $profile['arn'] = $_SESSION['arn'];
            }
        }

        return $profile;
    }

    public function editName()
    {
        $name = $this->request->get['name'] ?? "";

        if (empty($name)) {
            return $this->redirect("/profile/show-profile");
        }

        $heading = "Edit Name";

        $errors = $this->flashErrors();

        // stop agent seeing client menu
        if ($_SESSION['user_role'] === "agent") {
            unset($_SESSION['nino']);
        }

        return $this->view("Profile/edit-name.php", compact("heading", "errors", "name"));
    }

    public function updateName()
    {
        $name = trim(ucwords((string)$this->request->post['name'])) ?? '';

        if (!Validate::string($name, 1, 70)) {
            $this->addError("Name must be between 1 and 70 characters");
        }

        if (!empty($this->errors)) {
            return $this->redirect("/profile/edit-name");
        }

        $data['id'] = $_SESSION['user_id'];
        $data['name'] = Encryption::encrypt($name);

        $this->user->update($data);

        return $this->redirect("/profile/show-profile");
    }

    public function editEmail()
    {
        $email = $this->request->get['email'] ?? "";

        $heading = "Update Email";

        $errors = $this->flashErrors();

        // stop agent seeing client menu
        if ($_SESSION['user_role'] === "agent") {
            unset($_SESSION['nino']);
        }

        return $this->view("Profile/edit-email.php", compact("heading", "errors", "email"));
    }

    public function generateAndSendCode()
    {
        $new_email = trim(strtolower($this->request->post['new_email'])) ?? '';

        // check email format
        if (!Validate::email($new_email)) {

            $this->addError("Email is not in the correct format");

            return $this->redirect("/profile/edit-email");
        }

        // check email is unique
        $duplicate = false;
        $email_hash = Helper::getHash($new_email);
        $duplicate = $this->user->findUserBy("email_hash", $email_hash);

        if ($duplicate) {

            $this->addError("Email address is already registered");

            return $this->redirect("/profile/edit-email");
        }

        $user = $this->user->find($_SESSION['user_id']);

        if ($user) {

            $encrypted_email = Encryption::encrypt($new_email);

            // save the new email in database
            $this->user->update([
                'id' => $_SESSION['user_id'],
                'new_email' => $encrypted_email
            ]);

            // ensure code is sent to the new email address
            $user['email'] = $encrypted_email;

            $subject = "TaxUpdates - Confirm Email Address";
            $template = "Profile/email-reset-email";

            $email_response =  $this->user_mailer->handleEmailing($user, $subject, $template);

            if ($email_response['success']) {
                Flash::addMessage($email_response['message'], Flash::SUCCESS);
            } else {
                Flash::addMessage($email_response['message'], Flash::WARNING);
            }
        } else {
            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/profile/show-profile");
        }

        return $this->redirect("/profile/enter-code");
    }

    public function enterCode()
    {
        $retry = $this->request->get['retry'] ?? "";
        $attempts = $this->request->get['attempts'] ?? 0;
        $timer = 0;

        if (!empty($retry) && $attempts > 0) {
            $timer = $attempts * 5;
        }

        $heading = "Enter Code";

        $errors = $this->flashErrors();

        // stop agent seeing client menu
        if ($_SESSION['user_role'] === "agent") {
            unset($_SESSION['nino']);
        }

        return $this->view("Profile/enter-code.php", compact("heading", "errors", "timer"));
    }

    public function updateEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {

            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/profile/show-profile");
        }

        $authentication_code = trim($this->request->post['authentication_code'] ?? "");

        if (empty($authentication_code)) {
            $this->addError("Reset code is required.");
            return $this->redirect("/profile/enter-code");
        }

        $password = trim($this->request->post['password'] ?? '');

        $user = $this->user->find($_SESSION['user_id']);

        if (!$user) {
            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/profile/show-profile");
        }

        // check password is correct
        if (!password_verify($password, $user['password_hash'])) {

            $this->addError("Password is not correct");
            return $this->redirect("/profile/enter-code");
        }

        if (empty($user['authentication_code'])) {
            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/profile/show-profile");
        }

        // check code and update email address
        $encrypted_new_email = $user['new_email'] ?? '';

        // var_dump(Encryption::decrypt($encrypted_new_email));
        // exit;

        if (empty($encrypted_new_email)) {
            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/profile/show-profile");
        }

        $unencrypted_new_email = Encryption::decrypt($encrypted_new_email);
        $new_email_hash = Helper::getHash($unencrypted_new_email);

        $response = $this->user_validator->checkAuthenticationCode("profile", $authentication_code, $user, [], "", $encrypted_new_email, $new_email_hash);

        if ($response['success']) {
            Flash::addMessage($response['message'], Flash::SUCCESS);
        } else {
            Flash::addMessage($response['message'], Flash::WARNING);
        }

        return $this->redirect($response['location']);
    }

    public function confirmDeleteProfile()
    {
        // check if agent_admin
        $agent_admin = false;

        if ($_SESSION['user_role'] === "agent") {
            $agent_admin = $this->agent->isAdmin($_SESSION['user_id']);
        }

        $heading = "Delete Account";

        $errors = $this->flashErrors();

        return $this->view("Profile/confirm-delete.php", compact("heading", "agent_admin", "errors"));
    }

    public function deleteProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {

            return $this->redirect("/profile/show-profile");
        }

        $user = $this->user->find($_SESSION['user_id']);


        $user_id = $user['id'];

        $password = trim($this->request->post['password']);

        if (password_verify($password, $user['password_hash'])) {

            $role = $_SESSION['user_role'];

            // redirect firm admins
            if ($role === "agent" && $this->agent->isAdmin($user_id)) {

                return $this->redirect("/firm/delete-firm");
            }

            $this->user->delete($user_id);

            // don't delete agent submissions, they are only deleted with the firm
            if ($role === "individual") {

                $this->submission->deleteUserSubmissions($user_id);
            }

            // delete the session here, as it wasn't working with a redirect to session/destroy
            $_SESSION = array();

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            session_destroy();

            return $this->redirect("/");
        } else {

            $this->addError("Password is not correct");

            return $this->redirect("/profile/delete-profile");
        }

        return $this->redirect("/profile/show-profile");
    }
}
