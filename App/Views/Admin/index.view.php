<?php
/** @var array|\Traversable $accounts */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var array|null $flash */

use App\Models\Account;

if (!isset($accounts) || !is_iterable($accounts)) {
    $accounts = Account::getAll();
}

/**
 * Helper to read account fields from either array row or Account object.
 */
function acc_field($acc, string $field)
{
    if (is_array($acc)) {
        return $acc[$field] ?? null;
    }
    if (is_object($acc)) {
        switch ($field) {
            case 'id':
                return method_exists($acc, 'getId') ? $acc->getId() : null;
            case 'email':
                return method_exists($acc, 'getEmail') ? $acc->getEmail() : null;
            case 'role':
                return method_exists($acc, 'getRole') ? $acc->getRole() : null;
            case 'name':
                // prefer provided getName(), fall back to email
                return method_exists($acc, 'getName') ? $acc->getName() : (method_exists($acc, 'getEmail') ? $acc->getEmail() : null);
            default:
                return null;
        }
    }
    return null;
}
?>

<head>
    <link rel="stylesheet" href="<?= $link->asset('/css/admin.css') ?>">
</head>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h4>Accounts</h4>
            <div id="div-table">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($accounts as $acc): ?>
                        <tr>
                            <td><?= (int)(acc_field($acc, 'id') ?? 0) ?></td>
                            <td><?= htmlspecialchars((string)(acc_field($acc, 'email') ?? '')) ?></td>
                            <td>
                                <?php
                                if (is_array($acc)) {
                                    $name = trim(($acc['first_name'] ?? '') . ' ' . ($acc['last_name'] ?? ''));
                                    if ($name === '') {
                                        $name = (string)(acc_field($acc, 'email') ?? '');
                                    }
                                } else {
                                    $name = (string)(acc_field($acc, 'name') ?? '');
                                }
                                ?>
                                <?= htmlspecialchars($name) ?>
                            </td>
                            <td><?= htmlspecialchars((string)(acc_field($acc, 'role') ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($accounts)): ?>
                        <tr><td colspan="4">No accounts found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <h5>Change user role</h5>
            <form method="post" action="/admin/role" style="max-width:420px;">
                <div class="mb-2">
                    <label for="id">User ID</label>
                    <input id="id" name="id" type="number" class="form-control" required />
                </div>
                <div class="mb-2">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="customer">customer</option>
                        <option value="admin">admin</option>
                        <option value="reception">reception</option>
                        <option value="trainer">trainer</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Change role</button>
            </form>
        </div>
    </div>
</div>
