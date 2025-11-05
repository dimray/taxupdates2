<p>Ignoring a State Benefit will exclude it from your tax calculation. Confirm you wish to ignore
    <?= esc(formatCamelCase($benefit_type ?? '')) ?>.
</p>

<form class="hmrc-connection" action="/state-benefits/ignore-state-benefit" method="POST">

    <input type="hidden" name="benefit_id" value="<?= esc($benefit_id) ?>">
    <input type="hidden" name="benefit_type" value="<?= esc($benefit_type) ?>">

    <button type="submit">Ignore</button>
</form>

<p><a class="hmrc-connection" href="/state-benefits/list-state-benefits">Cancel</a></p>