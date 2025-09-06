<?php require ROOT_PATH . 'views/shared/header.php'; ?>

<main>

    <?php if (isset($_SESSION['tax_year']) && (!isset($hide_tax_year) || !$hide_tax_year)): ?>
        <?php include ROOT_PATH . "views/shared/tax-year.php"; ?>
    <?php endif; ?>

    <?php if (!empty($heading)): ?>
        <h1><?= esc($heading) ?></h1>
    <?php endif; ?>

    <?php if (!empty($_SESSION['client']['name'])): ?>
        <p>Client Name: <?= $_SESSION['client']['name'] ?></p>
    <?php endif; ?>

    <?php displayFlashMessages(); ?>

    <?php if (!empty($business_details)): ?>
        <?php displayArrayAsList($business_details); ?>
    <?php endif; ?>

    <?= $content ?>

</main>

<?php require ROOT_PATH . 'views/shared/footer.php'; ?>