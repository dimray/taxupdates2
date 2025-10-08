<?php if (!empty($other_income)): ?>

    <?php displayArrayAsList($other_income); ?>

    <p><a href="/other-income/create-and-amend-other-income">Edit</a></p>

<?php else: ?>

    <p>No Other Income to display</p>


    <p><a href="/other-income/create-and-amend-other-income">Add</a></p>

<?php endif; ?>


<p><a href="/other-income/confirm-delete-other-income">Delete</a></p>

<p><a href="/year-end/other-income">Back</a></p>