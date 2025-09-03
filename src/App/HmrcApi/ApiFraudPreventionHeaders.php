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
        $clientPublicPort = $_SERVER['REMOTE_PORT'] ?? 'Unknown';
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
        $govVendorForwarded = $this->getGovVendorForwarded();
        $vendorIp = $this->getVendorIp();

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

    // need to set nginx to get this
    private function getPublicIp(): ?string
    {
        // Try various headers in order of preference
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_REAL_IP',            // Some proxies
            'HTTP_X_FORWARDED_FOR',      // Standard proxy header (may contain multiple IPs)
            'REMOTE_ADDR'                // Direct connection
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];


                // Handle X-Forwarded-For which may contain multiple IPs
                if ($header === 'HTTP_X_FORWARDED_FOR') {
                    $ip = trim(explode(',', $ip)[0]);
                }

                if ($this->isPublicIp($ip)) {
                    return $ip;
                }
            }
        }

        return '';
    }

    private function getTimestamp(): string
    {
        $now = new DateTime("now", new DateTimeZone("UTC")); // Get current time in UTC
        $micro = microtime(true); // Get current timestamp with microseconds
        $milliseconds = sprintf("%03d", ($micro - floor($micro)) * 1000); // Extract milliseconds
        return $now->format("Y-m-d\TH:i:s") . ".$milliseconds" . "Z"; // Format correctly
    }

    private function getGovVendorForwarded()
    {
        $serverPublicIp = $this->getVendorIp();

        $senderIp = $this->getPublicIp() ?? $_SERVER['REMOTE_ADDR'] ?? null;
        if (!$senderIp) {
            // Cannot determine sender's IP
            return $_SERVER['HTTP_GOV_VENDOR_FORWARDED'] ?? '';
        }

        // Check if both IPs are public
        if ($serverPublicIp && $this->isPublicIp($senderIp)) {
            $existingHeader = $_SERVER['HTTP_GOV_VENDOR_FORWARDED'] ?? '';
            $newHop = 'by=' . urlencode($serverPublicIp) . '&for=' . urlencode($senderIp);
            if ($existingHeader) {
                return $existingHeader . ',' . $newHop;
            } else {
                return $newHop;
            }
        } else {
            // Do not add this hop
            return $_SERVER['HTTP_GOV_VENDOR_FORWARDED'] ?? '';
        }
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
        $domain = 'taxupdates.co.uk';
        $records = dns_get_record($domain, DNS_A);

        if (!empty($records[0]['ip'])) {
            return $records[0]['ip'];
        }

        return '';
    }

    // Helper function to check if IP is public
    private function isPublicIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
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
