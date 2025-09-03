<?php if (!empty($businesses)): ?>


<table>
    <thead>
        <tr>
            <th>Type of Business</th>
            <th>Business ID</th>
            <th>Trading Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($businesses as $business) : ?>
        <tr>
            <td><?= esc($business["typeOfBusiness"] ?? 'Not known') ?></td>
            <td><?= esc($business["businessId"] ?? 'Not Known') ?></td>
            <td><?= esc($business["tradingName"] ?? 'N/A') ?></td>
            <td>
                <form action="/business-details/retrieve-business-details">
                    <input type="hidden" name="business_id" value="<?= esc($business["businessId"] ?? '') ?>">
                    <input type="hidden" name="type_of_business" value="<?= esc($business["typeOfBusiness"] ?? '') ?>">
                    <?php if ($business['typeOfBusiness'] === "self-employment"): ?>
                    <input type="hidden" name="trading_name" value="<?= esc($business["tradingName"] ?? '') ?>">
                    <?php endif; ?>
                    <button class="link" type="submit">Select</button>
                </form>

            </td>

        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php else : ?>

<p>No businesses found.</p>

<?php endif; ?>

<br>

<p>It is currently not possible to add or remove a business through MTD software. To add or remove a business, go to <a
        href="https://www.gov.uk/log-in-register-hmrc-online-services?" target="blank">HMRC
        Online Services</a>.</p>