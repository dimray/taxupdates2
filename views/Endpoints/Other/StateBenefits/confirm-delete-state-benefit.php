<p>Confirm you wish to delete <?= formatCamelCase($benefit_type) ?></p>

<form class="hmrc-connection" action="/state-benefits/delete-state-benefit" method="POST">

    <input type="hidden" name="benefit_id" value="<?= esc($benefit_id) ?>">
    <input type="hidden" name="benefit_type" value="<?= esc($benefit_type) ?>">
    <button class="delete-button confirm-delete" type="submit">Delete</button>
</form>

<p><a class="hmrc-connection" href=" /state-benefits/list-state-benefits">Cancel</a></p>