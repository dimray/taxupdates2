<?php if (empty($calculation_id)): ?>

    <p>No Accounting Adjustments Found</p>

<?php elseif ($zero_adjustments): ?>

    <p>All Adjustments have been set to zero.</p>

<?php else: ?>

    <?php require "shared/self-employment-table.php"; ?>

<?php endif; ?>

<p><a href="/individual-calculations/trigger-calculation">View Tax Calculation</a></p>