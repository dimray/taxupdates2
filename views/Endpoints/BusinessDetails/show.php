<?php if (!empty($business_details)): ?>




<h2>Adjustments, Allowances And Losses</h2>

<h3>Annual Summary</h3>

<p>Explanation</p>

<p><a href="">Annual Summary</a></p>

<h3>BSAS</h3>

<p>Explanation</p>

<p><a href="">BSAS</a></p>

<h3>Losses</h3>


<p><a href="/individual-losses/loss-claims">Losses</a></p>

<p><a href="/individual-losses/brought-forward-losses">Pre-MTD Losses</a></p>

<h3>Change Reporting Period</h3>

<p><a href="/business-details/change-reporting-period?current_period=<?= $current_period ?>">Change Quarterly
        Period Type</a></p>


<?php else: ?>

<p>No details found.</p>

<?php endif; ?>