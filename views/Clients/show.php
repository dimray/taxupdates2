<?php if (!empty($clients)): ?>

    <div class="long-table">
        <table class="clients-table desktop-view">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>NI Number</th>
                    <th>Agent Status</th>
                </tr>
                <tr>
                    <?php if (count($clients) > 1): ?>

                        <th><input type="text" class="client-filter" id="filter_name" placeholder="Filter by name"></th>
                        <th><input type="text" class="client-filter" id="filter_nino" placeholder="Filter by NI number"></th>
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
                        <td><?= esc($client['client_name']) ?></td>
                        <td><?= esc($client['nino']) ?></td>
                        <td>
                            <?= $client['authorisation'] ? esc($client['authorisation'] . " agent") : "unauthorised" ?>
                        </td>




                        <?php if ($client['authorisation']): ?>
                            <td>
                                <form action="/clients/index" method="POST">
                                    <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                                    <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                                    <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">
                                    <input type="hidden" name="authorisation" value="<?= esc($client['authorisation'] ?? null) ?>">

                                    <input type="hidden" name="select_client" value="true">

                                    <button class="button" type="submit">Select</button>
                                </form>

                            </td>

                            <td>
                                <form action="/clients/index" method="POST">

                                    <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                                    <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                                    <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">

                                    <input type="hidden" name="show_submissions" value="true">

                                    <button class="link" type="submit">Submissions</button>

                                </form>

                            </td>


                        <?php else: ?>
                            <td></td>
                            <td></td>
                        <?php endif; ?>

                        <td>
                            <form action="/clients/index" method="POST">

                                <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                                <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                                <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">
                                <input type="hidden" name="authorisation" value="<?= esc($client['authorisation'] ?? null) ?>">

                                <input type="hidden" name="update_status" value="true">

                                <button class="link" type="submit">Update Status</button>
                            </form>
                        </td>

                        <?php if ($search_result): ?>
                            <td data-label="">
                                <p><a href="/clients/show-clients">Clear Search</a></p>
                            </td>
                        <?php endif; ?>

                        <td>
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


        <div class="mobile-view">

            <?php foreach ($clients as $client): ?>

                <div class="card">

                    <div class="data-row">
                        <div class="label">Name</div>
                        <div class="value"><?= esc($client['client_name']) ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">NI Number</div>
                        <div class="value"><?= esc($client['nino']) ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Agent Status</div>
                        <div class="value">
                            <?= $client['authorisation'] ? esc($client['authorisation'] . " agent") : "unauthorised" ?></div>
                    </div>

                </div>


                <?php if ($client['authorisation']): ?>


                    <form action="/clients/index" method="POST">
                        <input type="hidden" name="client_id" value="<?= esc($client['client_id']) ?>">
                        <input type="hidden" name="client_name" value="<?= esc($client['client_name']) ?>">
                        <input type="hidden" name="nino" value="<?= esc($client['nino']) ?>">
                        <input type="hidden" name="authorisation" value="<?= esc($client['authorisation'] ?? null) ?>">

                        <input type="hidden" name="select_client" value="true">


                        <button class="link" type="submit">Select Client</button>
                    </form>

                    <hr>

                <?php endif; ?>


                <?php if ($search_result): ?>

                    <p><a href="/clients/show-clients">Clear Search</a></p>

                <?php endif; ?>

            <?php endforeach; ?>
        </div>


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