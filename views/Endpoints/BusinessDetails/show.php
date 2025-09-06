<?php if (!empty($business_details)): ?>

    <h2>Cumulative Updates</h2>

    <p class="cumulative-updates"><a href="/obligations/retrieve-cumulative-obligations">Cumulative Updates</a></p>


    <h3>Other Filing For This Business</h3>


    <p><a href="/business-details/change-reporting-period?current_period=<?= $current_period ?>">Change Quarterly
            Period Type</a></p>


    <p><a href="/business-details/adjustments-allowances-losses">Adjustments, Allowances and Losses</a></p>


    <p><a href="/individual-losses/brought-forward-losses">Pre-MTD Losses</a></p>


<?php else: ?>

    <p>No details found.</p>

<?php endif; ?>