<form class="cumulative-data-form" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">


    <details>

        <summary>
            Instructions
        </summary>


        <p>Uploaded data should be in two columns, the first column showing the category name and the second column
            showing
            the
            total for that category. Column
            headers and unused categories are not required.</p>


        <p><strong>Only the HMRC-defined categories can be used to classify the amounts in your uploaded data. The
                available
                categories, and the required format, are
                shown in the example data.</strong></p>

        <dialog>

            <button type="button" class="close-dialog">x</button>

            <button class="copy-button">Copy data</button>

            <br>
            <br>

            <?php require ROOT_PATH . "views/Uploads/example-data.php"; ?>

        </dialog>

        <button type="button" class="open-dialog link">View Example Data</button>

        <p>If you need a simple MTD-compliant spreadsheet to track income and expenses, a template you can use or adapt
            is
            provided. To use the spreadsheet, click on the link below, then click 'File' at the top left,
            and
            select 'Make a
            copy'. To make your own copy, you will need a google account, you can create one for free if you don't
            already
            have
            one.</p>

        <?php if ($type_of_business === "self-employment"): ?>

            <p><a href="https://docs.google.com/spreadsheets/d/1PBl34xhQ4-LhrnuHd3HY01Q8tSvCM16K8SOlehACyck/edit?usp=sharing"
                    target="_blank">Get Self-Employment MTD Spreadsheet</a></p>

        <?php elseif ($type_of_business === "uk-property"): ?>

            <p><a href="https://docs.google.com/spreadsheets/d/1rgeGLCe2i72idvIeFBiUe_taRUZRm5lI_T_n35CFaK0/edit?usp=sharing"
                    target="_blank">Get UK Property MTD Spreadsheet</a></p>

        <?php elseif ($type_of_business === "foreign-property"): ?>

            <p><a href="https://docs.google.com/spreadsheets/d/1lqLZuJStvGOb4LwPrlcWFWIyxOx80uJ4KXIGfPabzBc/edit?usp=sharing"
                    target="_blank">Get Foreign Property MTD Spreadsheet</a></p>

        <?php endif; ?>

    </details>

    <?php if ($type_of_business === "foreign-property"): ?>

        <hr>

        <?php if (!empty($country_codes)): ?>

            <?php include ROOT_PATH . "views/shared/select-country.php"; ?>

            <p class="small">If you have properties in more than one country, upload one country's data and you
                will then be given the option to add further countries.</p>

            <input type="hidden" name="country_or_property" value="country">

        <?php else: ?>

            <?php include ROOT_PATH . "views/shared/select-foreign-property.php"; ?>

            <?php $source = urlencode("/uploads/create-cumulative-upload"); ?>

            <p class="small">If you have more than one property, upload one property's data and you will then be given the
                option to
                add further properties.
            </p>

            <p class="small">If your property is not shown in the drop-down box, you can add it <a
                    href="/property-business/create-foreign-property?source=<?= $source  ?>">here</a>.</p>


            <input type="hidden" name="country_or_property" value="property">

        <?php endif; ?>


        <hr>

        <span class="form-input">Foreign Tax Credit Relief</span>
        <div class="inline-checkbox">
            <input type="checkbox" name="foreign_tax_credit_relief" id="foreign_tax_credit_relief" value="1">
            <label for="foreign_tax_credit_relief">Tick Box To Claim Foreign Tax Credit Relief for this property</label>
        </div>

        <hr>


    <?php endif; ?>

    <?php
    /*
    <p><span class="either-or">Either</span> Upload your Cumulative Summary as a CSV file, using the upload link below,
        and then submit.
    </p>
    <p> <span class="either-or">OR</span> Paste your Cumulative
        Summary data in the box, and then submit.
    </p>
    */
    ?>

    <p class="upload_label">Upload Your File<span class="asterisk"> *</span></p>

    <br>

    <?php include ROOT_PATH . "views/shared/data-upload.php"; ?>

    <?php /* include ROOT_PATH . "views/shared/data-paste.php"; */ ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <br><br>

    <div>
        <button class="form-button" type="submit">Upload selected file</button>
    </div>

    <br>
    <hr>

    <?php include ROOT_PATH . "views/shared/mandatory-fields-simple.php"; ?>

</form>


<p><a href="/obligations/retrieve-cumulative-obligations">Cancel</a></p>

<?php $include_copy_text_script = true; ?>

<?php $include_dialog_script = true; ?>

<?php $include_scroll_to_errors_script = true; ?>