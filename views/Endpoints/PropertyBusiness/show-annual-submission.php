<?php if ($empty_data): ?>

    <p>There is no data to display.</p>

<?php elseif ($location === "uk"): ?>

    <?php include "shared/annual-submission-table-uk.php";  ?>

    <p><a href="/property-business/annual-submission#amend">Amend Submission</a></p>

    <p><a href="/property-business/delete-annual-submission">Delete Submission</a></p>

<?php elseif ($location === "foreign"): ?>

    <?php include "shared/annual-submission-table-foreign.php";  ?>

    <p><a href="/property-business/annual-submission">Amend Submission</a></p>

    <p><a href="/property-business/delete-annual-submission">Delete Submission</a></p>

<?php endif; ?>