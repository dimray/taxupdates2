 <div class="table-container">
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
                 <td><?= esc(formatNumber($income['turnover'] ?? 0)) ?></td>
                 <td></td>
                 <td><?= esc(formatNumber($income['turnover'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td>Other</td>
                 <td><?= esc(formatNumber($income['other'] ?? 0)) ?></td>
                 <td></td>
                 <td><?= esc(formatNumber($income['other'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td>Tax Taken Off Trading Income</td>
                 <td><?= esc(formatNumber($income['taxTakenOffTradingIncome'] ?? 0)) ?></td>
                 <td></td>
                 <td><?= esc(formatNumber($income['taxTakenOffTradingIncome'] ?? 0)) ?></td>
             </tr>



             <tr>
                 <th colspan="4" class="subheading">Expenses</th>
             </tr>

             <?php if (isset($expenses['consolidatedExpenses'])): ?>

                 <tr>
                     <td>
                         Consolidated Expenses
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                     </td>
                     <td>

                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                     </td>
                 </tr>



             <?php else: ?>

                 <tr>
                     <td>
                         Cost Of Goods
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['costOfGoods'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['costOfGoodsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>

                         <?= esc(formatNumber(($expenses['costOfGoods'] ?? 0) - ($disallowed['costOfGoodsDisallowable'] ?? 0))) ?>

                     </td>
                 </tr>

                 <tr>
                     <td>
                         Payments To Subcontractors
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['paymentsToSubcontractors'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['paymentsToSubcontractorsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['paymentsToSubcontractors'] ?? 0) - ($disallowed['paymentsToSubcontractorsDisallowable'] ?? 0))) ?>

                     </td>
                 </tr>

                 <tr>
                     <td>
                         Wages And Staff Costs
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['wagesAndStaffCosts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['wagesAndStaffCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['wagesAndStaffCosts'] ?? 0) - ($disallowed['wagesAndStaffCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Car Van Travel Expenses
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['carVanTravelExpenses'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['carVanTravelExpensesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['carVanTravelExpenses'] ?? 0) - ($disallowed['carVanTravelExpensesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Premises Running Costs
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['premisesRunningCosts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['premisesRunningCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['premisesRunningCosts'] ?? 0) - ($disallowed['premisesRunningCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Maintenance Costs
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['maintenanceCosts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['maintenanceCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['maintenanceCosts'] ?? 0) - ($disallowed['maintenanceCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Admin Costs
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['adminCosts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['adminCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['adminCosts'] ?? 0) - ($disallowed['adminCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Business Entertainment Costs
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['businessEntertainmentCosts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['businessEntertainmentCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['businessEntertainmentCosts'] ?? 0) - ($disallowed['businessEntertainmentCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Advertising Costs
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['advertisingCosts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['advertisingCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['advertisingCosts'] ?? 0) - ($disallowed['advertisingCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Interest On Bank Other Loans
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['interestOnBankOtherLoans'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['interestOnBankOtherLoansDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['interestOnBankOtherLoans'] ?? 0) - ($disallowed['interestOnBankOtherLoansDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Finance Charges
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['financeCharges'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['financeChargesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['financeCharges'] ?? 0) - ($disallowed['financeChargesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Irrecoverable Debts
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['irrecoverableDebts'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['irrecoverableDebtsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['irrecoverableDebts'] ?? 0) - ($disallowed['irrecoverableDebtsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Professional Fees
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['professionalFees'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['professionalFeesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['professionalFees'] ?? 0) - ($disallowed['professionalFeesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Depreciation
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['depreciation'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['depreciationDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['depreciation'] ?? 0) - ($disallowed['depreciationDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Other Expenses
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['otherExpenses'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($disallowed['otherExpensesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['otherExpenses'] ?? 0) - ($disallowed['otherExpensesDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

             <?php endif; ?>

             <tr>
                 <th colspan="4" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income</td>
                 <td><?= esc(formatNumber($total_income)) ?></td>
                 <td></td>
                 <td><?= esc(formatNumber($total_income)) ?></td>

             </tr>
             <tr>
                 <td>Total Expenses</td>
                 <td><?= esc(formatNumber($total_expenses)) ?></td>
                 <td><?= esc(formatNumber($total_disallowed)) ?></td>
                 <td><?= esc(formatNumber($total_allowed)) ?></td>
             </tr>
             <tr>
                 <td>Profit</td>
                 <td></td>
                 <td></td>
                 <td><?= esc(formatNumber($profit)) ?>
                 </td>
             </tr>
         </tbody>



     </table>
 </div>