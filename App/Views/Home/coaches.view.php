<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
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

<!-- Treneri -->
<section class="row-section">
    <div>
        <button class="btn btn-primary">Rezervuj si Trénera <span class="button-text-yellow">MAREK</span></button>
    </div>
    <div class="slashed-rectangle-right">
        <div class="slashed-rectangle-content">
            <div>
            <h5 class="couach-name">MAREK</h5>
            <p class="couach-short-info">SILOVÝ TRÉNING A KONDÍCIA</p>
            Marek je certifikovaný tréner so zameraním na silový a funkčný tréning. Pomôže vám vybudovať svaly, zlepšiť výkon a naučí vás správnu techniku cvičenia.
            </div>
        </div>
    </div>

    <img src="<?= $link->asset('/images/coach-1.png') ?>" alt="trener1" class="section-image-right">
</section>

<section class="row-section">
    <img src="<?= $link->asset('/images/coach-2.png') ?>" alt="trener2" class="section-image-left">

    <div class="slashed-rectangle-left">
        <div class="slashed-rectangle-content">
            <div>
                <h5 class="couach-name">LUCIA</h5>
                <p class="couach-short-info">FITENESS A ZDRAVÝ ŽIVOTNÝ ŠTÝL</p>
                Lucia je energická trénerka, ktorá kombinuje silový tréning s prvkami mobility a jógy. Pomôže vám cítiť sa lepšie, silnejšie a sebavedomejšie každý deň.
            </div>
        </div>
    </div>

    <div>
        <button class="btn btn-primary">Rezervuj si Trénera <span class="button-text-yellow">LUCIA</span></button>
    </div>
</section>

<section class="row-section">
    <div>
        <button class="btn btn-primary">Rezervuj si Trénera <span class="button-text-yellow">PETER</span></button>
    </div>

    <div class="slashed-rectangle-right">
        <div class="slashed-rectangle-content">
            <div>
                <h5 class="couach-name">MARTIN</h5>
                <p class="couach-short-info">SILOVÝ TRÉNING A KONDÍCIA</p>
                Martin sa zameriava na rozvoj sily, správnu techniku a dlhodobú kondíciu. Jeho tréningy sú dynamické, premyslené a prispôsobené úrovni každého klienta.
            </div>
        </div>
    </div>

    <img src="<?= $link->asset('/images/coach-3.png') ?>" alt="trener3" class="section-image-right">
</section>
