<footer>



    <?php /*
    <div class="footer-links">

        <?php if (isset($_SESSION['nino']) || isset($_SESSION['client']['nino'])): ?>
    <div class="tax-calculation">
        <a href="/individual-calculations/trigger-calculation">Generate A New Tax Calculation</a>
    </div>
    <?php endif; ?>

    </div>

    */ ?>

    <div class="footer-bg">

        <div id="light-dark" class="light-dark">
            <?php include ROOT_PATH . "/public/icons/light-dark.svg"; ?>
        </div>

        <p>
            <a href="/admin/contact-form">Contact</a> |
            <a href="/admin/terms-and-conditions">Terms</a> |
            <a href="/admin/privacy-policy">Privacy Policy</a>


        </p>


        <p>Useful Links:
            <a href="https://www.gov.uk/log-in-register-hmrc-online-services">HMRC Online Services</a>
        </p>

        <div class="copyright">
            <span>&copy; <?php echo date("Y"); ?> TaxUpdates. All Rights Reserved</span>
        </div>

    </div>

</footer>

<div id="loader" class="loader-hidden" role="status" aria-live="polite" aria-label="Connecting to HMRC">
    <!-- <div class="spinner"></div> -->
    <div class="loader-text">Connecting to
        <span class="hmrc-bounce">
            <span>H</span>
            <span>M</span>
            <span>R</span>
            <span>C</span>
        </span>
    </div>
</div>

<?php
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
?>

<?php if (!empty($include_collect_user_data_script)): ?>
    <script src=" /scripts/collect-user-data.js"></script>
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

<?php if (!empty($include_zero_adjustments_script)): ?>
    <script src="/scripts/zero-adjustments.js"></script>
<?php endif; ?>

<?php if (!empty($include_details_summary_script)): ?>
    <script src="/scripts/details-summary.js"></script>
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

<?php if (!empty($include_add_another_script)): ?>
    <script src="/scripts/add-another.js"></script>
<?php endif; ?>

<?php if (!empty($include_message_length_script)): ?>
    <script src="/scripts/message-length.js"></script>
<?php endif; ?>

<?php if (!empty($include_disable_form_send_button_script)): ?>
    <script src="/scripts/disable-form-send-button.js"></script>
<?php endif; ?>

<!-- always runs as needed for header menu -->
<script src="/scripts/mobile-nav.js"></script>
<script src="/scripts/light-dark.js"></script>
<script src="/scripts/loader.js"></script>

</body>

</html>