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
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Adjustments</th>
             </tr>

             <tr>
                 <td>Private Use Adjustment</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['adjustments']['privateUseAdjustment'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Balancing Charge</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['adjustments']['balancingCharge'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Allowances</th>
             </tr>

             <tr>
                 <td>Annual Investment Allowance</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['allowances']['annualInvestmentAllowance'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Cost Of Replacing Domestic Items</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['allowances']['costOfReplacingDomesticItems'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Other Capital Allowances</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['allowances']['otherCapitalAllowance'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Zero Emissions Car Allowance</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['allowances']['zeroEmissionsCarAllowance'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Property Income Allowance</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['allowances']['propertyIncomeAllowance'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Structured Building Allowance
                 </th>
             </tr>

             <tr>
                 <td>Amount</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['sba']['sba_amount'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Qualifying Date</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatDate($data['sba']['sba_qualifyingDate'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Qualifying Amount</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['sba']['sba_qualifyingAmountExpenditure'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Building Name</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc($data['sba']['sba_name'] ?? '-') ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Building Number</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc($data['sba']['sba_number'] ?? '-') ?></td>
                 <?php endforeach; ?>
             </tr>

             <tr>
                 <td>Building Postcode</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc($data['sba']['sba_postcode'] ?? '-') ?></td>
                 <?php endforeach; ?>
             </tr>

         </tbody>

     </table>

     <div class="mobile-view">

         <?php foreach ($foreign_property_data as $country => $data): ?>

             <div class="card">

                 <h3><?= getCountry($country) ?></h3>

                 <h3>Adjustments</h3>

                 <div class="data-row">
                     <div class="label">Private Use Adjustment</div>
                     <div class="value"><?= esc(formatNumber($data['adjustments']['privateUseAdjustment'] ?? '-')) ?></div>
                 </div>

                 <div class="data-row">
                     <div class="label">Balancing Charge</div>
                     <div class="value"><?= esc(formatNumber($data['adjustments']['balancingCharge'] ?? '-')) ?></div>
                 </div>

                 <h3>Allowances</h3>

                 <div class="data-row">
                     <div class="label">Annual Investment Allowance</div>
                     <div class="value"><?= esc(formatNumber($data['allowances']['annualInvestmentAllowance'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Cost Of Replacing Domestic Items</div>
                     <div class="value"><?= esc(formatNumber($data['allowances']['costOfReplacingDomesticItems'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Other Capital Allowances</div>
                     <div class="value"><?= esc(formatNumber($data['allowances']['otherCapitalAllowance'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Zero Emissions Car Allowance</div>
                     <div class="value"><?= esc(formatNumber($data['allowances']['zeroEmissionsCarAllowance'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Property Income Allowance</div>
                     <div class="value"><?= esc(formatNumber($data['allowances']['propertyIncomeAllowance'] ?? '-')) ?>
                     </div>
                 </div>

                 <h4>Structured Building Allowance</h4>

                 <div class="data-row">
                     <div class="label">Amount</div>
                     <div class="value"><?= esc(formatNumber($data['sba']['sba_amount'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Qualifying Date</div>
                     <div class="value"><?= esc(formatDate($data['sba']['sba_qualifyingDate'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Qualifying Amount</div>
                     <div class="value"><?= esc(formatNumber($data['sba']['sba_qualifyingAmountExpenditure'] ?? '-')) ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Building Name</div>
                     <div class="value"><?= esc($data['sba']['sba_name'] ?? '-') ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Building Number</div>
                     <div class="value"><?= esc($data['sba']['sba_number'] ?? '-') ?>
                     </div>
                 </div>

                 <div class="data-row">
                     <div class="label">Building Postcode</div>
                     <div class="value"><?= esc($data['sba']['sba_postcode'] ?? '-') ?>
                     </div>
                 </div>

             </div>

         <?php endforeach; ?>

     </div>

 </div>