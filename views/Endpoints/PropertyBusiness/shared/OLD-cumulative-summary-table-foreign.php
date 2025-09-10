<div class="long-table">
    <table class="number-table">
        <thead>
            <tr>
                <th></th>
                <?php foreach ($foreign_property_data as $entry): ?>
                    <th class="country-code"><?= esc($entry['countryCode']) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <div class="mobile-view">
                <?php foreach ($foreign_property_data as $entry): ?>
                    <th class="mobile-view country-code"><?= esc($entry['countryCode']) ?></th>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($income_fields)): ?>
                <tr>
                    <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Income</th>
                </tr>
                <?php foreach ($income_fields as $label): ?>
                    <tr>
                        <td class="desktop-view"><?= esc(formatApiString($label)) ?></td>
                        <?php foreach ($foreign_property_data as $entry): ?>
                            <td data-label="<?= esc(formatApiString($label)) ?>" class="table-number">
                                <?= esc(formatNumber($entry['income'][$label] ?? 0)) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>


            <?php endif; ?>

            <?php if (!empty($expense_fields)): ?>
                <tr>
                    <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Expenses</th>
                </tr>
                <?php foreach ($expense_fields as  $label): ?>
                    <tr>
                        <td class="desktop-view"><?= esc(formatApiString($label)) ?></td>
                        <?php foreach ($foreign_property_data as $entry): ?>
                            <td data-label="<?= esc(formatApiString($label)) ?>" class="table-number">
                                <?= esc(formatNumber($entry['expenses'][$label] ?? 0)) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <tr>
                <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Summary</th>
            </tr>

            <tr>
                <td class="desktop-view">Total Income</td>
                <?php foreach ($foreign_property_data as $entry): ?>
                    <?php
                    $income_total = array_sum($entry['income'] ?? []);
                    ?>
                    <td data-label="Total Income" class="table-number"><?= esc(formatNumber($income_total)) ?></td>
                <?php endforeach; ?>
            </tr>

            <tr>
                <td class="desktop-view">Total Expenses</td>
                <?php foreach ($foreign_property_data as $entry): ?>
                    <?php
                    $expenses_total = array_sum($entry['expenses'] ?? []);
                    ?>
                    <td data-label="Total Expenses" class="table-number"><?= esc(formatNumber($expenses_total)) ?></td>
                <?php endforeach; ?>
            </tr>

            <tr>
                <td class="desktop-view">Profit</td>
                <?php foreach ($foreign_property_data as $entry): ?>
                    <?php
                    $profit = (array_sum($entry['income'] ?? []) - array_sum($entry['expenses'] ?? []));
                    ?>
                    <td data-label="Profit" class="table-number"><?= esc(formatNumber($profit)) ?></td>
                <?php endforeach; ?>
            </tr>

            <?php if (!empty($finance_fields)): ?>
                <tr>
                    <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Finance Costs</th>
                </tr>
                <?php foreach ($finance_fields as  $label): ?>
                    <tr>
                        <td class="desktop-view"><?= esc(formatApiString($label)) ?></td>
                        <?php foreach ($foreign_property_data as $entry): ?>
                            <td data-label="<?= esc(formatApiString($label)) ?>" class="table-number">
                                <?= esc(formatNumber($entry['financeCosts'][$label] ?? 0)) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <tr>
                <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Other</th>
            </tr>

            <tr>
                <td class="desktop-view">Foreign Tax Credit Relief</td>
                <?php foreach ($foreign_property_data as $entry): ?>
                    <td data-label="Foreign Tax Credit Relief" class="table-boolean">
                        <?= isset($entry['income']['foreignTaxCreditRelief']) && $entry['income']['foreignTaxCreditRelief'] ? 'Claimed' : 'Not Claimed' ?>
                    </td>
                <?php endforeach; ?>
            </tr>

        </tbody>
    </table>
</div>