<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="/icons/favicon.svg">
    <title>TaxUpdates2</title>
    <meta name="description"
        content="One-stop site for Making Tax Digital for Income Tax for both landlords and sole traders">
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/light-dark.css">
    <link rel="stylesheet" href="/styles/base.css">
    <link rel="stylesheet" href="/styles/grid.css">
    <link rel="stylesheet" href="/styles/navigation.css">
    <link rel="stylesheet" href="/styles/buttons.css">
    <link rel="stylesheet" href="/styles/icons.css">
    <link rel="stylesheet" href="/styles/dialog-polyfill.css">
    <link rel="stylesheet" href="/styles/form.css">
    <link rel="stylesheet" href="/styles/list.css">
    <link rel="stylesheet" href="/styles/table.css">
    <link rel="stylesheet" href="/styles/details-summary.css">
    <link rel="stylesheet" href="/styles/clients.css">
    <link rel="stylesheet" href="/styles/print.css">


</head>

<body>

    <header class="primary-header">

        <p class="logo"><a href="/">TaxUpdates</a></p>

        <button class="menu-toggle" aria-controls="primary-navigation" aria-expanded="false" type="button">
            <!-- for screen readers -->
            <span class="sr-only">Menu</span>
            <div class="hamburger" aria-hidden="true">
                <div></div>
            </div>
        </button>

        <nav>

            <?php
            $current_path = $_SERVER['REQUEST_URI'];

            $agent_type = $_SESSION['agent_type'] ?? null;
            $user_role = $_SESSION['user_role'] ?? null;
            $authenticated_agent = ($user_role === 'agent' && isset($_SESSION['access_token']));
            $main_agent = ($authenticated_agent && $agent_type === "main");
            $supporting_agent = ($authenticated_agent && $agent_type === "supporting");

            // for giving active class to 'Year End' top level nav when one of its sub-items is active
            $year_end_paths = [
                '/business-details/list-all-businesses?year-end=true',
                '/other-income',
                '/capital-gains',
                '/tax-reliefs',
                '/final-declaration'
            ];

            $is_year_end_active = false;
            foreach ($year_end_paths as $path) {
                if (str_starts_with($current_path, $path)) {
                    $is_year_end_active = true;
                    break;
                }
            }

            ?>

            <ul data-state="closed" class="primary-navigation" id="primary-navigation">
                <?php if (isset($_SESSION['user_id'])): ?>

                <?php if ($_SESSION['user_role'] === 'individual' || isset($_SESSION['client']['nino'])):  ?>
                <li><a class="navLink topLink 
                        <?= str_starts_with($current_path, '/business-details/list-all-businesses?updates=true') ? 'active' : '' ?>"
                        href="/business-details/list-all-businesses?updates=true">Updates</a></li>
                <li>
                    <a class="navLink topLink <?= str_starts_with($current_path, '/year-end') ? 'active' : '' ?>"
                        href="/year-end/index">Year End</a>
                </li>
                <?php endif; ?>

                <?php if ($authenticated_agent): ?>
                <li><a class="navLink topLink <?= str_starts_with($current_path, '/clients/show-clients') ? 'active' : '' ?>"
                        href="/clients/show-clients">Clients</a></li>
                <?php endif; ?>

                <?php if ($authenticated_agent && !isset($_SESSION['client']['nino'])): ?>
                <li><a class="navLink topLink <?= str_starts_with($current_path, '/firm/show-firm') ? 'active' : '' ?>"
                        href="/firm/show-firm">Firm</a></li>
                <?php endif; ?>

                <?php if (!isset($_SESSION['client']['nino'])): ?>
                <li><a class="navLink topLink <?= str_starts_with($current_path, '/profile/show-profile') ? 'active' : '' ?>"
                        href="/profile/show-profile">Profile</a></li>
                <?php endif; ?>

                <li><a class="navLink topLink" href="/logout">Log out</a></li>

                <?php else: ?>
                <li><a class="navLink topLink" href="/login">Login</a></li>
                <li><a class="navLink topLink" href="/register">Register</a></li>

                <?php endif; ?>


            </ul>


        </nav>

    </header>