<?php if (!empty($hicbc)): ?>

    <?php displayArrayAsList($hicbc); ?>

    <p><a href="/charges/create-or-amend-high-income-child-benefit-charge-submission">Edit Child Benefit Charge</a></p>

<?php else: ?>

    <p>No High Income Child Benefit Charge to display</p>

    <p><a href="/charges/create-or-amend-high-income-child-benefit-charge-submission">Add Child Benefit Charge</a></p>

<?php endif; ?>


<p><a href="/charges/confirm-delete-high-income-child-benefit-charge-submission">Delete Child Benefit Charge</a></p>