<?php if (isset($calculation_id) && $calculation_id !== ""): ?>

    <?php if ($calculation_type === "intent-to-finalise" || $calculation_type === "intent-to-amend"): ?>

        <div id="countdown-msg">
            <p>HMRC is preparing your final tax calculation, please wait <span id="countdown" data-start="7">7</span>
                seconds...</p>

        </div>

        <form class="hmrc-connection" action="/individual-calculations/retrieve-calculation" method="GET">
            <input type="hidden" name="calculation_id" value="<?= $calculation_id ?>">
            <input type="hidden" name="calculation_type" value="<?= $calculation_type ?>">

            <button id="countdown-button" disabled=false class="form-button">Check And Approve Your Final Calculation</button>

        </form>

    <?php else: ?>

        <div id="countdown-msg">
            <p>HMRC is preparing your latest tax calculation, please wait <span id="countdown" data-start="7">7</span>
                seconds...</p>

        </div>

        <form class="hmrc-connection" action="/individual-calculations/retrieve-calculation">
            <input type="hidden" name="calculation_id" value="<?= $calculation_id ?>">

            <button class="button" id="countdown-button" disabled=false>View Your Calculation</button>

        </form>

    <?php endif; ?>

<?php else: ?>

    <p><a class="button hmrc-connection" href="/individual-calculations/trigger-calculation">Generate A New Calculation</a>
    </p>


<?php endif; ?>

<br>


<?php $include_countdown_script = true; ?>