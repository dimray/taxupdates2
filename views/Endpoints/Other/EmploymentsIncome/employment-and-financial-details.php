<?php displayArrayAsList($employment_data['employment']); ?>

<p class="underline bold large">Actions:</p>

<div class="spacer">

    <form action="/employments-income/create-amend-employment-financial-details" method="POST">
        <input type="hidden" name="employment_data" value="<?= esc(json_encode($employment_data['employment'])) ?>">
        <button class="link" type="submit">Edit Financial Details</button>
    </form>

    <?php if ($employment_type === "hmrc"): ?>

        <form action="/employments-income/confirm-delete-employment-financial-details" method="POST">
            <button class="link" type="submit">Delete Edits To Financial Details</button>
        </form>

        <form action="/employments-income/confirm-ignore-employment">
            <button class="link" type="submit">Ignore This Employment</button>
        </form>

        <form action="/employments-income/confirm-unignore-employment">
            <button class="link" type="submit">Unignore This Employment</button>
        </form>

    <?php elseif ($employment_type === "custom"): ?>

        <form action="/employments-income/confirm-delete-employment-financial-details" method="POST">
            <button class="link" type="submit">Delete Financial Details</button>
        </form>

        <form action="/employments-income/confirm-delete-custom-employment">
            <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">
            <button class="link" type="submit">Delete This Employment</button>
        </form>

    <?php endif; ?>

    <form action="/dividends-income/retrieve-directorship-and-dividend-information" method="GET">
        <input type="hidden" name="employer_name" value="<?= esc($employer_name) ?>">
        <input type="hidden" name="employment_id" value="<?= esc($employment_id) ?>">
        <button class="link" type="submit">Company Director</button>
    </form>

</div>

<hr>

<p><a class="hmrc-connection" href="/employments-income/list-employments">Back</a></p>