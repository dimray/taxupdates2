<?php if (!empty($clients)): ?>

<div class="long-table">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>NI Number</th>
                <th>Agent Status</th>
                <!-- <th>Update Status</th>
                    <th>Submissions</th> -->
                <!-- <th>Filing</th> -->


            </tr>
            <tr>
                <?php if (count($clients) > 1): ?>

                <th><input type="text" id="filter_name" placeholder="Filter by name"></th>
                <th><input type="text" id="filter_nino" placeholder="Filter by NI number"></th>
                <?php else: ?>

                <th></th>
                <th></th>
                <?php endif; ?>

                <!-- <th class="table-subheading">Agent Type</th>
                    <th class="table-subheading word-break"><span>Update</span> <span>Status</span></th> -->
            </tr>

        </thead>
        <tbody>

            <?php foreach ($clients as $client): ?>

            <tr>
                <td data-label="Name"><?= esc($client['client_name']) ?></td>
                <td data-label="NI Number"><?= esc($client['nino']) ?></td>
                <td data-label="Agent Status">
                    <?= $client['authorisation'] ? esc($client['authorisation'] . " agent") : "unauthorised" ?>
                </td>


                <td data-label="">
                    <form action="/clients/index" method="POST">

                        <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                        <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                        <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">
                        <input type="hidden" name="authorisation" value="<?= esc($client['authorisation'] ?? null) ?>">

                        <input type="hidden" name="update_status" value="true">

                        <button class="link" type="submit">Update Status</button>
                    </form>
                </td>

                <?php if ($client['authorisation']): ?>
                <td data-label="">
                    <form action="/clients/index" method="POST">

                        <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                        <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                        <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">

                        <input type="hidden" name="show_submissions" value="true">

                        <button class="link" type="submit">View Submissions</button>

                    </form>

                </td>

                <td data-label="">
                    <form action="/clients/index" method="POST">
                        <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                        <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                        <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">
                        <input type="hidden" name="authorisation" value="<?= esc($client['authorisation'] ?? null) ?>">

                        <input type="hidden" name="select_client" value="true">


                        <button class="button" type="submit">Select Client</button>
                    </form>

                </td>
                <?php else: ?>
                <td></td>
                <td></td>
                <?php endif; ?>

                <?php if ($search_result): ?>
                <td data-label="">
                    <p><a href="/clients/show-clients">Clear Search</a></p>
                </td>
                <?php endif; ?>

                <td data-label="">
                    <form action="/clients/confirm-delete" method="POST">

                        <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                        <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                        <button type="submit" class="x-delete">Delete</button>


                    </form>
                </td>



            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include ROOT_PATH . "views/shared/pagination.php" ?>

<?php include ROOT_PATH . "views/shared/errors.php"; ?>



<hr>

<h3>Search For A Client</h3>

<p>Find a specific client by NI Number</p>

<form class="inline-form end gap-0" method="POST" action="/clients/find-client">
    <div class="form-input width-200">
        <label for="search_nino">NI Number</label>
        <input type="text" name="search_nino" id="search_nino">
    </div>

    <button type="submit">Search</button>

</form>

<hr>


<?php else: ?>

<p>There are no clients to show. Get started by adding clients.</p>


<?php endif; ?>


<p><a href="/clients/add-clients">Add Clients</a></p>


<?php $include_clients_show_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>