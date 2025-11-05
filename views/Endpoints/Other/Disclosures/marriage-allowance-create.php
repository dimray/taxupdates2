<p>Transfer part of your personal allowance to your spouse or civil partner.</p>

<form action="/disclosures/process-create-marriage-allowance" method="POST" class="generic-form hmrc-connection">

    <div class="form-input">
        <label for="spouse_nino">Spouse's National Insurance Number <span class="asterisk">*</span></label>
        <input type="text" id="spouse_nino" name="spouseOrCivilPartnerNino"
            value="<?= esc($marriage_allowance['spouseOrCivilPartnerNino'] ?? '') ?>" maxlength="9" required>
    </div>

    <div class="form-input">
        <label for="spouse_first">Spouse's First Name</label>
        <input type="text" id="spouse_first" name="spouseOrCivilPartnerFirstName"
            value="<?= esc($marriage_allowance['spouseOrCivilPartnerFirstName'] ?? '') ?>" maxlength="35">
    </div>

    <div class="form-input">
        <label for="spouse_surname">Spouse's Surname <span class="asterisk">*</span></label>
        <input type="text" id="spouse_surname" name="spouseOrCivilPartnerSurname"
            value="<?= esc($marriage_allowance['spouseOrCivilPartnerSurname'] ?? '') ?>" maxlength="35" required>
    </div>

    <div class="form-input">
        <label for="spouse_dob">Spouse's Date Of Birth</label>
        <input type="date" id="spouse_dob" name="spouseOrCivilPartnerDateOfBirth"
            value="<?= esc($marriage_allowance['spouseOrCivilPartnerDateOfBirth'] ?? '') ?>">
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>



<p><a href="/other/index">Cancel</a></p>