<?php
/** @var \Framework\Support\LinkGenerator $link */
?>

<head>
    <title>Treneri</title>
    <link rel="stylesheet" href="<?= $link->asset('/css/coaches.css') ?>">
</head>

<body>

<div class="hero-coaches-wrapper">
    <img src="<?= $link->asset('/images/couches-hero.png') ?>" alt="Trener joga" class="hero-img">

    <div class="hero-text-container">
        <h2>NAŠI <span class="hero-text-yellow">TRÉNERI</span> </h2>
        <p>sú tu, aby vám pomohli dosiahnuť <span class="hero-text-yellow">vaše ciele</span></p>
    </div>
</div>

<!-- obrázok vpravo, kosodĺžnik vľavo -->
<section class="row-section section-left-koso" id="section1">
    <div class="parallelogram" id="koso1">
        <div class="parallelogram-content">
            <div>
            <h5 class="koso-name">MAREK</h5>
            <p class="koso-popisok">silový tréning a kondícia</p>

            Marek je certifikovaný tréner so zameraním na silový a funkčný tréning. Pomôže vám vybudovať svaly, zlepšiť výkon a naučí vás správnu techniku cvičenia.
            </div>
            <div>
                <button>
                    Rezervuj si
                </button>
            </div>

        </div>
    </div>

    <img src="<?= $link->asset('/images/coach-1.png') ?>" alt="trener1" class="section-image" id="img1">
</section>

<!-- obrázok vľavo, kosodĺžnik vpravo -->
<section class="row-section section-right-koso" id="section2">
    <img src="<?= $link->asset('/images/coach-1.png') ?>" alt="trener1" class="section-image" id="img2">

    <div class="parallelogram" id="koso2">
        <div class="parallelogram-content">
            Toto je text v kosodĺžniku (vpravo)
        </div>
    </div>
</section>

<!-- obrázok vpravo, kosodĺžnik vľavo -->
<section class="row-section section-left-koso" id="section1">
    <div class="parallelogram" id="koso1">
        <div class="parallelogram-content">
            Toto je text v kosodĺžniku (vľavo)
        </div>
    </div>

    <img src="<?= $link->asset('/images/coach-1.png') ?>" alt="trener1" class="section-image" id="img1">
</section>

</body>
