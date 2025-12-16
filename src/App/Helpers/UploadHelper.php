<?php

declare(strict_types=1);

namespace App\Helpers;

use finfo;

class UploadHelper
{
    private const MAX_CSV_FILE_SIZE_BYTES = 512 * 1024;

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
            return $errors;
        }

        // Check if file is empty
        if (filesize($file['tmp_name']) === 0) {
            $errors[] = "File is empty.";
            return $errors;
        }

        // Check file size (if no previous errors)
        if ($file['size'] > self::MAX_CSV_FILE_SIZE_BYTES) {
            $errors[] = "File too large (max 512KB).";
            return $errors;
        }

        // Check MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);

        if ($mime_type === false) {
            $errors[] = "Unable to determine file type. File may be corrupted or missing.";
            return $errors;
        }

        $allowed_mime_types = [
            "text/csv",
            "application/vnd.ms-excel",
            "text/plain", // Sometimes CSV files are detected as plain text
            "application/csv",  // Additional MIME type sometimes used
            "text/x-comma-separated-values"
        ];

        if (!in_array($mime_type, $allowed_mime_types)) {
            $errors[] = "File must be a CSV file. Detected format: {$mime_type}";
            return $errors;
        }

        // Check for correct number of columns
        if (empty($errors)) {
            $contents = file_get_contents($file['tmp_name']);

            if (strncmp($contents, "\xEF\xBB\xBF", 3) === 0) {
                $contents = substr($contents, 3);
                file_put_contents($file['tmp_name'], $contents);
            }

            $delimiter = self::detectDelimiter($file['tmp_name']);

            $errors = self::validateColumnAndRowCount($file['tmp_name'], $delimiter, $maxDataRows, $maxDataColumns);
        }

        return $errors;
    }


    private static function detectDelimiter(string $filePath): string
    {
        // Candidate delimiters in priority order
        $delimiters = [",", ";", "\t", "|"];

        // Read a small sample (first 10 lines)
        $lines = [];
        $fh = fopen($filePath, "r");

        if (!$fh) {
            return ","; // safe fallback
        }

        $maxLines = 10;
        while (($line = fgets($fh)) !== false && count($lines) < $maxLines) {

            if (trim($line) !== "") {
                $lines[] = $line;
            }
        }
        fclose($fh);

        if (empty($lines)) {
            return ","; // empty file → fallback
        }

        $scores = [];

        foreach ($delimiters as $delimiter) {
            $columnCounts = [];

            foreach ($lines as $line) {
                $columnCounts[] = substr_count($line, $delimiter) + 1;
            }

            // If all column counts match, it's a strong candidate
            $unique = array_unique($columnCounts);
            $consistent = count($unique) === 1;

            $scores[$delimiter] = [
                "consistent" => $consistent,
                "maxColumns" => max($columnCounts),
                "minColumns" => min($columnCounts),
            ];
        }

        // 1. Prefer delimiters with fully consistent column counts
        foreach ($scores as $delimiter => $stat) {
            if ($stat["consistent"] && $stat["maxColumns"] > 1) {
                return $delimiter;
            }
        }

        // 2. Otherwise, pick the delimiter that yields the highest column count (best guess)
        $best = ",";
        $bestColumns = 1;

        foreach ($scores as $delimiter => $stat) {
            if ($stat["maxColumns"] > $bestColumns) {
                $bestColumns = $stat["maxColumns"];
                $best = $delimiter;
            }
        }

        return $best;
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

        $errors = [];

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $errors[] = 'Unable to open file';
            return $errors;
        }

        $dataRowCount = 0;
        $consecutiveEmptyRows = 0;
        $usedColumnIndices = [];

        while (($line = fgetcsv($handle, 0, $delimiter)) !== false) {

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

        $delimiter = self::detectDelimiter($file['tmp_name']);

        if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {

                $row = array_map('trim', $row);

                // Skip empty or malformed rows
                if (count($row) < 2 || $row[0] === '') {
                    continue;
                }

                $key = $row[0];
                $value = $row[1] ?? '';

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

        $delimiter = self::detectDelimiter($file['tmp_name']);

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
        $delimiters = ["\t" => 0, "," => 0, ";" => 0, "|" => 0];

        foreach ($delimiters as $delimiter => $count) {
            $delimiters[$delimiter] = substr_count($line, $delimiter);
        }

        $detected = array_search(max($delimiters), $delimiters);

        return $delimiters[$detected] > 0 ? $detected : ",";
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

                // skip blank keys
                if ($key !== '') {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
