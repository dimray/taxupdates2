<?php

// can pass $first_tax_year and $last_tax_year in view

use App\Helpers\TaxYearHelper;

$input_first_tax_year = $first_tax_year ?? '2022-23'; // default fallback
$min_first_tax_year = TaxYearHelper::getCurrentTaxYear(-4);

// Extract start years as integers
[$input_start] = explode('-', $input_first_tax_year);
[$min_start] = explode('-', $min_first_tax_year);

$chosen_start = max((int)$input_start, (int)$min_start);
$chosen_end = substr((string)($chosen_start + 1), -2);

$first_tax_year = "{$chosen_start}-{$chosen_end}";
$last_tax_year = $last_tax_year ?? TaxYearHelper::getCurrentTaxYear();

// selected tax year
$tax_year = $_SESSION['tax_year'];

// save path for redirect
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$source = match (true) {
    str_contains($current_path, 'retrieve-calculation') => 'retrieve-calculation',
    default => null
};

if ($source === null) {
    $source = $_SERVER['REQUEST_URI'];
}

$param_fields = [];
foreach ($_GET as $key => $value) {
    if (!in_array($key, ['tax_year'])) {
        $param_fields[$key] = $value;
    }
}


?>

<form id="change_tax_year" action="/tax-year/change-tax-year" method="GET">

    <input type="hidden" name="source" value="<?= esc($source) ?>">

    <label for="select_tax_year">Tax Year</label>
    <select name="tax_year" id="select_tax_year">
        <?php

        [$last_start] = explode("-", $last_tax_year);
        [$first_start] = explode("-", $first_tax_year);

        for ($year = (int)$last_start; $year >= (int)$first_start; $year--) {
            $short_end = substr((string) ($year + 1), -2);
            $tax_year_option = "{$year}-{$short_end}";
            $selected = ($tax_year_option === $tax_year) ? "selected" : "";
            echo "<option value='{$tax_year_option}' {$selected}>{$tax_year_option}</option>";
        }

        ?>

    </select>


</form>

<?php $include_change_tax_year_script = true; ?>