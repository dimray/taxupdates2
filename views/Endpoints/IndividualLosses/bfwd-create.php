<p>Input the total of any pre-Making Tax Digital losses brought forward for this business.</p>

<p>The year the loss is brought forward from is the year before you
    joined MTD, not the year when the loss was originally incurred.</p>


<form class="generic-form" action="/individual-losses/register-brought-forward-loss" method="GET">

    <div>
        <div class="form-input">
            <label for="loss_amount">Amount Of Loss</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="loss_amount" id="loss_amount" required>
        </div>

        <div class="form-input">
            <label for="loss_year">Year Loss Brought Forward From</label>
            <select name="loss_year" id="loss_year" required>
                <option value="<?= esc($tax_years['year_1']) ?>" selected><?= esc($tax_years['year_1']) ?></option>
                <option value="<?= esc($tax_years['year_2']) ?>"><?= esc($tax_years['year_2']) ?></option>
                <option value="<?= esc($tax_years['year_3']) ?>"><?= esc($tax_years['year_3']) ?></option>
                <option value="<?= esc($tax_years['year_4']) ?>"><?= esc($tax_years['year_4']) ?></option>
            </select>
        </div>

        <?php if ($_SESSION['type_of_business'] === "self-employment"): ?>

            <div class="form-input">
                <label for="loss_type">Type Of Loss</label>
                <select name="loss_type" id="loss_type" required>
                    <option value="self-employment" selected>Self-Employment Income</option>
                    <option value="self-employment-class4">Class 4 National Insurance</option>
                </select>
            </div>

        <?php endif; ?>

    </div>

    <?php include ROOT_PATH . 'views/shared/errors.php'; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/individual-losses/brought-forward-losses">Cancel</a></p>