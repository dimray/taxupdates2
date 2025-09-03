<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Framework\Encryption;

class UserValidator
{
    public function __construct(private User $user, private UserDevice $user_device) {}

    private const MAX_ATTEMPTS = 7;

    public function checkAuthenticationCode(string $controller, string $authentication_code, array $user, array $device_data = [], string $password_hash = "", string $encrypted_new_email = "", string $new_email_hash = ""): array
    {
        $authentication_attempts = $user['authentication_code_attempts'] ?? 0;

        $user_id = (int) $user['id'];

        $user_email = Encryption::decrypt($user['email']) ?? '';

        $expiry = isset($user['authentication_code_expiry']) ? strtotime($user['authentication_code_expiry']) : 0;

        // code is right and has not expired
        if ($user && $expiry > time()  && $user['authentication_code'] == $authentication_code) {

            $data['id'] = $user_id;
            $data['authentication_code'] = null;
            $data['authentication_code_expiry'] = null;
            $data['authentication_code_attempts'] = 0;
            $data['is_active'] = 1;
            $data['email_send_count'] = 0;
            $data['last_email_sent_at'] = null;
            $data['new_email'] = null;
            $data['login_attempts'] = 0;
            $data['last_login_attempt'] = null;

            if (!empty($password_hash)) {
                $data['password_hash'] = $password_hash;
            }
            if (!empty($encrypted_new_email) && !empty($new_email_hash)) {
                $data['email'] = $encrypted_new_email;
                $data['email_hash'] = $new_email_hash;
            }

            $this->user->update($data);

            if (!empty($device_data)) {
                $this->saveDeviceData($device_data, $user_id);
            }

            $message = "";
            $location = "";
            if ($controller === "password") {
                $message = "You have successfully changed your password.";
                $location = "/session/new";
            } elseif ($controller === "profile") {
                $message = "You have successfully changed your email.";
                $location = "/profile/show-profile";
            } else {
                $message = "You are now logged in.";
                $location = "/";
            }

            return [
                'success' => true,
                'message' => $message,
                'location' => $location,
                'resend' => false
            ];
        } elseif ($user && $user['authentication_code'] === $authentication_code && $expiry < time()) {

            // code is right but has expired
            return [
                'success' => false,
                'message' => 'Your code has expired.',
                'location' => "/$controller/enter-code?email=" . urlencode($user_email),
                'resend' => true
            ];
        } else {
            // code is wrong and too many attempts

            if ($authentication_attempts > self::MAX_ATTEMPTS) {

                return [
                    'success' => false,
                    'message' => 'Too many incorrect attempts. A new code has been sent to your email.',
                    'location' => "/$controller/enter-code?email=" . urlencode($user_email),
                    'resend' => true
                ];
            }

            // code is wrong and not too many attempts

            $this->user->update([
                'id' => $user_id,
                'authentication_code_attempts' => $authentication_attempts + 1,
            ]);

            return [
                'success' => false,
                'message' => 'The entered code is not correct. Please try again.',
                'location' => "/$controller/enter-code?email=" . urlencode($user_email) . "&retry=true&attempts=" . $authentication_attempts,
                'resend' => false
            ];
        }
    }

    public function saveDeviceData(array $device_data, int $user_id)
    {
        $device_id = $device_data['deviceID'] ?? '';

        $unique_mfa_ref = hash('sha256', $user_id . ':' . $device_id);

        $existing_device = $this->user_device->findDevice($user_id, $device_id);

        // save the device in database
        if (!$existing_device) {

            unset($data);
            $data['user_id'] = $user_id;
            $data['device_id'] = $device_id;
            $data['last_verified_at'] = date('Y-m-d H:i:s');
            $data['unique_mfa_ref'] = $unique_mfa_ref;

            $this->user_device->insert($data);
        }
    }
}
