<form class="generic-form" action="/property-business/process-update-foreign-property" method="POST">

    <input type="hidden" name="property_id" value="<?= $property_id ?>">


    <div class="form-input">
        <label for="property_name">Property Name <span class="asterisk">*</span></label>
        <input type="text" name="property_name" value="<?= esc($property_name) ?>" id="property_name" min="1" max="105"
            required>
    </div>

    <div class="form-input">
        <label for="end_date">End Date </label>
        <input type="date" name="end_date" id="end_date" value="<?= esc($end_date ?? '') ?>" min="2026-04-06">
        <span class="small">Must be on or later than 6 April 2026</span>
    </div>




    <div class="form-input">
        <label for="end_reason">End Reason</label>
        <select name="end_reason" id="end_reason">
            <option value="" disabled <?= (empty($end_reason)) ? 'selected' : '' ?>>
                -- Select a Reason --
            </option>

            <option value="no-longer-renting-property-out"
                <?= ('no-longer-renting-property-out' === ($end_reason ?? '')) ? 'selected' : '' ?>>
                Property no longer let
            </option>

            <option value="disposal" <?= ('disposal' === ($end_reason ?? '')) ? 'selected' : '' ?>>
                Property disposed of
            </option>

            <option value="added-in-error" <?= ('added-in-error' === ($end_reason ?? '')) ? 'selected' : '' ?>> Added in
                error
            </option>

        </select>
        <span class="small">Required if End Date is included</span>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>


</form>

<?php include ROOT_PATH . "views/shared/mandatory-fields-simple.php";
