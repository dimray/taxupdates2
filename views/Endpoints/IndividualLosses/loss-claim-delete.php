<?php displayArrayAsList($claim_details); ?>

<p>Are you sure you want to delete this loss claim?</p>

<form class="hmrc-connection" action="/individual-losses/delete-loss-claim" method="POST">

    <input type="hidden" name="claim_id" value="<?= esc($claim_id) ?>">

    <button type="submit" class="confirm-delete">Delete</button>

</form>


<p><a class="hmrc-connection" href="/individual-losses/list-loss-claims">Cancel</a></p>