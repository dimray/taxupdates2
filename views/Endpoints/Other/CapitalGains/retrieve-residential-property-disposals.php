<?php if (!empty($customer_added_disposals)): ?>

<h2>Customer Added Disposals</h2>

<?php displayArrayAsList($customer_added_disposals); ?>

<p><a href="/capital-gains/create-amend-customer-added-residential-property-disposals">Edit Customer Added Disposals</a>
</p>
<p><a href="/capital-gains/confirm-delete-customer-added-residential-property-disposals">Delete Customer Added
        Disposals</a></p>

<?php endif; ?>

<?php if (!empty($real_time_disposals)): ?>

<h2>Reported Residential Property Disposals</h2>

<?php displayArrayAsList($real_time_disposals); ?>

<p><a href="/capital-gains/create-amend-cgt-on-residential-property-overrides">Edit Reported Disposals</a></p>
<p><a href="/capital-gains/confirm-delete-cgt-on-residential-property-overrides">Delete Reported Disposals</a></p>

<?php endif; ?>

<?php if (empty($customer_added_disposals) && empty($real_time_disposals)): ?>

<p>No Residential Property Disposals to display</p>

<p><a href="/capital-gains/create-amend-customer-added-residential-property-disposals">Add</a></p>

<?php endif; ?>

<p><a href="/year-end/capital-gains">Other Capital Gains</a></p>