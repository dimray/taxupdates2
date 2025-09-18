<p>You have uploaded data for the following countries:</p>
<ul class="list">
    <?php foreach ($country_names as $country): ?>
        <li><?= esc($country) ?></li>
    <?php endforeach; ?>
</ul>

<p>Do you need to add data for any other countries?</p>

<p><a href="/business-source-adjustable-summary/create">Yes, add another country</a></p>
<p><a href="/business-source-adjustable-summary/finalise">No, finalise adjustments</a></p>