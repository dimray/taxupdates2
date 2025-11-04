<p>You can use either: </p>

<div class="list">
    <ul>
        <li>cash accounting (based on money received and paid out)</li>
        <li>accrual accounting (based on dates income and expenses are incurred)</li>
    </ul>
</div>

<p>The accounting type can be changed after the tax year has ended and before the Final Declaration for the
    year is submitted.</p>

<p>This business currently uses <b><?= esc($accounting_type ?? 'unknown') ?> accounting</b>, and will use
    <b><?= esc($new_accounting_type ?? 'unknown') ?> accounting</b> if you change the type.
</p>


<p>Select which tax
    year you want the change to
    take effect,
    then confirm.</p>

<hr>

<br>

<!-- don't use shared tax year form as don't want to update period every time the year is changed-->
<form class="inline-form-center hmrc-connection" action="/business-details/update-accounting-type" method="GET">

    <input type="hidden" name="new_accounting_type" value="<?= $new_accounting_type ?>">

    <label for="select_tax_year">Tax Year Of Change:</label>

    <select name="tax_year" id="select_tax_year">
        <option value="<?= $previous_year ?>"><?= $previous_year ?></option>
        <option value="<?= $previous_previous_year ?>"><?= $previous_previous_year ?></option>
    </select>

    <button type="submit">Confirm</button>

</form>

<br>

<hr>




<a class="hmrc-connection" href="/business-details/retrieve-business-details">Cancel</a>