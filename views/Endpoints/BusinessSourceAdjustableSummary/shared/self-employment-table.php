 <div class="regular-table">
     <table class="number-table desktop-view">

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
                 <th colspan="4" class="subheading">Income Adjustments</th>
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
                 <th colspan="4" class="subheading">Expense Adjustments</th>
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
                         <?= esc(formatNumber($additions['costOfGoodsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>

                         <?= esc(formatNumber(($expenses['costOfGoods'] ?? 0) - ($additions['costOfGoodsDisallowable'] ?? 0))) ?>

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
                         <?= esc(formatNumber($additions['paymentsToSubcontractorsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['paymentsToSubcontractors'] ?? 0) - ($additions['paymentsToSubcontractorsDisallowable'] ?? 0))) ?>

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
                         <?= esc(formatNumber($additions['wagesAndStaffCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['wagesAndStaffCosts'] ?? 0) - ($additions['wagesAndStaffCostsDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['carVanTravelExpensesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['carVanTravelExpenses'] ?? 0) - ($additions['carVanTravelExpensesDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['premisesRunningCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['premisesRunningCosts'] ?? 0) - ($additions['premisesRunningCostsDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['maintenanceCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['maintenanceCosts'] ?? 0) - ($additions['maintenanceCostsDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['adminCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['adminCosts'] ?? 0) - ($additions['adminCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>

                 <tr>
                     <td>
                         Loan Interest
                     </td>
                     <td>
                         <?= esc(formatNumber($expenses['interestOnBankOtherLoans'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber($additions['interestOnBankOtherLoansDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['interestOnBankOtherLoans'] ?? 0) - ($additions['interestOnBankOtherLoansDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['financeChargesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['financeCharges'] ?? 0) - ($additions['financeChargesDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['irrecoverableDebtsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['irrecoverableDebts'] ?? 0) - ($additions['irrecoverableDebtsDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['professionalFeesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['professionalFees'] ?? 0) - ($additions['professionalFeesDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['depreciationDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['depreciation'] ?? 0) - ($additions['depreciationDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['otherExpensesDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['otherExpenses'] ?? 0) - ($additions['otherExpensesDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['advertisingCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['advertisingCosts'] ?? 0) - ($additions['advertisingCostsDisallowable'] ?? 0))) ?>
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
                         <?= esc(formatNumber($additions['businessEntertainmentCostsDisallowable'] ?? 0)) ?>
                     </td>
                     <td>
                         <?= esc(formatNumber(($expenses['businessEntertainmentCosts'] ?? 0) - ($additions['businessEntertainmentCostsDisallowable'] ?? 0))) ?>
                     </td>
                 </tr>


             <?php endif; ?>

             <tr>
                 <th colspan="4" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income Adjustment</td>
                 <td><?= esc(formatNumber($total_income)) ?></td>
                 <td></td>
                 <td><?= esc(formatNumber($total_income)) ?></td>

             </tr>
             <tr>
                 <td>Total Expense Adjustments</td>
                 <td><?= esc(formatNumber($total_expenses)) ?></td>
                 <td><?= esc(formatNumber($total_additions)) ?></td>
                 <td><?= esc(formatNumber($total_allowed)) ?></td>
             </tr>
             <tr>
                 <td>Net Adjustment</td>
                 <td></td>
                 <td></td>
                 <td><?= esc(formatNumber($profit)) ?>
                 </td>
             </tr>
         </tbody>

     </table>

     <div class="mobile-view">
         <div class="card">

             <h3>Income</h3>
             <div class="data-row">
                 <div class="label">Turnover</div>
                 <div class="value"><?= esc(formatNumber($income['turnover'] ?? 0)) ?></div>
             </div>

             <div class="data-row">
                 <div class="label">Other Income</div>
                 <div class="value"><?= esc(formatNumber($income['other'] ?? 0)) ?></div>
             </div>


             <h3>Allowable Expenses</h3>

             <?php if (isset($expenses['consolidatedExpenses'])): ?>

                 <div class="data-row">
                     <div class="label">Consolidated Expenses</div>
                     <div class="value"><?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?></div>
                 </div>

             <?php else: ?>

                 <div class="data-row">
                     <div class="label">Cost Of Goods</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['costOfGoods'] ?? 0) - ($disallowed['costOfGoodsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Payments To Subcontractors</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['paymentsToSubcontractors'] ?? 0) - ($disallowed['paymentsToSubcontractorsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Wages And Staff Costs</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['wagesAndStaffCosts'] ?? 0) - ($disallowed['wagesAndStaffCostsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Travel Expenses</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['carVanTravelExpenses'] ?? 0) - ($disallowed['carVanTravelExpensesDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Premises Running Costs</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['premisesRunningCosts'] ?? 0) - ($disallowed['premisesRunningCostsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Maintenance Costs</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['maintenanceCosts'] ?? 0) - ($disallowed['maintenanceCostsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Administrative Costs</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['adminCosts'] ?? 0) - ($disallowed['adminCostsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Loan Interest</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['interestOnBankOtherLoans'] ?? 0) - ($disallowed['interestOnBankOtherLoansDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Finance Costs</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['financeCharges'] ?? 0) - ($disallowed['financeChargesDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Irrecoverable Debts</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['irrecoverableDebts'] ?? 0) - ($disallowed['irrecoverableDebtsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Professional Fees</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['professionalFees'] ?? 0) - ($disallowed['professionalFeesDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Depreciation</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['depreciation'] ?? 0) - ($disallowed['depreciationDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Other Expenses</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['otherExpenses'] ?? 0) - ($disallowed['otherExpensesDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Advertising Costs</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['advertisingCosts'] ?? 0) - ($disallowed['advertisingCostsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Business Entertainment</div>
                     <div class="value">
                         <?= esc(formatNumber(($expenses['businessEntertainmentCosts'] ?? 0) - ($disallowed['businessEntertainmentCostsDisallowable'] ?? 0))) ?>
                     </div>
                 </div>

             <?php endif; ?>

             <h3>Summary</h3>

             <div class="data-row">
                 <div class="label">Total Income Adjustment</div>
                 <div class="value">
                     <?= esc(formatNumber($total_income)) ?>
                 </div>
             </div>

             <div class="data-row">
                 <div class="label">Allowable Expenses Adjustment</div>
                 <div class="value">
                     <?= esc(formatNumber($total_allowed)) ?>
                 </div>
             </div>

             <div class="data-row">
                 <div class="label">Net Adjustment</div>
                 <div class="value">
                     <?= esc(formatNumber($profit)) ?>
                 </div>
             </div>

         </div>
     </div>

 </div>