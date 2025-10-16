<?php if (!empty($investment_reliefs)): ?>

<?php displayArrayAsList($investment_reliefs); ?>

<p><a href="/reliefs/create-and-amend-relief-investments">Edit Reliefs</a></p>

<?php else: ?>

<p>No Other Income to display</p>

<p><a href="/reliefs/create-and-amend-relief-investments">Add Reliefs</a></p>

<?php endif; ?>


<p><a href="/reliefs/confirm-delete-relief-investments">Delete Reliefs</a></p>

<p><a href="/reliefs/index">Cancel</a></p>