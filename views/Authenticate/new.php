<?php if ($user_role === "individual"): ?>

<p>Please click on the link below to authenticate with HMRC.</p>

<p>You will need to do this the first time you login and occasionally when HMRC require re-authentication.</p>

<p>If you see this page repeatedly and are not able to access your tax information please ensure that you are using the
    correct
    username and password to authenticate with HMRC, and the details you have registered on this site are correct
    (particularly your National Insurance number).</p>

<p><a href="/authenticate/go-to-hmrc">Go To HMRC</a></p>

<?php endif; ?>

<?php if ($user_role === "agent" && $client_name): ?>

<p>If you are already authorised to act for <?= $client_name ?>, you have reached this page because HMRC are
    requiring you to authorise or re-authorise this site, or to update the scope of your authentication token, before
    you can view your client's data here.</p>

<p> Click on the 'Go To HMRC'
    link below. HMRC may
    show
    a screen asking for further information, in which case you only need to enter your client's National
    Insurance number and click 'Submit'.</p>

<p><a href="/authenticate/go-to-hmrc">Go To HMRC</a></p>

<hr>

<p>If you are not authorised to act for <?= $client_name ?>, request authorisation <a
        href="/agent-authorisation/request-new-authorisation">here</a>.</p>

<p>If you are unsure whether you are authorised to act for <?= $client_name ?>, you can check the status of the client
    relationship <a href="/agent-authorisation/request-status-of-relationship">here</a>.</p>

<?php elseif ($user_role === "agent"): ?>

<p>Please click on the link below to authenticate with HMRC.</p>
<p>You will need to do this the first time you login and occasionally when HMRC require re-authentication.</p>

<p><a href="/authenticate/go-to-hmrc">Go To HMRC</a></p>

<?php endif; ?>