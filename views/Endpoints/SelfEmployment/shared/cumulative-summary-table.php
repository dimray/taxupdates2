 <div class="regular-table">
     <table class="number-table">

         <thead>

             <tr>
                 <th></th>
                 <th>Total Amount</th>
                 <th>Total Disallowed</th>
                 <th>Total Allowed</th>
             </tr>

         </thead>
         <tbody>

             <tr>
                 <th colspan="4" class="subheading">Income</th>
             </tr>

             <tr>
                 <td>Turnover</td>
                 <td data-label="Total Amount"><?= esc(formatNumber($income['turnover'] ?? 0)) ?></td>
                 <td data-label="Total Disallowed"></td>
                 <td data-label="Total Allowed"><?= esc(formatNumber($income['turnover'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td>Other</td>
                 <td data-label="Total Amount"><?= esc(formatNumber($income['other'] ?? 0)) ?></td>
                 <td data-label="Total Disallowed"></td>
                 <td data-label="Total Allowed"><?= esc(formatNumber($income['other'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td>Tax Taken Off Trading Income</td>
                 <td data-label="Total Amount"><?= esc(formatNumber($income['taxTakenOffTradingIncome'] ?? 0)) ?></td>
                 <td data-label="Total Disallowed"></td>
                 <td data-label="Total Allowed"><?= esc(formatNumber($income['taxTakenOffTradingIncome'] ?? 0)) ?></td>
             </tr>



             <tr>
                 <th colspan="4" class="subheading">Expenses</th>
             </tr>

             <?php if (isset($expenses['consolidatedExpenses'])): ?>

                 <tr>
                     <td>
                         Consolidated Expenses
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">

                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                     </td>
                 </tr>



             <?php else: ?>

                 <tr>
                     <td>
                         Cost Of Goods
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['costOfGoods'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['costOfGoodsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">

                         <?= esc(formatNumber(($expenses['costOfGoods'] ?? 0) - ($disallowed['costOfGoodsDisallowable'] ?? 0))) ?>

                     </td>
                 </tr>

                 <tr>
                     <td>
                         Payments To Subcontractors
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['paymentsToSubcontractors'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['paymentsToSubcontractorsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['paymentsToSubcontractors'] ?? 0) - ($disallowed['paymentsToSubcontractorsDisallowable'] ?? 0))) ?>

                     </td>
                 </tr>

                 <tr>
                     <td>
                         Wages And Staff Costs
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['wagesAndStaffCosts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['wagesAndStaffCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['wagesAndStaffCosts'] ?? 0) - ($disallowed['wagesAndStaffCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Car Van Travel Expenses
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['carVanTravelExpenses'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['carVanTravelExpensesDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['carVanTravelExpenses'] ?? 0) - ($disallowed['carVanTravelExpensesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Premises Running Costs
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['premisesRunningCosts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['premisesRunningCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['premisesRunningCosts'] ?? 0) - ($disallowed['premisesRunningCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Maintenance Costs
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['maintenanceCosts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['maintenanceCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['maintenanceCosts'] ?? 0) - ($disallowed['maintenanceCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Admin Costs
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['adminCosts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['adminCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['adminCosts'] ?? 0) - ($disallowed['adminCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Business Entertainment Costs
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['businessEntertainmentCosts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['businessEntertainmentCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['businessEntertainmentCosts'] ?? 0) - ($disallowed['businessEntertainmentCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Advertising Costs
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['advertisingCosts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['advertisingCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['advertisingCosts'] ?? 0) - ($disallowed['advertisingCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Interest On Bank Other Loans
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['interestOnBankOtherLoans'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['interestOnBankOtherLoansDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['interestOnBankOtherLoans'] ?? 0) - ($disallowed['interestOnBankOtherLoansDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Finance Charges
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['financeCharges'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['financeChargesDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['financeCharges'] ?? 0) - ($disallowed['financeChargesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Irrecoverable Debts
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['irrecoverableDebts'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['irrecoverableDebtsDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['irrecoverableDebts'] ?? 0) - ($disallowed['irrecoverableDebtsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Professional Fees
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['professionalFees'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['professionalFeesDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['professionalFees'] ?? 0) - ($disallowed['professionalFeesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Depreciation
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['depreciation'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['depreciationDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['depreciation'] ?? 0) - ($disallowed['depreciationDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Other Expenses
                     </td>
                     <td data-label="Total Amount">
                         <?= esc(formatNumber($expenses['otherExpenses'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Disallowed">
                         <?= esc(formatNumber($disallowed['otherExpensesDisallowable'] ?? 0)) ?>
                     </td>
                     <td data-label="Total Allowed">
                         <?= esc(formatNumber(($expenses['otherExpenses'] ?? 0) - ($disallowed['otherExpensesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

             <?php endif; ?>

             <tr>
                 <th colspan="4" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income</td>
                 <td data-label="Total Amount"><?= esc(formatNumber($total_income)) ?></td>
                 <td data-label="Total Disallowed"></td>
                 <td data-label="Total Allowed"><?= esc(formatNumber($total_income)) ?></td>

             </tr>
             <tr>
                 <td>Total Expenses</td>
                 <td data-label="Total Amount"><?= esc(formatNumber($total_expenses)) ?></td>
                 <td data-label="Total Disallowed"><?= esc(formatNumber($total_disallowed)) ?></td>
                 <td data-label="Total Allowed"><?= esc(formatNumber($total_allowed)) ?></td>
             </tr>
             <tr>
                 <td>Profit</td>
                 <td data-label="Total Amount"></td>
                 <td data-label="Total Disallowed"></td>
                 <td data-label="Total Allowed"><?= esc(formatNumber($profit)) ?>
                 </td>
             </tr>
         </tbody>



     </table>
 </div>