<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Validate;
use App\Flash;
use Framework\Controller;
use Framework\Csrf;
use Framework\Encryption;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Agent;
use App\Models\Individual;
use App\Models\UserDevice;
use App\Services\UserLogin;
use App\Services\UserMailer;
use App\Services\UserValidator;

class Session extends Controller
{
    public function __construct(private User $user, private Individual $individual, private Agent $agent, private UserDevice $user_device, private UserMailer $user_mailer, private UserValidator $user_validator, private UserLogin $user_login) {}

    private const LOGIN_ATTEMPTS = 10;
    private const ACCOUNT_LOCK_TIME = 300; //5 ninutes

    public function new()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->redirect("/session/destroy");
        }

        $csrf_token = Csrf::generateToken();

        $heading = "Login";
        $errors = $this->flashErrors();

        $email = $this->request->get['email'] ?? '';

        return $this->view("Session/new.php", compact("heading", "email", "errors", "csrf_token"));
    }

    public function create()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->redirect("/session/destroy");
        }

        if (!Csrf::validateToken($this->request->post['csrf_token'] ?? null)) {
            Flash::addMessage("Invalid form submission. Please try again.", Flash::WARNING);
            return $this->redirect("/session/destroy");
        }

        unset($_SESSION['csrf_token']);
        unset($this->request->post['csrf_token']);

        // get device data
        $device_data = $this->request->post['device_data'] ?? null;
        if (!$device_data) {
            $this->addError($this->deviceError);
        }

        if (!empty($this->errors)) {
            return $this->redirect("/session/new");
        }

        $_SESSION['device_data'] = $device_data;
        unset($this->request->post['device_data']);

        // validate login data
        $data = $this->request->post;

        $data['email'] = strtolower(trim($data['email'] ?? ''));

        if (empty($data['email'])) {
            $this->addError("Email is required");
        }

        if (!Validate::email($data['email'] ?? '')) {
            $this->addError("Email is not in the correct format");
        }

        if (empty(trim($data['password']))) {
            $this->addError("Password is required");
        } else {
            $data['password'] = trim($data['password']);
        }

        $email = $data['email'];

        if (!empty($this->errors)) {
            return $this->redirect("/session/new?email=" . urlencode($email));
        }

        $user = $this->user->findUserBy("email", $email);

        if (!$user) {
            $this->addError("The combination of email and password is not recognised");
        }

        if ($user && !$user['is_active']) {
            $this->addError("Your account has not been activated. Check your email for an activation code, or register again.");
        }

        // limit no. of login attempts
        $last_login_attempt = isset($user['last_login_attempt']) ? strtotime($user['last_login_attempt']) : 0;

        if ($user && $user['login_attempts'] >= self::LOGIN_ATTEMPTS && time() - $last_login_attempt < self::ACCOUNT_LOCK_TIME) {

            $this->user->update([
                'id' => $user['id'],
                'last_login_attempt' => date('Y-m-d H:i:s')
            ]);

            $this->addError("Too many unsuccessful login attempts. Please wait 5 minutes before trying again, or reset your password.");
            return $this->redirect("/session/new?email=" . urlencode($email));
        }

        if (!empty($this->errors)) {

            return $this->redirect("/session/new?email=" . urlencode($email));
        }

        // wrong password
        if (!password_verify($data['password'], $user['password_hash'])) {

            $this->addError("The combination of email and password is not recognised");

            // update unsuccessful attempts
            $this->user->update([
                'id' => $user['id'],
                'login_attempts' => $user['login_attempts'] + 1,
                'last_login_attempt' => date('Y-m-d H:i:s'),
            ]);

            return $this->redirect("/session/new?email=" . urlencode($email));
        }

        // password is verified
        // reset login count
        $this->user->update([
            'id' => $user['id'],
            'login_attempts' => 0,
            'last_login_attempt' => null,
        ]);

        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $new_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            // Save $new_hash to database
            $user_data['id'] = (int)$user['id'];
            $user_data['password_hash'] = $new_hash;
            $this->user->update($user_data);
        }

        // check if device is known
        if (!Helper::isDeviceDataValid($device_data)) {
            $this->addError($this->deviceError);
            return $this->redirect("/session/new");
        }

        $device_data = json_decode($device_data, true);

        $device_id = $device_data['deviceID'];

        $user_id = (int) $user['id'];

        $existing_device = $this->user_device->findDevice($user_id, $device_id);

        // email a code if device is not known
        if (!$existing_device) {

            $subject = "Complete Your TaxUpdates Login";
            $template = "Session/verification-email";
            $email_response = $this->user_mailer->handleEmailing($user, $subject, $template);

            $user_email = $user['email'];

            if (!$email_response['success']) {
                $this->addError($email_response['message'] ?? '');
                return $this->redirect("/session/enter-code?email=" . urlencode($user_email));
            } else {
                Flash::addMessage("A verification code has been emailed to you.", Flash::SUCCESS);
            }

            return $this->redirect("/session/enter-code?email=" . urlencode($user_email));
        }

        // login and redirect
        $this->user_login->loginUser($user);

        $redirect = $_SESSION['redirect'] ?? "/";

        return $this->redirect($redirect);
    }

    public function enterCode()
    {
        $retry = $this->request->get['retry'] ?? "";
        $attempts = $this->request->get['attempts'] ?? 0;
        $email =  $this->request->get['email'] ?? "";

        $timer = 0;

        if (!empty($retry) && $attempts > 0) {
            $timer = $attempts * 5;
        }

        $errors = $this->flashErrors();

        $heading = "Enter Code To Complete Login";

        return $this->view("Session/enter-code.php", compact("heading", "errors", "timer", "email"));
    }

    public function checkVerificationCode()
    {
        $authentication_code = trim($this->request->post['authentication_code'] ?? '');

        // find the user
        $email = strtolower(trim($this->request->post['email']));

        $user = $this->user->findUserBy("email", $email);

        if (!$user) {
            Flash::addMessage("Unable to find your account. Please try to login again.", Flash::WARNING);
            return $this->redirect("/session/new");
        }

        $device_data = $this->request->post['device_data'] ?? [];

        if (!Helper::isDeviceDataValid($device_data)) {
            $this->addError($this->deviceError);
            return $this->redirect("/session/new");
        }

        $_SESSION['device_data'] = $device_data;
        $device_data = json_decode($device_data, true);

        $response = $this->user_validator->checkAuthenticationCode("session", $authentication_code, $user, $device_data);

        if ($response['success']) {
            // log in

            Flash::addMessage($response['message'], Flash::SUCCESS);

            // get the updated user
            $updated_user = $this->user->findUserBy("email", $email);

            $this->user_login->loginUser($updated_user);

            $redirect = $_SESSION['redirect'] ?? "/";

            return $this->redirect($redirect);
        } else {
            Flash::addMessage($response['message'], Flash::WARNING);
        }

        if ($response['resend']) {

            // resend email
            $subject = "Complete Your TaxUpdates Login";
            $template = "Session/verification-email";
            $email_response = $this->user_mailer->handleEmailing($user, $subject, $template);

            if ($email_response['success']) {
                Flash::addMessage($email_response['message'], Flash::SUCCESS);
            } else {
                Flash::addMessage($email_response['message'], Flash::WARNING);
            }
        }

        return $this->redirect($response['location']);
    }

    public function processResendCode()
    {
        $email = strtolower(trim($this->request->get['email'] ?? ""));

        $user = $this->user->findUserBy("email", $email);

        if ($user) {

            $subject = "Complete Your TaxUpdates Login";
            $template = "Session/verification-email";
            $email_response = $this->user_mailer->handleEmailing($user, $subject, $template);

            if ($email_response['success']) {
                Flash::addMessage($email_response['message'], Flash::SUCCESS);
            } else {
                Flash::addMessage($email_response['message'], Flash::WARNING);
            }
        } else {
            Flash::addMessage("Something has gone wrong, please log in again", Flash::WARNING);
        }

        return $this->redirect("/session/enter-code?email=" . urlencode($email));
    }

    public function destroy()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
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

        // Finally, destroy the session.
        session_destroy();

        return $this->redirect("/");
    }

    private string $deviceError = "Failed to collect the device data that is required by HMRC as a condition of using the service. Please try again. If this happens repeatedly, please see the Privacy Policy for more details and possible solutions, or get in touch.";
}
