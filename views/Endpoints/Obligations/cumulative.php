<?php if (!empty($obligations)): ?>

    <h2>Filing Obligations</h2>

    <div class="regular-table">
        <table class="desktop-view">

            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Due Date</th>
                    <th>Received Date</th>
                    <th>Status</th>

                </tr>
            </thead>

            <tbody>
                <?php foreach ($obligations as $obligation): ?>
                    <tr>
                        <td><?= esc(formatDate($obligation["periodStartDate"])) ?></td>
                        <td><?= esc(formatDate($obligation["periodEndDate"])) ?></td>
                        <td><?= esc(formatDate($obligation["dueDate"])) ?></td>
                        <td><?= esc(formatDate($obligation["receivedDate"] ?? '')) ?></td>
                        <td><?= esc(ucfirst($obligation["status"])) ?></td>

                        <?php if ($obligation['status'] === "fulfilled"): ?>
                            <td>
                                <form class="hmrc-connection" action="/<?= $controller ?>/retrieve-cumulative-period-summary"
                                    method="GET">

                                    <button class="link" type="submit">View</button>
                                </form>

                            </td>
                        <?php endif; ?>

                        <?php if ($obligation['status'] === "open"): ?>
                            <td>
                                <form action="/uploads/create-cumulative-upload" method="GET">
                                    <input type="hidden" value="<?= esc($obligation['periodStartDate'] ?? '') ?>"
                                        name="period_start_date">
                                    <input type="hidden" value="<?= esc($obligation['periodEndDate'] ?? '') ?>"
                                        name="period_end_date">
                                    <button class="link" type="submit">Upload</button>
                                </form>
                            </td>
                        <?php endif; ?>

                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>


        <div class="mobile-view">

            <?php foreach ($obligations as $obligation): ?>

                <div class="card">
                    <div class="data-row">
                        <div class="label">Start Date</div>
                        <div class="value"><?= esc(formatDate($obligation["periodStartDate"])) ?></div>
                    </div>



                    <div class="data-row">
                        <div class="label">End Date</div>
                        <div class="value"><?= esc(formatDate($obligation["periodEndDate"])) ?></div>
                    </div>



                    <div class="data-row">
                        <div class="label">Due Date</div>
                        <div class="value"><?= esc(formatDate($obligation["dueDate"])) ?></div>
                    </div>



                    <div class="data-row">
                        <div class="label">Date Received</div>
                        <div class="value"><?= esc(formatDate($obligation["receivedDate"] ?? '')) ?></div>
                    </div>



                    <div class="data-row">
                        <div class="label">Status</div>
                        <div class="value"><?= esc(formatDate($obligation["status"])) ?></div>
                    </div>
                </div>


                <?php if ($obligation['status'] === "fulfilled"): ?>
                    <td>
                        <form class="hmrc-connection" action="/<?= $controller ?>/retrieve-cumulative-period-summary" method="GET">

                            <button class="link" type="submit">View</button>
                        </form>

                    </td>
                <?php endif; ?>

                <?php if ($obligation['status'] === "open"): ?>
                    <td>
                        <form action="/uploads/create-cumulative-upload" method="GET">
                            <input type="hidden" value="<?= esc($obligation['periodStartDate'] ?? '') ?>" name="period_start_date">
                            <input type="hidden" value="<?= esc($obligation['periodEndDate'] ?? '') ?>" name="period_end_date">
                            <button class="link" type="submit">Upload</button>
                        </form>
                    </td>
                <?php endif; ?>

                <hr>


            <?php endforeach; ?>

        </div>


    </div>

<?php else: ?>

    <p>No Cumulative Summary Obligations found for this year.</p>

<?php endif; ?>

<p><a href="/business-details/retrieve-business-details">Business Details and Admin</a></p>