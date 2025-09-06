<?php if (!empty($submission_details)): ?>

    <div class="print-container">

        <hr>
        <?php displayArrayAsList($submission_details); ?>
        <hr>

        <?php displayArrayAsList($submission_payload); ?>

        <br>

        <?php include ROOT_PATH . "views/shared/print-button.php"; ?>

    </div>


<?php else: ?>

    <p>Unable to retrieve submission.</p>

<?php endif; ?>

<br>

<p><a href="/submissions/get-submissions">All Submissions</a></p>

<?php $include_print_script = true; ?>