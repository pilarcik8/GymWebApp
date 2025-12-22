<?php
/** @var array|\Traversable $accounts */
/** @var \Framework\Auth\AppUser $user */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */

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
            <div class="text-center text-danger mb-3">
                <?= @$message ?>
            </div>
            <div id="div-table">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Change role</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($accounts as $acc): ?>
                        <?php
                        $id = (int)(acc_field($acc, 'id') ?? 0);
                        $email = (string)(acc_field($acc, 'email') ?? '');
                        if (is_array($acc)) {
                            $name = trim(($acc['first_name'] ?? '') . ' ' . ($acc['last_name'] ?? ''));
                            if ($name === '') {
                                $name = $email;
                            }
                        } else {
                            $name = (string)(acc_field($acc, 'name') ?? $email);
                        }
                        $role = (string)(acc_field($acc, 'role') ?? '');
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= htmlspecialchars($email) ?></td>
                            <td><?= htmlspecialchars($name) ?></td>
                            <td><?= htmlspecialchars($role) ?></td>

                            <!-- Change role: inline POST form with select -->
                            <td>
                                <form method="post" action="<?= $link->url("changeRole") ?>" class="d-flex align-items-center">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <select name="role" class="form-control form-control-sm me-2" required>
                                        <option value="customer" <?= $role === 'customer' ? 'selected' : '' ?>>customer</option>
                                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>admin</option>
                                        <option value="reception" <?= $role === 'reception' ? 'selected' : '' ?>>reception</option>
                                        <option value="trainer" <?= $role === 'trainer' ? 'selected' : '' ?>>trainer</option>
                                    </select>
                                    <button type="submit" name="changeRole" class="btn btn-sm btn-primary">Change</button>
                                </form>
                            </td>

                            <!-- Delete user: inline POST form with confirmation -->
                            <td>
                                <form method="post" action="<?= $link->url("deleteUser") ?>" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" name="deleteUser" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($accounts)): ?>
                        <tr><td colspan="6">No accounts found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <hr>

            <!-- Previous standalone forms removed: actions are now per-row in the table -->

        </div>
    </div>
</div>