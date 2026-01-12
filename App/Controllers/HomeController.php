<?php

namespace App\Controllers;

use App\Models\Pass;
use App\Models\Account;
use App\Models\Group_Class_Participant;
use App\Configuration;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

/**
 * Class HomeController
 * Handles actions related to the home page and other public actions.
 *
 * This controller includes actions that are accessible to all users, including a default landing page and a contact
 * page. It provides a mechanism for authorizing actions based on user permissions.
 *
 * @package App\Controllers
 */
class HomeController extends BaseController
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
        return $this->html();
    }

    /**
     * Displays the contact page.
     *
     * This action serves the HTML view for the contact page, which is accessible to all users without any
     * authorization.
     *
     * @return Response The response object containing the rendered HTML for the contact page.
     */
    public function coaches(Request $request): Response
    {
        return $this->html();
    }

    public function gallery(Request $request): Response
    {
        return $this->html();
    }

    public function group_classes(Request $request): Response
    {
        return $this->html();
    }

    public function permits(Request $request): Response
    {
        $message = $_SESSION['flash_message'] ?? null;

        return $this->html(compact('message'));
    }

    public function buy_permit(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        if (!$request->hasValue('buy_permit')) {
            return $this->redirect($this->url('home.permits'));
        }

        $userId = (int)$request->post('user_id');
        $days = (int)$request->post('days');
        $price = (float)$request->post('price');
        $account = Account::getOne($userId);

        if (!$account) {
            $_SESSION['flash_message'] = 'Účet nebol nájdený.';
            return $this->redirect($this->url('home.permits'));
        }

        $now = new \DateTime();
        $activePasses = Pass::getCount('`user_id` = ? AND `expiration_date` > ?', [$userId, $now->format('Y-m-d H:i:s')]);

        if ($activePasses > 0) {
            $_SESSION['flash_message'] = 'Máte už aktívnu permanentku.';
            return $this->redirect($this->url('home.permits'));
        }

        if ($account->getRole() !== 'customer') {
            $_SESSION['flash_message'] = 'Len zákazníci môžu kupovať permanentky.';
            return $this->redirect($this->url('home.permits'));
        }

        if ($account->getCredit() < $price) {
            $_SESSION['flash_message'] = 'Nedostatok kreditu na nákup permanentky.';
            return $this->redirect($this->url('home.permits'));
        }

        $account->setCredit($account->getCredit() - $price);
        $account->save();

        $this->app->getSession()->set(Configuration::IDENTITY_SESSION_KEY, $account);

        $purchaseDate = new \DateTime();
        $expiration = (clone $purchaseDate)->modify("+$days days");

        $pass = new Pass();
        $pass->setUserId($userId);
        $pass->setPurchaseDate($purchaseDate->format('Y-m-d H:i:s'));
        $pass->setExpirationDate($expiration->format('Y-m-d H:i:s'));
        $pass->save();
        $_SESSION['flash_message'] = 'Permanentka zakúpená.';

        return $this->redirect($this->url('home.permits'));
    }

    public function joinGroupClass(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        if ($this->user->getRole() !== 'customer') {
            return $this->redirect($this->url('home.permits'));
        }

        $groupId = null;
        if ($request->isPost()) {
            $groupId = $request->post('group_class_id');
        }

        if ($groupId === null || $groupId <= 0) {
            return $this->redirect($this->url('home.group_classes'));
        }

        $existing = Group_Class_Participant::getAll('`customer_id` = ? AND `group_class_id` = ?', [$this->user->getID(), $groupId]);
        if (count($existing) > 0) {
            return $this->redirect($this->url('home.group_classes'));
        }

        $participant = new Group_Class_Participant();
        $participant->setCustomerId($this->user->getID());
        $participant->setGroupClassId($groupId);
        $participant->save();

        return $this->redirect($this->url('home.group_classes'));
    }

    public function leaveGroupClass(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        $groupId = $request->post('group_class_id');
        $groupId = $groupId !== null ? (int)$groupId : null;
        if ($groupId === null || $groupId <= 0) {
            return $this->redirect($this->url('home.group_classes'));
        }

        Group_Class_Participant::executeRawSQL(
            'DELETE FROM `group_class_participants` WHERE `customer_id` = ? AND `group_class_id` = ?',
            [$this->user->getID(), $groupId]
        );


        return $this->redirect($this->url('home.group_classes'));
    }
}

