<?php

/** @var \Framework\Support\LinkGenerator $link */
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymApp | Domov</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once __DIR__ . "/../Layouts/header.view.php"; ?>

<main class="container py-5">
    <h1 class="text-center mb-4">Vitajte v našej posilňovni!</h1>
    <p class="lead text-center">Spravujte tréningy, permanentky a rezervácie jednoducho online.</p>
    <div class="text-center mt-4">
        <a href="/login" class="btn btn-primary me-2">Prihlásiť sa</a>
        <a href="/register" class="btn btn-outline-secondary">Registrovať</a>
    </div>
</main>

<?php include_once __DIR__ . "/../Layouts/footer.view.php"; ?>

</body>
</html>
