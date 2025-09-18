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
                 <th colspan="<?= 1 + count($foreign_property_data) ?>" class="subheading">Income</th>
             </tr>


             <tr>
                 <td>Rent Received</td>
                 <?php foreach ($foreign_property_data as $country => $data): ?>
                     <td><?= esc(formatNumber($data['totalRentsReceived'] ?? '-')) ?></td>
                 <?php endforeach; ?>
             </tr>

             <!-- and continue on. Use uk-property-table for format, and annual-submission-table-foreign from PropertyBusiness -->

         </tbody>



     </table>


 </div>