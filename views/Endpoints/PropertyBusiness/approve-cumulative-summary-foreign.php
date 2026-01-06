<br>

<?php include ROOT_PATH . "views/Endpoints/PropertyBusiness/shared/cumulative-summary-table-foreign.php";  ?>


<form class="hmrc-submission" action="<?= '/property-business/submit-cumulative-period-summary' ?>" method="GET">

    <div class="spacer-10"></div>
    <?php include ROOT_PATH . "views/shared/submission-declaration.php" ?>

    <?php include ROOT_PATH . "views/shared/errors.php" ?>

    <div class="spacer-10"></div>

    <button class="form-button" type="submit">Submit</button>

</form>


<p><a href="/uploads/create-cumulative-upload?cancel-foreign-property=true">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>