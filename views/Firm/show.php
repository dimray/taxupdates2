<?php if (!empty($agents)): ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agents as $agent): ?>
                    <tr>
                        <td><?= esc($agent['name']) ?></td>
                        <td><?= esc($agent['email']) ?></td>
                        <td><?= $agent['agent_admin'] === 1 ? "Yes" : "No" ?></td>
                        <?php if ($is_admin && !$agent['agent_admin']): ?>
                            <td>
                                <?php $query_string = http_build_query(["agent_user_id" => $agent['user_id']]) ?>
                                <p><a href="/firm/delete-agent?<?= $query_string ?>">Remove</a></p>
                            </td>
                            <td>
                                <?php $query_string = http_build_query(["agent_user_id" => $agent['user_id']]) ?>
                                <p><a href="/firm/transfer-admin?<?= $query_string ?>">Make Admin</a></p>
                            </td>

                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include ROOT_PATH . "views/shared/pagination.php" ?>
<?php else: ?>

    <p>No agents found.</p>

<?php endif; ?>

<?php if ($is_admin): ?>

    <p><a href="/firm/confirm-delete-firm" class="confirm-delete">Delete Firm</a></p>

<?php endif; ?>