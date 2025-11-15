<?php

/** @var \Framework\Support\LinkGenerator $link */
?>

<head>
    <title>GymApp | Domov</title>
    <link rel="stylesheet" href="/css/home-page.css">
</head>
<body>

<div class="home-hero-div">
    <img src="/images/bezec-home.png" alt="Beziaci muz" class="home-hero-img">
    <div class="home-hero-text">
        <span class="hero-line-1">BUĎ LEPŠÍM JA</span>
        <br>
        <span class="hero-line-2">už dnes</span>
    </div>
</div>

<div class="sluzby-wrapper">
    <div class="sluzby-riadok-1">NAŠE SLUŽBY</div>
    <div class="sluzby-riadok-2">ČO SILVER GYM PONÚKA?</div>
</div>

<div class="co-ponukame-wrapper">
    <div class="co-ponukame">
        <div class="square-odd">
            <h2 class="sq-title">Silový tréning</h2>
            <p class="sq-text">Profesionálne vybavenie na naberanie sily a budovanie svalov.</p>
        </div>

        <div class="square-even">
            <h2 class="sq-title">Cardio zóna</h2>
            <p class="sq-text">Bežecké pásy, bicykle a eliptické trenažéry pre lepšiu kondíciu.</p>
        </div>

        <div class="square-odd">
            <h2 class="sq-title">Skupinové lekcie</h2>
            <p class="sq-text">Dynamické tréningy pre všetky úrovne – od jógy po HIIT.</p>
        </div>

        <div class="square-even">
            <h2 class="sq-title">Osobný tréner</h2>
            <p class="sq-text">Individuálne plány a profesionálne vedenie na mieru.</p>
        </div>

        <div class="square-odd">
            <h2 class="sq-title">Regenerácia</h2>
            <p class="sq-text">Priestor na strečing a techniky pre rýchlejšiu obnovu.</p>
        </div>

        <div class="square-even">
            <h2 class="sq-title">Poradenstvo</h2>
            <p class="sq-text">Pomoc pri výbere stravy, ktorá podporí tvoje ciele.</p>
        </div>
    </div>
</div>

<div class="otvaracie-hodiny-container">
    <div class="otvaracie-hodiny-image">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <!-- Čiary -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
            </div>

            <!-- Obrázky -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/images/home-slide-1.jpg" class="d-block w-100" alt="Obrázok 1">
                </div>
                <div class="carousel-item">
                    <img src="/images/home-slide-2.jpg" class="d-block w-100" alt="Obrázok 2">
                </div>
                <div class="carousel-item">
                    <img src="/images/home-slide-3.jpg" class="d-block w-100" alt="Obrázok 3">
                </div>
            </div>

            <!-- Šípky -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="otvaracie-hodiny-text">
        <h2>Otváracie hodiny:</h2>
        <p>Pondelok: 06:00 – 22:00</p>
        <p>Utorok: 06:00 – 22:00</p>
        <p>Streda: 06:00 – 22:00</p>
        <p>Štvrtok: 06:00 – 22:00</p>
        <p>Piatok: 06:00 – 22:00</p>
        <p>Sobota: 08:00 – 20:00</p>
        <p>Nedeľa: 08:00 – 20:00</p>
        <p class="small-text">
            Počas štátnych sviatkov a špeciálnych podujatí sa otváracie hodiny môžu meniť.
        </p>
    </div>
</div>


<div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2191.8254369133188!2d18.75455428248802!3d49.20939143362418!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4714594c3d8ab747%3A0x81aa846c95c9eabf!2s%C3%9Astav%20telesnej%20v%C3%BDchovy%20%C5%BDilinskej%20univerzity%20v%20%C5%BDiline!5e0!3m2!1ssk!2ssk!4v1763224783343!5m2!1ssk!2ssk" width="1000" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
</body>

