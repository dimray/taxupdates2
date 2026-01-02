<form class="generic-form hmrc-connection" action="/property-business/process-create-foreign-property" method="POST">

    <input type="hidden" name="source" value="<?= $source ?>">

    <div class="form-input">
        <label for="property_name">Property Name <span class="asterisk">*</span></label>
        <input type="text" name="property_name" id="property_name" min="1" max="105" required>
        <span class="small">Give the property a unique name by which you will recognise it</span>
    </div>

    <?php include ROOT_PATH . "views/shared/select-country.php"; ?>

    <div class="form-input">
        <label for="tax_year">Tax Year <span class="asterisk">*</span></label>
        <select name="tax_year" id="tax_year">
            <?php

            [$last_start] = explode("-", $last_tax_year);
            [$first_start] = explode("-", $first_tax_year);

            for ($year = (int)$last_start; $year >= (int)$first_start; $year--) {
                $short_end = substr((string) ($year + 1), -2);
                $tax_year_option = "{$year}-{$short_end}";
                $selected = ($tax_year_option === $last_tax_year) ? "selected" : "";
                echo "<option value='{$tax_year_option}' {$selected}>{$tax_year_option}</option>";
            }

            ?>

        </select>
        <span class="small">Year property added to business, or year you joined Making Tax Digital</span>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Add Property</button>
</form>

<?php include ROOT_PATH . "views/shared/mandatory-fields-simple.php";
