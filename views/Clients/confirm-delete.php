<p>Confirm you wish to delete <?= $client_name ?></p>


<form action="/clients/delete" method="POST">
    <input type="hidden" name="client_id" value="<?= $client_id ?>">
    <button class="confirm-delete" type="submit">Delete</button>
</form>

<br>

<a href="/clients/show-clients">Cancel</a>