<form class="generic-form hmrc-connection" action="/individual-losses/update-brought-forward-loss" method="GET">

    <p>Loss ID: <?= $loss_id ?></p>

    <input type="hidden" name="loss_id" value="<?= esc($loss_id) ?>">
    <input type="hidden" name="loss_year" value="<?= esc($loss_year) ?>">

    <div>
        <div class="form-input">
            <label for="loss_amount">Loss Amount:</label>
            <input type="text" name="loss_amount" id="loss_amount" value="<?= esc($loss_amount) ?>">
        </div>
    </div>


    <button type="submit" class="form-button">Update</button>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

</form>

<br>



<p><a class="hmrc-connection" href="/individual-losses/list-brought-forward-losses?<?= $query_string ?>">Cancel</a></p>