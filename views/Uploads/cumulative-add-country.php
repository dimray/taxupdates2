<p>You have uploaded data for the following countries:</p>
<ul class="list">
    <?php foreach ($country_names as $country): ?>
        <li><?= esc($country) ?></li>
    <?php endforeach; ?>
</ul>

<p>Do you need to add data for any other countries?</p>

<form action="/uploads/create-cumulative-upload" method="GET">
    <button class="link" type="submit">Yes, add another country</button>
</form>

<br>

<form action="/uploads/approve-foreign-property" method="GET">
    <button class="link" type="submit">No, continue to summary</button>
</form>