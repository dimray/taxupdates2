<?php if (!$add_country): ?>

    <h2>Zero Adjustments</h2>

    <p>There is no requirement to submit accounting adjustments unless they are needed.</p>

    <p>
        In some circumstances, such as when the accounting dates have changed, HMRC require adjustments to be submitted even
        if they are zero. If
        you are required to submit adjustments and they are all zero, tick and submit the 'Zero Adjustments' checkbox.
    </p>

    <form action="/business-source-adjustable-summary/process" method="POST" class="generic-form">

        <div class="inline-checkbox">
            <label><input type="checkbox" name="zeroAdjustments" id="zero-adjustments-toggle" value="true"><span>Set All
                    Adjustments To Zero</span></label>

            <button class="button" type="submit">Submit</button>

        </div>

    </form>

<?php endif; ?>


<h2>Adjustments</h2>

<p> Adjustments should be submitted as a positive or negative amount. For example, if you have already
    submitted advertising costs of £250 but the figure should be £200, the adjustment required would be -50.
    Adjustments are always made against the total of the original quarterly updates. Each new adjustment will
    overwrite the previous adjustment.
</p>