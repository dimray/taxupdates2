<?php

declare(strict_types=1);

namespace App\HmrcApi;

use App\Models\UserDevice;
use Exception;
use DateTime;
use DateTimeZone;


class ApiFraudPreventionHeaders
{
    public function __construct(private UserDevice $userDevice) {}

    public function setHeaders()
    {

        if (isset($_SESSION['device_data']) && !empty($_SESSION['device_data'])) {
            $device_data = json_decode($_SESSION['device_data'], true);
        } else {

            throw new Exception("Session['device_data'] not set");
        }


        $jsUserAgent = $device_data['userAgent'] ?? 'MISSING_HEADER';
        $deviceID = $device_data['deviceID'];
        $govClientMultiFactor = $this->getGovClientMultiFactor($device_data['deviceID']);
        $publicIp = $this->getPublicIp();
        $timestamp = $this->getTimestamp();
        $clientPublicPort =  $clientPublicPort = !empty($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : ($_SESSION['user_port'] ?? '');
        // gov-client-screens
        $screenWidth = abs(intval($device_data['screenWidth'])) ?? 1;
        $screenHeight = abs(intval($device_data['screenHeight'])) ?? 1;
        $scalingFactor = $device_data['scalingFactor'] ?? 1;
        $colorDepth = $device_data['colorDepth'] ?? 1;
        // gov-client-screens
        $timezone = $device_data['timezone'];
        $userIds = $this->getUserIds();
        // gov-client-windowsize
        $windowWidth = abs(intval($device_data['windowWidth'] ?? 1));
        $windowHeight = abs(intval($device_data['windowHeight'] ?? 1));
        // gov-client-windowsize       
        $vendorIp = $this->getVendorIp();
        $govVendorForwarded = $this->getGovVendorForwarded($publicIp, $vendorIp);

        // User ID, percent encoded
        $email = rawurlencode($_SESSION['email']);

        // Fraud prevention headers https://developer.service.hmrc.gov.uk/guides/fraud-prevention/connection-method/web-app-via-server/
        $govHeaders = [
            "Gov-Client-Connection-Method: WEB_APP_VIA_SERVER",
            "Gov-Client-Browser-JS-User-Agent: $jsUserAgent",
            "Gov-Client-Device-ID: $deviceID",
            "Gov-Client-Multi-Factor: $govClientMultiFactor",
            "Gov-Client-Public-IP: $publicIp",
            "Gov-Client-Public-IP-Timestamp: $timestamp",
            "Gov-Client-Public-Port: $clientPublicPort",
            "Gov-Client-Screens: width=$screenWidth&height=$screenHeight&scaling-factor=$scalingFactor&colour-depth=$colorDepth",
            "Gov-Client-Timezone: $timezone",
            "Gov-Client-User-IDs: $userIds",
            "Gov-Client-Window-Size: width=$windowWidth&height=$windowHeight",
            "Gov-Vendor-Forwarded: $govVendorForwarded",
            "Gov-Vendor-Product-Name: TaxUpdates",
            "Gov-Vendor-Public-IP: $vendorIp",
            "Gov-Vendor-Version: TaxUpdates=1.0"
        ];

        // log submitted headers if need to see them.
        // file_put_contents(
        //     ROOT_PATH . 'logs/fraud_headers.log',
        //     "[" . date('Y-m-d H:i:s') . "] Headers generated for: " . ($_SERVER['REQUEST_URI'] ?? 'unknown') . "\n" .
        //         print_r($govHeaders, true) . "\n" .
        //         str_repeat("-", 80) . "\n\n",
        //     FILE_APPEND
        // );

        return $govHeaders;
    }

    private function getPublicIp(): string
    {
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';

        if (!empty($remoteAddr) && $this->isPublicIp($remoteAddr)) {
            return $remoteAddr;
        }

        // Fallback to the IP saved on login
        $userIp = $_SESSION['user_ip'] ?? '';

        if (!empty($userIp) && $this->isPublicIp($userIp)) {
            return $userIp;
        }

        return '';
    }

    private function getTimestamp(): string
    {
        $micro = microtime(true);
        $seconds = floor($micro);
        $milliseconds = sprintf("%03d", ($micro - $seconds) * 1000);
        $dt = new DateTime("@$seconds");   // "@$seconds" makes it UTC timestamp
        $dt->setTimezone(new DateTimeZone("UTC"));
        return $dt->format("Y-m-d\TH:i:s") . ".$milliseconds" . "Z";
    }

    private function getGovVendorForwarded(string $public_ip, string $vendor_ip)
    {
        $clientIp = $public_ip;
        $vendorIp = $vendor_ip;

        $hops = [];

        // Log the hop from the client to your vendor server.
        // "by" is the server that received the request (your vendor IP).
        // "for" is the client that sent the request.
        if (!empty($clientIp) && !empty($vendorIp)) {
            $hops[] = 'by=' . rawurlencode($vendorIp) . '&for=' . rawurlencode($clientIp);
        }

        return implode(',', $hops);
    }

    private function getUserIds()
    {
        $identifiers = [
            'email' => $_SESSION['email'],
            'user_id' => (string) $_SESSION['user_id']
        ];

        $encoded = [];

        foreach ($identifiers as $key => $value) {
            $encoded[] = rawurlencode($key) . '=' . rawurlencode($value);
        }

        return implode('&', $encoded);
    }

    private function getVendorIp()
    {
        return '77.37.65.79';
    }

    // Helper function to check if IP is public
    private function isPublicIp($ip)
    {
        return (bool) filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }

    private function getGovClientMultiFactor(string $device_id)
    {
        $user_id = (int) $_SESSION['user_id'];
        $device_details = $this->userDevice->findDevice($user_id, $device_id);
        $factors = [];


        if (isset($_SESSION['device_verified_this_login'])) {
            $factors[] = [
                'type' => 'AUTH_CODE',
                'timestamp' => gmdate('Y-m-d\TH:i\Z', $_SESSION['login_time'] ?? time()),
                'unique-reference' => hash('sha256', $_SESSION['user_id'] . '-email')
            ];
        }

        if ($device_details) {
            $timestamp = strtotime($device_details['last_verified_at'] ?? 0);
            $factors[] = [
                'type' => 'OTHER',
                'timestamp' => gmdate('Y-m-d\TH:i\Z', $timestamp),
                'unique-reference' => $device_details['unique_mfa_ref']
            ];
        }

        if (empty($factors)) {
            return null;
        }

        $encoded = array_map(function ($factor) {
            return sprintf(
                "type=%s&timestamp=%s&unique-reference=%s",
                rawurlencode($factor['type']),
                rawurlencode($factor['timestamp']),
                rawurlencode($factor['unique-reference'])
            );
        }, $factors);

        return implode(',', $encoded);
    }
}
