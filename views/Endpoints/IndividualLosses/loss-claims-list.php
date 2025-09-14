<?php if (!empty($claims)):  ?>

    <div class="regular-table">
        <table class="left-align-headers desktop-view">
            <thead>
                <tr>

                    <th>Business ID</th>
                    <th>Loss Type</th>
                    <th>Claim Type</th>
                    <th>Loss Year</th>
                    <th>Claim ID</th>
                    <?php if ($sideways_claim_count > 1): ?>
                        <th>Sequence</th>
                    <?php endif; ?>

                </tr>

            </thead>
            <tbody>
                <?php foreach ($claims as $claim): ?>
                    <tr>
                        <td><?= esc($claim['businessId'] ?? "") ?></td>
                        <td><?= esc($claim['typeOfLoss'] ?? "") ?></td>
                        <td><?= esc($claim['typeOfClaim'] ?? "") ?></td>
                        <td><?= esc(formatNumber($claim['taxYearClaimedFor'] ?? "")) ?></td>
                        <td><?= esc($claim['claimId'] ?? "") ?></td>
                        <?php if ($sideways_claim_count > 1): ?>
                            <td><?= esc($claim['sequence'] ?? "")  ?></td>
                        <?php endif; ?>
                        <td>
                            <form action="/individual-losses/edit-loss-claim" method="GET">
                                <input type="hidden" name="type_of_claim" value="<?= $claim['typeOfClaim'] ?>">
                                <input type="hidden" name="claim_id" value="<?= $claim['claimId'] ?>">

                                <input type="hidden" name="business_id" value="<?= $claim['businessId'] ?>">
                                <input type="hidden" name="type_of_loss" value="<?= $claim['typeOfLoss'] ?>">
                                <input type="hidden" name="tax_year_claimed_for" value="<?= $claim['taxYearClaimedFor'] ?>">

                                <button type="submit" class="link">Edit Claim Type</button>
                            </form>
                        </td>
                        <td>
                            <form action="/individual-losses/delete-loss-claim" method="GET">

                                <input type="hidden" name="claim_id" value="<?= $claim['claimId'] ?>">
                                <input type="hidden" name="type_of_claim" value="<?= $claim['typeOfClaim'] ?>">
                                <input type="hidden" name="business_id" value="<?= $claim['businessId'] ?>">
                                <input type="hidden" name="type_of_loss" value="<?= $claim['typeOfLoss'] ?>">
                                <input type="hidden" name="tax_year_claimed_for" value="<?= $claim['taxYearClaimedFor'] ?>">

                                <button type="submit" class="link">Delete Claim</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mobile-view">

            <?php foreach ($claims as $claim): ?>

                <div class="card">

                    <div class="data-row">
                        <div class="label">Business ID</div>
                        <div class="value"><?= esc($claim['businessId'] ?? "") ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Loss Type</div>
                        <div class="value"><?= esc($claim['typeOfLoss'] ?? "") ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Claim Type</div>
                        <div class="value"><?= esc($claim['typeOfClaim'] ?? "") ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Loss Year</div>
                        <div class="value"><?= esc($claim['taxYearClaimedFor'] ?? "") ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Claim ID</div>
                        <div class="value"><?= esc($claim['claimId'] ?? "") ?></div>
                    </div>
                    <?php if ($sideways_claim_count > 1): ?>
                        <div class="data-row">
                            <div class="label">Sequence</div>
                            <div class="value"><?= esc($claim['sequence'] ?? "") ?></div>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="display-inline">

                    <form action="/individual-losses/edit-loss-claim" method="GET">
                        <input type="hidden" name="type_of_claim" value="<?= $claim['typeOfClaim'] ?>">
                        <input type="hidden" name="claim_id" value="<?= $claim['claimId'] ?>">

                        <input type="hidden" name="business_id" value="<?= $claim['businessId'] ?>">
                        <input type="hidden" name="type_of_loss" value="<?= $claim['typeOfLoss'] ?>">
                        <input type="hidden" name="tax_year_claimed_for" value="<?= $claim['taxYearClaimedFor'] ?>">

                        <button type="submit" class="link">Edit Claim Type</button>
                    </form>

                    <form action="/individual-losses/delete-loss-claim" method="GET">

                        <input type="hidden" name="claim_id" value="<?= $claim['claimId'] ?>">

                        <input type="hidden" name="type_of_claim" value="<?= $claim['typeOfClaim'] ?>">
                        <input type="hidden" name="business_id" value="<?= $claim['businessId'] ?>">
                        <input type="hidden" name="type_of_loss" value="<?= $claim['typeOfLoss'] ?>">
                        <input type="hidden" name="tax_year_claimed_for" value="<?= $claim['taxYearClaimedFor'] ?>">

                        <button type="submit" class="link">Delete Claim</button>
                    </form>

                </div>

            <?php endforeach; ?>

        </div>


    </div>

<?php else: ?>

    <p>No loss claims found</p>

    <p><a href="/individual-losses/loss-claims">Make A Loss Claim</a></p>

<?php endif; ?>

<hr>



<?php if ($sideways_claim_count > 1) : ?>
    <br>

    <form action="/individual-losses/edit-loss-claims-sequence" method="POST">

        <input type="hidden" name="claims_string" value="<?= esc($claims_string) ?>">
        <button class="link" type="submit">Change
            The Order Of
            Loss Claims</button>

    </form>

<?php endif; ?>

<p><a href="/individual-losses/loss-claims">Add A Claim</a></p>