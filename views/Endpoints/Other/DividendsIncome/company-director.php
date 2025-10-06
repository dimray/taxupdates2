<?php if (!empty($director_dividends)): ?>

<?php displayArrayAsList($director_dividends); ?>

<form action="/dividends-income/create-amend-directorship-and-dividend-information" method="GET">

    <input type="hidden" name="employment_id" value="<?= esc($employment_id) ?>">
    <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">

    <button class="link" method="submit">Edit Director Details And Dividends</button>

</form>



<?php else: ?>

<p>No Company Director information or dividends to display</p>

<form action="/dividends-income/create-amend-directorship-and-dividend-information" method="GET">

    <input type="hidden" name="employment_id" value="<?= esc($employment_id) ?>">
    <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">

    <button class="link" method="submit">Add Director Details And Dividends</button>

</form>

<?php endif; ?>


<form action="/dividends-income/confirm-delete-directorship-and-dividend-information" method="GET">

    <input type="hidden" name="employment_id" value="<?= esc($employment_id) ?>">
    <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">

    <button class="link" method="submit">Delete Director Details And Dividends</button>

</form>

<p><a href="/dividends-income/index">Back</a></p>