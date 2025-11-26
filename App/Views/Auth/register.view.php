<?php
/** @var string|null $message */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/login-register.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>

<div class="login-hero">
    <div class="row">
        <div class="card card-signin my-5">
            <h1>REGISTRÁCIA</h1>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <form class="form-signin" method="post" action="<?= $link->url("login") ?>">
                <div class="form-label-group mb-3">
                    <input name="name" type="text" id="name" class="form-control" placeholder="Meno" required autofocus>
                </div>

                <div class="form-label-group mb-3">
                    <input name="last-name" type="text" id="last-name" class="form-control" placeholder="Priezvisko" required autofocus>
                </div>

                <div class="form-label-group mb-3">
                    <input name="email" type="email" id="email" class="form-control" placeholder="Email" required autofocus>
                </div>

                <div class="form-label-group mb-3">
                    <input name="password" type="password" id="password" class="form-control" placeholder="Heslo" required>
                </div>

                <div class="form-label-group mb-3">
                    <input name="password-confirm" type="password" id="password_confirm" class="form-control" placeholder="Znova Heslo" required>
                </div>
                <button class="btn btn-primary button-green" type="submit" name="submit">SIGN IN</button>
            </form>
        </div>
    </div>
</div>

