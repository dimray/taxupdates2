<?php if (!empty($other_expenses)): ?>

    <?php displayArrayAsList($other_expenses); ?>

    <br>

    <p><a href="/expenses/create-and-amend-other-expenses">Edit Expenses</a></p>

<?php else: ?>

    <p>No Other Expenses to display</p>

    <p><a href="/expenses/create-and-amend-other-expenses">Add Expenses</a></p>

<?php endif; ?>


<p><a href="/expenses/confirm-delete-other-expenses">Delete Expenses</a></p>