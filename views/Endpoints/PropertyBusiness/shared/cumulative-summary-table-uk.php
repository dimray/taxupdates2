 <div class="regular-table">
     <table class="number-table desktop-view">

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
                 <td>Rent Received</td>
                 <td><?= esc(formatNumber($income['periodAmount'] ?? 0)) ?></td>
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
                 <td><?= esc(formatNumber($income['otherIncome'] ?? 0)) ?></td>
             </tr>
             <tr>
                 <td>Tax Deducted</td>
                 <td><?= esc(formatNumber($income['taxDeducted'] ?? 0)) ?></td>
             </tr>


             <tr>
                 <th colspan="2" class="subheading">Expenses</th>
             </tr>

             <?php if (isset($expenses['consolidatedExpenses'])): ?>

             <tr>
                 <td>Consolidated Expenses</td>
                 <td>
                     <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                 </td>
             </tr>

             <?php else: ?>

             <tr>
                 <td>Premises Costs</td>
                 <td>
                     <?= esc(formatNumber($expenses['premisesRunningCosts'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>Repairs And Maintenance</td>
                 <td>
                     <?= esc(formatNumber($expenses['repairsAndMaintenance'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>Professional Fees</td>
                 <td>
                     <?= esc(formatNumber($expenses['professionalFees'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>Cost Of Services</td>
                 <td>
                     <?= esc(formatNumber($expenses['costOfServices'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>Travel Costs</td>
                 <td>
                     <?= esc(formatNumber($expenses['travelCosts'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>Deductible Finance Costs</td>
                 <td>
                     <?= esc(formatNumber($expenses['financialCosts'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>Other Costs</td>
                 <td>
                     <?= esc(formatNumber($expenses['other'] ?? 0)) ?>
                 </td>
             </tr>

             <?php endif; ?>

             <?php if (!empty($rentaroom)): ?>

             <tr>
                 <th colspan="2" class="subheading">RentARoom</th>
             </tr>

             <tr>
                 <td>
                     Rents Received
                 </td>
                 <td>
                     <?= esc(formatNumber($rentaroom['rentsReceived'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>
                     Allowance Claimed
                 </td>
                 <td>
                     <?= esc(formatNumber($rentaroom['amountClaimed'] ?? 0)) ?>
                 </td>
             </tr>

             <?php endif; ?>



             <tr>
                 <th colspan="2" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income</td>
                 <td><?= esc(formatNumber($total_income)) ?></td>

             </tr>
             <tr>
                 <td>Total Expenses</td>
                 <td><?= esc(formatNumber($total_expenses)) ?></td>

             </tr>

             <?php if (!empty($rentaroom)): ?>

             <tr>
                 <td>RentARoom Profit</td>
                 <td><?= esc(formatNumber($rentaroom_profit)) ?></td>

             </tr>

             <?php endif; ?>

             <tr>
                 <td>Profit Before Residential Finance Costs</td>
                 <td><?= esc(formatNumber($profit)) ?></td>
             </tr>



             <tr>
                 <th colspan="2" class="subheading">Residential Finance Costs</th>
             </tr>

             <tr>
                 <td>
                     Finance Costs
                 </td>
                 <td>
                     <?= esc(formatNumber($residential_finance['residentialFinancialCost'] ?? 0)) ?>
                 </td>
             </tr>

             <tr>
                 <td>
                     Finance Costs Brought Forward
                 </td>
                 <td>
                     <?= esc(formatNumber($residential_finance['residentialFinancialCostsCarriedForward'] ?? 0)) ?>
                 </td>
             </tr>

         </tbody>

     </table>



     <div class="mobile-view">

         <div class="card">
             <div>

                 <h3>Income</h3>

                 <div class="data-row">
                     <div class="label">Rent Received</div>
                     <div class="value"><?= esc(formatNumber($income['periodAmount'] ?? 0)) ?></div>
                 </div>
                 <div class="data-row">
                     <div class="label">Lease Premiums</div>
                     <div class="value"><?= esc(formatNumber($income['premiumsOfLeaseGrant'] ?? 0)) ?></div>
                 </div>
                 <div class="data-row">
                     <div class="label">Reverse Premiums</div>
                     <div class="value"><?= esc(formatNumber($income['reversePremiums'] ?? 0)) ?></div>
                 </div>
                 <div class="data-row">
                     <div class="label">Other Income</div>
                     <div class="value"><?= esc(formatNumber($income['otherIncome'] ?? 0)) ?></div>
                 </div>
                 <div class="data-row">
                     <div class="label">Tax Deducted</div>
                     <div class="value"><?= esc(formatNumber($income['taxDeducted'] ?? 0)) ?></div>
                 </div>

             </div>

             <h3>Expenses</h3>

             <div>

                 <?php if (isset($expenses['consolidatedExpenses'])): ?>

                 <div class="data-row">
                     <div class="label">Consolidated Expenses</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['consolidatedExpenses'] ?? 0)) ?>
                     </div>
                 </div>

                 <?php else: ?>

                 <div class="data-row">
                     <div class="label">Premises Costs</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['premisesRunningCosts'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Repairs And Maintenance</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['repairsAndMaintenance'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Professional Fees</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['professionalFees'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Cost Of Services</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['costOfServices'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Travel Costs</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['travelCosts'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Deductible Finance Costs</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['financialCosts'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Other Costs</div>
                     <div class="value">
                         <?= esc(formatNumber($expenses['other'] ?? 0)) ?>
                     </div>
                 </div>

                 <?php endif; ?>

             </div>

             <?php if (!empty($rentaroom)): ?>

             <div>

                 <h3>RentARoom</h3>

                 <div class="data-row">
                     <div class="label">
                         Rents Received
                     </div>
                     <div class="value">
                         <?= esc(formatNumber($rentaroom['rentsReceived'] ?? 0)) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">
                         Allowance Claimed
                     </div>
                     <div class="value">
                         <?= esc(formatNumber($rentaroom['amountClaimed'] ?? 0)) ?>
                     </div>
                 </div>

             </div>

             <?php endif; ?>

             <div>

                 <h3>Summary</h3>

                 <div class="data-row">
                     <div class="label">Total Income</div>
                     <div class="data"><?= esc(formatNumber($total_income)) ?></div>
                 </div>

                 <div class="data-row">
                     <div class="label">Total Expenses</div>
                     <div class="data"><?= esc(formatNumber($total_expenses)) ?></div>
                 </div>

                 <?php if (!empty($rentaroom)): ?>

                 <div class="data-row">
                     <div class="label">RentARoom Profit</div>
                     <div class="data"><?= esc(formatNumber($rentaroom_profit)) ?></div>

                 </div>

                 <?php endif; ?>

                 <div class="data-row">
                     <div class="label">Profit Before Residential Finance Costs</div>
                     <div class="data"><?= esc(formatNumber($profit)) ?></div>
                 </div>

             </div>


             <h3>Residential Finance Costs</h3>


             <div class="data-row">
                 <div class="label">
                     Finance Costs
                 </div>
                 <div class="data">
                     <?= esc(formatNumber($residential_finance['residentialFinancialCost'] ?? 0)) ?>
                 </div>
             </div>

             <div class="data-row">
                 <div class="label">
                     Finance Costs Brought Forward
                 </div>
                 <div class="data">
                     <?= esc(formatNumber($residential_finance['residentialFinancialCostsCarriedForward'] ?? 0)) ?>
                 </div>
             </div>

         </div>

     </div>

 </div>