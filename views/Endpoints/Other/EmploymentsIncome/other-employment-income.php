<?php if (!empty($other_income)): ?>

    <?php displayArrayAsList($other_income); ?>
    <p><a href="/employments-income/create-amend-other-employment-income">Edit</a></p>

<?php else: ?>

    <p>No other employment income to display.</p>
    <p><a href="/employments-income/create-amend-other-employment-income">Add</a></p>

<?php endif; ?>


<p><a href="/employments-income/delete-other-employment-income">Delete</a></p>


<p><a href="/employments-income/index">Back</a></p>