<p><a href="/individual-calculations/trigger-calculation">View Tax Calculation</a></p>

<?php if ($type === "cumulative"): ?>

    <p><a href="/self-employment/retrieve-cumulative-period-summary">View Cumulative Summary</a></p>

<?php elseif ($type === "annual"): ?>

    <p><a href="/self-employment/retrieve-annual-submission">View Annual Submission</a></p>

<?php elseif ($type === "annual-deleted"): ?>

    <p><a href="/business-details/retrieve-business-details">Annual Filing For This Business</a></p>

<?php endif; ?>