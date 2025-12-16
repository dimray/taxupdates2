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

<form action="/clients/process-upload" method="POST" enctype="multipart/form-data">

    <?php include ROOT_PATH . "views/shared/data-upload.php"; ?>

    <br>

    <button class="link" type="submit">2. Submit Uploaded Data</button>

</form>

<br>
<hr>

<h2>Or paste your client list here:</h2>

<form action="/clients/process-upload" method="POST" enctype="multipart/form-data">

    <?php include ROOT_PATH . "views/shared/data-paste.php"; ?>

    <br>

    <button type="submit">Submit Pasted Data</button>

    <hr>

</form>



<p><a href="/clients/show-clients">Cancel</a></p>

<?php $include_copy_text_script = true; ?>
<?php $include_dialog_script = true; ?>