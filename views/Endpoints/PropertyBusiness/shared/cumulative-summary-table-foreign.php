 <div class="<?= count($foreign_property_data) > 2 ? 'long-table' : 'regular-table' ?>">
     <table class="number-table">

         <thead>

             <tr>
                 <th></th>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <th><?= $country['countryCode'] ?></th>
                 <?php endforeach; ?>
             </tr>

         </thead>
         <tbody>

             <div class="mobile-view">
                 <?php foreach ($foreign_property_data as $country): ?>
                     <th class="mobile-view country-code"><?= esc($country['countryCode']) ?></th>
                 <?php endforeach; ?>
             </div>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Income</th>
             </tr>

             <tr>
                 <td class="desktop-view">Rent Received</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Rent Received"><?= esc(formatNumber($country['income']['rentAmount'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td class="desktop-view">Lease Premiums</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Lease Premiums">
                         <?= esc(formatNumber($country['income']['premiumsOfLeaseGrant'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td class="desktop-view">Other Property Income</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Other Property Income">
                         <?= esc(formatNumber($country['income']['otherPropertyIncome'] ?? 0)) ?>
                     </td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td class="desktop-view">Foreign Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Foreign Tax Deducted">
                         <?= esc(formatNumber($country['income']['foreignTaxPaidOrDeducted'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td class="desktop-view">UK Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="UK Tax Deducted">
                         <?= esc(formatNumber($country['income']['specialWithholdingTaxOrUkTaxPaid'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td class="desktop-view">UK Tax Deducted</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="UK Tax Deducted">
                         <?= esc(formatNumber($country['income']['specialWithholdingTaxOrUkTaxPaid'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>
             <tr>
                 <td class="desktop-view">Foreign Tax Credit Relief Claimed</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Foreign Tax Credit Relief Claimed">
                         <?= esc(formatNumber($country['income']['foreignTaxCreditRelief'] ? 'Yes' : 'No')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Expenses</th>
             </tr>

             <?php if ($consolidated_expenses): ?>
                 <tr>
                     <td class="desktop-view">Consolidated Expenses</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Consolidated Expenses">
                             <?= esc(formatNumber($country['expenses']['consolidatedExpenses'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>
             <?php endif; ?>

             <?php if ($non_consolidated_expenses): ?>

                 <tr>
                     <td class="desktop-view">Premises Costs</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Premises Costs">
                             <?= esc(formatNumber($country['expenses']['premisesRunningCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td class="desktop-view">Repairs And Maintenance</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Repairs And Maintenance">
                             <?= esc(formatNumber($country['expenses']['repairsAndMaintenance'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td class="desktop-view">Finance Costs</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Finance Costs">
                             <?= esc(formatNumber($country['expenses']['financialCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td class="desktop-view">Professional Fees</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Professional Fees">
                             <?= esc(formatNumber($country['expenses']['professionalFees'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td class="desktop-view">Travel Costs</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Travel Costs">
                             <?= esc(formatNumber($country['expenses']['travelCosts'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td class="desktop-view">Services</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Services">
                             <?= esc(formatNumber($country['expenses']['costOfServices'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

                 <tr>
                     <td class="desktop-view">Other Expenses</td>
                     <?php foreach ($foreign_property_data as $country): ?>
                         <td data-label="Other Expenses">
                             <?= esc(formatNumber($country['expenses']['other'] ?? 0)) ?></td>
                     <?php endforeach; ?>
                 </tr>

             <?php endif; ?>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Residential Finance Costs
                 </th>
             </tr>

             <tr>
                 <td class="desktop-view">Costs</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Residential Finance Costs">
                         <?= esc(formatNumber($country['financeCosts']['residentialFinancialCost'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td class="desktop-view">Costs Brought Forward</td>
                 <?php foreach ($foreign_property_data as $country): ?>
                     <td data-label="Costs Brought Forward">
                         <?= esc(formatNumber($country['financeCosts']['broughtFwdResidentialFinancialCost'] ?? 0)) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Summary</th>
             </tr>

             <tr>
                 <td>Total Income</td>
             </tr>


         </tbody>

     </table>
 </div>