 <div class="<?= count($foreign_property_data) > 2 ? 'long-table' : 'regular-table' ?>">

     <table class="number-table desktop-view">

         <thead>
             <tr>
                 <th></th>
                 <?php foreach ($foreign_property_data as $key => $data): ?>
                     <?php if ($country_or_property === "country"): ?>

                         <th class="align-right"><?= getCountry($key) ?></th>
                     <?php else: ?>
                         <th class="align-right"><?= getProperty($key, $foreign_properties) ?></th>
                     <?php endif; ?>
                 <?php endforeach; ?>
             </tr>

         </thead>
         <tbody>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Income</th>
             </tr>

             <tr>
                 <td>Rent Received</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td><?= esc(formatNumber($data['income']['rentAmount'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>Lease Premiums</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td>
                         <?= esc(formatNumber($data['income']['premiumsOfLeaseGrant'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>Other Property Income</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td>
                         <?= esc(formatNumber($data['income']['otherPropertyIncome'] ?? 0)) ?>
                     </td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>Foreign Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td>
                         <?= esc(formatNumber($data['income']['foreignTaxPaidOrDeducted'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td>UK Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td>
                         <?= esc(formatNumber($data['income']['specialWithholdingTaxOrUkTaxPaid'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Expenses</th>
             </tr>

             <?php if ($consolidated_expenses): ?>
                 <tr>
                     <td>Consolidated Expenses</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['consolidatedExpenses'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>
             <?php endif; ?>

             <?php if ($non_consolidated_expenses): ?>

                 <tr>
                     <td>Premises Costs</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['premisesRunningCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Repairs And Maintenance</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['repairsAndMaintenance'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Finance Costs</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['financialCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Professional Fees</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['professionalFees'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Travel Costs</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['travelCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Services</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['costOfServices'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Other Expenses</td>
                     <?php foreach ($foreign_property_data as $data): ?>
                         <td>
                             <?= esc(formatNumber($data['expenses']['other'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

             <?php endif; ?>



             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income</td>
                 <?php foreach ($totals as $total): ?>

                     <td><?= esc(formatNumber($total['total_income'] ?? '')) ?></td>

                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Total Expenses</td>
                 <?php foreach ($totals as $total): ?>

                     <td><?= esc(formatNumber($total['total_expenses'] ?? '')) ?></td>


                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Profit</td>
                 <?php foreach ($totals as $total): ?>

                     <td><?= esc(formatNumber($total['profit'] ?? '')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Residential Finance Costs
                 </th>
             </tr>

             <tr>
                 <td>Costs</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td data-label="Residential Finance Costs">
                         <?= esc(formatNumber($data['residentialFinance']['residentialFinancialCost'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Costs Brought Forward</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td>
                         <?= esc(formatNumber($data['residentialFinance']['broughtFwdResidentialFinancialCost'] ?? 0)) ?>
                     </td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Other</th>
             </tr>

             <tr>
                 <td>Foreign Tax Credit Relief</td>
                 <?php foreach ($foreign_property_data as $data): ?>
                     <td>
                         <?= $data['foreignTaxCreditRelief'] ? "Claimed" : "Not claimed" ?></td>
                 <?php endforeach; ?>
             </tr>


         </tbody>

     </table>


     <div class="mobile-view">
         <?php foreach ($foreign_property_data as $key => $data): ?>
             <div class="card">

                 <?php if ($country_or_property === "country"): ?>
                     <h2 class="header"><?= getCountry($key) ?></h2>
                 <?php else: ?>
                     <h2 class="header"><?= getProperty($key, $foreign_properties) ?></h2>
                 <?php endif; ?>

                 <div>
                     <h3>Income</h3>
                     <div class="data-row">
                         <div class="label">Rent Received</div>
                         <div class="value"><?= esc(formatNumber($data['income']['rentAmount'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Lease Premiums</div>
                         <div class="value"><?= esc(formatNumber($data['income']['premiumsOfLeaseGrant'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Other Property Income</div>
                         <div class="value"><?= esc(formatNumber($data['income']['otherPropertyIncome'] ?? 0)) ?></div>
                     </div>
                     <div class="data-row">
                         <div class="label">Foreign Tax Deducted</div>
                         <div class="value"><?= esc(formatNumber($data['income']['foreignTaxPaidOrDeducted'] ?? 0)) ?>
                         </div>
                     </div>
                     <div class="data-row">
                         <div class="label">UK Tax Deducted</div>
                         <div class="value">
                             <?= esc(formatNumber($data['income']['specialWithholdingTaxOrUkTaxPaid'] ?? 0)) ?></div>
                     </div>
                 </div>

                 <div>
                     <h3>Expenses</h3>
                     <?php if ($consolidated_expenses): ?>
                         <div class="data-row">
                             <div class="label">Consolidated Expenses</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['consolidatedExpenses'] ?? 0)) ?>
                             </div>
                         </div>
                     <?php endif; ?>
                     <?php if ($non_consolidated_expenses): ?>
                         <div class="data-row">
                             <div class="label">Premises Costs</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['premisesRunningCosts'] ?? 0)) ?>
                             </div>
                         </div>
                         <div class="data-row">
                             <div class="label">Repairs And Maintenance</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['repairsAndMaintenance'] ?? 0)) ?>
                             </div>
                         </div>
                         <div class="data-row">
                             <div class="label">Finance Costs</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['financialCosts'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Professional Fees</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['professionalFees'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Travel Costs</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['travelCosts'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Services</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['costOfServices'] ?? 0)) ?></div>
                         </div>
                         <div class="data-row">
                             <div class="label">Other Expenses</div>
                             <div class="value"><?= esc(formatNumber($data['expenses']['other'] ?? 0)) ?></div>
                         </div>
                     <?php endif; ?>
                 </div>

                 <div>
                     <h3>Summary</h3>
                     <div class="data-row">
                         <div class="label">Total Income</div>

                         <div class="value"><?= esc(formatNumber($totals[$key]['total_income'] ?? '')) ?>
                         </div>

                     </div>
                     <div class="data-row">
                         <div class="label">Total Expenses</div>

                         <div class="value">
                             <?= esc(formatNumber($totals[$key]['total_expenses'] ?? '')) ?>
                         </div>

                     </div>
                     <div class="data-row">
                         <div class="label">Profit</div>

                         <div class="value"><?= esc(formatNumber($totals[$key]['profit'] ?? '')) ?>
                         </div>

                     </div>
                 </div>

                 <div>
                     <h3>Residential Finance Costs</h3>
                     <div class="data-row">
                         <div class="label">Costs</div>
                         <div class="value">
                             <?= esc(formatNumber($data['residentialFinance']['residentialFinancialCost'] ?? 0)) ?>
                         </div>
                     </div>
                     <div class="data-row">
                         <div class="label">Costs Brought Forward</div>
                         <div class="value">
                             <?= esc(formatNumber($data['residentialFinance']['broughtFwdResidentialFinancialCost'] ?? 0)) ?>
                         </div>
                     </div>
                 </div>

                 <div>
                     <h3>Other</h3>
                     <div class="data-row">
                         <div class="label">Foreign Tax Credit Relief</div>
                         <div class="value"><?= $data['foreignTaxCreditRelief'] ? "Claimed" : "Not claimed" ?></div>
                     </div>
                 </div>
             </div>
         <?php endforeach; ?>
     </div>

 </div>