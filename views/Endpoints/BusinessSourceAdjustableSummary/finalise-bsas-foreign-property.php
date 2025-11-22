<?php if (!empty($zero_adjustments)): ?>

    <div class="inline-checkbox">
        <input type="checkbox" name="zeroAdjustments" id="zero-adjustments-toggle" value="true" checked disabled>
        <label for="zeroAdjustments">Set All Adjustments To Zero</label>
    </div>

<?php else: ?>

    <?php include "shared/foreign-property-table.php"; ?>

<?php endif; ?>

<?php include ROOT_PATH . "views/shared/errors.php"; ?>

<form class="hmrc-connection" action="/business-source-adjustable-summary/submit" method="POST">


    <?php include ROOT_PATH . "/views/shared/submission-declaration.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/business-source-adjustable-summary/create">Cancel</a></p>

<?php $include_scroll_to_error_script = true; ?>