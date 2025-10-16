<?php if (!empty($state_benefits) || !empty($customer_added_state_benefits)): ?>

    <div class="long-table">
        <table class="desktop-view number-table">
            <thead>
                <tr>
                    <th></th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Amount</th>
                    <th>Tax Paid</th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($state_benefits)): ?>

                    <tr>
                        <th class="subheading" colspan="5">State Benefits</th>
                    </tr>

                    <?php foreach ($state_benefits as $benefit): ?>

                        <tr>
                            <td><?= esc(formatCamelCase($benefit['benefitType'] ?? '')) ?></td>
                            <td><?= esc(formatDate($benefit['startDate'] ?? '')) ?></td>
                            <td><?= esc(formatDate($benefit['endDate'] ?? '')) ?></td>
                            <td class="table-number"><?= esc(formatNumber($benefit['amount'] ?? '')) ?></td>
                            <td class="table-number"><?= esc(formatNumber($benefit['taxPaid'] ?? '')) ?></td>

                            <?php if (isset($benefit['dateIgnored'])): ?>
                                <!-- ignore is get request because it goes to confirm, which is post. Unignore is post as has no confirmation -->

                                <td>
                                    <form action="/state-benefits/unignore-state-benefit" method="POST">
                                        <input type="hidden" name="benefit_id" value="<?= esc($benefit['benefitId']) ?>">
                                        <input type="hidden" name="benefit_type" value="<?= esc($benefit['benefitType'] ?? '') ?>">

                                        <button type="submit" class="link">Unignore</button>
                                    </form>
                                </td>

                            <?php else: ?>


                                <td>
                                    <form action="/state-benefits/confirm-ignore-state-benefit" method="GET">
                                        <input type="hidden" name="benefit_id" value="<?= esc($benefit['benefitId']) ?>">
                                        <input type="hidden" name="benefit_type" value="<?= esc($benefit['benefitType'] ?? '') ?>">

                                        <button type="submit" class="link">Ignore</button>
                                    </form>
                                </td>

                            <?php endif; ?>



                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                <?php if (!empty($customer_added_state_benefits)): ?>
                    <tr>
                        <th class="subheading" colspan="5">User Added</th>
                    </tr>


                    <?php foreach ($customer_added_state_benefits as $added_benefit): ?>

                        <tr>
                            <td><?= esc(formatCamelCase($added_benefit['benefitType'] ?? '')) ?></td>
                            <td><?= esc(formatDate($added_benefit['startDate'] ?? '')) ?> </td>
                            <td><?= esc(formatDate($added_benefit['endDate'] ?? '')) ?> </td>
                            <td class="table-number"><?= esc(formatNumber($added_benefit['amount'] ?? '')) ?> </td>
                            <td class="table-number"><?= esc(formatNumber($added_benefit['taxPaid'] ?? '')) ?> </td>

                            <?php if (isset($added_benefit['amount']) || isset($added_benefit['taxPaid'])): ?>

                                <td>
                                    <form action="/state-benefits/confirm-delete-state-benefit" method="GET">
                                        <input type="hidden" name="benefit_id" value="<?= esc($added_benefit['benefitId'] ?? '') ?>">
                                        <input type="hidden" name="benefit_type"
                                            value="<?= esc($added_benefit['benefitType'] ?? '') ?>">
                                        <button type="submit" class="link">Delete</button>
                                    </form>
                                </td>

                            <?php else: ?>

                                <td>
                                    <form action="/state-benefits/amend-state-benefit-amounts" method="GET">
                                        <input type="hidden" name="benefit_id" value="<?= esc($added_benefit['benefitId'] ?? '') ?>">
                                        <input type="hidden" name="benefit_type"
                                            value="<?= esc($added_benefit['benefitType'] ?? '') ?>">


                                        <button type="submit" class="link">Add Amount</button>
                                    </form>
                                </td>

                            <?php endif; ?>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

        <div class="mobile-view">

            <?php if (!empty($state_benefits)): ?>
                <h2>State Benefits</h2>
                <?php foreach ($state_benefits as $benefit): ?>

                    <div class="card">

                        <div class="data-row">
                            <div class="label">Benefit</div>
                            <div class="value"><?= esc(formatCamelCase($benefit['benefitType'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">From Date</div>
                            <div class="value"><?= esc(formatDate($benefit['startDate'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">To Date</div>
                            <div class="value"><?= esc(formatDate($benefit['endDate'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Amount</div>
                            <div class="value"><?= esc(formatNumber($benefit['amount'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Tax Paid</div>
                            <div class="value"><?= esc(formatNumber($benefit['taxPaid'] ?? '')) ?></div>
                        </div>

                    </div>

                    <?php if (isset($benefit['dateIgnored'])): ?>

                        <form action="/state-benefits/unignore-state-benefit" method="POST">
                            <input type="hidden" name="benefit_id" value="<?= esc($benefit['benefitId']) ?>">
                            <input type="hidden" name="benefit_type" value="<?= esc($benefit['benefitType'] ?? '') ?>">

                            <button type="submit" class="link">Unignore</button>
                        </form>

                    <?php else: ?>

                        <form action="/state-benefits/confirm-ignore-state-benefit" method="GET">
                            <input type="hidden" name="benefit_id" value="<?= esc($benefit['benefitId']) ?>">
                            <input type="hidden" name="benefit_type" value="<?= esc($benefit['benefitType'] ?? '') ?>">

                            <button type="submit" class="link">Ignore</button>
                        </form>

                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($customer_added_state_benefits)): ?>

                <h3>User Added</h3>
                <?php foreach ($customer_added_state_benefits as $added_benefit): ?>

                    <div class="card">

                        <div class="data-row">
                            <div class="label">Benefit</div>
                            <div class="value"><?= esc(formatCamelCase($added_benefit['benefitType'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">From Date</div>
                            <div class="value"><?= esc(formatDate($added_benefit['startDate'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">To Date</div>
                            <div class="value"><?= esc(formatDate($added_benefit['endDate'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Amount</div>
                            <div class="value"><?= esc(formatNumber($added_benefit['amount'] ?? '')) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Tax Paid</div>
                            <div class="value"><?= esc(formatNumber($added_benefit['taxPaid'] ?? '')) ?></div>
                        </div>

                    </div>

                    <?php if (isset($added_benefit['amount']) || isset($added_benefit['taxPaid'])): ?>

                        <form action="/state-benefits/confirm-delete-state-benefit" method="GET">
                            <input type="hidden" name="benefit_id" value="<?= esc($added_benefit['benefitId'] ?? '') ?>">
                            <input type="hidden" name="benefit_type" value="<?= esc($added_benefit['benefitType'] ?? '') ?>">
                            <button type="submit" class="link">Delete</button>
                        </form>

                    <?php else: ?>

                        <form action="/state-benefits/amend-state-benefit-amounts" method="GET">
                            <input type="hidden" name="benefit_id" value="<?= esc($added_benefit['benefitId'] ?? '') ?>">
                            <input type="hidden" name="benefit_type" value="<?= esc($added_benefit['benefitType'] ?? '') ?>">


                            <button type="submit" class="link">Add Amount</button>
                        </form>

                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>


        </div>

    </div>

<?php else: ?>

    <p>No State Benefits found.</p>

<?php endif; ?>

<p><a href="/state-benefits/create-state-benefit">Add Benefit</a></p>