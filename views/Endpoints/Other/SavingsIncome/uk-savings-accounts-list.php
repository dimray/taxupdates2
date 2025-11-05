<?php if (!empty($savings_accounts)): ?>

    <table>

        <thead>

            <th>Account ID</th>
            <th>Account Name</th>

        </thead>

        <tbody>

            <?php foreach ($savings_accounts as $account): ?>

                <tr>
                    <td><?= esc($account['savingsAccountId'] ?? '') ?></td>
                    <td> <?= esc($account['accountName'] ?? '') ?></td>
                    <td>
                        <form class="hmrc-connection" action="/savings/retrieve-uk-savings-account-annual-summary" method="GET">
                            <input type="hidden" name="account_id" value="<?= esc($account['savingsAccountId'] ?? '') ?>">
                            <input type="hidden" name="account_name" value="<?= esc($account['accountName'] ?? '') ?>">
                            <button type="submit" class="link">View Interest</button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>



<?php else: ?>

    <p>No Savings Accounts to display</p>

<?php endif; ?>

<p><a href="/savings/add-uk-savings-account">Add A Savings Account</a></p>

<p><a class="hmrc-connection" href="/savings/retrieve-savings-income">Foreign And Securities Interest</a></p>