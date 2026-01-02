<?php if (!empty($businesses)): ?>

<div class="short-table">
    <table class="desktop-view">
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
                <td data-label="Type Of Business"><?= esc($business["typeOfBusiness"] ?? 'Not known') ?></td>
                <td data-label="Business ID"><?= esc($business["businessId"] ?? 'Not Known') ?></td>
                <td data-label="Trading Name"><?= esc($business["tradingName"] ?? 'N/A') ?></td>
                <td>
                    <?php if ($year_end): ?>

                    <form action="/business-details/retrieve-business-details" class="hmrc-connection">
                        <input type="hidden" name="business_id" value="<?= esc($business["businessId"] ?? '') ?>">
                        <input type="hidden" name="type_of_business"
                            value="<?= esc($business["typeOfBusiness"] ?? '') ?>">
                        <?php if ($business['typeOfBusiness'] === "self-employment"): ?>
                        <input type="hidden" name="trading_name" value="<?= esc($business["tradingName"] ?? '') ?>">
                        <?php endif; ?>
                        <button class="link" type="submit">Select</button>
                    </form>

                    <?php else: ?>

                    <form action="/obligations/retrieve-cumulative-obligations" class="hmrc-connection">
                        <input type="hidden" name="business_id" value="<?= esc($business["businessId"] ?? '') ?>">
                        <input type="hidden" name="type_of_business"
                            value="<?= esc($business["typeOfBusiness"] ?? '') ?>">
                        <?php if ($business['typeOfBusiness'] === "self-employment"): ?>
                        <input type="hidden" name="trading_name" value="<?= esc($business["tradingName"] ?? '') ?>">
                        <?php endif; ?>
                        <button class="link" type="submit">Select</button>
                    </form>

                    <?php endif; ?>

                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <div class="mobile-view">
        <?php foreach ($businesses as $business): ?>

        <div class="card">

            <div class="data-row">
                <div class="label">Type Of Business</div>
                <div class="value"><?= esc($business["typeOfBusiness"] ?? 'Not known') ?></div>
            </div>

            <div class="data-row">
                <div class="label">Business ID</div>
                <div class="value"><?= esc($business["businessId"] ?? 'Not Known') ?></div>
            </div>

            <div class="data-row">
                <div class="label">Trading Name</div>
                <div class="value"><?= esc($business["tradingName"] ?? 'N/A') ?></div>
            </div>

        </div>

        <?php if ($year_end): ?>

        <form action="/business-details/retrieve-business-details" class="hmrc-connection">
            <input type="hidden" name="business_id" value="<?= esc($business["businessId"] ?? '') ?>">
            <input type="hidden" name="type_of_business" value="<?= esc($business["typeOfBusiness"] ?? '') ?>">
            <?php if ($business['typeOfBusiness'] === "self-employment"): ?>
            <input type="hidden" name="trading_name" value="<?= esc($business["tradingName"] ?? '') ?>">
            <?php endif; ?>
            <button class="link" type="submit">Select</button>
        </form>

        <?php else: ?>

        <form action="/obligations/retrieve-cumulative-obligations" class="hmrc-connection">
            <input type="hidden" name="business_id" value="<?= esc($business["businessId"] ?? '') ?>">
            <input type="hidden" name="type_of_business" value="<?= esc($business["typeOfBusiness"] ?? '') ?>">
            <?php if ($business['typeOfBusiness'] === "self-employment"): ?>
            <input type="hidden" name="trading_name" value="<?= esc($business["tradingName"] ?? '') ?>">
            <?php endif; ?>
            <button class="link" type="submit">Select</button>
        </form>

        <?php endif; ?>

        <hr>

        <?php endforeach; ?>
    </div>


</div>

<?php else : ?>

<p>No businesses found.</p>

<?php endif; ?>

<?php if (!$supporting_agent): ?>
<p><a href="/individual-calculations/trigger-calculation">Tax Calculation</a></p>
<?php endif; ?>