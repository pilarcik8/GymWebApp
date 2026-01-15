<?php
/** @var \App\Models\TrainerInfo $trainerInfo */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
/** @var \Framework\Support\View $view */
/** @var \App\Models\Image[] $trainerImages */

$view->setLayout('root');

$shortValue = $trainerInfo ? $trainerInfo->getShort() : '';
$descValue = $trainerInfo ? $trainerInfo->getDescription() : '';

$currentImage = null;
if ($trainerInfo && $trainerInfo->getImageId()) {
    foreach ($trainerImages as $img) {
        if ($img->getId() === $trainerInfo->getImageId()) {
            $currentImage = $img;
            break;
        }
    }
}
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/trainer-profile.css') ?>">
</head>

<div class="coach-panel">
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="trainer-forms-wrapper">
            <div class="trainer-form-box">
                <h2>Textové informácie</h2>
                <!-- FORM 1: zmena textových informácií -->
                <form method="post" action="<?= $link->url('coach.editTrainerInfo') ?>" class="trainer-form mb-4">
                    <!-- Short Description -->
                    <div class="form-group mb-3">
                        <label for="short">Krátky Popis</label>
                        <input
                            type="text"
                            id="short"
                            name="short"
                            class="form-control"
                            maxlength="255"
                            value="<?= $shortValue ?>"
                            placeholder="Napr. Certifikovaný osobný tréner s 5 rokmi skúseností"
                        >
                        <small class="form-text">*Krátka charakteristika o tebe, ktorá sa zobrazí v zozname trénerov.</small>
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-3">
                        <label for="description">Podrobný Popis</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            rows="6"
                            placeholder="Napiš viac o sebe, tvojich skúsenostiach a špecializáciách..."
                        ><?= $descValue ?></textarea>
                        <small class="form-text">*Detailný popis tvojho profilu, ktorý sa zobrazí v tvojom profili.</small>
                    </div>

                    <!-- Submit Button for text info -->
                    <div class="form-group">
                        <button type="submit" name="editTrainerInfo" class="btn btn-primary button-green">
                            Uložiť Zmeny
                        </button>
                    </div>
                </form>
            </div>

            <div class="trainer-form-box">
                <h2>Profilová fotka</h2>
                <!-- FORM 2: zmena profilovej fotky -->
                <form method="post" action="<?= $link->url('coach.editTrainerPhoto') ?>" class="trainer-form" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <?php if ($currentImage): ?>
                            <label>Aktuálna fotografia:</label>
                            <div class="current-image mb-2">
                                <img src="<?= $link->asset('/uploads/trainer/' . $currentImage->getFilename()) ?>"
                                     alt="Profil"
                                class="img-thumbnail">
                            </div>
                        <?php endif; ?>

                            <input
                                type="file"
                                id="image"
                                name="image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                            >
                            <small class="form-text">*Maximálna veľkosť: 5 MB. Podporované formáty: JPG, PNG, GIF, WebP.</small>
                        </div>

                        <div class="form-group d-flex gap-2 align-items-center">
                            <button type="submit" name="editTrainerPhoto" class="btn btn-outline-primary">
                                Zmeniť fotografiu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

