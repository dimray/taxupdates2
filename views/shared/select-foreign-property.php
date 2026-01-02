<div class="form-input">

    <label for="foreign_property">Select Property <span class="asterisk">*</span></label>

    <select id="foreign_property" name="hmrc_property_id" required>
        <option value="" <?= empty($hmrc_property_id) ? 'selected' : '' ?> hidden>
            Please select a property
        </option>

        <?php foreach ($foreign_properties as $property):

            // Extract the necessary values
            $id = $property['propertyId'];
            $name = $property['propertyName'];
            $country = getCountry($property['countryCode']);

            // Check if this property should be pre-selected (assuming $hmrc_property_id holds the selected ID)
            $selected = (
                isset($hmrc_property_id) &&
                $hmrc_property_id === $id
            ) ? 'selected' : '';
        ?>
            <option value="<?= esc($id) ?>" <?= $selected ?>>
                <?= esc($name) ?> (<?= esc($country) ?>)
            </option>
        <?php endforeach; ?>

    </select>



</div>