<br>

<?php include ROOT_PATH . "views/Endpoints/SelfEmployment/shared/cumulative-summary-table.php"; ?>





<form action="/self-employment/submit-cumulative-period-summary" method="GET">


    <?php include ROOT_PATH . "views/shared/submission-declaration.php" ?>



    <?php include ROOT_PATH . "views/shared/errors.php" ?>

    <button class="form-button" type="submit">Submit</button>

</form>



<p><a href="/uploads/create-cumulative-upload">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>