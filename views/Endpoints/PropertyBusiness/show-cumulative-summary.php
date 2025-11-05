<?php if ($empty_data): ?>

    <p>There is no data to display.</p>

<?php elseif ($location === "uk"): ?>

    <?php include  "shared/cumulative-summary-table-uk.php";  ?>

<?php elseif ($location === "foreign"): ?>

    <?php include "shared/cumulative-summary-table-foreign.php";  ?>

<?php endif; ?>


<p><a class="hmrc-connection" href="/obligations/retrieve-cumulative-obligations">Back</a></p>