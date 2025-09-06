<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use Framework\Csrf;
use Framework\Encryption;
use App\Validate;
use App\Flash;
use App\Helpers\Helper;
use App\Models\Agent;
use App\Models\AgentFirm;
use App\Models\Individual;
use App\Models\User;
use App\Services\UserLogin;
use App\Services\UserMailer;
use App\Services\UserValidator;
use Exception;

class Register extends Controller
{
    public function __construct(private User $user, private Individual $individual, private Agent $agent, private AgentFirm $agent_firm, private UserMailer $user_mailer, private UserValidator $user_validator, private UserLogin $user_login) {}

    private const SPAM_TIME = 3;

    public function new()
    {
        $csrf_token = Csrf::generateToken();

        $heading = "Register";
        $errors = $this->flashErrors();

        $data = $_SESSION['registration_data'] ?? [];
        unset($_SESSION['registration_data']);

        return $this->view("Register/new.php", compact("heading", "errors", "data", "csrf_token"));
    }

    public function newAgent()
    {
        $csrf_token = Csrf::generateToken();

        $heading = "Authorised Tax Agent";
        $errors = $this->flashErrors();

        $data = $_SESSION['registration_data'] ?? [];
        unset($_SESSION['registration_data']);

        return $this->view("Register/new-agent.php", compact("heading", "errors", "data", "csrf_token"));
    }

    public function create()
    {
        if (!Csrf::validateToken($this->request->post['csrf_token'] ?? null)) {
            Flash::addMessage("Invalid form submission. Please try again.", Flash::WARNING);
            return $this->redirect("/register/new");
        }

        unset($_SESSION['csrf_token']);
        unset($this->request->post['csrf_token']);

        $data = array_map("trim", $this->request->post);

        // check if it's spam
        if (time() - $data['start_time'] < self::SPAM_TIME) {
            return $this->redirect("/register/spam");
        }

        if (!empty($data['phone'])) {
            return $this->redirect("/register/spam");
        }

        unset($data['start_time']);
        unset($data['phone']);

        // validate data for individuals and agents
        $data = $this->validateUser($data);

        $role = $data['user_role'] ?? "";

        if (!empty($this->errors)) {

            if ($role === "agent") {
                return $this->redirect("/register/new-agent");
            } else {
                return $this->redirect("/register/new");
            }
        }

        unset($_SESSION['registration_data']);

        // create user in database
        $data['is_active'] = 0;

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

        unset($data['password'], $data['confirm_password']);

        // $data['email_hash'] = Helper::getHash($data['email']);
        // $data['email'] = Encryption::encrypt($data['email']);
        // $data['name'] = Encryption::encrypt($data['name']);

        if ($role === "individual") {

            $this->saveIndividualToDatabase($data);

            if (!empty($this->errors)) {
                return $this->redirect("/register/new");
            }
        }

        if ($role === "agent") {

            $this->saveAgentToDatabase($data);

            if (!empty($this->errors)) {
                return $this->redirect("/register/new-agent");
            }
        }

        // send email
        $email = $data['email'];

        $user = $this->user->findUserBy("email", $email);
        $subject = "Activate Your TaxUpdates Account";
        $template = "Register/activation-email";

        $response = $this->user_mailer->handleEmailing($user, $subject, $template);

        $user_email = $user['email'];

        if (!$response['success']) {
            $this->addError($response['message'] ?? '');
            return $this->redirect("/register/enter-code?email=" . urlencode($user_email));
        } else {
            Flash::addMessage("An activation code has been emailed to you.", Flash::SUCCESS);
        }

        return $this->redirect("/register/enter-code?email=" . urlencode($user_email));
    }

