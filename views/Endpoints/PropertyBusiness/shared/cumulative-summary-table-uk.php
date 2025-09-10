 <div class="regular-table">
     <table class="number-table">

         <thead>

             <tr>
                 <th></th>
                 <th>Amount</th>
             </tr>

         </thead>
         <tbody>

             <tr>
                 <th colspan="2" class="subheading">Income</th>
             </tr>

             <tr>
                 <td class="desktop-view">Rent Received</td>
                 <td data-label="Rent Received"><?= esc(formatNumber($income['periodAmount'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td class="desktop-view">Lease Premiums</td>
                 <td data-label="Lease Premiums"><?= esc(formatNumber($income['premiumsOfLeaseGrant'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td class="desktop-view">Reverse Premiums</td>
                 <td data-label="Reverse Premiums"><?= esc(formatNumber($income['reversePremiums'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td class="desktop-view">Other Income</td>
                 <td data-label="Other Income"><?= esc(formatNumber($income['otherIncome'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td class="desktop-view">Tax Deducted</td>
                 <td data-label="Tax Deducted"><?= esc(formatNumber($income['taxDeducted'] ?? 0)) ?></td>
             </tr>


             <tr>
                 <th colspan="2" class="subheading">Expenses</th>
             </tr>

             <?php if (isset($expenses['consolidatedExpenses'])): ?>

                 <tr>
                     <td class="desktop-view">Consolidated Expenses</td>
                     <td data-label="Consolidated Expenses">
                         <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                     </td>
                 </tr>

             <?php else: ?>

                 <tr>
                     <td class="desktop-view">Premises Costs</td>
                     <td data-label="Premises Costs">
                         <?= esc(formatNumber($expenses['premisesRunningCosts'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">Repairs And Maintenance</td>
                     <td data-label="Repairs And Maintenance">
                         <?= esc(formatNumber($expenses['repairsAndMaintenance'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">Professional Fees</td>
                     <td data-label="Professional Fees">
                         <?= esc(formatNumber($expenses['professionalFees'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">Cost Of Services</td>
                     <td data-label="Cost Of Services">
                         <?= esc(formatNumber($expenses['costOfServices'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">Travel Costs</td>
                     <td data-label="Travel Costs">
                         <?= esc(formatNumber($expenses['travelCosts'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">Deductible Finance Costs</td>
                     <td data-label="Deductible Finance Costs">
                         <?= esc(formatNumber($expenses['financialCosts'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">Other Costs</td>
                     <td data-label="Other Costs">
                         <?= esc(formatNumber($expenses['other'] ?? 0)) ?>
                     </td>
                 </tr>

             <?php endif; ?>

             <?php if (!empty($rentaroom)): ?>

                 <tr>
                     <th colspan="2" class="subheading">RentARoom</th>
                 </tr>

                 <tr>
                     <td class="desktop-view">
                         Rents Received
                     </td>
                     <td data-label="Rents Received">
                         <?= esc(formatNumber($rentaroom['rentsReceived'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">
                         Allowance Claimed
                     </td>
                     <td data-label="Allowance Claimed">
                         <?= esc(formatNumber($rentaroom['amountClaimed'] ?? 0)) ?>
                     </td>
                 </tr>

             <?php endif; ?>



             <tr>
                 <th colspan="2" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td class="desktop-view">Total Income</td>
                 <td data-label="Total Income"><?= esc(formatNumber($total_income)) ?></td>

             </tr>
             <tr>
                 <td class="desktop-view">Total Expenses</td>
                 <td data-label="Total Expenses"><?= esc(formatNumber($total_expenses)) ?></td>

             </tr>

             <?php if (!empty($rentaroom)): ?>

                 <tr>
                     <td class="desktop-view">RentARoom Profit</td>
                     <td data-label="RentARoom Profit"><?= esc(formatNumber($rentaroom_profit)) ?></td>

                 </tr>

             <?php endif; ?>

             <tr>
                 <td class="desktop-view">Profit Before Residential Finance Costs</td>
                 <td data-label="Profit"><?= esc(formatNumber($profit)) ?></td>
             </tr>

             <?php if (!empty($residential_finance)): ?>

                 <tr>
                     <th colspan="2" class="subheading">Residential Finance Costs</th>
                 </tr>

                 <tr>
                     <td class="desktop-view">
                         Finance Costs
                     </td>
                     <td data-label="Finance Costs">
                         <?= esc(formatNumber($residential_finance['residentialFinancialCost'] ?? 0)) ?>
                     </td>
                 </tr>

                 <tr>
                     <td class="desktop-view">
                         Finance Costs Brought Forward
                     </td>
                     <td data-label="Finance Costs b/fwd">
                         <?= esc(formatNumber($residential_finance['residentialFinancialCostsCarriedForward'] ?? 0)) ?>
                     </td>
                 </tr>

             <?php endif; ?>

         </tbody>

     </table>
 </div>