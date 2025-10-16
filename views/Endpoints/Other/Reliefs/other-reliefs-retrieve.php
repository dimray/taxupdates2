<?php if (!empty($other_reliefs)): ?>

    <?php displayArrayAsList($other_reliefs); ?>

    <p><a href="/reliefs/create-and-amend-other-reliefs">Edit Reliefs</a></p>

<?php else: ?>

    <p>No Other Reliefs to display</p>

    <p><a href="/reliefs/create-and-amend-other-reliefs">Add Reliefs</a></p>

<?php endif; ?>


<p><a href="/reliefs/confirm-delete-other-reliefs">Delete Reliefs</a></p>

<p><a href="/reliefs/index">Cancel</a></p>