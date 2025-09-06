<form action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">


    <label for="csv_upload" class="csv_upload_label">1. Select CSV file</label>
    <span id="csv_filename"></span>


    <input type="file" id="csv_upload" name="csv_upload"
        accept=".csv, text/csv, application/vnd.ms-excel, text/plain, application/csv, text/tab-separated-values"
        required>

    <br>
    <br>


    <button class="link" type="submit">2. Submit selected file</button>

</form>

<?php $include_file_upload_script = true; ?>