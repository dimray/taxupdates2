<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/reliefs/process-create-and-amend-charitable-giving-tax-relief" method="POST"
    class="generic-form hmrc-connection">

    <?php if (isset($gift_aid_payments)): ?>

        <h2>Gift Aid</h2>

        <h3>Non UK Charities</h3>

        <div class="nested-input">
            <label><span>Foreign Charity Names <span class="asterisk">*</span></span>
                <span class="small">Separate names with
                    commas</span>
                <input type="text" maxlength="300" name="giftAidPayments[nonUkCharities][charityNames]"
                    value="<?= esc(implode(', ', $gift_aid_payments['nonUkCharities']['charityNames'] ?? [])) ?>">

            </label>
        </div>

        <div class="nested-input">
            <label><span>Total Gift Aid Donations To Foreign Charities <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="giftAidPayments[nonUkCharities][totalAmount]"
                    value="<?= esc($gift_aid_payments['nonUkCharities']['totalAmount']  ?? '') ?>">
            </label>
        </div>

        <hr>

        <h3>UK Charities</h3>

        <div class="nested-input">
            <label>Total Regular Payments
                <input type="number" min="0" max="99999999999.99" step="0.01" name="giftAidPayments[totalAmount]"
                    value="<?= esc($gift_aid_payments['totalAmount']  ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Total One Off Payments
                <input type="number" min="0" max="99999999999.99" step="0.01" name="giftAidPayments[oneOffAmount]"
                    value="<?= esc($gift_aid_payments['oneOffAmount']  ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Payments Treated As Made In Previous Tax Year
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="giftAidPayments[amountTreatedAsPreviousTaxYear]"
                    value="<?= esc($gift_aid_payments['amountTreatedAsPreviousTaxYear']  ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Payments In Following Tax Year Treated As Made This Tax Year
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="giftAidPayments[amountTreatedAsSpecifiedTaxYear]"
                    value="<?= esc($gift_aid_payments['amountTreatedAsSpecifiedTaxYear']  ?? '') ?>">
            </label>
        </div>

        <hr>

    <?php endif; ?>

    <?php if (isset($gifts)): ?>

        <h2>Gifts</h2>

        <h3>Non UK Charities</h3>

        <div class="nested-input">
            <label><span>Foreign Charity Names <span class="asterisk">*</span></span>
                <span class="small">Separate names with
                    commas</span>
                <input type="text" maxlength="300" name="gifts[nonUkCharities][charityNames]"
                    value="<?= esc(implode(', ', $gifts['nonUkCharities']['charityNames'] ?? [])) ?>">

            </label>
        </div>

        <div class="nested-input">
            <label><span>Total Value <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01" name="gifts[nonUkCharities][totalAmount]"
                    value="<?= esc($gifts['nonUkCharities']['totalAmount']  ?? '') ?>">
            </label>
        </div>

        <hr>

        <h3>UK Charities</h3>

        <div class="nested-input">
            <label>Value Of Land And Buildings Gifted
                <input type="number" min="0" max="99999999999.99" step="0.01" name="gifts[landAndBuildings]"
                    value="<?= esc($gifts['landAndBuildings'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Value Of Shares And Securities Gifted
                <input type="number" min="0" max="99999999999.99" step="0.01" name="gifts[sharesOrSecurities]"
                    value="<?= esc($gifts['sharesOrSecurities']  ?? '') ?>">
            </label>
        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>


<p><a class="hmrc-connection" href="/reliefs/retrieve-charitable-giving-tax-relief">Cancel</a></p>


<?php $include_scroll_to_errors_script = true; ?>