    public function enterCode()
    {
        $retry = $this->request->get['retry'] ?? "";
        $attempts = $this->request->get['attempts'] ?? 0;
        $email =  strtolower(trim($this->request->get['email'] ?? ""));

        $timer = 0;

        if (!empty($retry) && $attempts > 0) {
            $timer = $attempts * 5;
        }

        $errors = $this->flashErrors();

        $heading = "Activate Account";

        return $this->view("Register/enter-code.php", compact("heading", "errors", "timer", "email"));
    }

    public function processResendCode()
    {
        $user_email = strtolower(trim($this->request->get['email'] ?? ""));

        $user = $this->user->findUserBy("email", $user_email);
        $subject = "Activate Your TaxUpdates Account";
        $template = "Register/activation-email";

        if ($user) {

            $response = $this->user_mailer->handleEmailing($user, $subject, $template);

            if (!$response['success']) {
                $this->addError($response['message'] ?? '');
                return $this->redirect("/register/enter-code?email=" . urlencode($user_email));
            }
        }

        Flash::addMessage("Please check your email, a new code has been sent.", Flash::SUCCESS);

        return $this->redirect("/register/enter-code?email=" . urlencode($user_email));
    }

    public function activateAccount()
    {
        // check authentication code
        // activate user
        // save device data

        $authentication_code = trim($this->request->post['authentication_code'] ?? "");

        $user_email = strtolower(trim($this->request->post['email'])) ?? "";

        $user = $this->user->findUserBy("email", $user_email);

        if (!$user) {
            Flash::addMessage("Unable to find your account details. Please try registering again.", Flash::WARNING);
            return $this->redirect("/register/new");
        }

        // find device data
        $user_role = $user['user_role'] ?? '';
        $device_data = $this->request->post['device_data'] ?? "";

        if (!Helper::isDeviceDataValid($device_data)) {
            $this->addError($this->deviceError);
            if ($user_role === "agent") {
                return $this->redirect("/register/new-agent");
            } else {
                return $this->redirect("/register/new");
            }
        } else {
            $_SESSION['device_data'] = $device_data;

            $device_data = json_decode($device_data, true);
        }

        $response = $this->user_validator->checkAuthenticationCode("register", $authentication_code, $user, $device_data);

        if ($response['success']) {
            // log straight in

            Flash::addMessage($response['message'], Flash::SUCCESS);

            // get the updated user
            $updated_user = $this->user->findUserBy("email", $user_email);

            $this->user_login->loginUser($updated_user);

            return $this->redirect("/");
        } else {
            Flash::addMessage($response['message'], Flash::WARNING);
        }

        if ($response['resend']) {

            // resend email
            $subject = "Activate Your TaxUpdates Account";
            $template = "Register/activation-email";
            $this->user_mailer->handleEmailing($user, $subject, $template);
        }

        return $this->redirect($response['location']);
    }

    public function spam()
    {
        $heading = "Registration Failed";

        return $this->view("Register/spam.php", compact("heading"));
    }

