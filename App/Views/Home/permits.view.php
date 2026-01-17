<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var \Framework\Auth\AppUser $user */
/** @var array|\Traversable $permits */
/** @var string|null $message */

$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/home/permits.css') ?>">
<script src="<?= $link->asset('js/permits.js') ?>"></script>

<?php if (!empty($message)): ?>
    <div id="modal-overlay" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="modal-window" role="document">
            <div class="modal-body">
                <p><?= $message ?></p>
                <div class="text-center modal-close">
                    <button id="modal-close" class="btn btn-warning">OK</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="permits-hero">
    <h1>Cenník</h1>
    <h2>Pernamentky</h2>

    <div class="container">

        <?php foreach ($permits as $p):
            $title = $p['title'];
            $days = (int)$p['days'];
            $price = (float)$p['price'];
            $priceFormatted = number_format($price, 2, ',', '');
            $pricePerDay = number_format($price / max(1, $days), 2, ',', '');
            ?>
            <div class="rectengle">
                <div class="rect-header">
                    <h3><?= $title ?></h3>
                </div>

                <div class="rect-body">
                    <p>Dĺžka trvania: <span><?= $days ?> dní</span></p>
                    <p>Celková cena: <span><?= $priceFormatted ?> €</span></p>
                    <p>Cena za deň: <span><?= $pricePerDay ?> € / deň</span></p>
                </div>
                <form method="post" action="<?= $link->url('home.buy_permit') ?>">
                    <input type="hidden" name="user_id" value="<?= $user->isLoggedIn() ? $user->getId() : '' ?>">
                    <input type="hidden" name="days" value="<?= $days ?>">
                    <input type="hidden" name="price" value="<?= $price ?>">
                    <button class="button-green btn btn-primary" type="submit" name="buy_permit">Kúpiť</button>
                </form>
            </div>
        <?php endforeach; ?>

    </div>
</div>
