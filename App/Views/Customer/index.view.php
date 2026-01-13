<?php
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */

use App\Models\Account;
use App\Models\Pass;
use App\Models\Training;

$now = (new DateTimeImmutable())->format('Y-m-d H:i:s');
$pass = Pass::getAll('`user_id` = ?', [$user->getID()], 'expiration_date DESC');
$train = Training::getAll('`customer_id` = ?', [$user->getID()], 'start_date DESC');

function splitDateTime($datetimeString) {
    $dt = new DateTimeImmutable($datetimeString);
    $date = $dt->format('d.m.Y');
    $time = $dt->format('H:i');
    return [$date, $time];
}
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/customer.css') ?>">
</head>

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
        <!-- Passes card (default collapsed) -->
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
                    <?php foreach ($pass as $p) { ?>
                        <tr>
                            <td>
                                <?php
                                $arr = splitDateTime($p->getPurchaseDate());
                                $date = $arr[0];
                                $time = $arr[1];
                                echo $date . ' ' . $time;
                                ?>
                            </td>
                            <td>
                                <?php
                                $arr = splitDateTime($p->getExpirationDate());
                                echo $arr[0];
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($p->getExpirationDate() < $now) {
                                    echo '<span class="text-danger">Expirated</span>';
                                } else {
                                    echo '<span class="text-success">Active</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($pass)): ?>
                        <tr><td colspan="3">Žiadne pernametky neboli nájdené.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Trainings card (default collapsed) -->
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
                        <th>Tréner</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($train as $t) { ?>
                        <tr>
                            <td>
                                <?php
                                $arr = splitDateTime($t->getPurchaseDate());
                                $date = $arr[0];
                                $time = $arr[1];
                                echo $date . ' ' . $time;
                                ?>
                            </td>
                            <td>
                                <?php
                                $arr = splitDateTime($t->getStartDate());
                                echo $arr[0];
                                ?>
                            </td>
                            <td>
                                <?php
                                $trainer = Account::getOne($t->getTrainerId());
                                if ($trainer) {
                                    echo trim($trainer->getFirstName() . ' ' . $trainer->getLastName());
                                } else {
                                    echo '—';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($train)): ?>
                        <tr><td colspan="3">Žiadne tréningy neboli nájdené.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // per-table toggles
    var toggles = document.querySelectorAll('.table-toggle');
    toggles.forEach(function(btn) {
        // set initial icon based on collapsed state
        var card = btn.closest('.table-card');
        if (!card) return;
        var icon = btn.querySelector('i');
        if (!icon) return;
        var isCollapsed = card.classList.contains('collapsed');
        // collapsed -> show right chevrons; opened -> show down chevrons
        if (isCollapsed) {
            icon.classList.remove('bi-chevron-double-down');
            icon.classList.add('bi-chevron-double-right');
        } else {
            icon.classList.remove('bi-chevron-double-right');
            icon.classList.add('bi-chevron-double-down');
        }
        btn.setAttribute('aria-expanded', (!isCollapsed).toString());

        btn.addEventListener('click', function() {
            var card = btn.closest('.table-card');
            if (!card) return;
            var icon = btn.querySelector('i');
            if (!icon) return;
            var nowCollapsed = card.classList.toggle('collapsed');
            // toggle icon classes
            if (nowCollapsed) {
                icon.classList.remove('bi-chevron-double-down');
                icon.classList.add('bi-chevron-double-right');
            } else {
                icon.classList.remove('bi-chevron-double-right');
                icon.classList.add('bi-chevron-double-down');
            }
            btn.setAttribute('aria-expanded', (!nowCollapsed).toString());
        });
    });
});
</script>
