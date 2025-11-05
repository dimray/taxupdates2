<form class="generic-form hmrc-connection" action="/savings/process-create-amend-uk-savings-account-annual-summary"
    method="POST">

    <input type="hidden" name="account_name" value="<?= esc($account_name) ?>">
    <input type="hidden" name="account_id" value="<?= esc($account_id) ?>">

    <div class="form-input">
        <label for="taxed_interest">Taxed UK Interest</label>
        <input type="number" name="taxed_interest" id="taxed_interest"
            value="<?= esc($uk_interest['taxedUkInterest'] ?? '') ?>" min="0" max="99999999999.99" step="0.01">
    </div>

    <div class="form-input">
        <label for="untaxed_interest">Untaxed UK Interest</label>
        <input type="number" name="untaxed_interest" id="untaxed_interest"
            value="<?= esc($uk_interest['untaxedUkInterest'] ?? '') ?>" min="0" max="99999999999.99" step="0.01">
    </div>

    <button class="form-button" type="submit">Submit</button>
</form>


<p><a class="hmrc-connection" href="/savings/list-uk-savings-accounts">Cancel</a></p>