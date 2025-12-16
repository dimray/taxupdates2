<?php if (!empty($requests)): ?>

<hr>

<?php foreach ($requests as $request): ?>

<?php
        $query_string = http_build_query(["invitation_id" => esc($request['request_id'])]);
        ?>

<ul>
    <li><b>Status: </b><?= esc($request['status']) ?></li>
    <li><b>Agent Type: </b><?= formatDateTime(esc($request['agent_type'])) ?></li>
    <li><b>Created: </b><?= formatDateTime(esc($request['created'])) ?></li>
    <li><b>Expires: </b><?= formatDateTime(esc($request['expires'])) ?></li>
    <li><b>Request ID: </b><?= esc($request['request_id']) ?></li>

    <?php if (strtolower($request['status']) !== "accepted"): ?>
    <li><b>Client Url: </b>
        <span class="copy-element"><?= esc($request['client_url']) ?></span>
    </li>
    <button class="copy-button">Copy Client Url</button>

    <br>
    <p><a href="/agent-authorisation/confirm-cancel-invitation?<?= $query_string ?>">Cancel This Invitation</a></p>
    <?php endif; ?>
</ul>

<hr>

<?php endforeach; ?>



<?php else: ?>

<p>There are no requests from the last 30 days to show. To request an authorisation, add the client to
    your Client List and select them on the Clients page.</p>

<?php endif; ?>


<p><a href="/clients/show-clients">Clients</a></p>



<?php $include_copy_text_script = true; ?>