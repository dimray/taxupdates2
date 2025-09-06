<?php if (!empty($obligations)): ?>

    <h2>Filing Obligations</h2>

    <div class="table-container">
        <table>

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
                                <form action="/<?= $controller ?>/retrieve-cumulative-period-summary" method="GET">

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
    </div>

<?php else: ?>

    <p>No Cumulative Summary Obligations found for this year.</p>

<?php endif; ?>

<p><a href="/business-details/retrieve-business-details">Business Details</a></p>