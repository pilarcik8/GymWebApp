<?php
/** @var array|\Traversable $groupClasses */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/coach-panel.css') ?>">
    <script src="<?= $link->asset('js/coach-panel.js') ?>"></script>
</head>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4>Vaše skupinové tréningy</h4>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <div id="div-table">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Názov</th>
                        <th>Dátum</th>
                        <th>Čas</th>
                        <th>Dĺžka trvania (minúty)</th>
                        <th>Kapacita</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($groupClasses as $item):
                        $gc = $item['model'];
                        $date = $item['date'];
                        $time = $item['time'];
                        $id = $gc->getId();
                        $reservations = $item['reservations'] ?? 0;
                        $desc = trim((string) $gc->getDescription());
                        ?>
                        <tr>
                            <td><?= $gc->getName() ?></td>
                            <td><?= $date ?></td>
                            <td><?= $time ?></td>
                            <td><?= $gc->getDurationMinutes() ?></td>
                            <td><?= $reservations ?>/<?= $gc->getCapacity() ?></td>
                            <td>
                                <form id="buttons" method="post" action="<?= $link->url("deleteGroupClass") ?>" onsubmit="return confirm('Naozaj chcete odstrániť tohto skupinový tréning? Tréning nebude možné navrátiť.');">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" name="deleteGroupClass" class="btn btn-sm btn-danger">Odstrániť</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-info toggle-desc" data-id="<?= $id ?>">
                                    Popis
                                </button>
                            </td>
                        </tr>

                        <tr id="desc" class="desc-row" data-id="<?= $id ?>">
                            <td colspan="8" class="bg-light">
                                <?php if ($desc !== ''): ?>
                                    <?= $desc ?>
                                <?php else: ?>
                                    <em class="text-muted">Žiadny popis.</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                    if (empty($groupClasses)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Nemáte žiadne skupinové hodiny naplánované.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div id="form-create-class">
            <div>
                <h4>Vytvoriť skupinový tréning</h4>
                <form id="form" action="<?= $link->url('createGroupClass') ?>" method="post" class="row">
                    <div class="col-md-8">
                        <label for="gc-name" class="form-label">Pomenovanie</label>
                        <input id="gc-name" name="name" type="text" class="form-control" required maxlength="255" />
                    </div>

                    <div class="col-md-2">
                        <label for="gc-capacity" class="form-label">Kapacita</label>
                        <input id="gc-capacity" name="capacity" type="number" class="form-control" required min="1" value="20" />
                    </div>

                    <div class="col-md-3">
                        <label for="gc-date" class="form-label">Dátum</label>
                        <input id="gc-date" name="date" type="datetime-local" class="form-control" required value="<?= date('Y-m-d\\TH:i') ?>"/>
                    </div>

                    <div class="col-md-3">
                        <label for="gc-duration" class="form-label">Dĺžka trvania (minúty)</label>
                        <input id="gc-duration" name="duration_minutes" type="number" class="form-control" required min="1" value="60" />
                    </div>

                    <div class="col-md-10">
                        <label for="gc-description" class="form-label">Popis</label>
                        <textarea id="gc-description" name="description" class="form-control" rows="3" maxlength="1000"></textarea>
                    </div>

                    <input type="hidden" name="trainer_id" value="<?= $user->getID() ?>" />

                    <div class="col-12">
                        <button type="submit" id="button" name="createGroupClass" class="btn btn-primary">Vytvor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
