<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var \Framework\Auth\AppUser $user */

use App\Models\Group_Class;
use App\Models\Account;

$view->setLayout('root');

$now = (new DateTimeImmutable())->format('Y-m-d H:i:s');
$groupClasses = Group_Class::getAll('`start_datetime` > ?', [$now], 'start_datetime ASC');

function splitDateTime($datetimeString) {
    $dt = new DateTimeImmutable($datetimeString);
    $date = $dt->format('d.m.Y');
    $time = $dt->format('H:i');
    return [$date, $time];
}
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/group_classes.css') ?>">
</head>

<div class="bg-img">
    <div class="group-card container py-4">
        <h1>Plánované skupinové hodiny</h1>

        <div id="div-table" class="table-responsive mt-4">
            <table class="table table-sm table-striped mb-0">
                <thead>
                <tr>
                    <th>Meno</th>
                    <th>Popis</th>
                    <th>Tréner</th>
                    <th>Dátum</th>
                    <th>Čas</th>
                    <th>Dĺžka (min)</th>
                    <th>Kapacita</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($groupClasses)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Nemáte žiadne skupinové hodiny naplánované.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($groupClasses as $gc):
                        $arr = splitDateTime($gc->getStartDatetime());
                        $date = $arr[0];
                        $time = $arr[1];
                        $id = (int)$gc->getId();
                        $reservations = 0; // implement reservation count if available
                        $desc = trim((string)$gc->getDescription());
                        $trainer = Account::getOne($gc->getTrainerId());
                        $trainerName = $trainer ? trim($trainer->getFirstName() . ' ' . $trainer->getLastName()) : '—';
                        ?>
                        <tr>
                            <td><?= $gc->getName() ?></td>
                            <td>
                                <?php if ($desc !== ''):?>
                                    <button type="button" class="btn btn-sm btn-outline-primary show-desc"
                                            data-desc="<?= $desc ?>" data-title="<?= htmlspecialchars($gc->getName()) ?>">
                                        Popis
                                    </button>
                                <?php else: ?>
                                    <em class="text-muted">Žiadny popis.</em>
                                <?php endif; ?>
                            </td>

                            <td><?= $trainerName ?></td>
                            <td><?= $date ?></td>
                            <td><?= $time ?></td>
                            <td><?= $gc->getDurationMinutes() ?></td>
                            <td><?= $reservations ?>/<?= $gc->getCapacity() ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success">Prihlásiť</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal markup (hidden) -->
<div id="desc-modal" class="desc-modal d-none" aria-hidden="true">
    <div class="desc-modal-backdrop"></div>
    <div class="desc-modal-dialog">
        <div class="desc-modal-header">
            <h5 id="desc-modal-title" class="mb-0"></h5>
            <button type="button" id="desc-modal-close" class="btn-close" aria-label="Close"></button>
        </div>
        <div id="desc-modal-body" class="desc-modal-body"></div>
    </div>
</div>

<script>
    (function () {
        function showModal(title, htmlContent) {
            var modal = document.getElementById('desc-modal');
            document.getElementById('desc-modal-title').textContent = title;
            document.getElementById('desc-modal-body').innerHTML = htmlContent;
            modal.classList.remove('d-none');
            modal.setAttribute('aria-hidden', 'false');
        }
        function hideModal() {
            var modal = document.getElementById('desc-modal');
            modal.classList.add('d-none');
            modal.setAttribute('aria-hidden', 'true');
            document.getElementById('desc-modal-body').innerHTML = '';
            document.getElementById('desc-modal-title').textContent = '';
        }

        Array.from(document.getElementsByClassName('show-desc')).forEach(function (btn) {
            btn.addEventListener('click', function () {
                var desc = btn.getAttribute('data-desc') || '';
                var title = btn.getAttribute('data-title') || '';

                showModal(title, desc);
            });
        });

        document.getElementById('desc-modal-close').addEventListener('click', hideModal);
        document.querySelector('.desc-modal-backdrop').addEventListener('click', hideModal);

        // close on escape
        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') hideModal();
        });
    })();
</script>
