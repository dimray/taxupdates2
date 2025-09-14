 <div class="<?= count($foreign_property_data) > 2 ? 'long-table' : 'regular-table' ?>">

     <table class="number-table desktop-view">

         <thead>

             <tr>
                 <th></th>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <th class="align-center"><?= getCountry($country['countryCode']) ?></th>
                 <?php endforeach; ?>
             </tr>

         </thead>
         <tbody>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Income</th>
             </tr>

             <tr>
                 <td>Rent Received</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td><?= esc(formatNumber($country['income']['rentAmount'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>Lease Premiums</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td>
                         <?= esc(formatNumber($country['income']['premiumsOfLeaseGrant'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>Other Property Income</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td>
                         <?= esc(formatNumber($country['income']['otherPropertyIncome'] ?? 0)) ?>
                     </td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>Foreign Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td>
                         <?= esc(formatNumber($country['income']['foreignTaxPaidOrDeducted'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>UK Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td>
                         <?= esc(formatNumber($country['income']['specialWithholdingTaxOrUkTaxPaid'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Expenses</th>
             </tr>

             <?php if ($consolidated_expenses): ?>
                 <tr>
                     <td>Consolidated Expenses</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['consolidatedExpenses'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>
             <?php endif; ?>

             <?php if ($non_consolidated_expenses): ?>

                 <tr>
                     <td>Premises Costs</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['premisesRunningCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Repairs And Maintenance</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['repairsAndMaintenance'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Finance Costs</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['financialCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Professional Fees</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['professionalFees'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Travel Costs</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['travelCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Services</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['costOfServices'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Other Expenses</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td>
                             <?= esc(formatNumber($country['expenses']['other'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

             <?php endif; ?>



             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td><?= esc(formatNumber($totals[$country['countryCode']]['total_income'] ?? '')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Total Expenses</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td><?= esc(formatNumber($totals[$country['countryCode']]['total_expenses'] ?? '')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Profit</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td><?= esc(formatNumber($totals[$country['countryCode']]['profit'] ?? '')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Residential Finance Costs
                 </th>
             </tr>

             <tr>
                 <td>Costs</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Residential Finance Costs">
                         <?= esc(formatNumber($country['residentialFinance']['residentialFinancialCost'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Costs Brought Forward</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td>
                         <?= esc(formatNumber($country['residentialFinance']['broughtFwdResidentialFinancialCost'] ?? 0)) ?>
                     </td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Other</th>
             </tr>

             <tr>
                 <td>Foreign Tax Credit Relief</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td>
                         <?= $country['foreignTaxCreditRelief'] ? "Claimed" : "Not claimed" ?></td>
                 <?php endforeach; ?>
             </tr>


         </tbody>

     </table>


     <div class="mobile-view">
         <?php foreach ($foreign_property_data as $country): ?>
             <div class="card">
                 <h2 class="header"><?= getCountry($country['countryCode']) ?></h2>

                 <div>
                     <h3>Income</h3>
                     <div class="data-row">
                         <div class="label">Rent Received</div>
                         <div class="value"><?= esc(formatNumber($country['income']['rentAmount'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Lease Premiums</div>
                         <div class="value"><?= esc(formatNumber($country['income']['premiumsOfLeaseGrant'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Other Property Income</div>
                         <div class="value"><?= esc(formatNumber($country['income']['otherPropertyIncome'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Foreign Tax Deducted</div>
                         <div class="value"><?= esc(formatNumber($country['income']['foreignTaxPaidOrDeducted'] ?? 0)) ?>
                         </div>
                     </div>
                     <div class="data-row">
                         <div class="label">UK Tax Deducted</div>
                         <div class="value">
                             <?= esc(formatNumber($country['income']['specialWithholdingTaxOrUkTaxPaid'] ?? 0)) ?></div>
                     </div>
                 </div>

                 <div>
                     <h3>Expenses</h3>
                     <?php if ($consolidated_expenses): ?>
                         <div class="data-row">
                             <div class="label">Consolidated Expenses</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['consolidatedExpenses'] ?? 0)) ?>
                             </div>
                         </div>
                     <?php endif; ?>
                     <?php if ($non_consolidated_expenses): ?>
                         <div class="data-row">
                             <div class="label">Premises Costs</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['premisesRunningCosts'] ?? 0)) ?>
                             </div>
                         </div>
                         <div class="data-row">
                             <div class="label">Repairs And Maintenance</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['repairsAndMaintenance'] ?? 0)) ?>
                             </div>
                         </div>
                         <div class="data-row">
                             <div class="label">Finance Costs</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['financialCosts'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Professional Fees</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['professionalFees'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Travel Costs</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['travelCosts'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Services</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['costOfServices'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Other Expenses</div>
                             <div class="value"><?= esc(formatNumber($country['expenses']['other'] ?? 0)) ?></div>
                         </div>
                     <?php endif; ?>
                 </div>

                 <div>
                     <h3>Summary</h3>
                     <div class="data-row">
                         <div class="label">Total Income</div>
                         <div class="value"><?= esc(formatNumber($totals[$country['countryCode']]['total_income'] ?? '')) ?>
                         </div>
                     </div>
                     <div class="data-row">
                         <div class="label">Total Expenses</div>
                         <div class="value">
                             <?= esc(formatNumber($totals[$country['countryCode']]['total_expenses'] ?? '')) ?>
                         </div>
                     </div>
                     <div class="data-row">
                         <div class="label">Profit</div>
                         <div class="value"><?= esc(formatNumber($totals[$country['countryCode']]['profit'] ?? '')) ?></div>
                     </div>
                 </div>

                 <div>
                     <h3>Residential Finance Costs</h3>
                     <div class="data-row">
                         <div class="label">Costs</div>
                         <div class="value">
                             <?= esc(formatNumber($country['residentialFinance']['residentialFinancialCost'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Costs Brought Forward</div>
                         <div class="value">
                             <?= esc(formatNumber($country['residentialFinance']['broughtFwdResidentialFinancialCost'] ?? 0)) ?>
                         </div>
                     </div>
                 </div>

                 <div>
                     <h3>Other</h3>
                     <div class="data-row">
                         <div class="label">Foreign Tax Credit Relief</div>
                         <div class="value"><?= $country['foreignTaxCreditRelief'] ? "Claimed" : "Not claimed" ?></div>
                     </div>
                 </div>
             </div>
         <?php endforeach; ?>
     </div>

 </div>