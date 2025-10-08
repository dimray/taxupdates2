<?php if (!empty($foreign_income)): ?>

    <?php displayArrayAsList($foreign_income); ?>


    <p><a href="/foreign-income/create-and-amend-foreign-income">Edit Foreign Income</a></p>

<?php else: ?>

    <p>No Foreign Income to display</p>



    <p><a href="/foreign-income/create-and-amend-foreign-income">Add Foreign Income</a></p>

<?php endif; ?>


<p><a href="/foreign-income/confirm-delete-foreign-income">Delete Foreign Income</a></p>