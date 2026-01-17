<?php
/** @var array|\Traversable $accounts */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */

?>


<link rel="stylesheet" href="<?= $link->asset('/css/user/admin.css') ?>">


<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4>Účty</h4>
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <div id="div-table">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Meno</th>
                        <th>Roľa</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($accounts as $acc): ?>
                        <?php
                        $id = $acc->getId();
                        $role = $acc->getRole();
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= $acc->getEmail() ?></td>
                            <td><?= $acc->getName() ?></td>
                            <td><?= $role ?></td>

                            <td>
                                <form method="post" action="<?= $link->url("changeRole") ?>" class="d-flex align-items-center">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <select name="role" class="form-control form-control-sm me-2" required>
                                        <option value="customer" <?= $role === 'customer' ? 'selected' : '' ?>>customer</option>
                                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>admin</option>
                                        <option value="reception" <?= $role === 'reception' ? 'selected' : '' ?>>reception</option>
                                        <option value="trainer" <?= $role === 'trainer' ? 'selected' : '' ?>>trainer</option>
                                    </select>
                                    <button type="submit" name="changeRole" class="btn btn-sm btn-primary">Zmen</button>
                                </form>
                            </td>

                            <td>
                                <form method="post" action="<?= $link->url("deleteUser") ?>" onsubmit="return confirm('Naozaj chcete odstrániť tohto používatela? Používateľov účet nebude možné navrátiť.');">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" name="deleteUser" class="btn btn-sm btn-danger">Odstrán</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($accounts)): ?>
                        <tr><td colspan="6">Žiadne účty neboli nájdené.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>