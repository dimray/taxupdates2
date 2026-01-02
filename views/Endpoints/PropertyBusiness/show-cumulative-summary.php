<?php if ($empty_data): ?>

<p>There is no data to display.</p>

<?php elseif ($location === "uk"): ?>

<?php include  "shared/cumulative-summary-table-uk.php";  ?>

<?php elseif ($location === "foreign"): ?>

<?php include "shared/cumulative-summary-table-foreign.php";  ?>

<?php endif; ?>


<p><a class="hmrc-connection" href="/obligations/retrieve-cumulative-obligations">Filing Obligations</a></p>

<?php if (!$supporting_agent): ?>

<p><a class="hmrc-connection" href="/individual-calculations/trigger-calculation">Tax Calculation</a></p>

<?php endif; ?>