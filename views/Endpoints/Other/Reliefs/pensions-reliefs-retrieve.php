<?php if (!empty($pensions_reliefs)): ?>

    <?php displayArrayAsList($pensions_reliefs); ?>

    <p><a href="/reliefs/create-and-amend-pensions-reliefs">Edit Reliefs</a></p>

<?php else: ?>

    <p>No Pensions Reliefs to display</p>

    <p><a href="/reliefs/create-and-amend-pensions-reliefs">Add Reliefs</a></p>

<?php endif; ?>


<p><a href="/reliefs/confirm-delete-pensions-reliefs">Delete Reliefs</a></p>

<p><a href="/reliefs/index">Cancel</a></p>