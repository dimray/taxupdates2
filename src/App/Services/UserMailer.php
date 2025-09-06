<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Framework\Encryption;
use App\Email;
use Framework\Viewer;

class UserMailer
{
    public function __construct(private User $user, private Viewer $viewer) {}

    private const EMAIL_COOLDOWN = 60;
    private const MAX_EMAILS = 7;
    private const EMAIL_RESET_WINDOW = 43200; // 12 hours

    public function handleEmailing(array $user, string $subject, string $template): array
    {
        $user_id = (int) $user['id'];
        $user_email = $user['email'];
        $user_name = $user['name'];

        $last_sent = isset($user['last_email_sent_at']) ? strtotime($user['last_email_sent_at']) : 0;
        $send_count = (int) $user['email_send_count'] ?? 0;

        if ($last_sent > time() - self::EMAIL_COOLDOWN) {
            return [
                'success' => false,
                'message' => 'A code has just been sent. Please wait one minute before requesting another.'
            ];
        }

        // reset email send count to zero if last email was sent more than 12 hours ago
        if ($last_sent <= time() - self::EMAIL_RESET_WINDOW) {
            $send_count = 0;
            $this->user->update([
                'id' => $user_id,
                'email_send_count' => 0
            ]);
        }

        if ($send_count >= self::MAX_EMAILS && $last_sent > time() - self::EMAIL_RESET_WINDOW) {
            return [
                'success' => false,
                'message' => 'Too many emails have been sent. Please wait 12 hours before trying again.'
            ];
        }

        $activation_code = $this->createAuthenticationCode($user_id);

        $this->sendActivationEmail($subject, $template, $user_name, $user_email, $activation_code);

        $this->user->update([
            'id' => $user_id,
            'email_send_count' => $user['email_send_count'] + 1,
            'last_email_sent_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'success' => true,
            'message' => 'A code has been sent to you, please check your email.'
        ];
    }

    private function createAuthenticationCode(int $user_id): int
    {
        $authentication_code = random_int(100000, 999999);

        $expiry_timestamp = time() + 60 * 15;

        $authentication_code_expiry = date('Y-m-d H:i:s', $expiry_timestamp);

        $data = [
            'id' => $user_id,
            'authentication_code' => $authentication_code,
            'authentication_code_expiry' => $authentication_code_expiry,
            'authentication_code_attempts' => 0
        ];

        $this->user->update($data);

        return $authentication_code;
    }

    private function sendActivationEmail(string $subject, string $template, string $name, string $email, int $authentication_code)
    {
        $html = $this->viewer->renderEmail($template . ".html", compact("name", "authentication_code", "email"));
        $text = $this->viewer->renderTextEmail($template . ".txt", compact("name", "authentication_code", "email"));

        Email::send($subject, $email, $html, $text);
    }
}
