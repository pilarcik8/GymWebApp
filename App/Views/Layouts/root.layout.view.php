<?php
/** @var string $contentHTML */
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
?>

<!doctype html>
<html lang="sk">
<head>
    <title><?= App\Configuration::APP_NAME ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bronze Gym - Template</title>
    <!-- Boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= $link->asset('/css/root.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>
<body>

<!--navbar-->
<nav class="navbar navbar-expand-lg bg-light py-2" id="navbar">
    <div class="container-fluid d-flex justify-content-between">

        <div class="d-flex align-items-center gap-3 left-group">

            <div class="navbar-nav d-flex flex-row gap-3">
                <a id = "home-icon-name" href="<?= $link->url("home.index") ?>" class="d-flex align-items-center text-decoration-none fw-bold text-dark gap-2">
                    <!-- ikona dumbell-->
                    <i class="fa-solid fa-dumbbell fa-lg" aria-hidden="true"></i>
                    BRONZE GYM
                </a>
                <a class="nav-link" href="<?= $link->url("home.coaches") ?>">Tréneri</a>
                <a class="nav-link" href="<?= $link->url('home.permits') ?>">Pernamentky</a>
                <a class="nav-link" href="<?= $link->url('home.group-classes') ?>">Skupinové hodiny</a>
                <a class="nav-link" href="<?= $link->url('home.gallery') ?>">Galéria</a>
            </div>

        </div>

        <!-- Sign In / Log In  -->
        <div class="d-flex gap-2 align-items-stretch" id="login-signin-container">

            <a href="/" class="d-flex align-items-center gap-1 text-dark text-decoration-none p-2" style="min-width:90px; justify-content:center;">
                <i id="user-icon" class="fa-solid fa-user"></i>
                <span class="nav-link">Sign In</span>
            </a>

            <a href="<?= $link->url("auth.login") ?>" class="d-flex align-items-center gap-1 text-dark text-decoration-none p-2" style="min-width:90px; justify-content:center;">
                <span class="nav-link">Log In</span>
            </a>

        </div>

    </div>
</nav>

<div class="container-fluid mt-3">
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
                <a href="tel:+421900000000" class="text-dark text-decoration-underline">+421 900 000 000</a>
            </div>
        </div>

        <!-- EMAIL -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-envelope-fill fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Napíš nám</div>
                <a href="mailto:info@bronzegym.sk" class="text-dark text-decoration-underline">info@bronzegym.sk</a>
            </div>
        </div>

        <!-- FACEBOOK -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-facebook fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Facebook</div>
                <a href="#" class="text-dark text-decoration-underline">/bronzegym</a>
            </div>
        </div>

        <!-- INSTAGRAM -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-instagram fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">Instagram</div>
                <a href="#" class="text-dark text-decoration-underline">@bronzegym</a>
            </div>
        </div>

        <!-- YOUTUBE -->
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-youtube fs-3"></i>
            <div class="text-start">
                <div class="fw-bold">YouTube</div>
                <a href="#" class="text-dark text-decoration-underline">Bronze Gym</a>
            </div>
        </div>

    </div>

    <div class="text-center mt-4 small">
        © 2025 Bronze Gym — All rights reserved.
    </div>
</div>
</body>
</html>
