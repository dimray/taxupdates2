<p>You can use either: </p>

<ul>
    <li>standard quarterly periods (6 April to 5 April)</li>
    <li>calendar quarterly periods (1 April to 31 March) </li>
</ul>

<p>Note that the quarterly period cannot be changed after a submission has been
    made for the year.</p>

<p>This business currently uses <?= esc($current_period ?? 'unknown') ?>
    periods, and will use <?= $new_period ?? 'unknown' ?> periods if you change the reporting period.</p>

<p>Select which tax
    year you want the change to
    take effect,
    then confirm.</p>

<!-- don't use shared tax year form as don't want to update period every time the year is changed-->
<form class="inline-form" action="/business-details/update-reporting-period" method="GET">

    <input type="hidden" name="new_period" value="<?= $new_period ?>">

    <label for="tax_year">Tax Year Of Change:</label>

    <select name="tax_year" id="select_tax_year">
        <option value="<?= $next_year ?>"><?= $next_year ?></option>
        <option value="<?= $tax_year ?>" selected><?= $tax_year ?></option>
        <option value="<?= $previous_year ?>"><?= $previous_year ?></option>
    </select>

    <button type="submit">Confirm</button>

</form>


<br>

<a href="/business-details/retrieve-business-details">Cancel</a>