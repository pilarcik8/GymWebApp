<?php
/** @var string $contentHTML */
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Auth\AppUser $user */

?>

<!doctype html>
<html lang="sk">
<head>
    <title><?= App\Configuration::APP_NAME ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bronze Gym - Template</title>
    <!-- Boostrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Font Awesome Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $link->asset('favicons/192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $link->asset('favicons/32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $link->asset('favicons/16x16.png') ?>">
    <link rel="manifest" href="<?= $link->asset('favicons/site.webmanifest') ?>">
    <link rel="shortcut icon" href="<?= $link->asset('favicons/16x16.ico') ?>">

    <!-- CSS, JS -->
    <link rel="stylesheet" href="<?= $link->asset('/css/root.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>
<body>

<!--navbar-->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid d-flex justify-content-between">

        <a id="home-icon-name" href="<?= $link->url("home.index") ?>"
           class="navbar-brand d-flex align-items-center text-dark fw-bold gap-1">
            <i class="fa-solid fa-dumbbell fa-lg"></i>
            BRONZE GYM
        </a>

        <!-- Toggler pre mobil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mobileNav"
                aria-controls="mobileNav" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="mobileNav">

            <div class="navbar-nav ms-auto">

                <a class="nav-link" href="<?= $link->url("home.coaches") ?>">Tréneri</a>
                <a class="nav-link" href="<?= $link->url('home.permits') ?>">Pernamentky</a>
                <a class="nav-link" href="<?= $link->url('home.group_classes') ?>">Skupinové hodiny</a>
                <a class="nav-link" href="<?= $link->url('home.gallery') ?>">Galéria</a>

                <hr class="d-lg-none">

                <?php if ($user->isLoggedIn()) { ?>

                    <!-- DROPDOWN PRIHLÁSENÉHO POUŽÍVATEĽA -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold d-flex align-items-center gap-1"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="fa-solid fa-user"></i>
                            <?= $user->getName() ?>
                        </a>

                        <ul class="dropdown-menu">

                            <!-- TODO: Dalsie linky tu -->

                            <li>
                                <a class="dropdown-item text-danger" href="<?= $link->url("auth.logout") ?>">
                                    <i class="fa-solid fa-right-from-bracket"></i> Log Out
                                </a>
                            </li>

                        </ul>
                    </div>

                <?php } else { ?>

                    <!-- NEPRIHLÁSENÝ POUŽÍVATEĽ -->
                    <a id="login-signin-container" href="<?= $link->url("auth.register") ?>" class="nav-link">
                        <i class="fa-solid fa-user"></i>
                        Sign In
                    </a>

                    <a href="<?= $link->url("auth.login") ?>" class="nav-link">
                        Log In
                    </a>

                <?php } ?>

            </div>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div>
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>

<!-- FOOTER -->
<div class="mt-auto bg-light text-dark text-center text-lg-start border-top py-4" id="footer">

    <div class="container d-flex justify-content-center gap-5 flex-wrap">

        <!-- TELEFÓN -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-telephone-fill fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Zavolaj nám</div>
                <a href="/" class="text-dark text-decoration-underline">+421 900 000 000</a>
            </div>
        </div>

        <!-- EMAIL -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-envelope-fill fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Napíš nám</div>
                <a href="/" class="text-dark text-decoration-underline">info@bronzegym.sk</a>
            </div>
        </div>

        <!-- FACEBOOK -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-facebook fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Facebook</div>
                <a href="/" class="text-dark text-decoration-underline">/bronzegym</a>
            </div>
        </div>

        <!-- INSTAGRAM -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-instagram fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Instagram</div>
                <a href="/" class="text-dark text-decoration-underline">@bronzegym</a>
            </div>
        </div>

        <!-- YOUTUBE -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-youtube fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">YouTube</div>
                <a href="/" class="text-dark text-decoration-underline">Bronze Gym</a>
            </div>
        </div>

    </div>

    <div class="text-center mt-4 small">
        © <?php echo date("Y"); ?> Bronze Gym — All rights reserved.
    </div>
</div>
</body>
</html>
