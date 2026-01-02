<p>If a loss has been used as part of a successful Final Declaration, it is not possible to delete it and an error will
    be returned. In these circumstances, HMRC instead
    advise amending the loss amount to zero.</p>

<hr>

<p>Loss ID: <?= $loss_id ?></p>

<p>Are you sure you want to delete this loss?</p>



<form class="hmrc-connection" action="/individual-losses/delete-brought-forward-loss" method="POST">

    <input type="hidden" name="loss_id" value="<?= esc($loss_id) ?>">
    <input type="hidden" name="loss_year" value="<?= esc($loss_year) ?>">

    <button type="submit" class="confirm-delete">Delete</button>

</form>


<p><a class="hmrc-connection" href="/individual-losses/list-brought-forward-losses?<?= $query_string ?>">Cancel</a></p>