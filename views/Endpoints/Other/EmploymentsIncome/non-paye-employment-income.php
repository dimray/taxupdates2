<?php if (!empty($non_paye_income)): ?>

    <?php displayArrayAsList($non_paye_income); ?>

<?php else: ?>

    <p>No non-PAYE employment income to display.</p>

<?php endif; ?>

<p><a href="/employments-income/create-amend-non-paye-employment-income">Add or Edit</a></p>
<p><a href="/employments-income/confirm-delete-non-paye-employment-income">Delete</a></p>


<p><a href="/employments-income/index">Back</a></p>