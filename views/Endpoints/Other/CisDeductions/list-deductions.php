<?php if (!empty($cis_totals)): ?>

    <h2>Total CIS Deductions</h2>

    <?php displayArrayAsList($cis_totals); ?>

    <hr>

<?php endif; ?>

<?php if (!empty($cis_deductions)): ?>

    <h2>CIS Deductions By Contractor</h2>

    <?php foreach ($cis_deductions as $deduction): ?>

        <?php
        $period_data = $deduction['periodData'] ?? [];
        unset($deduction['periodData']);
        unset($deduction['fromDate']);
        unset($deduction['toDate']);
        ?>

        <?php displayArrayAsList($deduction); ?>

        <div class="long-table">

            <table class="number-table desktop-view">

                <thead>
                    <tr>
                        <th>Period From</th>
                        <th>Period To</th>
                        <th>Gross Amount</th>
                        <th>Cost Of Materials</th>
                        <th>CIS Deducted</th>



                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($period_data as $period): ?>
                        <tr>
                            <td><?= esc(formatDate($period['deductionFromDate'] ?? "")) ?></td>
                            <td><?= esc(formatDate($period['deductionToDate'] ?? "")) ?></td>
                            <td class="table-number">£<?= formatNumber($period['grossAmountPaid'] ?? 0) ?></td>
                            <td class="table-number">£<?= formatNumber($period['costOfMaterials'] ?? 0) ?></td>
                            <td class="table-number">£<?= formatNumber($period['deductionAmount'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mobile-view">

                <?php foreach ($period_data as $period): ?>

                    <div class="card">

                        <div class="data-row">
                            <div class="label">Period From</div>
                            <div class="value"><?= esc(formatDate($period['deductionFromDate'] ?? "")) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Period To</div>
                            <div class="value"><?= esc(formatDate($period['deductionToDate'] ?? "")) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Gross Amount</div>
                            <div class="value">£<?= formatNumber($period['grossAmountPaid'] ?? 0) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">Cost Of Materials</div>
                            <div class="value">£<?= formatNumber($period['costOfMaterials'] ?? 0) ?></div>
                        </div>

                        <div class="data-row">
                            <div class="label">CIS Deducted</div>
                            <div class="value">£<?= formatNumber($period['deductionAmount'] ?? 0) ?></div>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

        <!-- within a submission, the submission id appears to be the same for every period -->
        <?php if (isset($period_data[0]['submissionId'])): ?>

            <br>

            <!-- Edit Submission Form -->
            <form action="/cis-deductions/amend-cis-deductions" method="POST">
                <input type="hidden" name="submission_id" value="<?= esc($period_data[0]['submissionId'] ?? '') ?>">
                <input type="hidden" name="contractor" value="<?= esc(json_encode($deduction)) ?>">
                <input type="hidden" name="period_data" value="<?= esc(json_encode($period_data)) ?>">
                <button class="link" type="submit">Edit Deductions By This Contractor</button>
            </form>

            <!-- Delete Submission Form -->
            <form action="/cis-deductions/confirm-delete-cis-deductions" method="POST">

                <input type="hidden" name="submission_id" value="<?= esc($period_data[0]['submissionId'] ?? '') ?>">
                <input type="hidden" name="contractor" value="<?= esc($deduction['contractorName']) ?>">
                <button class="link" type="submit">Delete Deductions By This Contractor</button>
            </form>
        <?php endif; ?>

        <br>
        <hr>

    <?php endforeach; ?>

<?php endif; ?>

<?php if (empty($cis_totals) && empty($cis_deductions)): ?>

    <p>No CIS deductions to display.</p>

<?php endif; ?>


<p><a href="/cis-deductions/create-cis-deductions">Add A Contractor</a></p>