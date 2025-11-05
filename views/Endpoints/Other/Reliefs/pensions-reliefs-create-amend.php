<form action="/reliefs/process-create-and-amend-pensions-reliefs" method="POST" class="generic-form hmrc-connection">

    <?php if (isset($pension_reliefs)): ?>

        <h2>Pension Reliefs</h2>

        <div class="nested-input">
            <label>Regular Pension Contributions <span class="small">Payments where basic rate
                    tax relief is claimed by the pension provider ('relief at source')</span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="pensionReliefs[regularPensionContributions]"
                    value="<?= esc($pension_reliefs['regularPensionContributions'] ?? '') ?>">
            </label>

        </div>

        <div class="nested-input">
            <label>One Off Pension Contributions <span class="small">Total of any one-off payments</span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="pensionReliefs[oneOffPensionContributionsPaid]"
                    value="<?= esc($pension_reliefs['oneOffPensionContributionsPaid'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Annuity Payments<div class="small">Payments to a retirement annuity contract
                    where basic rate tax relief will not be claimed by the provider</div>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="pensionReliefs[retirementAnnuityPayments]"
                    value="<?= esc($pension_reliefs['retirementAnnuityPayments'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Payments To Employer Scheme <span class="small">Payments which were not
                    deducted from pay before tax</span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="pensionReliefs[paymentToEmployersSchemeNoTaxRelief]"
                    value="<?= esc($pension_reliefs['paymentToEmployersSchemeNoTaxRelief'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Payments To Overseas Pension Schemes <span class="small">Payments to a scheme which is
                    not UK-registered, which are eligible for tax relief and were not deducted from pay before
                    tax</span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="pensionReliefs[overseasPensionSchemeContributions]"
                    value="<?= esc($pension_reliefs['overseasPensionSchemeContributions'] ?? '') ?>">
            </label>
        </div>


    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a class="hmrc-connection" href="/reliefs/retrieve-pensions-reliefs">Cancel</a></p>


<?php $include_scroll_to_errors_script = true; ?>