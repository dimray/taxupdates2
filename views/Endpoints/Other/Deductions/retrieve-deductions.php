<?php if (!empty($deductions)): ?>

    <?php displayArrayAsList($deductions); ?>

    <br>

    <p><a href="/deductions/create-and-amend-deductions">Edit</a></p>

<?php else: ?>

    <p>No Deductions to display</p>

    <br>

    <p><a href="/deductions/create-and-amend-deductions">Add</a></p>

<?php endif; ?>


<p><a href="/deductions/confirm-delete-deductions">Delete</a></p>