<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/capital-gains/process-create-and-amend-other-capital-gains" method="POST"
    class="generic-form hmrc-connection">

    <?php if (isset($disposals)): ?>

        <h2>Disposals</h2>

        <div id="disposals-container">

            <?php foreach (($disposals ?? []) as $i => $disposal): ?>

                <div class="disposals-group field-container" data-group="disposals">

                    <div class="nested-input">
                        <label><span>Type Of Asset <span class="asterisk">*</span></span>
                            <select data-name="assetType">
                                <?php $scheme_plan_type = $disposal['assetType'] ?? ''; ?>
                                <option value="" disabled selected hidden>Pick an option</option>
                                <option value="other-property" <?= $scheme_plan_type === 'other-property' ? 'selected' : '' ?>>
                                    Other Property</option>
                                <option value="unlisted-shares"
                                    <?= $scheme_plan_type === 'unlisted-shares' ? 'selected' : '' ?>>
                                    Unlisted Shares</option>
                                <option value="listed-shares" <?= $scheme_plan_type === 'listed-shares' ? 'selected' : '' ?>>
                                    Listed Shares</option>
                                <option value="other-asset" <?= $scheme_plan_type === 'other-asset' ? 'selected' : '' ?>>Other
                                    Asset</option>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Description Of Asset <span class="asterisk">*</span></span>
                            <input type="text" data-name="assetDescription" maxlength="90"
                                value="<?= esc($disposal['assetDescription'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Acquisition Date <span class="asterisk">*</span></span>
                            <input type="date" data-name="acquisitionDate"
                                value="<?= esc($disposal['acquisitionDate'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Disposal Date <span class="asterisk">*</span></span>
                            <input type="date" data-name="disposalDate" value="<?= esc($disposal['disposalDate'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Disposal Proceeds <span class="asterisk">*</span></span>
                            <input type="number" data-name="disposalProceeds" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['disposalProceeds'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Allowable Costs <span class="asterisk">*</span></span>
                            <input type="number" data-name="allowableCosts" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['allowableCosts'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Gain
                            <input type="number" data-name="gain" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['gain'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Loss
                            <input type="number" data-name="loss" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['loss'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                    <h3>Reliefs</h3>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="PRR"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('PRR', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Private Residence Relief (no Letting Relief)</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="LET"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('LET', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Private Residence Relief And Letting Relief</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="GHO"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('GHO', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Gift Hold-Over Relief</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="ROR"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('ROR', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Rollover Relief</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="PRO"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('PRO', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Provisional Rollover Relief</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="ESH"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('ESH', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Employee Shares</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="BAD"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('BAD', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Business Asset Disposal Relief</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="INV"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('INV', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Investors Relief</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="NVC"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('NVC', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Negligible Value Claims</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="EOT"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('EOT', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Employee Ownership Trust</span>
                    </label>

                    <label class="inline-checkbox stacked label-text">
                        <input type="checkbox" data-name="claimOrElectionCodes[]" value="OTH"
                            <?= !empty($disposal['claimOrElectionCodes']) && in_array('OTH', $disposal['claimOrElectionCodes'], true) ? "checked" : "" ?>>
                        <span>Other Claims</span>
                    </label>

                    <br>

                    <div class="nested-input">
                        <label>Gain After Relief
                            <input type="number" data-name="gainAfterRelief" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['gainAfterRelief'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Loss After Relief
                            <input type="number" data-name="lossAfterRelief" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['lossAfterRelief'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Capital Gains Real Time Tax Paid
                            <input type="number" data-name="rttTaxPaid" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($disposal['rttTaxPaid'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <h2>Non Standard Gains</h2>

    <div class="nested-input">
        <label>Carried Interest Gain
            <input type="number" name="nonStandardGains[carriedInterestGain]" min="0" max="99999999999.99" step="0.01"
                value="<?= $non_standard_gains['carriedInterestGain'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Carried Interest Real Time Tax Paid
            <input type="number" name="nonStandardGains[carriedInterestRttTaxPaid]" min="0" max="99999999999.99"
                step="0.01" value="<?= $non_standard_gains['carriedInterestRttTaxPaid'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Attributed Gains (connected parties)
            <input type="number" name="nonStandardGains[attributedGains]" min="0" max="99999999999.99" step="0.01"
                value="<?= $non_standard_gains['attributedGains'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Attributed Gains Real Time Tax Paid
            <input type="number" name="nonStandardGains[attributedGainsRttTaxPaid]" min="0" max="99999999999.99"
                step="0.01" value="<?= $non_standard_gains['attributedGainsRttTaxPaid'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Other Gains
            <input type="number" name="nonStandardGains[otherGains]" min="0" max="99999999999.99" step="0.01"
                value="<?= $non_standard_gains['otherGains'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Other Gains Real Time Tax Paid
            <input type="number" name="nonStandardGains[otherGainsRttTaxPaid]" min="0" max="99999999999.99" step="0.01"
                value="<?= $non_standard_gains['otherGainsRttTaxPaid'] ?? '' ?>">
        </label>
    </div>

    <hr>


    <h2>Losses</h2>

    <div class="nested-input">
        <label>Brought Forward Losses Used
            <input type="number" name="losses[broughtForwardLossesUsedInCurrentYear]" min="0" max="99999999999.99"
                step="0.01" value="<?= $losses['broughtForwardLossesUsedInCurrentYear'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Trading Losses Set Against Capital Gains
            <input type="number" name="losses[setAgainstInYearGains]" min="0" max="99999999999.99" step="0.01"
                value="<?= $losses['setAgainstInYearGains'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Capital Losses Set Against Income
            <input type="number" name="losses[setAgainstInYearGeneralIncome]" min="0" max="99999999999.99" step="0.01"
                value="<?= $losses['setAgainstInYearGeneralIncome'] ?? '' ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Capital Losses Set Against Previous Year Income
            <input type="number" name="losses[setAgainstEarlierYear]" min="0" max="99999999999.99" step="0.01"
                value="<?= $losses['setAgainstEarlierYear'] ?? '' ?>">
        </label>
    </div>

    <hr>

    <h2>Adjustments</h2>

    <div class="nested-input">
        <label>Adjustments to CGT Payable
            <input type="number" name="adjustments" min="-99999999999.99" max="99999999999.99" step="0.01"
                value="<?= $adjustments ?? '' ?>">
        </label>
    </div>

    <hr>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>


<p><a class="hmrc-connection" href="/capital-gains/retrieve-other-capital-gains">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>