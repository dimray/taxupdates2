<?php if (!empty($clients)): ?>

    <div class="client-list-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>NI Number</th>

                    <th colspan="2" class="center-text">HMRC Authorisation</th>
                </tr>
                <tr>
                    <?php if (count($clients) > 1): ?>

                        <th><input type="text" id="filter_name" placeholder="Filter by name"></th>
                        <th><input type="text" id="filter_nino" placeholder="Filter by NI number"></th>
                    <?php else: ?>

                        <th></th>
                        <th></th>
                    <?php endif; ?>

                    <th class="table-subheading">Current Status</th>
                    <th class="table-subheading word-break"><span>Update</span> <span>Status</span></th>
                </tr>

            </thead>
            <tbody>

                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= esc($client['client_name']) ?></td>
                        <td><?= esc($client['nino']) ?></td>
                        <td><?= esc($client['authorisation'] ?? "unauthorised") ?></td>


                        <td>
                            <form class="inline-form show-clients" action="/clients/index" method="POST">
                                <input type="hidden" name="select_client" value="true">
                                <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                                <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                                <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">
                                <input type="hidden" name="authorisation" value="<?= esc($client['authorisation'] ?? null) ?>">

                                <input type="checkbox" name="check_authorisation" value="1"
                                    title="Update HMRC authorisation status">

                                <button class="link" type="submit">Select
                                    Client</button>
                            </form>
                        </td>

                        <?php if ($client['authorisation']): ?>
                            <td>
                                <button class="link" type="submit" name="show_submissions"
                                    value="<?= $client['client_id'] ?>">Submissions</button>
                            </td>
                        <?php endif; ?>

                        <?php if ($search_result): ?>
                            <td>
                                <p><a href="/clients/show-clients">Clear Search</a></p>
                            </td>
                        <?php endif; ?>

                        <td>
                            <form action="/clients/confirm-delete" method="POST">

                                <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                                <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                                <button type="submit" class="x-delete">x</button>


                            </form>
                        </td>



                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>



    <?php include ROOT_PATH . "views/shared/errors.php"; ?>



    <?php include ROOT_PATH . "views/shared/pagination.php" ?>

    <h3>Search For A Client</h3>

    <p>Find a specific client by NI Number</p>

    <form class="generic-form gap-0" method="POST" action="/clients/find-client">
        <div class="form-input width-200">
            <label for="search_nino">NI Number</label>
            <input type="text" name="search_nino" id="search_nino">
        </div>

        <button class="link" type="submit">Search</button>

    </form>

    <br>

<?php else: ?>

    <p>There are no clients to show. Get started by adding clients.</p>


<?php endif; ?>


<p><a href="/clients/add-clients">Add Clients</a></p>

<?php if (!empty($clients)): ?>

    <p><a href="/clients/delete-clients">Delete Clients</a></p>

<?php endif; ?>

<?php $include_clients_show_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>