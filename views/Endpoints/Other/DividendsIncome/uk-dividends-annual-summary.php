<?php if (!empty($uk_dividends)): ?>

    <?php displayArrayAsList($uk_dividends); ?>

    <p><a href="/dividends-income/create-amend-uk-dividends-annual-summary">Edit Dividends</a></p>

<?php else: ?>

    <p>No UK Dividends to display</p>

    <p><a href="/dividends-income/create-amend-uk-dividends-annual-summary">Add Dividends</a></p>

<?php endif; ?>


<p><a href="/dividends-income/confirm-delete-uk-dividends-income-annual-summary">Delete Dividends</a></p>

<p><a href="/dividends-income/index">Back</a></p>