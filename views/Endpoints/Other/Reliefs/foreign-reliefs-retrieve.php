<?php if (!empty($foreign_reliefs)): ?>

    <?php displayArrayAsList($foreign_reliefs); ?>

    <p><a href="/reliefs/create-and-amend-foreign-reliefs">Edit Reliefs</a></p>

<?php else: ?>

    <p>No Foreign Reliefs to display</p>

    <p><a href="/reliefs/create-and-amend-foreign-reliefs">Add Reliefs</a></p>

<?php endif; ?>


<p><a href="/reliefs/confirm-delete-foreign-reliefs">Delete Reliefs</a></p>

<p><a href="/reliefs/index">Cancel</a></p>