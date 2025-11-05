<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>


<form action="/charges/process-create-or-amend-high-income-child-benefit-charge-submission" method="POST"
    class="generic-form hmrc-connection">


    <div class="form-input">
        <label for="amountOfChildBenefitReceived"><span>Amount Of Child Benefit Received <span
                    class="asterisk">*</span></span></label>
        <input type="number" name="amountOfChildBenefitReceived" id="amountOfChildBenefitReceived"
            value="<?= esc($amount_of_child_benefit_received ?? '') ?>" min="0" max="99999999999.99" step="0.01">
    </div>

    <div class="form-input">
        <label for="numberOfChildren"><span>Number Of Children <span class="asterisk">*</span></span></label>
        <input type="number" name="numberOfChildren" id="numberOfChildren" value="<?= esc($number_of_children ?? '') ?>"
            min="0" max="99" step="1">
    </div>

    <div class="form-input">
        <label for="dateCeased">Date Child Benefit Ended</label>
        <input type="date" name="dateCeased" id="dateCeased" value="<?= esc($date_ceased ?? '') ?>">
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>

</form>

<p><a class="hmrc-connection" href="/charges/retrieve-high-income-child-benefit-charge-submission">Cancel</a></p>