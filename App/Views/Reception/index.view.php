<?php
/** @var array|\Traversable $accounts */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
/** @var \Framework\Support\View $view */

$view->setLayout('root');
?>

<link rel="stylesheet" href="<?= $link->asset('/css/user/reception.css') ?>">
<script src="<?= $link->asset('js/reception.js') ?>"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4>Zákaznícke Účty</h4>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>

            <!-- Filter podľa mena/emailu (AJAX) -->
            <div class="mb-3">
                <label for="customer-search" class="form-label">Filtrovať zákazníkov podľa mena alebo emailu</label>
                <input type="text"
                       id="customer-search"
                       class="form-control"
                       placeholder="Začnite písať meno alebo email"
                       value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>">
            </div>

            <div id="div-table">
                <table class="table table-sm table-striped mb-0" id="reception-customers-table">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Meno</th>
                        <th>Kredit</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($accounts as $acc): ?>
                        <?php
                        $role = $acc->getRole();
                        $name = $acc->getName();
                        $id = $acc->getId();
                        ?>
                        <tr>
                            <td><?= $acc->getEmail() ?></td>
                            <td><?= $name ?></td>
                            <td><?= $acc->getCredit() ?></td>
                            <td>
                                <button type="button"
                                        id="button-add-amount"
                                        class="button-green btn btn-sm btn-primary open-add-credit"
                                        data-id="<?= $id ?>"
                                        data-name="<?= $name ?>">
                                    Pridať
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($accounts)): ?>
                        <tr><td colspan="6">Žiadny zákazníci neboli nájdené.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-add-credit" class="modal-overlay" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <h5 id="modal-title">Pridať kredit</h5>
        <div id="modal-message" class="modal-label"></div>
        <form method="post" action="<?= $link->url("addCredit") ?>">
            <input type="hidden" name="id" id="modal-account-id" value="">
            <div class="form-group">
                <label for="modal-amount">Koľko chcete pridať</label>
                <input id="modal-amount" name="amount" type="number" step="0.01" min="0" class="form-control" required placeholder="0.00">
            </div>
            <div class="modal-actions">
                <button type="button" id="modal-button-cancel" class="btn btn-secondary">Zrušiť</button>
                <button type="submit" id="modal-button-send" name="addCredit" class="btn btn-primary">Odoslať</button>
            </div>
        </form>
    </div>
</div>
