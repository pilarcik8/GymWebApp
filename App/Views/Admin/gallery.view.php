<?php
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
use App\Configuration;

?>

<div class="container-fluid py-4">
    <h2>Editor Galérie</h2>

    <div class="text-center text-danger mb-3">
        <?= @$message ?>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="mb-3">Pridať obrázok</h5>
                <form method="post" enctype="multipart/form-data" action="<?= $link->url('admin.uploadGalleryImage') ?>">
                    <input type="hidden" name="uploadGalleryImage" value="1">

                    <div class="mb-2">
                        <label class="form-label">Súbor</label>
                        <input type="file" name="image" accept="image/*" required class="form-control">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nadpis (voliteľné)</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Alternatíva (text, voliteľné)</label>
                        <input type="text" name="alt" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success" type="submit">Pridať</button>
                        <button class="btn btn-outline-secondary" type="reset">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="mb-3">Existujúce obrázky</h5>

                <div class="d-flex flex-wrap gap-3">
                    <?php if (!empty($images)) : ?>
                        <?php foreach ($images as $img) : ?>
                            <div class="card" style="width: 12rem;">
                                <div class="card-body p-2 text-center">
                                    <div class="fw-semibold mb-2"><?= $img->getTitle() ?: 'Bez nadpisu' ?></div>
                                </div>

                                <img src="<?= $link->asset(trim(Configuration::UPLOAD_URL, '/') . '/gallery/' . $img->getFilename()) ?>" class="card-img-top" alt="<?= $img->getAlt() ?: '' ?>">

                                <div class="card-body p-2">
                                    <div class="text-muted small mb-2"><?= $img->getAlt() ?: 'Bez alternatívneho textu' ?></div>
                                    <form method="post" action="<?= $link->url('admin.deleteGalleryImage') ?>" onsubmit="return confirm('Naozaj chcete odstrániť tento obrázok?');">
                                        <input type="hidden" name="deleteGalleryImage" value="1">
                                        <input type="hidden" name="id" value="<?= $img->getId() ?>">
                                        <button class="btn btn-danger btn-sm w-100" type="submit"><i class="bi bi-trash"></i> Odstrániť</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="text-muted">Žiadne obrázky neboli nájdené.</div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>