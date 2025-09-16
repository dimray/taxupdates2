<footer>

    <?php

    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';
    ?>

</footer>

<?php if (!empty($include_collect_user_data_script)): ?>
    <script src="/scripts/collect-user-data.js"></script>
<?php endif; ?>

<?php if (!empty($include_countdown_script)): ?>
    <script src="/scripts/countdown.js"></script>
<?php endif; ?>

<?php if (!empty($include_change_tax_year_script)): ?>
    <script src="/scripts/change-tax-year.js"></script>
<?php endif; ?>

<?php if (!empty($include_scroll_to_errors_script)): ?>
    <script src="/scripts/scroll-to-errors.js"></script>
<?php endif; ?>

<?php if (!empty($include_rentaroom_toggle_script)): ?>
    <script src="/scripts/rentaroom-toggle.js"></script>
<?php endif; ?>

<?php if (!empty($include_clients_show_script)): ?>
    <script src="/scripts/clients-show.js"></script>
<?php endif; ?>

<?php if (!empty($include_clients_add_script)): ?>
    <script src="/scripts/clients-add.js"></script>
<?php endif; ?>

<?php if (!empty($include_dialog_script)): ?>
    <script src="/scripts/dialog-polyfill.js"></script>
    <script src="/scripts/dialog.js"></script>
<?php endif; ?>

<?php if (!empty($include_copy_text_script)): ?>
    <script src="/scripts/copy-text.js"></script>
<?php endif; ?>

<?php if (!empty($include_file_upload_script)): ?>
    <script src="/scripts/file-upload.js"></script>
<?php endif; ?>

<?php if (!empty($include_print_script)): ?>
    <script src="/scripts/print.js"></script>
<?php endif; ?>

<!-- always runs as needed for header menu -->
<script src="/scripts/details-summary.js"></script>
<script src="/scripts/mobile-nav.js"></script>

</body>

</html>