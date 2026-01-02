<p>You have uploaded data for the following properties:</p>
<ul class="list">
    <?php foreach ($selected_properties as $property): ?>
        <li><?= esc($property['propertyName']) . " (" . $property['countryCode'] . ")" ?></li>
    <?php endforeach; ?>
</ul>

<p>Do you need to add data for any other properties?</p>

<p><a href="/uploads/create-cumulative-upload">Yes, add another property</a></p>
<p><a href="/uploads/approve-foreign-property">No, continue to summary</a></p>