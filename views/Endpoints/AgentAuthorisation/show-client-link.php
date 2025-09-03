<p>This is the authorisation link to pass to your client, who must visit this link and authenticate with
    HMRC to confirm the
    authorisation. The link expires in 21 days.
</p>


<p class="copy-element"><?= esc($invitation_url) ?></p>
<button class="copy-button" type="button">Copy Link</button>

<hr>


<p>This is the Invitation ID. Your client does not need this, but it will help you identify the request
    if you need to check or cancel it.</p>


<p class="copy-element"><?= esc($invitation_id) ?></p>
<button class="copy-button" type="button">Copy Invitation ID</button>

<hr>


<p><a href="/agent-authorisation/list-authorisation-requests">View All Authorisation Requests</a></p>

<p><a href="/clients/show-clients">View Clients</a></p>

<?php $include_copy_text_script = true ?>