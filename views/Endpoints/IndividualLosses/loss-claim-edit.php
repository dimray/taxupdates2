<?php displayArrayAsList($claim_details); ?>

<form class="generic-form" action="/individual-losses/update-loss-claim" method="GET">

    <input type="hidden" name="claim_id" value="<?= $claim_details['claimId'] ?>">

    <div>

        <div class="form-input">
            <label for="type_of_claim">Update Claim To:</label>
            <select name="type_of_claim" id="type_of_claim" required>

                <?php if ($type_of_claim !== "carry-sideways"): ?>
                    <option value="carry-sideways">Carry Sideways</option>
                <?php endif; ?>

                <?php if ($type_of_claim !== "carry-forward"): ?>
                    <option value="carry-forward">Carry Forward</option>
                <?php endif; ?>


                <?php if ($_SESSION['type_of_business'] !== "self-employment"): ?>
                    <?php if ($type_of_claim !== "carry-forward-to-carry-sideways"): ?>
                        <option value="carry-forward-to-carry-sideways">Carry Forward To Carry Sideways</option>
                    <?php endif; ?>
                <?php endif; ?>
            </select>
        </div>

    </div>

    <button type="submit" class="form-button">Update</button>
</form>

<p><a href="/individual-losses/list-loss-claims">Cancel</a></p>