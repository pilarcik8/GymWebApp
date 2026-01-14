<?php

namespace App\Controllers;

use App\Models\Pass;
use App\Models\Account;
use App\Models\Group_Class_Participant;
use App\Models\Group_Class;
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
        $account = Account::getOne($this->user->getId());

        return $this->html(compact('account'));
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

    // PERNAMETKY
    public function permits(Request $request): Response
    {
        $permits = [
            ['title' => 'Týždenná', 'days' => 7,   'price' => 20.0],
            ['title' => 'Mesačná',  'days' => 30,  'price' => 49.99],
            ['title' => 'Ročná',    'days' => 365, 'price' => 399.99],
        ];

        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        return $this->html(compact('permits', 'message'));
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
            $_SESSION['flash_message'] = 'Máte aktívnu permanentku.';
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

    //SPOLOCNE TRÉNINGY
    public function group_classes(Request $request): Response
    {
        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');

        $raw = Group_Class::getAll('`start_datetime` > ?', [$now], 'start_datetime ASC');

        $classIds = array_map(function($g){ return $g->getId(); }, $raw);

        $reservationsMap = [];
        $registeredMap = [];
        $trainersMap = [];

        if (count($classIds)) {
            $ph = implode(',', array_fill(0, count($classIds), '?'));
            $sql = "SELECT group_class_id, COUNT(*) AS cnt FROM `group_class_participants` WHERE group_class_id IN ($ph) GROUP BY group_class_id";
            $resRows = Group_Class_Participant::executeRawSQL($sql, $classIds);
            foreach ($resRows as $r) { $reservationsMap[$r['group_class_id']] = $r['cnt']; }

            if ($this->user->isLoggedIn()) {
                $userId = $this->user->getID();
                $params = array_merge([$userId], $classIds);
                $sql2 = "SELECT group_class_id FROM `group_class_participants` WHERE customer_id = ? AND group_class_id IN ($ph)";
                $rows2 = Group_Class_Participant::executeRawSQL($sql2, $params);
                foreach ($rows2 as $r) { $registeredMap[$r['group_class_id']] = true; }
            }

            $trainerIds = array_values(array_unique(array_map(function($g){ return $g->getTrainerId(); }, $raw)));
            if (count($trainerIds)) {
                $phT = implode(',', array_fill(0, count($trainerIds), '?'));
                $trainerRows = Account::getAll("`id` IN ($phT)", $trainerIds);
                foreach ($trainerRows as $t) { $trainersMap[$t->getId()] = $t->getFirstName() . ' ' . $t->getLastName(); }
            }
        }

        $groupClasses = [];
        foreach ($raw as $gc) {
            $id = $gc->getId();
            $dt = new \DateTimeImmutable($gc->getStartDatetime());
            $groupClasses[] = [
                'model' => $gc,
                'date' => $dt->format('d.m.Y'),
                'time' => $dt->format('H:i'),
                'reservations' => isset($reservationsMap[$id]) ? $reservationsMap[$id] : 0,
                'is_registered' => !empty($registeredMap[$id]),
                'trainerName' => isset($trainersMap[$gc->getTrainerId()]) ? $trainersMap[$gc->getTrainerId()] : '—',
            ];
        }

        return $this->html(compact('now', 'groupClasses'));
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

