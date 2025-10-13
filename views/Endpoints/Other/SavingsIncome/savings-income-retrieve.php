<?php if (!empty($savings_income)): ?>

    <?php displayArrayAsList($savings_income); ?>

    <p><a href="/savings/create-amend-savings-income">Edit</a></p>

<?php else: ?>

    <p>No Interest to display</p>

    <p><a href="/savings/create-amend-savings-income">Add</a></p>

<?php endif; ?>


<p><a href="/savings/confirm-delete-savings-income">Delete</a></p>

<p><a href="/savings/list-uk-savings-accounts">UK Bank Interest</a></p>