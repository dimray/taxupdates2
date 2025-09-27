 <div class="<?= count($foreign_property_data) > 2 ? 'long-table' : 'regular-table' ?>">

     <table class="number-table desktop-view">

         <thead>

             <tr>
                 <th></th>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <th class="align-right"><?= getCountry($country) ?></th>
                 <?php endforeach; ?>
             </tr>

         </thead>

         <tbody>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Income Adjustments</th>
             </tr>


             <tr>
                 <td>Rent Received</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['income']['totalRentsReceived'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Lease Premiums</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['income']['premiumsOfLeaseGrant'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Other Income</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['income']['otherPropertyIncome'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>


             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Expense Adjustments</th>
             </tr>

             <?php if (isset($data['expenses']['consolidatedExpenses'])): ?>

                 <tr>
                     <td>Consolidated Expenses</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['consolidatedExpenses'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

             <?php else: ?>

                 <tr>
                     <td>Premises Running Costs</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['premisesRunningCosts'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Repairs And Maintenance</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['repairsAndMaintenance'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Financial Costs</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['financialCosts'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Professional Fees</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['professionalFees'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Cost Of Services</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['costOfServices'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Residential Finance Costs</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['residentialFinancialCost'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Other Expenses</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['other'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td>Travel Costs</td>
                     <?php foreach ($foreign_property_data as $country => $data): ?>
                         <td><?= esc(formatNumber($data['expenses']['travelCosts'] ?? '-')) ?></td>
                     <?php endforeach; ?>
                 </tr>

             <?php endif; ?>

             <tr>
                 <th colspan="2" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income Adjustment</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber(array_sum($data['income'] ?? []))) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Total Expense Adjustments</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber(array_sum($data['expenses'] ?? []))) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Net Adjustment</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber((array_sum($data['income'] ?? []) - array_sum($data['expenses'] ?? [])))) ?>
                     </td>
                 <?php endforeach; ?>
             </tr>


         </tbody>

     </table>

     <div class="mobile-view">

         <?php foreach ($foreign_property_data as $country => $data): ?>

             <div class="card">

                 <h3><?= getCountry($country) ?></h3>

                 <h3>Income Adjustments</h3>

                 <div class="data-row">
                     <div class="label">Rent Received</div>
                     <div class="value"><?= esc(formatNumber($data['income']['totalRentsReceived'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Lease Premiums</div>
                     <div class="value"><?= esc(formatNumber($data['income']['premiumsOfLeaseGrant'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Other Income</div>
                     <div class="value"><?= esc(formatNumber($data['income']['otherPropertyIncome'] ?? '-')) ?>
                     </div>
                 </div>

                 <h3>Expense Adjustments</h3>

                 <?php if (isset($data['expenses']['consolidatedExpenses'])): ?>

                     <div class="data-row">
                         <div class="label">Consolidated Expenses</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['consolidatedExpenses'] ?? '-')) ?></div>
                     </div>

                 <?php else: ?>

                     <div class="data-row">
                         <div class="label">Premises Running Costs</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['premisesRunningCosts'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Repairs And Maintenance</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['repairsAndMaintenance'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Financial Costs</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['financialCosts'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Professional Fees</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['professionalFees'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Cost Of Services</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['costOfServices'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Residential Finance Costs</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['residentialFinancialCost'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Other Costs</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['other'] ?? '-')) ?></div>
                     </div>

                     <div class="data-row">
                         <div class="label">Travel Costs</div>
                         <div class="value"><?= esc(formatNumber($data['expenses']['travelCosts'] ?? '-')) ?></div>
                     </div>

                 <?php endif; ?>

             </div>

         <?php endforeach; ?>


     </div>


 </div>