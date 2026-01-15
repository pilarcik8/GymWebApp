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
                </div>
            </div>
            <button class="btn btn-primary">Rezervuj</button>
        </div>
        <img class="coach-foto-left" src="<?= $link->asset($coach['img']) ?>" alt="trener">
    </div>
<?php endforeach; ?>
