<p>Confirm you wish to delete Director and Dividend information.</p>

<form action="/dividends-income/delete-directorship-and-dividend-information" method="POST">

    <input type="hidden" name="employment_id" value="<?= $employment_id ?>">

    <button class="delete-button confirm-delete" type="submit">Delete</button>
</form>

<p><a href="/dividends-income/retrieve-directorship-and-dividend-information?<?= $query_string ?>">Cancel</a></p>