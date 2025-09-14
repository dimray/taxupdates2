<?php if (!empty($claims  && $sideways_claim_count > 1)):  ?>




    <div class="regular-table">

        <div class="desktop-view">

            <p>Enter the order in which claims should be used in the 'sequence' column. The sequence
                must start with 1
                and have no
                gaps (e.g. 1,2,3 or 2,3,1).</p>

            <form action="/individual-losses/update-loss-claims-sequence" method="POST">

                <table class="left-align-headers">

                    <thead>
                        <tr>

                            <th>Business ID</th>
                            <th>Loss Type</th>
                            <th>Claim Type</th>
                            <th>Loss Year</th>
                            <th>Claim ID</th>
                            <th>Sequence</th>

                        </tr>

                    </thead>
                    <tbody>
                        <?php foreach ($claims as $index => $claim): ?>
                            <tr>
                                <td><?= esc($claim['businessId'] ?? "") ?></td>
                                <td><?= esc($claim['typeOfLoss'] ?? "") ?></td>
                                <td><?= esc($claim['typeOfClaim'] ?? "") ?></td>
                                <td><?= esc(formatNumber($claim['taxYearClaimedFor'] ?? "")) ?></td>
                                <td><?= esc($claim['claimId'] ?? "") ?></td>

                                <td>

                                    <input type="hidden" name="claims[<?= $index ?>][claimId]"
                                        value="<?= esc($claim['claimId']) ?>">
                                    <input type="number" name="claims[<?= $index ?>][sequence]" step="1" min="1"
                                        max="<?= count($claims) ?>" pattern="\d*" inputmode="numeric" required>

                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>

                </table>


                <?php include ROOT_PATH . "views/shared/errors.php"; ?>

                <button class="button" type="submit">Submit</button>

            </form>

        </div>









        <div class="mobile-view">

            <p>Enter the order in which claims should be used in the 'sequence' boxes. The sequence
                must start with 1
                and have no
                gaps (e.g. 1,2,3 or 2,3,1). Every loss must include a sequence number.</p>

            <form action="/individual-losses/update-loss-claims-sequence" method="POST">

                <?php foreach ($claims as $index => $claim): ?>

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

                        <div class="data-row">
                            <div class="label">Sequence</div>

                            <input type="hidden" name="claims[<?= $index ?>][claimId]" value="<?= esc($claim['claimId']) ?>">
                            <div class="value">
                                <input type="number" name="claims[<?= $index ?>][sequence]" step="1" min="1"
                                    max="<?= count($claims) ?>" pattern="\d*" inputmode="numeric" required>
                            </div>
                        </div>


                    </div>

                <?php endforeach; ?>

                <?php include ROOT_PATH . "views/shared/errors.php"; ?>

                <button class="button" type="submit">Submit</button>

            </form>

        </div>

    </div>




<?php else: ?>

    <p>You do not have more than one sideways loss claim.</p>

    <p><a href="https://taxupdates.test/individual-losses/list-loss-claims">View all losses</a></p>



<?php endif; ?>


<p><a href="/individual-losses/list-loss-claims">Cancel</a></p>