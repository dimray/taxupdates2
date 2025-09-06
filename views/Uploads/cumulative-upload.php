<h2>Instructions</h2>

<p>You must only use the HMRC-defined categories to classify the amounts in your uploaded data. All the available
    categories are
    shown in the example data.</p>

<p>Uploaded data should be in two columns, the first column showing the category name and the second column showing the
    total for that category. Column
    headers and unused categories are not required.</p>

<dialog>

    <button type="button" class="close-dialog">x</button>

    <button class="copy-button">Copy data</button>

    <br>
    <br>

    <?php require ROOT_PATH . "views/Uploads/example-data.php"; ?>

</dialog>

<button type="button" class="open-dialog link">View Example Data</button>

<p>If you need a simple MTD-compliant spreadsheet to track income and expenses, one is provided <a
        href="https://docs.google.com/spreadsheets/d/1PBl34xhQ4-LhrnuHd3HY01Q8tSvCM16K8SOlehACyck/edit?usp=sharing"
        target="_blank">here</a>. To use the spreadsheet, click on the link, then click 'File' at the top left, and
    select 'Make a
    copy'. To make your own copy, you will need a google account, you can create one for free if you don't already have
    one.</p>

<p><a href="https://docs.google.com/spreadsheets/d/1PBl34xhQ4-LhrnuHd3HY01Q8tSvCM16K8SOlehACyck/edit?usp=sharing"
        target="_blank">Self-Employment MTD Spreadsheet</a></p>



<?php include ROOT_PATH . "views/shared/errors.php"; ?>

<hr>

<h2>Upload your Cumulative Summary as a CSV file</h2>

<?php include ROOT_PATH . "views/shared/data-upload.php"; ?>

<br>
<hr>

<h2>Or paste your Cumulative Summary data here</h2>

<?php include ROOT_PATH . "views/shared/data-paste.php"; ?>

<br>
<hr>



<p><a href="/obligations/retrieve-cumulative-obligations">Cancel</a></p>

<?php $include_copy_text_script = true; ?>

<?php $include_dialog_script = true; ?>