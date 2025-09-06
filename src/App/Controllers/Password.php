<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use Framework\Csrf;
use App\Models\User;
use App\Helpers\Helper;
use App\Services\UserMailer;
use App\Services\UserValidator;
use App\Validate;
use App\Flash;


class Password extends Controller
{

    public function __construct(private User $user, private UserMailer $user_mailer, private UserValidator $user_validator) {}

    public function reset()
    {
        $errors = $this->flashErrors();

        $csrf_token = Csrf::generateToken();

        $heading = "Reset Password";

        return $this->view("Password/reset.php", compact("heading", "errors", "csrf_token"));
    }

    public function create()
    {
        if (!Csrf::validateToken($this->request->post['csrf_token'] ?? null)) {
            Flash::addMessage("Invalid form submission. Please try again.", Flash::WARNING);
            return $this->redirect("/password/reset");
        }

        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            return $this->redirect("/password/reset");
        }

        if (isset($_SESSION['user_id'])) {
            return $this->redirect("/session/destroy");
        }

        $email = strtolower(trim($this->request->post['email'])) ?? '';

        if (!Validate::email($email)) {
            $this->addError("Email format is not correct");
            return $this->redirect("/password/reset");
        }

        $user = $this->user->findUserBy("email", $email);

        if ($user) {

            $subject = "TaxUpdates Password Reset Code";
            $template = "Password/reset-email";
            $email_response =  $this->user_mailer->handleEmailing($user, $subject, $template);

            if ($email_response['success']) {
                Flash::addMessage($email_response['message'], Flash::SUCCESS);
            } else {
                Flash::addMessage($email_response['message'], Flash::WARNING);
            }
        } else {

            Flash::addMessage("If your email is registered, a password reset code has been emailed to you.", Flash::SUCCESS);
        }

        return $this->redirect("/password/enter-code?email=" . urlencode($email));
    }

    public function enterCode()
    {
        $csrf_token  = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;

        $email = $this->request->get['email'] ?? "";
        $retry = $this->request->get['retry'] ?? "";
        $attempts = $this->request->get['attempts'] ?? 0;

        $timer = 0;

        if (!empty($retry) && $attempts > 0) {
            $timer = $attempts * 5;
        }

        $errors = $this->flashErrors();

        $heading = "Enter Your New Password";

        return $this->view("Password/enter-code.php", compact("heading", "errors", "timer", "email", "csrf_token"));
    }

    public function completeReset()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            return $this->redirect("/password/reset");
        }

        if (!Csrf::validateToken($this->request->post['csrf_token'] ?? null)) {
            Flash::addMessage("Invalid form submission. Please try again.", Flash::WARNING);
            return $this->redirect("/password/reset");
        }

        unset($_SESSION['csrf_token']);
        unset($this->request->post['csrf_token']);

        // check form data
        $email = trim(strtolower($this->request->post['email'] ?? ''));
        $password = trim($this->request->post['password'] ?? '');
        $confirm_password = trim($this->request->post['confirm_password'] ?? '');
        $authentication_code = trim($this->request->post['authentication_code'] ?? '');

        if (empty($email)) {
            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/password/reset");
        }

        if (!Validate::string($password, 6, 100)) {
            $this->addError("Password must be between 6 and 100 characters");
        }

        if (!Validate::password_confirmation($password, $confirm_password)) {
            $this->addError("Passwords do not match");
        }

        if (empty($authentication_code)) {
            $this->addError("Reset code is required");
        }

        if (!empty($this->errors)) {

            return $this->redirect("/password/new-password?email=" . urlencode($email));
        }

        // get user
        $user = $this->user->findUserBy("email", $email);

        if (!$user) {
            Flash::addMessage("An error occurred. Please try again.", Flash::WARNING);
            return $this->redirect("/password/reset");
        }

        // check code and update password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $response = $this->user_validator->checkAuthenticationCode("password", $authentication_code, $user, [], $password_hash);

        if ($response['success']) {
            Flash::addMessage($response['message'], Flash::SUCCESS);
        } else {
            Flash::addMessage($response['message'], Flash::WARNING);
        }

        return $this->redirect($response['location']);
    }

    // when user clicks resend code link
    public function processResendCode()
    {
        $email = strtolower(trim($this->request->get['email'])) ?? '';

        $user = $this->user->findUserBy("email", $email);

        if ($user) {

            $subject = "TaxUpdates Password Reset Code";
            $template = "Password/reset-email";
            $email_response =  $this->user_mailer->handleEmailing($user, $subject, $template);

            if ($email_response['success']) {
                Flash::addMessage($email_response['message'], Flash::SUCCESS);
            } else {
                Flash::addMessage($email_response['message'], Flash::WARNING);
            }
        } else {

            Flash::addMessage("If your email is registered, a password reset code has been emailed to you.", Flash::SUCCESS);
        }

        return $this->redirect("/password/enter-code?email=" . urlencode($email));
    }
}
