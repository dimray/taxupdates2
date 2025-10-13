<?php if (!empty($disclosures)): ?>

    <?php displayArrayAsList($disclosures); ?>

    <p><a href="/disclosures/create-and-amend-disclosures">Edit Disclosures</a></p>

<?php else: ?>

    <p>No Disclosures to display</p>

    <p><a href="/disclosures/create-and-amend-disclosures">Add Disclosures</a></p>

<?php endif; ?>


<p><a href="/disclosures/confirm-delete-disclosures">Delete Disclosures</a></p>