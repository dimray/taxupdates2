<div class="form-input">

    <label for="countryCode">Select Country <span class="asterisk">*</span></label>

    <select id="countryCode" name="country_code" required>

        <option value="" <?= empty($country_code) ? 'selected' : '' ?> hidden>Please select a country</option>

        <?php foreach ($country_codes as $continent => $countries): ?>

            <optgroup label="<?= esc($continent) ?>">

                <?php foreach ($countries as $code => $name): ?>

                    <option value="<?= esc($code) ?>"
                        <?= (isset($country_code) && $country_code === $code) ? 'selected' : '' ?>>

                        <?= esc($name) ?> - <?= esc($code) ?>

                    </option>

                <?php endforeach; ?>

            </optgroup>

        <?php endforeach; ?>

    </select>



</div>