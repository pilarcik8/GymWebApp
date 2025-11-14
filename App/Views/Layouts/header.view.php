<!doctype html>
<html lang="sk">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bronze Gym - Header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .brand-icon {
            width: 28px;
            height: 28px;
        }
        /* Reverse order on the left side now */
        .left-group {
            flex-direction: row-reverse;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light border-bottom py-2">
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
                <a class="nav-link" href="/cennik">Cenník</a>
                <a class="nav-link" href="/treningy">Tréningy</a>
                <a class="nav-link" href="/o-nas">O nás</a>
                <a class="nav-link" href="/kontakt">Kontakt</a>
            </div>

        </div>

        <!-- RIGHT: Log in / Sign up -->
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary">Log in</button>
            <button class="btn btn-primary">Sign up</button>
        </div>

    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
