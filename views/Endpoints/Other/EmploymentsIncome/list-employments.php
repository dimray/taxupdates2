<?php if (!empty($employments) || !empty($custom_employments)): ?>

    <table>

        <tbody>

            <tr>
                <th class="subheading" colspan="2">Employment Or Pension Data Retrieved By HMRC</th>
            </tr>

            <?php if (!empty($employments)): ?>

                <?php foreach ($employments as $employment): ?>

                    <tr>
                        <td><?= esc($employment['employerName']) ?></td>

                        <td>
                            <form class="hmrc-connection" action="/employments-income/retrieve-employment-and-financial-details">
                                <input type="hidden" name="employment_id" value="<?= esc($employment['employmentId']) ?>">
                                <input type="hidden" name="employment_type" value="hmrc">
                                <button class="link" type="submit">Details</button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No employment data retrieved by HMRC</p>

            <?php endif; ?>

            <tr>
                <th class="subheading" colspan="2">Custom Employments Or Pensions Added By User</th>
            </tr>

            <?php if (!empty($custom_employments)): ?>


                <?php foreach ($custom_employments as $custom_employment): ?>
                    <tr>
                        <td><?= esc($custom_employment['employerName']) ?></td>

                        <td>
                            <form class="hmrc-connection" action="/employments-income/retrieve-employment-and-financial-details">
                                <input type="hidden" name="employment_id" value="<?= esc($custom_employment['employmentId']) ?>">
                                <input type="hidden" name="employment_type" value="custom">
                                <button class="link" type="submit">Details</button>
                            </form>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No user-added custom employments found</p>

            <?php endif; ?>

        </tbody>

    </table>

<?php else: ?>

    <p>No employments found.</p>

<?php endif; ?>



<?php if ($before_current_tax_year): ?>
    <p><a href="/employments-income/add-custom-employment">Add Employment</a></p>

<?php else: ?>

    <p>Employment details will usually be shown here, based on information submitted to HMRC by your employer. If an
        employment is missing, it can be added after the end of the tax year to which it relates.</p>

<?php endif; ?>