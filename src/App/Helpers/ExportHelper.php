<?php

declare(strict_types=1);

namespace App\Helpers;

class ExportHelper
{

    // filename is the name of the exported file
    // headers is the headings in the csv

    public static function generateCsvString(array $data, array $headers = ['Category', 'Amount']): string
    {
        // Check if the data is a flat array of key-value pairs.
        // If not, flatten it recursively.
        if (!self::isFlatArray($data)) {
            $data = self::flattenArray($data, $headers[0], $headers[1]);
        }

        // Use a temporary in-memory stream to build the CSV - automatically deleted on fclose($output)
        $output = fopen('php://temp', 'r+');

        // Write the header row to the stream
        if (!empty($headers)) {
            fputcsv($output, $headers);
        }

        // Write the data rows
        foreach ($data as $row) {
            // Ensure values match header order
            $sanitizedRow = array_map(fn($key) => $row[$key] ?? '', $headers);
            fputcsv($output, $sanitizedRow);
        }

        // Rewind the stream to the beginning
        rewind($output);

        // Get the full content and close the stream
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    private static function isFlatArray(array $arr): bool
    {
        foreach ($arr as $value) {
            if (is_array($value)) {
                return false;
            }
        }
        return true;
    }

    private static function flattenArray(
        array $data,
        string $key_name = 'Category', // The desired key name for the category string
        string $value_name = 'Amount',     // The desired key name for the amount value
        string $currentPath = ''              // Internal parameter for recursion to build the path
    ): array {
        $flat_data = [];

        foreach ($data as $key => $value) {
            // Construct the full path for the current item
            $new_path = $currentPath;

            if (is_array($value)) {

                if (!is_int($key)) {
                    $new_path .= (empty($new_path) ? '' : '-') . $key;
                }

                $flat_data = array_merge(
                    $flat_data,
                    self::flattenArray($value, $key_name, $value_name, $new_path)
                );
            } else {

                $new_path .= (empty($new_path) ? '' : '-') . $key;

                if (is_bool($value)) {
                    $flat_data[] = [
                        $key_name => $new_path,
                        $value_name => $value ? 'true' : 'false'
                    ];
                } else {
                    $flat_data[] = [
                        $key_name => $new_path,
                        $value_name => $value
                    ];
                }
            }
        }

        return $flat_data;
    }
}
