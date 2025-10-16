<?php if (!empty($charitable_giving)): ?>

    <?php displayArrayAsList($charitable_giving); ?>

    <p><a href="/reliefs/create-and-amend-charitable-giving-tax-relief">Edit Reliefs</a></p>

<?php else: ?>

    <p>No Charitable Giving Tax Reliefs to display</p>

    <p><a href="/reliefs/create-and-amend-charitable-giving-tax-relief">Add Reliefs</a></p>

<?php endif; ?>

<p><a href="/reliefs/confirm-delete-charitable-giving-tax-relief">Delete Reliefs</a></p>

<p><a href="/reliefs/index">Cancel</a></p>