<p>Are you sure you want to delete the Annual Submission for <?= $tax_year ?>?</p>

<form action="/self-employment/delete-annual-submission" method="POST">

    <input type="hidden" name="delete_annual_submission" value="true">

    <button type="submit" class="confirm-delete">Delete</button>
</form>

<p><a href="/self-employment/retrieve-annual-submission">Cancel</a></p>