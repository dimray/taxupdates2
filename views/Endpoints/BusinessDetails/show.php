<?php if (!empty($business_details)): ?>




        <h2>Adjustments, Allowances And Losses</h2>



        <p><a href="/<?= $type_of_business ?>/annual-submission">Annual Submission</a><span class="small">Capital Allowances and
                        other adjustments</span></p>


        <p><a href="">Accounting Adjustments</a></p>

        <p><a href="/individual-losses/loss-claims">Losses</a></p>

        <p><a href="/individual-losses/brought-forward-losses">Pre-Making Tax Digital Losses</a></p>


        <p><a href="/business-details/change-reporting-period?current_period=<?= $current_period ?>">Change Quarterly
                        Period Type</a></p>


<?php else: ?>

        <p>No details found.</p>

<?php endif; ?>