<?php if (!empty($response)): ?>

<?php include ROOT_PATH . "views/Endpoints/SelfEmployment/shared/cumulative-summary-table.php";  ?>

<?php else: ?>

<p>No Cumulative Summary found for this period.</p>

<?php endif; ?>

<p><a class="hmrc-connection" href="/obligations/retrieve-cumulative-obligations">Filing Obligations</a></p>

<?php if (!$supporting_agent): ?>

<p><a class="hmrc-connection" href="/individual-calculations/trigger-calculation">Tax Calculation</a></p>

<?php endif; ?>