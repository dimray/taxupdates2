<?php if (!empty($pension_charges)): ?>

    <?php displayArrayAsList($pension_charges); ?>

    <p><a href="/charges/create-and-amend-pension-charges">Edit Pension Charges</a></p>

<?php else: ?>

    <p>No Pension Charges to display</p>

    <p><a href="/charges/create-and-amend-pension-charges">Add Pension Charges</a></p>

<?php endif; ?>


<p><a href="/charges/confirm-delete-pension-charges">Delete Pension Charges</a></p>