<?php
/** @var string|null $message */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var string|null $email */


$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/log-reg.css') ?>">

<div class="hero">
    <div>
        <div>
            <h1>PRIHLÁSENIE</h1>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <form class="form-signin" method="post" action="<?= $link->url("login") ?>">
                <div class="form-label-group mb-3">
                    <input name="email" type="email" id="email" class="form-control" placeholder="Email" required autofocus value="<?= $email ?? '' ?>">
                </div>

                <div class="form-label-group mb-3">
                    <input name="password" type="password" id="password" class="form-control" placeholder="Heslo" required>
                </div>
                <button class="btn btn-primary button-green" type="submit" name="submit">LOG IN</button>
            </form>
        </div>
    </div>
</div>

