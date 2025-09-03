<form action="/agent-authorisation/cancel-invitation">

    <input type="hidden" name="invitation_id" value="<?= $invitation_id ?>">

    <p>Cancel the invitation with ID <?= $invitation_id ?>.</p>

    <button class="confirm-delete" type="submit">Cancel Invitation</button>
</form>

<p><a href="/agent-authorisation/list-authorisation-requests">Authorisation Requests</a></p>