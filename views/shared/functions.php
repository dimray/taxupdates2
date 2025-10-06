<?php

use App\Flash;

function esc(?string $content): string
{
    if ($content === null) {
        return '';
    }
    return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
}

function displayFlashMessages()
{
    $flash = new Flash();
    $messages = $flash->getMessages();

    if (isset($messages)) {
        foreach ($messages as $message) {
            echo '<p class="flash-message flash-' . esc($message['type']) . '">' . esc($message['body']) . '</p>';
        }
    }
}

function displayArrayAsList(array $array, int $level = 0): void
{
    $key_replacements = [
        'arn' => "Agent Reference",
        'nino' => "NI Number",
        "nics" => "National Insurance Contributions",
        "businessAssetsDisposalsAndInvestorsRel" => "Business Assets Disposal And Investors Relief",
        "cgtTaxBands" => "CGT Tax Bands",
        "capitalGainsTaxAfterFTCR" => "Capital Gains Tax After Foreign Tax Credit Relief",
        "totalIncomeTaxAndNicsAndCgt" => "Total Income Tax, NIC, CGT",
        "bbsi" => "Bank & Building Society Interest",
        "SaUnderpaymentsCodedOut" => "Self Assessment Underpayments Coded Out",
        "earningsNotTaxableUK" => "Earnings Not Taxable In UK",

        // Add more as needed
    ];

    $value_replacements = [
        'ACCRUALS' => "Accruals",
        'CASH' => "Cash",
        'self-employment' => "Self Employment",
        'uk-property' => "UK Property",
        'foreign-property' => "Foreign Property"
        // Add more as needed
    ];

    // Custom word replacements (case-insensitive)
    $word_replacements = [
        'nics' => 'NIC',
        'cgt' => 'CGT',
        'isa' => 'ISA',
        'ppd' => 'Real Time CGT',
        'prf' => 'Private Residence Relief',
        'srn' => 'Scheme Reference Number',
        'ftcr' => 'Foreign Tax Credit Relief'
        // add more as needed
    ];

    $no_formatting = ["email", "payrollId"];

    // Add level class for indentation
    $listClass = 'api-list' . ($level > 0 ? ' list-level-' . min($level, 3) : '');

    echo "<ul class='$listClass'>";

    foreach ($array as $key => $value) {
        $display_key = $key_replacements[$key] ?? formatApiString($key);

        // Apply word replacements to the formatted display key
        foreach ($word_replacements as $search => $replace) {
            $display_key = preg_replace('/\b' . preg_quote($search, '/') . '\b/i', $replace, $display_key);
        }

        $is_numeric_key = is_numeric($key);

        // Handle special formatting cases
        if (in_array($key, $no_formatting)) {
            echo "<li class='list-item'>
                    <span class='list-key'>" . esc(ucwords($display_key)) . ":</span>
                    <span class='list-value'>" . esc($value) . "</span>
                  </li>";
            continue;
        }

        // Handle array values
        if (is_array($value)) {
            // Skip empty arrays
            if (empty($value)) {
                continue;
            }

            // Detect and flatten single-item numeric arrays (e.g. [0] => [...])
            if (!$is_numeric_key && array_keys($value) === [0] && is_array($value[0])) {
                echo "<li><h4 class='list-header'>" . esc(ucwords($display_key)) . "</h4>";
                displayArrayAsList($value[0], $level + 1);
                echo "</li>";

                continue;
            }

            // Standard array handling
            if ($is_numeric_key) {
                echo "<li class='list-group-item'>";
                displayArrayAsList($value, $level + 1);
                echo "</li>";
            } else {
                echo "<li><h4 class='list-header'>" . esc(ucwords($display_key)) . "</h4>";
                displayArrayAsList($value, $level + 1);
                echo "</li>";
            }
            continue;
        }



        // Format different value types
        $value_output = "";

        if (is_bool($value)) {
            $value_output = $value ? "true" : "false";
        } elseif (is_numeric($value)) {
            if ($key === "rate") {
                $value_output  = formatNumber($value) . "%";
            } else {
                $value_output = formatNumber($value);
            }
        } elseif (empty($value)) {
            $value_output = "N/A";
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $value_output = formatDate($value);
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?Z$/', $value)) {
            // Match ISO datetime (e.g. 2025-07-30T14:16:35.698Z)
            $value_output = formatDateTime($value);
        } else {
            $value_output = $value;
        }

        foreach ($word_replacements as $search => $replace) {
            $display_key = preg_replace('/\b' . preg_quote($search, '/') . '\b/i', $replace, $display_key);
        }


        // Apply value replacements
        if (array_key_exists($value_output, $value_replacements)) {
            $value_output = $value_replacements[$value_output];
        }

        // Skip numeric keys in output
        $output = $is_numeric_key ?
            "<li class='list-value'>" . esc($value_output) . "</li>" :
            "<li class='list-item'>
                <span class='list-key'>" . esc(ucwords($display_key)) . ":</span>
                <span class='list-value'>" . esc($value_output) . "</span>
             </li>";

        echo $output;
    }

    echo "</ul>";
}

function formatApiString(string $input): string
{

    // Split the string into words based on capital letters.
    $words = preg_split('/(?=[A-Z])/', $input, -1, PREG_SPLIT_NO_EMPTY);

    // Capitalize the first letter of each word and join them with spaces.
    $formattedString = implode(' ', array_map('ucfirst', $words));

    // Fix common abbreviations
    $formattedString = preg_replace('/\bId\b/i', 'ID', $formattedString);
    $formattedString = preg_replace('/\bUk\b/i', 'UK', $formattedString);
    $formattedString = preg_replace('/\bnic\b/i', 'NIC', $formattedString);
    $formattedString = preg_replace('/\bRfc\b/i', 'RFC', $formattedString); //residential finance costs

    return $formattedString;
}

function formatNumber($value)
{
    if (is_numeric($value)) {
        return number_format((float) $value, 2, '.', ',');
    } else {
        return $value;
    }
}

// format YYYY-MM-DD
function formatDate(string $date_string): string
{
    $date_string = trim((string) $date_string);

    if ($date_string === '') {
        return '';
    }

    // Try strict Y-m-d first
    $dateTime = DateTime::createFromFormat('Y-m-d', $date_string);

    // If that fails, try ISO8601 (what HMRC often uses)
    if (!$dateTime) {
        $dateTime = date_create($date_string);
    }

    return $dateTime ? $dateTime->format('d M Y') : $date_string;
}

function formatDateTime($input)
{
    try {
        if (is_numeric($input)) {
            // Assume it's a Unix timestamp
            $utcDate = (new DateTime('@' . $input))->setTimezone(new DateTimeZone('Europe/London'));
        } else {
            // Assume it's an ISO date string
            $utcDate = new DateTime($input, new DateTimeZone('UTC'));
            $utcDate->setTimezone(new DateTimeZone('Europe/London'));
        }

        return $utcDate->format('F j Y, g:i A');
    } catch (Exception $e) {
        return (string) $input;
    }
}

function getCountry($country_code): string
{
    $country_data = require ROOT_PATH . "config/mappings/country-codes.php";

    $country_string = "";
    foreach ($country_data as $continent) {
        foreach ($continent as $code => $country) {
            if (strtoupper($code) === strtoupper($country_code)) {
                $country_string = $country;
                break;
            }
        }
    }

    return $country_string;
}
