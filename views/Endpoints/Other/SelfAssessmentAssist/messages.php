<?php if (!empty($messages)): ?>

<div class="list">
    <ul>
        <li>NI Number: <?= $nino ?></li>
        <li>Tax Year: <?= $tax_year ?></li>
    </ul>
</div>


<?php foreach ($messages as $message): ?>

<h2><?= $message['title'] ?></h2>

<p><?= $message['body'] ?></p>



<?php if (!empty($message['links'])): ?>
<?php foreach ($message['links']  as $link): ?>

<p><a href="<?= (strpos($link['url'], 'http') === 0 ? $link['url'] : 'https://' . esc($link['url'])) ?>" target="_blank"
        rel="noopener">
        <?= $link['title'] ?>
    </a></p>

<?php endforeach; ?>
<?php endif; ?>

<?php
        $path = (strpos($message['path'], 'http') === 0)
            ? $message['path']
            : 'https://' . $message['path'];
        ?>
<p>
    <a href="<?= esc($path) ?>" target="_blank" rel="noopener">
        <?= esc($message['action']) ?>
    </a>
</p>

<hr>

<?php endforeach; ?>

<p><a class="different-link" href="/self-assessment-assist/acknowledge-report?<?= $query_string ?>">Acknowledge
        Report</a><span class="small">This sends an acknowledgement to HMRC that the messages have been read</span></p>

<?php else: ?>

<p>No messages to display</p>

<?php endif; ?>

<p><a href="/individual-calculations/retrieve-calculation?calculation_id=<?= $calculation_id ?>">Go back to
        the same tax calculation</a></p>