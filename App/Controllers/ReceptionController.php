<?php

namespace App\Controllers;

use App\Models\Account;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;


class ReceptionController extends BaseController
{
    /**
     * Authorizes controller actions based on the specified action name.
     *
     * In this implementation, all actions are authorized unconditionally.
     *
     * @param string $action The action name to authorize.
     * @return bool Returns true, allowing all actions.
     */
    public function authorize(Request $request, string $action): bool
    {
        if (!$this->user->isLoggedIn())
            return false;

        if ($this->user->getRole() !== 'reception')
            return false;

        return true;
    }

    /**
     * Displays the default home page.
     *
     * This action serves the main HTML view of the home page.
     *
     * @return Response The response object containing the rendered HTML for the home page.
     */
    public function index(Request $request): Response
    {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $accounts = Account::getAll('`role` = ?', ['customer']);

        return $this->html(compact('message', 'accounts'));
    }

    public function addCredit(Request $request): Response
    {
        if ($request->hasValue('addCredit')) {
            $amountRaw = $request->post('amount');
            $idRaw = $request->post('id');

            // Validácia ID
            $id = $idRaw !== null ? (int)$idRaw : 0;
            if ($id <= 0) {
                $_SESSION['flash_message'] = 'Neplatné ID účtu.';
                return $this->redirect($this->url('reception.index'));
            }

            // Validácia sumy – musí byť číslo, > 0 a v rozumnom rozsahu
            if ($amountRaw === null || $amountRaw === '') {
                $_SESSION['flash_message'] = 'Zadajte sumu kreditu.';
                return $this->redirect($this->url('reception.index'));
            }
            if (!is_numeric($amountRaw)) {
                $_SESSION['flash_message'] = 'Suma musí byť číslo.';
                return $this->redirect($this->url('reception.index'));
            }

            $amount = (float)$amountRaw;
            if ($amount <= 0) {
                $_SESSION['flash_message'] = 'Suma musí byť väčšia ako 0.';
                return $this->redirect($this->url('reception.index'));
            }
            if ($amount > 1000) {
                $_SESSION['flash_message'] = 'Naraz je možné pridať maximálne 1000 kreditov.';
                return $this->redirect($this->url('reception.index'));
            }

            $acc = Account::getOne($id);
            if (!$acc) {
                $_SESSION['flash_message'] = "Účet s ID $id neexistuje.";
                return $this->redirect($this->url('reception.index'));
            }

            if ($acc->getRole() !== 'customer') {
                $_SESSION['flash_message'] = "Účet s ID $id nie je zákaznícky účet.";
                return $this->redirect($this->url('reception.index'));
            }

            $acc->setCredit($acc->getCredit() + $amount);
            $acc->save();
            $_SESSION['flash_message'] = "Kredit bol úspešne navýšený o $amount pre účet #$id.";
        }

        return $this->redirect($this->url("reception.index"));
    }
}
