<?php if (!empty($insurance_income)): ?>

    <?php displayArrayAsList($insurance_income); ?>

    <p><a href="/insurance-income/create-and-amend-insurance-policies-income">Edit Insurance Income</a></p>

<?php else: ?>

    <p>No Insurance Income to display</p>

    <p><a href="/insurance-income/create-and-amend-insurance-policies-income">Add Insurance Income</a></p>

<?php endif; ?>


<p><a href="/insurance-income/confirm-delete-insurance-policies-income">Delete Insurance Income</a></p>