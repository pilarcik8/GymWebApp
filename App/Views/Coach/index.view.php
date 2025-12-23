<?php
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */

$trainer_id = $user->getID();
$groupClasses = \App\Models\GroupClass::getAll('`trainer_id` = ?', [$trainer_id]);
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/coach-panel.css') ?>">
</head>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4>Your Group Trainings</h4>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Meno</th>
                    <th>Začiatok</th>
                    <th>Dĺžka (min)</th>
                    <th>Kapacita</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($groupClasses as $gc): ?>
                    <tr>
                        <td><?= $gc->getId() ?></td>
                        <td><?= $gc->getName() ?></td>
                        <td><?= $gc->getDate() ?></td>
                        <td><?= $gc->getDurationMinutes() ?></td>
                        <td>0/<?= $gc->getCapacity() ?></td>
                        <td>
                            <a href="">Edit</a> |
                            <a href="" onclick="return confirm('Delete?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach;
                if (empty($groupClasses)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nemáte žiadne skupinové hodiny naplánované.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Vytvoriť skupinovú hodinu</h5>
                <form action="" method="post" class="row g-3">
                    <div class="col-md-6">
                        <label for="gc-name" class="form-label">Name</label>
                        <input id="gc-name" name="name" type="text" class="form-control" required maxlength="255" />
                    </div>

                    <div class="col-md-6">
                        <label for="gc-date" class="form-label">Date &amp; time</label>
                        <input id="gc-date" name="date" type="datetime-local" class="form-control" required />
                    </div>

                    <div class="col-md-4">
                        <label for="gc-duration" class="form-label">Duration (minutes)</label>
                        <input id="gc-duration" name="duration_minutes" type="number" class="form-control" required min="1" />
                    </div>

                    <input type="hidden" name="trainer_id" value="<?= $trainer_id ?>" />

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Create Group Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>