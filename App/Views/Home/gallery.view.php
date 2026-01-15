<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var array|null $images */

use App\Configuration;

$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/gallery.css') ?>">
<h2>Naše <span id="title-white-word">spomienky</span> </h2>

<div class="gallery-container container py-4">
    <?php if (empty($images)) : ?>
        <div class="text-center">Žiadne obrázky.</div>
    <?php else: ?>
        <div class="gallery-grid">
            <?php foreach ($images as $img) : ?>
                <?php $filename = $img->getFilename(); ?>
                <figure class="gallery-item">
                    <?php if ($img->getTitle()) : ?>
                        <figcaption class="gallery-item-title"><?= $img->getTitle() ?></figcaption>
                    <?php endif; ?>

                    <div class="gallery-media">
                        <img
                            data-src="<?= $link->asset(trim(Configuration::UPLOAD_URL, '/') . '/gallery/' . $filename) ?>"
                            src="<?= $link->asset(trim(Configuration::UPLOAD_URL, '/') . '/gallery/' . $filename) ?>"
                            alt="<?= $img->getAlt() ?: $img->getTitle() ?: 'Gallery image' ?>"
                            class="gallery-img"
                            loading="lazy"
                        >
                    </div>

                    <!-- alt text hidden by default; will be shown only when image fails to load -->
                    <div class="gallery-item-alt" style="display:none;"><?= $img->getAlt() ?: 'Obrázok sa nezobrazil' ?></div>

                </figure>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

