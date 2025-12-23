<?php
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */

?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/admin.css') ?>">
</head>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4>Your Group Trainings</h4>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
        </div>
    </div>
</div>