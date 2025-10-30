<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use App\Email;

class Admin extends Controller
{

    public function privacyPolicy()
    {
        $heading = "Privacy Policy";

        return $this->view("Admin/privacy-policy.php", compact("heading"));
    }

    public function termsAndConditions()
    {
        $heading = "Terms And Conditions";

        return $this->view("Admin/terms-and-conditions.php", compact("heading"));
    }

    public function contactForm()
    {
        $heading = "Contact";

        $data = $_SESSION['contact_form_data'] ?? [];
        unset($_SESSION['contact_form_data']);

        $errors = $this->flashErrors();

        return $this->view("Admin/contact-form.php", compact("heading", "errors", "data"));
    }

    public function submitForm()
    {
        $data = $this->request->post;

        if (time() - $data['start_time'] < 3) {
            $this->addError("Form submitted too quickly (suspected spam). Please try again");
            return $this->redirect("/admin/contact-form");
        }

        if (!empty($data['phone'])) {
            $this->addError("Spam detected. Please try again");
            return $this->redirect("/admin/contact-form");
        }

        $this->validateForm($data);

        if (!empty($this->errors)) {
            $_SESSION['contact_form_data'] = $data;
            return $this->redirect("/admin/contact-form");
        }

        $emailError = $this->sendEmail($data);

        if ($emailError) {
            $this->addError($emailError);
            $_SESSION['contact_form_data'] = $data;
            return $this->redirect("admin/contact-form");
        } else {
            return $this->redirect("/admin/sent");
        }
    }

    public function sent()
    {
        $heading = "Message Sent";
        return $this->view("Admin/sent.php", compact("heading"));
    }

    private function validateForm(array $data)
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            $this->addError("Please fill in all fields");
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $this->addError("Email is not formatted correctly");
        }

        if (strlen($data['message']) > 300) {
            $this->addError("Message is longer than 300 characters");
        }
    }

    private function sendEmail(array $data): ?string
    {
        $name = htmlspecialchars($data['name'], ENT_QUOTES);
        $email = htmlspecialchars($data['email'], ENT_QUOTES);
        $message = htmlspecialchars($data['message'], ENT_QUOTES);

        $to = "support@taxupdates.co.uk";
        $subject = "TaxUpdates feedback form";
        $html = "<p>Name: {$name}</p><p>Email: {$email}</p><p>Message: {$message}</p>";
        $text = "Name: {$name}; Email: {$email}; Message: {$message}";

        $error = Email::send($subject, $to, $html, $text, $email, $name);

        return $error;
    }
}
