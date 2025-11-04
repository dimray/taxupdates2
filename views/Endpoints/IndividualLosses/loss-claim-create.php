<form class="generic-form hmrc-connection" action="/individual-losses/process-loss-claim" method="GET">

    <p>You are telling HMRC how to allocate a loss made by this business in <b><?= esc($loss_year) ?></b>. If the loss
        was not incurred
        in <?= esc($loss_year) ?>,
        change the tax
        year at the top of this page.</p>


    <div>
        <?php if ($type_of_business === "self-employment"): ?>

            <div class="form-input">
                <label for="type_of_claim">Use Of Loss:</label>
                <select name="type_of_claim" id="type_of_claim">

                    <option value="carry-forward">Carry Forward</option>

                    <option value="carry-sideways">Carry Sideways</option>

                </select>

            </div>

        <?php else: ?>
            <div class="form-input">
                <label for="type_of_claim">Use Of Loss:</label>
                <select name="type_of_claim" id="type_of_claim">

                    <option value="carry-sideways">Carry Sideways</option>

                    <option value="carry-forward-to-carry-sideways">Carry Forward To Carry Sideways</option>

                </select>

            </div>
        <?php endif; ?>
    </div>

    <button class="form-button" type="submit">Submit Claim</button>

</form>

<p><a href="/individual-losses/loss-claims">Cancel</a></p>