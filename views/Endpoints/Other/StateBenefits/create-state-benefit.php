<form action="/state-benefits/process-create-state-benefit" method="POST" class="generic-form">

    <div class="form-input">
        <label for="benefit_type">Benefit Type</label>
        <select name="benefitType" id="benefit_type" required>
            <option value="" disabled selected>-- Please select --</option>
            <option value="incapacityBenefit">Incapacity Benefit</option>
            <option value="statePension">State Pension</option>
            <option value="statePensionLumpSum">State Pension Lump Sum</option>
            <option value="employmentSupportAllowance">Employment Support Allowance</option>
            <option value="jobSeekersAllowance">Jobseekers Allowance</option>
            <option value="bereavementAllowance">Bereavement Allowance</option>
            <option value="otherStateBenefits">Other State Benefits</option>
        </select>
    </div>

    <div class="form-input">
        <label for="start_date">Start Date</label>
        <input type="date" name="startDate" id="start_date" required>
    </div>

    <div class="form-input">
        <label for="end_date">End Date</label>
        <input type="date" name="endDate" id="end_date">
    </div>


    <?php include ROOT_PATH . "/views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>


<p><a href="/state-benefits/list-state-benefits">Cancel</a></p>