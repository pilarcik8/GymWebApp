<?php
/** @var string $contentHTML */
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bronze Gym - Template</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>
<nav class="navbar navbar-expand-lg bg-light border-bottom py-2" id="navbar">
    <div class="container-fluid d-flex justify-content-between">

        <!-- LEFT: Brand + Nav (now swapped to left, visually right → left) -->
        <div class="d-flex align-items-center gap-3 left-group">

            <!-- Navigation links -->
            <div class="navbar-nav d-flex flex-row gap-3">
                <a href="/" class="d-flex align-items-center text-decoration-none fw-bold text-dark gap-2">
                    <svg class="brand-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 12h2v2H2zM20 12h2v2h-2z" fill="#000"/>
                        <rect x="4" y="9" width="16" height="6" rx="1" fill="#000"/>
                        <rect x="0" y="10" width="4" height="4" rx="0.5" fill="#000"/>
                        <rect x="20" y="10" width="4" height="4" rx="0.5" fill="#000"/>
                    </svg>
                    BRONZE GYM
                </a>
                <a class="nav-link" href="/cennik">Tréneri</a>
                <a class="nav-link" href="/treningy">Pernamentky</a>
                <a class="nav-link" href="/o-nas">Skupinové hodiny</a>
                <a class="nav-link" href="/kontakt">Galéria</a>
            </div>

        </div>

        <!-- RIGHT: Log in / Sign up -->
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary">Log in</button>
            <button class="btn btn-primary">Sign up</button>
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



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
