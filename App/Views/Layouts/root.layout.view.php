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
    <!-- Boostrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Boostrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Font Awesome Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= $link->asset('/css/root.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>
<body>

<!--navbar-->
<nav class="navbar navbar-expand-xxl bg-light py-2" id="navbar"> <!-- tu rozhodujem kedi sa zmeni navbar -->
    <div class="container-fluid d-flex justify-content-between">

        <a id="home-icon-name" href="<?= $link->url("home.index") ?>"
           class="navbar-brand d-flex align-items-center text-dark fw-bold gap-2">
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

            <div class="navbar-nav ms-auto gap-lg-3">

                <a class="nav-link" href="<?= $link->url("home.coaches") ?>">Tréneri</a>
                <a class="nav-link" href="<?= $link->url('home.permits') ?>">Pernamentky</a>
                <a class="nav-link" href="<?= $link->url('home.group_classes') ?>">Skupinové hodiny</a>
                <a class="nav-link" href="<?= $link->url('home.gallery') ?>">Galéria</a>

                <hr class="d-lg-none">

                <!-- Login / Sign in (mobil verzia) -->
                <a id="login-signin-container" href="<?= $link->url("auth.register") ?>" class="nav-link">
                    <i class="fa-solid fa-user"></i>
                    Sign In
                </a>

                <a href="<?= $link->url("auth.login") ?>" class="nav-link">
                    Log In
                </a>

            </div>

        </div>
    </div>
</nav>

<!-- CONTENT -->
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
