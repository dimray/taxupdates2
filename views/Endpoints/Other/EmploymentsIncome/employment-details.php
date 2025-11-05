<?php displayArrayAsList($employment_data); ?>

<p class="underline bold large">Actions:</p>

<form action="/employments-income/create-amend-employment-financial-details" method="POST">
    <button class="link" type="submit">Add Financial Details</button>
</form>

<br>

<?php if ($employment_type === "custom"): ?>

    <form action="/employments-income/confirm-delete-custom-employment" method="GET">
        <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">
        <button class="link" type="submit">Delete This Employment</button>
    </form>

<?php endif; ?>

<form action="/dividends-income/retrieve-directorship-and-dividend-information" method="GET">
    <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">
    <input type="hidden" name="employment_id" value="<?= esc($employment_id) ?>">
    <button class="link" type="submit">Company Director</button>
</form>

<p><a class="hmrc-connection" href="/employments-income/list-employments">Back</a></p>