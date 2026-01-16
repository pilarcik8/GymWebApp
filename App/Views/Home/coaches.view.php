<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var array|\Traversable $coaches */

$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/coaches.css') ?>">
<!-- Hero -->
<div class="hero-coaches-wrapper">
    <img src="<?= $link->asset('/images/couches-hero.png') ?>" alt="Trener joga" class="hero-img">

    <div class="hero-text-container">
        <h2>NAŠI <span class="hero-text-yellow">TRÉNERI</span> </h2>
        <p>sú tu, aby vám pomohli dosiahnuť <span class="hero-text-yellow">vaše ciele</span></p>
    </div>
</div>
<?php if (!empty($message ?? null)): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<!-- Treneri rendered by loop -->
<?php foreach ($coaches as $coach): ?>
    <div class="container">
        <div class="slashed-rectangle">
            <div class="slashed-rectangle-content">
                <div>
                    <h5 class="coach-name"><?= htmlspecialchars($coach['name']) ?></h5>
                    <?php if (!empty($coach['short'])): ?>
                        <p class="coach-short-info"><?= htmlspecialchars($coach['short']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($coach['desc'])): ?>
                        <p class="coach-desc"><?= nl2br(htmlspecialchars($coach['desc'])) ?></p>
                    <?php endif; ?>
                    <p class="coach-training-price">Cena individuálneho tréningu: 20 €</p>
                </div>
            </div>
            <form method="post" action="<?= $link->url('home.buy_training') ?>">
                <input type="hidden" name="trainer_id" value="<?= (int)$coach['id'] ?>">
                <input type="hidden" name="price" value="20.0">
                <label>
                    Dátum a čas tréningu:
                    <input type="datetime-local" name="start_datetime" required>
                </label>
                <button class="btn btn-primary" type="submit" name="buy_training">Kúpiť</button>
            </form>
        </div>
        <img class="coach-foto-left" src="<?= $link->asset($coach['img']) ?>" alt="trener">
    </div>
<?php endforeach; ?>
