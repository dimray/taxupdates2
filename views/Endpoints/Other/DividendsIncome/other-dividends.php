<?php if (!empty($dividends_income)): ?>

    <?php displayArrayAsList($dividends_income); ?>


    <p><a href="/dividends-income/create-amend-dividends-income">Edit Dividends</a></p>

<?php else: ?>

    <p>No Other Dividends to display</p>



    <p><a href="/dividends-income/create-amend-dividends-income">Add Dividends</a></p>

<?php endif; ?>


<p><a href="/dividends-income/confirm-delete-dividends-income">Delete Dividends</a></p>



<p><a href="/dividends-income/index">Back</a></p>