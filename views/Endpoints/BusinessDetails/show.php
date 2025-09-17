<?php if (!empty($business_details)): ?>




        <h2>Adjustments, Allowances And Losses</h2>



        <p><a href="/<?= $type_of_business ?>/annual-submission">Annual Submission</a><span class="small">Capital Allowances and
                        other adjustments</span></p>


        <p><a href="/business-source-adjustable-summary/index">Accounting Adjustments</a><span class="small">Adjust Cumulative
                        Summary figures</span></p>

        <p><a href="/individual-losses/loss-claims">Losses</a><span class="small">If you make a loss, tell HMRC how to allocate
                        it</span></p>

        <p><a href="/individual-losses/brought-forward-losses">Pre-Making Tax Digital Losses</a><span class="small">Bring
                        pre-MTD losses into account</span></p>


        <p><a href="/business-details/change-reporting-period?current_period=<?= $current_period ?>">Change Quarterly
                        Period Type</a><span class="small">Change between Standard and Calendar periods</span></p>

        <p><a href="">Retrieve Accounting Type</a></p>

        <p><a href="">Update Accounting Type</a></p>


<?php else: ?>

        <p>No details found.</p>

<?php endif; ?>