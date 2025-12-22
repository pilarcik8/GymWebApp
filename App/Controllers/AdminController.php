<?php

namespace App\Controllers;

use App\Models\Account;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class AdminController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        if (!$this->user->isLoggedIn())
            return false;

        if ($this->user->getRole() !== 'admin')
            return false;

        return true;
    }

    public function index(Request $request): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // read flash message (if any) and clear it
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        // load accounts for the admin view
        $accounts = Account::getAll();

        return $this->html(compact('message', 'accounts'));
    }

    /**
     * @throws \Exception
     */
    public function changeRole(Request $request): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($request->hasValue('changeRole')) {
            $id = (int)$request->post('id');
            $role = trim($request->post('role'));

            $account = Account::getOne($id);
            if ($account) {
                if ($account->getRole() === $role) {
                    $_SESSION['flash_message'] = "Používateľ #$id už má rolu $role.";
                    return $this->redirect($this->url("admin.index"));
                }

                if ($account->getRole() === "admin") {
                    $adminCount = Account::getCount('`role` = ?', ["admin"]);
                    if ($adminCount <= 1 && $role !== "admin") {
                        $_SESSION['flash_message'] = "Nie je možné zmeniť rolu posledného administrátora.";
                        return $this->redirect($this->url("admin.index"));
                    }
                }
                $account->setRole($role);
                $account->save();

                $_SESSION['flash_message'] = "Role používateľa #$id bola zmenená na $role.";
            } else {
                $_SESSION['flash_message'] = "Používateľ s ID #$id nebol nájdený.";
            }
        }

        return $this->redirect($this->url("admin.index"));
    }

    public function deleteUser(Request $request): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($request->hasValue('deleteUser')) {
            $id = (int)$request->post('id');

            $account = Account::getOne($id);
            if (!$account) {
                $_SESSION['flash_message'] = "Používateľ s ID #$id nebol nájdený.";
                return $this->redirect($this->url("admin.index"));
            }

            if (method_exists($this->user, 'getId') && (int)$this->user->getId() === $id) {
                $_SESSION['flash_message'] = "Nemôžete vymazať svoj vlastný účet.";
                return $this->redirect($this->url("admin.index"));
            }

            $role = $account->getRole();
            if ($role === 'admin') {
                $adminCount = Account::getCount('`role` = ?', ["admin"]);
                if ($adminCount <= 1) {
                    $_SESSION['flash_message'] = "Nie je možné vymazať posledného administrátora.";
                    return $this->redirect($this->url("admin.index"));
                }
            }

            $account->delete();
            $_SESSION['flash_message'] = "Používateľ #$id bol vymazaný.";
        }

        return $this->redirect($this->url("admin.index"));
    }
}
