<?php if (!empty($submissions)): ?>

    <table>
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

<?php else: ?>

    <p>No submissions found for this tax year.</p>

<?php endif; ?>