<p>You are deleting all CIS Deductions for <?= $contractor_name ?> in <?= $tax_year ?> </p>

<form action="/cis-deductions/delete-cis-deductions" method="POST">

    <input type="hidden" name="submission_id" value="<?= $submission_id ?>">

    <button class="delete-button" type="submit">Confirm</button>
</form>

<p><a href="/cis-deductions/retrieve-cis-deductions">Cancel</a></p>