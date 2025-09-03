<?php $form_action = "/clients/process-upload"; ?>

<p>Your data should have two columns, with the client name in one column and
    the client NI Number in the other. The maximum number of clients that can be uploaded at once is 100. Column headers
    are not needed.</p>

<dialog>

    <button type="button" class="close-dialog">x</button>

    <?php require ROOT_PATH . "views/Clients/client-upload-example.php"; ?>

    <br>

    <!-- class is needed for copy-text.js -->
    <button class="copy-button">Copy</button>
</dialog>

<button type="button" class="open-dialog link">View Example Data</button>

<br>
<br>
<hr>

<h2>Upload client list as a CSV file:</h2>


<form action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">


    <label for="csv_upload" class="csv_upload_label">1. Select your CSV file</label>


    <input type="file" id="csv_upload" name="csv_upload"
        accept=".csv, text/csv, application/vnd.ms-excel, text/plain, application/csv, text/tab-separated-values"
        required>

    <br>
    <br>


    <button class="link" type="submit">2. Submit selected file</button>

</form>

<br>
<hr>

<h2>Or paste your client list here:</h2>

<form action="<?= $form_action ?>" method="POST">

    <textarea name="pasted_data" rows="10" cols="80" placeholder="Paste data here..."></textarea><br>

    <button type="submit" class="link">Submit data</button>
</form>

<br>
<hr>

<p><a href="/clients/show-clients">Cancel</a></p>

<?php $include_copy_text_script = true; ?>
<?php $include_dialog_script = true; ?>