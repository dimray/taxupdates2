<?php if (!empty($pensions_income)): ?>

    <?php displayArrayAsList($pensions_income); ?>

    <p><a href="/pensions-income/create-and-amend-pensions-income">Edit</a></p>

<?php else: ?>

    <p>No Pensions Income to display</p>

    <p><a href="/pensions-income/create-and-amend-pensions-income">Add</a></p>

<?php endif; ?>


<p><a href="/pensions-income/confirm-delete-pensions-income">Delete</a></p>