<?php if (!empty($employment_expenses)): ?>

    <?php displayArrayAsList($employment_expenses); ?>

    <?php if ($tax_year_ended): ?>
        <p><a href="/expenses/create-and-amend-employment-expenses">Edit Expenses</a></p>
    <?php endif; ?>

<?php else: ?>

    <p>No Employment Expenses to display</p>

    <?php if ($tax_year_ended): ?>
        <p><a href="/expenses/create-and-amend-employment-expenses">Add Expenses</a></p>
    <?php endif; ?>

<?php endif; ?>

<?php if ($tax_year_ended): ?>

    <p><a href="/expenses/confirm-ignore-employment-expenses">Ignore HMRC Provided Expenses</a></p>
    <p><a href="/expenses/confirm-delete-employment-expenses">Delete Expenses</a></p>

<?php endif; ?>