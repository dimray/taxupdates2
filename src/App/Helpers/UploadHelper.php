<?php

declare(strict_types=1);

namespace App\Helpers;

use finfo;

class UploadHelper
{
    // *********** UPLOADS ****************************************

    public static function processCsvErrors(array $file, int $maxDataRows, int $maxDataColumns): array
    {
        $errors = [];

        // Check upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = "No file was uploaded.";
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = "File exceeds maximum upload size.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = "File was only partially uploaded. Please try again.";
                    break;
                default:
                    $errors[] = "Unknown upload error. Please ensure the file is in the correct format and try again.";
                    break;
            }
            return $errors;
        }

        // Check if file was actually uploaded
        if (!is_uploaded_file($file['tmp_name'])) {
            $errors[] = "File does not exist or was not uploaded properly.";
        }

        // Check if file is empty
        if (filesize($file['tmp_name']) === 0) {
            $errors[] = "File is empty.";
        }

        // Check file size (if no previous errors)
        if ($file['size'] > 100000) {
            $errors[] = "File too large (max 1KB).";
        }

        // Check MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);

        $allowed_mime_types = [
            "text/csv",
            "application/vnd.ms-excel",
            "text/plain", // Sometimes CSV files are detected as plain text
            "application/csv"  // Additional MIME type sometimes used
        ];

        if (!in_array($mime_type, $allowed_mime_types)) {
            $errors[] = "File must be a CSV file. Detected format: {$mime_type}";
        }

        // Check for correct number of columns
        if (empty($errors)) {
            $delimiter = self::determineCsvDelimiter($file['tmp_name'], $mime_type);

            $errors = self::validateColumnAndRowCount($file['tmp_name'], $delimiter, $maxDataRows, $maxDataColumns);
        }

        return $errors;
    }

    private static function determineCsvDelimiter(string $filePath, string $mimeType): string
    {
        // Common MIME type hints for delimiters
        $mimeHints = [
            'text/tab-separated-values' => "\t",
            'application/vnd.ms-excel' => ",",
            'text/csv' => ",",
            'application/csv' => ",",
            'text/plain' => ",",
        ];

        // Prioritize delimiter based on explicit MIME type hints
        if (isset($mimeHints[$mimeType])) {
            return $mimeHints[$mimeType];
        }

        // Fallback to scanning the file for the most common delimiter
        return self::detectDelimiter($filePath);
    }


    private static function detectDelimiter(string $filePath): string
    {
        $delimiters = ["," => 0, "\t" => 0, ";" => 0, "|" => 0];

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return ",";
        }

        $line = fgets($handle);
        fclose($handle);

        if ($line === false) {
            return ",";
        }

        foreach ($delimiters as $delimiter => &$count) {
            $count = substr_count($line, $delimiter);
        }

        $detected = array_search(max($delimiters), $delimiters);

        return $detected !== false ? $detected : ","; // Default to comma if no delimiter found
    }


    private static function validateColumnAndRowCount(
        string $filePath,
        string $delimiter,
        int $maxDataRows,
        int $maxDataColumns,
        int $maxScanDistance = 50,
        int $maxEmptyRows = 200,
        int $maxTotalColumns = 50
    ): array {
        // maxTotalColumns is total number of columns from the start scanned.
        // maxScanDistance is max empty rows between data columns

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return ['error' => 'Unable to open file'];
        }

        $errors = [];
        $dataRowCount = 0;
        $consecutiveEmptyRows = 0;
        $usedColumnIndices = [];

        while (($line = fgetcsv($handle, 5000, $delimiter)) !== false) {

            // Limit columns scanned to maxTotalColumns
            $line = array_slice($line, 0, $maxTotalColumns);
            // Trim all values
            $line = array_map('trim', $line);

            // Check if row is empty
            $isRowEmpty = count(array_filter($line, fn($cell) => $cell !== '')) === 0;

            if ($isRowEmpty) {
                $consecutiveEmptyRows++;
                if ($consecutiveEmptyRows >= $maxEmptyRows) {
                    break; // Stop scanning after maxEmptyRows, assume no more data
                }
                continue;
            }

            $consecutiveEmptyRows = 0; // Reset on non-empty row

            $dataRowCount++;

            // Check if data row count exceeds the limit
            if ($dataRowCount > $maxDataRows) {
                $errors[] = "Your file has more than the maximum allowed $maxDataRows rows of data.";
                break; // Stop scanning
            }

            // Identify non-empty columns
            $nonEmptyIndexes = array_filter(array_keys($line), fn($index) => $line[$index] !== '');

            // Check non-empty column count
            $nonEmptyCount = count($nonEmptyIndexes);
            if ($nonEmptyCount > $maxDataColumns) {
                $errors[] = "Your file has more than $maxDataColumns columns containing data";
                break;
            }

            // Check distance between non-empty columns
            if ($nonEmptyCount > 0) {
                $minIndex = min($nonEmptyIndexes);
                $maxIndex = max($nonEmptyIndexes);
                if ($maxIndex - $minIndex > $maxScanDistance) {
                    $errors[] = "Please ensure your data is in adjacent columns";
                    break;
                }

                // Track column consistency (optional, can be removed if not needed)
                foreach ($nonEmptyIndexes as $index) {
                    $usedColumnIndices[$index] = true;
                }
                if (count($usedColumnIndices) > $maxDataColumns) {
                    $errors[] = "Your data is inconsistent across multiple columns";
                    break;
                }
            }
        }

        fclose($handle);
        return $errors;
    }

    public static function parseKeyValueCsv(array $file): array
    {

        $data = [];

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return $data;
        }

        $delimiter = self::determineCsvDelimiter($file['tmp_name'], $file['type'] ?? 'application/octet-stream');

        if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $row = array_map('trim', $row);

                $key = $row[0];
                $value = $row[1];

                if ($key === '') {
                    continue;
                }

                $data[$key] = $value;
            }

            fclose($handle);
        }

        return $data;
    }

    public static function parseClientCsv(array $file): array
    {
        $clients = [];

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return $clients;
        }

        $delimiter = self::determineCsvDelimiter($file['tmp_name'], $file['type'] ?? 'application/octet-stream');

        if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $row = array_map('trim', $row);

                // Skip empty or malformed rows
                if (count($row) < 2 || $row[0] === '' || $row[1] === '') {
                    continue;
                }

                $clients[] = [
                    'name' => $row[0],
                    'nino' => $row[1],
                ];
            }

            fclose($handle);
        }

        return $clients;
    }


    // *************** PASTED DATA ***********************************************


    public static function processPasteErrors(string $pasted_data, int $maxDataRows, int $maxDataColumns): array
    {

        $errors = [];

        $pasted_data = trim($pasted_data);
        if ($pasted_data === '') {
            $errors[] = "No data was pasted.";
            return $errors;
        }

        $lines = array_filter(array_map('trim', explode("\n", $pasted_data)));
        $first_line = reset($lines) ?: '';

        $delimiter = self::detectDelimiterFromLine($first_line);

        $rowCount = 0;

        foreach ($lines as $line) {
            $row = str_getcsv($line, $delimiter);
            $rowCount++;

            $cols = count($row);
            if ($cols > $maxDataColumns) {
                $errors[] = "Too many columns on row {$rowCount} (maximum is {$maxDataColumns}).";
                break;
            }

            if ($rowCount > $maxDataRows) {
                $errors[] = "Too many rows (maximum is {$maxDataRows}).";
                break;
            }
        }

        if ($rowCount === 0) {
            $errors[] = "No usable rows found.";
        }

        return $errors;
    }

    private static function detectDelimiterFromLine(string $line): string
    {
        $delimiters = ["\t" => 0, "," => 0, ";" => 0, "|" => 0, " " => 0];

        foreach ($delimiters as $delimiter => $count) {
            $delimiters[$delimiter] = substr_count($line, $delimiter);
        }

        $detected = array_search(max($delimiters), $delimiters);

        return $delimiters[$detected] > 0 ? $detected : "\t";
    }


    public static function parseDataToStructuredArray(string $pasted_data, array $columns): array
    // this is for pasted clients
    {
        $pasted_data = trim($pasted_data);
        if ($pasted_data === '') {
            return [];
        }

        $lines = array_filter(array_map('trim', explode("\n", $pasted_data)));
        $first_line = reset($lines) ?: '';

        $delimiter = self::detectDelimiterFromLine($first_line);

        $clients = [];

        foreach ($lines as $line) {
            $row = str_getcsv($line, $delimiter);

            if (count($row) !== count($columns)) {
                continue;
            }

            $clients[] = array_combine($columns, $row);
        }

        return $clients;
    }

    public static function parseDataToKeyValueArray(string $pasted_data): array
    {
        // This is for pasted cumulative summary
        $pasted_data = trim($pasted_data);
        if ($pasted_data === '') {
            return [];
        }

        $lines = array_filter(array_map('trim', explode("\n", $pasted_data)));
        $first_line = reset($lines) ?: '';

        $delimiter = self::detectDelimiterFromLine($first_line);

        $result = [];

        foreach ($lines as $line) {
            $fields = str_getcsv($line, $delimiter);

            if (count($fields) >= 2) {
                $key = trim($fields[0]);
                $value = trim($fields[1]);

                // Optional: skip blank keys
                if ($key !== '') {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
