<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaxUpdates2</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/base.css">
    <link rel="stylesheet" href="/styles/icons.css">
    <link rel="stylesheet" href="/styles/dialog-polyfill.css">
    <link rel="stylesheet" href="/styles/form.css">
    <link rel="stylesheet" href="/styles/list.css">
    <link rel="stylesheet" href="/styles/table.css">
    <link rel="stylesheet" href="/styles/clients.css">
    <link rel="stylesheet" href="/styles/print.css">

</head>

<body>

    <header>
        <nav>
            <a href="/">Home</a>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <span><a href="/register">Register</a> or <a href="/login">Login</a></span>
            <?php else: ?>
                <a href="/logout">Logout</a>
                <a href="/profile/show-profile"> <?= file_get_contents(ROOT_PATH . "public/icons/profile.svg") ?></a>
            <?php endif; ?>

        </nav>
    </header>