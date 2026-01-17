<?php if (!empty($submissions)): ?>
    `<div class="long-table">
        <table class="desktop-view">
            <thead>
                <tr>
                    <th>Period Start</th>
                    <th>Period End</th>
                    <th>Submission Type</th>
                    <th>Time Submitted</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?= esc(formatDate($submission['period_start'])) ?></td>
                        <td><?= esc(formatDate($submission['period_end'])) ?></td>
                        <td><?= esc($submission_types[$submission['submission_type']] ?? $submission['submission_type']) ?></td>
                        <td><?= esc(formatDateTime($submission['submitted_at'])) ?></td>

                        <td>
                            <form action="/submissions/view-submission" method="GET">
                                <input type="hidden" name="submission_reference"
                                    value="<?= esc($submission['submission_reference']) ?>">
                                <button class="link" type="submit">View</button>
                            </form>
                        </td>

                        <td>
                            <form action="/submissions/download-submission" method="GET">

                                <input type="hidden" name="submission_reference"
                                    value="<?= esc($submission['submission_reference']) ?>">

                                <button class="link" type="submit">Download csv</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mobile-view">
            <?php foreach ($submissions as $submission): ?>

                <div class="card">
                    <div class="data-row">
                        <div class="label">Period Start</div>
                        <div class="value"><?= esc(formatDate($submission['period_start'])) ?></div>
                    </div>
                    <div class="data-row">
                        <div class="label">Period End</div>
                        <div class="value"><?= esc(formatDate($submission['period_end'])) ?></div>
                    </div>
                    <div class="data-row">
                        <div class="label">Submission Type</div>
                        <div class="value">
                            <?= esc($submission_types[$submission['submission_type']] ?? $submission['submission_type']) ?>
                        </div>
                    </div>
                    <div class="data-row">
                        <div class="label">Time Submitted</div>
                        <div class="value"><?= esc(formatDateTime($submission['submitted_at'])) ?></div>
                    </div>


                    <form action="/submissions/view-submission" method="GET">
                        <input type="hidden" name="submission_reference"
                            value="<?= esc($submission['submission_reference']) ?>">
                        <button class="link" type="submit">View</button>
                    </form>

                    <form action="/submissions/download-submission" method="GET">

                        <input type="hidden" name="submission_reference"
                            value="<?= esc($submission['submission_reference']) ?>">

                        <button class="link" type="submit">Download csv</button>
                    </form>

                </div>

            <?php endforeach; ?>
        </div>


    </div>

<?php else: ?>

    <p>No submissions found for this tax year.</p>

<?php endif; ?>