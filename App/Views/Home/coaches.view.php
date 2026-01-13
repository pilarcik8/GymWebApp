<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('root');

// TODO: Vymenit data trenerov za data z databazy
// TODO: Uprav model a databazu
$coaches = [
        [
                'name' => 'MAREK',
                'short' => 'SILOVÝ TRÉNING A KONDÍCIA',
                'desc' => 'Marek je certifikovaný tréner so zameraním na silový a funkčný tréning. Pomôže vám vybudovať svaly, zlepšiť výkon a naučí vás správnu techniku cvičenia.',
                'img'  => '/images/coach-1.png',
        ],
        [
                'name' => 'LUCIA',
                'short' => 'FITNESS A ZDRAVÝ ŽIVOTNÝ ŠTÝL',
                'desc' => 'Lucia je energická trénerka, ktorá kombinuje silový tréning s prvkami mobility a jógy. Pomôže vám cítiť sa lepšie, silnejšie a sebavedomejšie každý deň.',
                'img'  => '/images/coach-2.png',
        ],
        [
                'name' => 'MARTIN',
                'short' => 'SILOVÝ TRÉNING A KONDÍCIA',
                'desc' => 'Martin sa zameriava na rozvoj sily, správnu techniku a dlhodobú kondíciu. Jeho tréningy sú dynamické, premyslené a prispôsobené úrovni každého klienta.',
                'img'  => '/images/coach-3.png',
        ],
];
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/coaches.css') ?>">
</head>

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
                    <h5 class="coach-name"><?= $coach['name'] ?></h5>
                    <p class="coach-short-info"><?= $coach['short'] ?></p>
                    <?= $coach['desc'] ?>
                </div>
            </div>
            <button class="btn btn-primary">Rezervuj</button>
        </div>
        <img class="coach-foto-left" src="<?= $link->asset($coach['img']) ?>" alt="trener">
    </div>
<?php endforeach; ?>
