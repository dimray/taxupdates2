<p>Fields marked <span class="asterisk">*</span> are required.
</p>

<form class="generic-form" action="/employments-income/process-create-amend-employment-financial-details" method="POST">

    <h2>Salary</h2>

    <div class="form-input">
        <label for="taxablePayToDate">Taxable Pay <span class="asterisk">*</span></label>
        <input type="number" min="0" max="999999999999.99" step="0.01" name="pay[taxablePayToDate]"
            is="taxablePayToDate" value="<?= esc($pay['taxablePayToDate'] ?? '') ?>" required>
    </div>

    <div class="form-input">
        <label for="totalTaxToDate">Total Tax <span class="asterisk">*</span></label>
        <input type="number" min="-999999999999.99" max="999999999999.99" step="0.01" name="pay[totalTaxToDate]"
            id="totalTaxToDate" value="<?= esc($pay['totalTaxToDate'] ?? '') ?>" required>
    </div>



    <details class="reduced-styling">
        <summary>
            <h2>Student Loans</h2>
        </summary>

        <br>

        <div class="form-input">
            <label for="uglDeductionAmount">UnderGraduate Student Loan Deductions</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="deductions[studentLoans][uglDeductionAmount]" id="uglDeductionAmount"
                value="<?= esc($deductions['studentLoans']['uglDeductionAmount'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="pglDeductionAmount">PostGraduate Student Loan Deductions</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="deductions[studentLoans][pglDeductionAmount]" id="pglDeductionAmount"
                value="<?= esc($deductions['studentLoans']['pglDeductionAmount'] ?? '') ?>">
        </div>

        <br>

    </details>


    <details class="reduced-styling">
        <summary>
            <h2>Taxable Benefits</h2>
        </summary>

        <br>

        <div class="form-input">
            <label for="accommodation">Accommodation</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[accommodation]"
                id="accommodation" value="<?= esc($benefits_in_kind['accommodation'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="assets">Assets Used</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[assets]" id="assets"
                value="<?= esc($benefits_in_kind['assets'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="assetTransfer">Assets Transferred</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[assetTransfer]"
                id="assetTransfer" value="<?= esc($benefits_in_kind['assetTransfer'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="beneficialLoan">Employer Loans</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[beneficialLoan]"
                id="beneficialLoan" value="<?= esc($benefits_in_kind['beneficialLoan'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="car">Car</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[car]" id="car"
                value="<?= esc($benefits_in_kind['car'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="carFuel">Car Fuel</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[carFuel]" id="carFuel"
                value="<?= esc($benefits_in_kind['carFuel'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="educationalServices">Education</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[educationalServices]"
                id="educationalServices" value="<?= esc($benefits_in_kind['educationalServices'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="entertaining">Entertaining</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[entertaining]"
                id="entertaining" value="<?= esc($benefits_in_kind['entertaining'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="expenses">Expenses</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[expenses]" id="expenses"
                value="<?= esc($benefits_in_kind['expenses'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="medicalInsurance">Medical Insurance</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[medicalInsurance]"
                id="medicalInsurance" value="<?= esc($benefits_in_kind['medicalInsurance'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="telephone">Phone</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[telephone]"
                id="telephone" value="<?= esc($benefits_in_kind['telephone'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="service">Services</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[service]" id="service"
                value="<?= esc($benefits_in_kind['service'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="taxableExpenses">Taxable Expenses</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[taxableExpenses]"
                id="taxableExpenses" value="<?= esc($benefits_in_kind['taxableExpenses'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="van">Van</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[van]" id="van"
                value="<?= esc($benefits_in_kind['van'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="vanFuel">Van Fuel</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[vanFuel]" id="vanFuel"
                value="<?= esc($benefits_in_kind['vanFuel'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="mileage">Mileage</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[mileage]" id="mileage"
                value="<?= esc($benefits_in_kind['mileage'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="nonQualifyingRelocationExpenses">Non Qualifying Relocation Expenses</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[nonQualifyingRelocationExpenses]" id="nonQualifyingRelocationExpenses"
                value="<?= esc($benefits_in_kind['nonQualifyingRelocationExpenses'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="nurseryPlaces">Childcare</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[nurseryPlaces]"
                id="nurseryPlaces" value="<?= esc($benefits_in_kind['nurseryPlaces'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="otherItems">Other</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[otherItems]"
                id="otherItems" value="<?= esc($benefits_in_kind['otherItems'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="paymentsOnEmployeesBehalf">Payments On Behalf Of Employee</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[paymentsOnEmployeesBehalf]" id="paymentsOnEmployeesBehalf"
                value="<?= esc($benefits_in_kind['paymentsOnEmployeesBehalf'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="personalIncidentalExpenses">Personal Incidental Expenses</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[personalIncidentalExpenses]" id="personalIncidentalExpenses"
                value="<?= esc($benefits_in_kind['personalIncidentalExpenses'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="qualifyingRelocationExpenses">Qualifying Relocation Expenses</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[qualifyingRelocationExpenses]" id="qualifyingRelocationExpenses"
                value="<?= esc($benefits_in_kind['qualifyingRelocationExpenses'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="employerProvidedProfessionalSubscriptions">Professional Subscriptions</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[employerProvidedProfessionalSubscriptions]"
                id="employerProvidedProfessionalSubscriptions"
                value="<?= esc($benefits_in_kind['employerProvidedProfessionalSubscriptions'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="employerProvidedServices">Services</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[employerProvidedServices]" id="employerProvidedServices"
                value="<?= esc($benefits_in_kind['employerProvidedServices'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="incomeTaxPaidByDirector">Directors Income Tax</label>
            <input type="number" min="0" max="999999999999.99" step="0.01"
                name="benefitsInKind[incomeTaxPaidByDirector]" id="incomeTaxPaidByDirector"
                value="<?= esc($benefits_in_kind['incomeTaxPaidByDirector'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="travelAndSubsistence">Travel And Subsistence</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[travelAndSubsistence]"
                id="travelAndSubsistence" value="<?= esc($benefits_in_kind['travelAndSubsistence'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="vouchersAndCreditCards">Vouchers</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[vouchersAndCreditCards]"
                id="vouchersAndCreditCards" value="<?= esc($benefits_in_kind['vouchersAndCreditCards'] ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="nonCash">Non Cash Benefits</label>
            <input type="number" min="0" max="999999999999.99" step="0.01" name="benefitsInKind[nonCash]" id="nonCash"
                value="<?= esc($benefits_in_kind['nonCash'] ?? '') ?>">
        </div>

        <br>

    </details>

    <details class="reduced-styling">
        <summary>
            <h2>Off-Payroll Working</h2>
        </summary>

        <div class="inline-checkbox">
            <label>
                <input type="checkbox" name="off_payroll_worker" value="true">
                Tick box if PAYE is deducted by employer under the Off-Payroll Working Rules (IR35)
            </label>
        </div>
    </details>



    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Update</button>

</form>

<p><a href="/employments-income/list-employments">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>