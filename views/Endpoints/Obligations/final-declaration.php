<?php if (!empty($obligations)): ?>



    <div class="regular-table">
        <table class="desktop-view">

            <thead>
                <tr>
                    <th>Period Start Date</th>
                    <th>Period End Date</th>
                    <th>Due Date</th>
                    <th>Received</th>
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
                                <form class="hmrc-connection" action="/individual-calculations/retrieve-final-calculation"
                                    method="GET">
                                    <button class="link" type="submit">View Final Declaration</button>
                                </form>
                            </td>
                        <?php endif; ?>

                        <?php if ($obligation['status'] === "fulfilled" && $before_amendment_deadline): ?>
                            <td>
                                <form action="/individual-calculations/prepare-final-declaration" method="GET">
                                    <input type="hidden" name="calculation_type" value="intent-to-amend">
                                    <button class="link" type="submit">Amend Final Declaration</button>
                                </form>
                            </td>

                        <?php endif; ?>

                        <?php if ($obligation['status'] === "open" && $before_current_tax_year): ?>
                            <td>
                                <form action="/individual-calculations/prepare-final-declaration" method="GET">
                                    <input type="hidden" name="calculation_type" value="intent-to-finalise">
                                    <button class="link" type="submit">Prepare Final Declaration</button>
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
                        <div class="label">Received</div>
                        <div class="value"><?= esc(formatDate($obligation["receivedDate"] ?? '')) ?></div>
                    </div>

                    <div class="data-row">
                        <div class="label">Status</div>
                        <div class="value"><?= esc(ucfirst($obligation["status"])) ?></div>
                    </div>
                </div>


                <?php if ($obligation['status'] === "fulfilled"): ?>
                    <form class="hmrc-connection" action="/individual-calculations/retrieve-final-calculation" method="GET">
                        <button class="link" type="submit">View Final Declaration</button>
                    </form>

                    <hr>

                <?php endif; ?>

                <?php if ($obligation['status'] === "fulfilled" && $before_amendment_deadline): ?>
                    <form action="/individual-calculations/prepare-final-declaration" method="GET">
                        <input type="hidden" name="calculation_type" value="intent-to-amend">
                        <button class="link" type="submit">Amend Final Declaration</button>
                    </form>

                    <hr>

                <?php endif; ?>

                <?php if ($obligation['status'] === "open" && $before_current_tax_year): ?>
                    <form action="/individual-calculations/prepare-final-declaration" method="GET">
                        <input type="hidden" name="calculation_type" value="intent-to-finalise">
                        <button class="link" type="submit">Prepare Final Declaration</button>
                    </form>

                    <hr>

                <?php endif; ?>

            <?php endforeach; ?>
        </div>



    </div>




<?php else: ?>

    <p>No Final Declaration Obligation found for this year.</p>

<?php endif; ?>

<?php if (!$before_current_tax_year): ?>

    <p>The Final Declaration cannot be submitted until after the tax year has ended.</p>

<?php endif; ?>