    private function validateUser(array $data): array
    {
        // standardise inputs
        $data = Helper::standardiseInputs($data);

        // check inputs
        if (!Validate::string($data['name'] ?? '', 1, 70)) {
            $this->addError("Name must be between 1 and 70 characters");
        }

        if (!Validate::email($data['email'] ?? '')) {
            $this->addError("Email is not in the correct format");
        }

        if (strlen($data['email'] ?? '') > 254) {
            $this->addError("Email address is too long.");
        }

        $role = $data['user_role'] ?? "";
        if (empty($role)) {
            $this->addError("An error occurred, please start again");
        }

        if ($role === "individual") {

            if (!Validate::nino($data['nino'] ?? '')) {
                $this->addError("National Insurance Number is not in the correct format");
            }
        }

        if ($role === "agent") {

            if (!Validate::arn($data['arn'] ?? '')) {
                $this->addError("Agent Services Account Number is not in the correct format");
            }
        }

        if (!Validate::string($data['password'] ?? '', 6, 100)) {
            $this->addError("Password must be between 6 and 100 characters");
        }

        if (!Validate::password_confirmation($data['password'] ?? '', $data['confirm_password'] ?? '')) {
            $this->addError("Passwords do not match");
        }

        if (!empty($this->errors)) {

            $_SESSION['registration_data'] = $data;

            return $data;
        }

        // check email is unique
        $duplicate = false;
        $email = strtolower(trim($data['email']));

        $duplicate = $this->user->findUserBy("email", $email);

        if ($duplicate) {

            if (!$duplicate['is_active']) {

                $this->user->delete($duplicate['id']);
            } else {

                $this->addError("Email address is already registered");
            }
        }

        if (!empty($this->errors)) {

            $_SESSION['registration_data'] = $data;

            return $data;
        }

        // check nino is unique. 
        // Arn doesn't need to be unique as multiple agents can register from one firm
        $duplicate = false;

        if ($role === "individual") {

            $duplicate = $this->individual->searchForNino($data['nino']);

            if ($duplicate) {

                $user = $this->user->find($duplicate['user_id']);

                if ($user && !$user['is_active']) {
                    $this->user->delete($user['id']);
                } else {
                    $this->addError("National Insurance Number is already registered");
                }
            }
        }

        if (!empty($this->errors)) {

            $_SESSION['registration_data'] = $data;
        }

        return $data;
    }

    private function saveIndividualToDatabase(array $data): void
    {
        $individual = [];

        try {

            $this->user->beginTransaction();

            $individual['nino_hash'] = Helper::getHash($data['nino']);

            $individual['nino'] = Encryption::encrypt($data['nino']);

            unset($data['nino']);

            $inserted = $this->user->insert($data);
            if (!$inserted) {
                $error = $this->user->getLastPdoError();
                throw new \Exception("Failed to insert user: " . $error);
            }

            $individual['user_id'] = $this->user->getLastId();

            if (!$individual['user_id']) {
                throw new \Exception("Failed to get last inserted user ID.");
            }

            $inserted = $this->individual->insert($individual);
            if (!$inserted) {
                $error = $this->individual->getLastPdoError();
                throw new \Exception("Failed to insert individual: " . $error);
            }

            $this->user->commit();
        } catch (Exception $e) {

            $this->user->rollBack();

            error_log($e->getMessage());

            $this->addError("An error occurred. Please try again");
        }
    }

    private function saveAgentToDatabase(array $data): void
    {
        // agents have data, agent_data and firm_data
        $agent_data = [];
        $firm_data = [];

        $this->user->beginTransaction();

        try {

            $agent_data['agent_admin'] = 0;

            // check if arn exists in agent_firm table
            $firm = $this->agent_firm->searchForArn($data['arn']);

            // if it doesn't, make this agent the admin and put encrypted arn in agent_data
            if ($firm === false) {
                $agent_data['agent_admin'] = 1;

                $firm_data['arn_hash'] = Helper::getHash($data['arn']);

                $firm_data['arn'] = Encryption::encrypt($data['arn']);
            }

            unset($data['arn']);

            // create the user
            $this->user->insert($data);

            $agent_data['user_id'] = $this->user->getLastId();

            // if firm doesn't already exist, insert it and get id
            if ($firm === false) {

                $this->agent_firm->insert($firm_data);

                $agent_data['agent_firm_id'] = $this->agent_firm->getLastId();
            } else {
                // if it does, get id
                $agent_data['agent_firm_id'] = $firm['id'];
            }
            // create an agent using id and firm id 
            $this->agent->insert($agent_data);

            $this->user->commit();
        } catch (Exception $e) {

            $this->user->rollBack();

            error_log($e->getMessage());

            $this->addError("An error occurred. Please try again");
        }
    }

    // used in ActivateAccount
    private string $deviceError = "Failed to collect the device data that is required by HMRC as a condition of using the service. Please try again. If this happens repeatedly, please see the Privacy Policy for more details and possible solutions, or get in touch.";
}
