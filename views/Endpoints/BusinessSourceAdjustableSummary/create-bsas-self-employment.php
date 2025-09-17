<p> Adjustments should be submitted as a positive or negative amount. For example, if you have already
    submitted advertising costs of £250 but the figure should be £200, the adjustment required would be -50.
    Adjustments are always made against the total of the original quarterly updates. Each new adjustment will
    overwrite the previous adjustment.
</p>

<p>
    In some circumstances, such as when the accounting dates have changed, HMRC require adjustments to be submitted. If
    all adjustments are zero and adjustments are required, tick the 'Zero Adjustments' checkbox. Otherwise, there is no
    requirement to submit accounting adjustments if no values need adjusting.
</p>

<form action="/business-source-adjustable-summary/process" method="POST" class="generic-form"
    id="zero-adjustments-form">

    <h2>Zero Adjustments</h2>

    <div class="inline-checkbox">
        <input type="checkbox" name="zeroAdjustments" id="zero-adjustments-toggle" value="true">
        <label for="zeroAdjustments">Set All Adjustments To Zero</label>
    </div>

    <h2>Income</h2>

    <div class="form-input">
        <label for="turnover">Turnover</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="turnover" id="turnover"
            value="<?= $income['turnover'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="other">Other Income</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="other" id="other"
            value="<?= $income['other'] ?? '' ?>">
    </div>

    <h2>Expenses</h2>

    <div class="input-group">
        <div class="form-input">
            <label for="costOfGoods">Cost Of Goods</label>

            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="costOfGoods"
                id="costOfGoods" value="<?= $expenses['costOfGoods'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="costOfGoodsDisallowable">Cost Of Goods Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="costOfGoodsDisallowable"
                id="costOfGoodsDisallowable" value="<?= $additions['costOfGoodsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="paymentsToSubcontractors">Payments To Subcontractors</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="paymentsToSubcontractors"
                id="paymentsToSubcontractors" value="<?= $expenses['paymentsToSubcontractors'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="">Payments To Subcontractors Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="paymentsToSubcontractorsDisallowable" id="paymentsToSubcontractorsDisallowable"
                value="<?= $additions['paymentsToSubcontractorsDisallowable'] ?? '' ?>">
        </div>
    </div>


</form>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_zero_adjustments_script = true; ?>