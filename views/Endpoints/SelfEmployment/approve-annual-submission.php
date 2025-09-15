<?php include "shared/annual-submission-table.php"; ?>

<br>

<form action="/self-employment/create-amend-annual-submission" method="POST">

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <?php include ROOT_PATH . "views/shared/submission-declaration.php"; ?>

    <br>

    <button class="button" type="submit">Submit</button>

</form>

<p><a href="/self-employment/create-annual-submission">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>