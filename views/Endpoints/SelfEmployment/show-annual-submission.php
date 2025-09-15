<?php if (!($sba || $esba || $allowances || $adjustments || $non_financials)): ?>

<p>No Annual Submission found for this period.</p>

<p><a href="/self-employment/annual-submission">Create Annual Submission</a></p>

<?php else: ?>

<?php include "shared/annual-submission-table.php"; ?>

<p><a href="/self-employment/annual-submission">Amend Submission</a></p>

<p><a href="/self-employment/delete-annual-submission">Delete Submission</a></p>

<?php endif; ?>