<?php if (!empty($periods_of_account)): ?>

    <p>Periods Of Account for this business are as follows:</p>

    <div class="list">

        <?php displayArrayAsList($periods_of_account); ?>
    </div>

    <p><a href="/business-details/create-update-periods-of-account?<?= esc($periods_query_string) ?>">Adjust Periods Of
            Account</a></p>

<?php else: ?>

    <p>No Periods Of Account found</p>

<?php endif; ?>

<p><a href="/business-details/retrieve-business-details">Cancel</a></p>