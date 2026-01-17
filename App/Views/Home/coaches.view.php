<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var array|\Traversable $coaches */

$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/home/coaches.css') ?>">
<script src="<?= $link->asset('/js/coaches.js') ?>"></script>

<!-- Hero -->
<div class="hero-coaches-wrapper">
    <img src="<?= $link->asset('/images/couches-hero.png') ?>" alt="Trener joga" class="hero-img">

    <div class="hero-text-container">
        <h2>NAŠI <span class="hero-text-yellow">TRÉNERI</span> </h2>
        <h5>sú tu, aby vám pomohli dosiahnuť <span class="hero-text-yellow">vaše ciele</span></h5>
    </div>
</div>
<?php if (!empty($message ?? null)): ?>
    <div class="alert alert-info">
        <?= $message ?>
    </div>
<?php endif; ?>

<?php if (empty($coaches)): ?>
    <div class="alert alert-warning text-center mt-4">
        Momentálne nemáme žiadnych dostupných trénerov.
    </div>
<?php endif; ?>
<?php foreach ($coaches as $index => $coach): ?>
    <div class="coach-card">
        <div class="coach-card-main">
            <div class="coach-text-block">
                <h5 class="coach-name"><?= $coach['name'] ?></h5>
                <?php if (!empty($coach['short'])): ?>
                    <p class="coach-short-info"><?= $coach['short'] ?></p>
                <?php endif; ?>
                <?php if (!empty($coach['desc'])): ?>
                    <p class="coach-desc"><?= $coach['desc'] ?></p>
                <?php endif; ?>
                <p class="coach-training-price">
                    Cena individuálneho tréningu:
                    <?= number_format((float)$coach['price'], 2, ',', ' ') ?> €
                </p>
            </div>

            <div class="coach-photo-block">
                <img class="coach-photo" src="<?= $link->asset($coach['img']) ?>" alt="trener">
            </div>
        </div>

        <div class="coach-card-actions">
            <form method="post" action="<?= $link->url('home.buy_training') ?>" class="trainer-booking-form" data-coach-id="<?= (int)$coach['id'] ?>">
                <input type="hidden" name="trainer_id" value="<?= (int)$coach['id'] ?>">
                <input type="hidden" name="price" value="<?= (string)$coach['price'] ?>">

                <div class="trainer-booking-initial">
                    <button type="button" class="btn btn-primary trainer-booking-start">Kúpiť</button>
                </div>

                <div class="trainer-booking-details" style="display: none;">
                    <label>
                        Dátum a čas tréningu:
                        <input type="datetime-local" name="start_datetime" required>
                    </label>
                    <button class="btn btn-primary" type="submit" name="buy_training">Potvrdiť kúpu</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

