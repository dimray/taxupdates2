<p>Add clients you are already authorised to act for, or add new clients then request HMRC authorisation on the 'Show
    Clients' page.
</p>


<form class="generic-form" action="/clients/update-clients" method="POST">
    <div id="client-rows-container">
        <div class="client-row">
            <div class="client-inputs">
                <div class="form-input">
                    <label for="name_0">Name</label>
                    <input type="text" name="clients[0][name]" id="name_0">
                </div>
                <div class="form-input">
                    <label for="nino_0">NI Number</label>
                    <input type="text" name="clients[0][nino]" id="nino_0">
                </div>
            </div>
            <div class="row-actions">
                <button type="button" class="add-row-btn">+</button>
            </div>
        </div>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="link">Submit</button>
</form>


<p><a href="/clients/upload-clients">Add Clients From A Spreadsheet</a></p>

<hr>



<h2>Client Maintenance</h2>

<p>To edit a client record do one of the following:</p>
<ul>
    <li>delete the record then enter it again</li>
    <li>overwrite the record by saving a new record with the same NI Number</li>
</ul>

<p>Editing or deleting a client here doesn't affect the information HMRC hold, including agent authorisation.</p>

<p><a href="/clients/show-clients">Cancel</a></p>


<?php $include_clients_add_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>