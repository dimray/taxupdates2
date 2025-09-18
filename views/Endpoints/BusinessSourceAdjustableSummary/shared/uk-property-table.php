<div class="short-table">

    <table class="number-table desktop-view">



        <tbody>
            <tr>
                <th colspan="2" class="subheading">Income Adjustments</th>
            </tr>


            <tr>
                <td>Rent Received</td>
                <td><?= esc(formatNumber($income['totalRentsReceived'] ?? 0)) ?></td>
            </tr>

            <tr>
                <td>Lease Premiums</td>
                <td><?= esc(formatNumber($income['premiumsOfLeaseGrant'] ?? 0)) ?></td>
            </tr>

            <tr>
                <td>Reverse Premiums</td>
                <td><?= esc(formatNumber($income['reversePremiums'] ?? 0)) ?></td>
            </tr>

            <tr>
                <td>Other Income</td>
                <td><?= esc(formatNumber($income['otherPropertyIncome'] ?? 0)) ?></td>
            </tr>

            <tr>
                <th colspan="2" class="subheading">Expenses Adjustments</th>
            </tr>

            <?php if (isset($expenses['consolidatedExpenses'])): ?>

                <tr>
                    <td>
                        Consolidated Expenses
                    </td>

                    <td>
                        <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                    </td>
                </tr>

            <?php else: ?>

                <tr>
                    <td>Premises Costs</td>
                    <td><?= esc(formatNumber($expenses['premisesRunningCosts'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Repairs And Maintenance</td>
                    <td><?= esc(formatNumber($expenses['repairsAndMaintenance'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Deductible Finance Costs</td>
                    <td><?= esc(formatNumber($expenses['financialCosts'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Professional Fees</td>
                    <td><?= esc(formatNumber($expenses['professionalFees'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Services</td>
                    <td><?= esc(formatNumber($expenses['costOfServices'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Residential Finance Costs</td>
                    <td><?= esc(formatNumber($expenses['residentialFinancialCost'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Other Expenses</td>
                    <td><?= esc(formatNumber($expenses['other'] ?? 0)) ?></td>
                </tr>

                <tr>
                    <td>Travel Costs</td>
                    <td><?= esc(formatNumber($expenses['travelCosts'] ?? 0)) ?></td>
                </tr>

            <?php endif; ?>

            <tr>
                <th colspan="2" class="subheading">Summary</th>
            </tr>

            <tr>
                <td>Total Income Adjustment</td>
                <td><?= esc(formatNumber($total_income)) ?></td>
            </tr>

            <tr>
                <td>Total Expenses Adjustment</td>
                <td><?= esc(formatNumber($total_expenses)) ?></td>
            </tr>

            <tr>
                <td>Net Adjustment</td>
                <td><?= esc(formatNumber($profit)) ?></td>
            </tr>



        </tbody>




    </table>


</div>