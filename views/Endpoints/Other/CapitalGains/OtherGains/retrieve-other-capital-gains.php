<h2>Disposals Other Than Residential Property</h2>

<?php if (!empty($other_capital_gains)): ?>

<?php displayArrayAsList($other_capital_gains); ?>

<p><a href="/capital-gains/create-and-amend-other-capital-gains">Edit Gains</a></p>

<?php else: ?>

<p>No Other Capital Gains to display</p>

<p><a href="/capital-gains/create-and-amend-other-capital-gains">Add Gains</a></p>

<?php endif; ?>

<p><a href="/capital-gains/confirm-delete-other-capital-gains">Delete Gains</a></p>

<p><a href="/year-end/capital-gains">Residential Property Disposals</a></p>