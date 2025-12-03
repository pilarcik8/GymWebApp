<?php
/** @var string|null $message */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/login-register.css') ?>">
</head>

<div class="login-hero">
    <div class="row">
        <div class="card card-signin my-5">
            <h1>REGISTRÁCIA</h1>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <form class="form-signin" method="post" action="<?= $link->url("register") ?>">
                <div class="form-label-group mb-3">
                    <input name="first_name" type="text" id="first_name" class="form-control" placeholder="Meno" required autofocus>
                </div>

                <div class="form-label-group mb-3">
                    <input name="last_name" type="text" id="last_name" class="form-control" placeholder="Priezvisko" required autofocus>
                </div>

                <div class="form-label-group mb-3">
                    <input name="email" type="email" id="email" class="form-control" placeholder="Email" required autofocus>
                </div>

                <div class="form-label-group mb-3">
                    <input name="password" type="password" id="password" class="form-control" placeholder="Heslo" required>
                </div>

                <div class="form-label-group mb-3">
                    <input name="password2" type="password" id="password2" class="form-control" placeholder="Znova Heslo" required>
                </div>
                <button class="btn btn-primary button-green" type="submit" name="register">SIGN IN</button>
            </form>
        </div>
    </div>
</div>

