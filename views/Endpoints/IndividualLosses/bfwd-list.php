<?php if (!empty($losses)):  ?>

    <div class="long-table">
        <table class="left-align-headers desktop-view">
            <thead>
                <tr>
                    <th>Loss ID</th>
                    <th>Business ID</th>
                    <th>Loss Type</th>
                    <th>Amount</th>
                    <th>Bfwd From</th>

                </tr>

            </thead>
            <tbody>
                <?php foreach ($losses as $loss): ?>
                    <tr>
                        <td><?= esc($loss['lossId']) ?? "" ?></td>
                        <td><?= esc($loss['businessId']) ?? "" ?></td>
                        <td><?= esc($loss['typeOfLoss']) ?? "" ?></td>
                        <td><?= esc(formatNumber($loss['lossAmount'])) ?? "" ?></td>
                        <td><?= esc($loss['taxYearBroughtForwardFrom']) ?? "" ?></td>
                        <td>
                            <form action="/individual-losses/edit-brought-forward-loss" method="GET">
                                <input type="hidden" name="loss_id" value="<?= esc($loss['lossId']) ?>">
                                <input type="hidden" name="loss_amount" value="<?= esc($loss['lossAmount']) ?>">
                                <input type="hidden" name="loss_year" value="<?= esc($loss['taxYearBroughtForwardFrom']) ?>">
                                <button class="link" type="submit">Edit Amount</button>
                            </form>
                        </td>
                        <td>
                            <form action="/individual-losses/delete-brought-forward-loss" method="GET">
                                <input type="hidden" name="loss_id" value="<?= esc($loss['lossId']) ?>">
                                <input type="hidden" name="loss_year" value="<?= esc($loss['taxYearBroughtForwardFrom']) ?>">
                                <button class="link" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mobile-view">
            <?php foreach ($losses as $loss): ?>

                <div class="card">

                    <div class="data-row">
                        <div class="label">Loss ID</div>
                        <div class="value"><?= esc($loss['lossId']) ?? "" ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Business ID</div>
                        <div class="value"><?= esc($loss['businessId']) ?? "" ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Loss Type</div>
                        <div class="value"><?= esc($loss['typeOfLoss']) ?? "" ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Amount</div>
                        <div class="value"><?= esc(formatNumber($loss['lossAmount'])) ?? "" ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Bfwd From</div>
                        <div class="value"><?= esc($loss['taxYearBroughtForwardFrom']) ?? "" ?></div>
                    </div>

                </div>

                <div class="display-inline">
                    <form action="/individual-losses/edit-brought-forward-loss" method="GET">
                        <input type="hidden" name="loss_id" value="<?= esc($loss['lossId']) ?>">
                        <input type="hidden" name="loss_amount" value="<?= esc($loss['lossAmount']) ?>">
                        <input type="hidden" name="loss_year" value="<?= esc($loss['taxYearBroughtForwardFrom']) ?>">
                        <button class="link" type="submit">Edit Amount</button>
                    </form>

                    <form action="/individual-losses/delete-brought-forward-loss" method="GET">
                        <input type="hidden" name="loss_id" value="<?= esc($loss['lossId']) ?>">
                        <input type="hidden" name="loss_year" value="<?= esc($loss['taxYearBroughtForwardFrom']) ?>">
                        <button class="link" type="submit">Delete</button>
                    </form>
                </div>

                <hr>


            <?php endforeach; ?>
        </div>
    </div>

<?php else: ?>

    <p>No losses found</p>

<?php endif; ?>


<hr>

<p>Showing losses brought forward from <?= $loss_year ?></p>

<form class="inline-form" action="/individual-losses/list-brought-forward-losses" method="GET">

    <div class="form-input">
        <label for="loss_year">Show losses from a different year</label>
        <select name="loss_year" id="loss_year" required>
            <?php foreach ($tax_years as $year): ?>
                <option value="<?= esc($year) ?>" <?= $year === $loss_year ? 'selected' : '' ?>>
                    <?= esc($year) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit">Submit</button>
</form>

<hr>

<p>HMRC's systems do
    not automatically
    transfer pre-Making Tax Digital losses, therefore you need to add brought forward losses before they will
    show here. Losses
    can be added in the section relating to the business which made the loss.
</p>

<p><a href="/individual-losses/create-brought-forward-loss">Add A Pre-MTD Loss</a></p>