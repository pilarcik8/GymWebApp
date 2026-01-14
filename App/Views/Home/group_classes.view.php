<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var \Framework\Auth\AppUser $user */
/** @var array|\Traversable $groupClasses */

$view->setLayout('root');
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/group-classes.css') ?>">
    <script src="<?= $link->asset('/js/group-classes.js') ?>"></script>
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
                    <th>Dĺžka (minúty)</th>
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
                    <?php foreach ($groupClasses as $item):
                        $gc = $item['model'];
                        $date = $item['date'];
                        $time = $item['time'];
                        $id = $gc->getId();
                        $reservations = $item['reservations'];
                        $capacity = $gc->getCapacity();
                        $desc = trim((string)$gc->getDescription());
                        $trainerName = $item['trainerName'];
                        $isRegistered = $item['is_registered'];
                        ?>
                        <tr>
                            <td><?= $gc->getName() ?></td>
                            <td>
                                <?php if ($desc !== ''):?>
                                    <button type="button" class="btn btn-sm btn-outline-primary show-desc" data-desc="<?= $desc ?>" data-title="<?= $gc->getName() ?>">Popis</button>
                                <?php else: ?>
                                    <em class="text-muted">Žiadny popis.</em>
                                <?php endif; ?>
                            </td>

                            <td><?= $trainerName ?></td>
                            <td><?= $date ?></td>
                            <td><?= $time ?></td>
                            <td><?= $gc->getDurationMinutes() ?></td>
                            <td><?= $reservations ?>/<?= $capacity ?></td>
                            <td>
                                <?php if ($user->getRole() !== 'customer') : ?>
                                    <span class="badge bg-danger">Nie si zákazník</span>
                                <?php elseif ($isRegistered): ?>
                                    <form method="post" action="<?= $link->url('home.leaveGroupClass') ?>" class="d-inline">
                                        <input type="hidden" name="group_class_id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Odhlásiť</button>
                                    </form>
                                <?php elseif ($reservations >= $capacity): ?>
                                    <span class="badge bg-danger">Plné</span>
                                <?php else: ?>
                                    <?php if ($user->isLoggedIn()): ?>
                                        <form method="post" action="<?= $link->url('home.joinGroupClass') ?>" class="d-inline">
                                            <input type="hidden" name="group_class_id" value="<?= $id ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Prihlásiť</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="<?= $link->url('auth.login') ?>" class="btn btn-sm btn-success">Prihlásiť</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
