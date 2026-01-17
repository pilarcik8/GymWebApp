<?php
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
/** @var \Framework\Support\View $view */
/** @var array|\Traversable $passes */
/** @var array|\Traversable $trainings */
/** @var string $now */

$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/user/user-panel.css') ?>">
<script src="<?= $link->asset('js/user-panel.js') ?>"></script>

<div id="background" class="container-fluid">
    <div class="row">
        <div class="col d-flex align-items-center justify-content-between">
            <h4>Tranzakcie</h4>
        </div>
        <div class="col-12">
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
        </div>
    </div>

    <div class="flex-tables mt-3">
        <div class="table-card collapsed">
            <div class="card-header">
                <h5>Pernametky</h5>
                <button type="button" class="table-toggle btn btn-sm btn-outline-secondary" aria-expanded="false" title="Zobraziť/skryť">
                    <i class="bi bi-chevron-double-right" aria-hidden="true"></i>
                </button>
            </div>

            <div class="table-responsive mt-2">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Deň zakúpenia</th>
                        <th>Deň vypršania</th>
                        <th>Stav</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($passes as $p) { ?>
                        <tr>
                            <td><?= $p['purchase_formatted'] ?></td>
                            <td><?= $p['expiration_formatted'] ?></td>
                            <td>
                                <?php if ($p['expiration_raw'] < $now) { ?>
                                    <span class="text-danger">Vypršala</span>
                                <?php } else { ?>
                                    <span class="text-success">Aktívna</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($passes)): ?>
                        <tr><td colspan="3">Žiadne pernametky neboli nájdené.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-card collapsed">
            <div class="card-header">
                <h5>Tréningy</h5>
                <button type="button" class="table-toggle btn btn-sm btn-outline-secondary" aria-expanded="false" title="Zobraziť/skryť">
                    <i class="bi bi-chevron-double-right" aria-hidden="true"></i>
                </button>
            </div>

            <div class="table-responsive mt-2">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Deň zakúpenia</th>
                        <th>Deň tréningu</th>
                        <th>Čas tréningu</th>
                        <th>Tréner</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($trainings as $t) { ?>
                        <tr>
                            <td><?= $t['purchase_formatted'] ?></td>
                            <td><?= $t['start_date_formatted'] ?></td>
                            <td><?= $t['start_time_formatted'] ?? '' ?></td>
                            <td><?= $t['trainerName'] ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($trainings)): ?>
                        <tr><td colspan="4">Žiadne tréningy neboli nájdené.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
