<?php

namespace App\Controllers;

use App\Models\Account;
use App\Models\Pass;
use App\Models\Training;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;


class CustomerController extends BaseController
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

        if ($this->user->getRole() !== 'customer')
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

        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $userId = $this->user->getID();

        $rawPasses = Pass::getAll('`user_id` = ?', [$userId], 'expiration_date DESC');
        $rawTrainings = Training::getAll('`customer_id` = ?', [$userId], 'start_date DESC');

        $passes = [];
        foreach ($rawPasses as $p) {
            $purchase = new \DateTimeImmutable($p->getPurchaseDate());
            $expiration = new \DateTimeImmutable($p->getExpirationDate());
            $passes[] = [
                'model' => $p,
                'purchase_formatted' => $purchase->format('d.m.Y H:i'),
                'expiration_formatted' => $expiration->format('d.m.Y'),
                'expiration_raw' => $p->getExpirationDate(),
            ];
        }

        $trainings = [];
        foreach ($rawTrainings as $t) {
            $purchase = new \DateTimeImmutable($t->getPurchaseDate());
            $start = new \DateTimeImmutable($t->getStartDate());
            $trainer = Account::getOne($t->getTrainerId());
            $trainerName = $trainer ? trim($trainer->getFirstName() . ' ' . $trainer->getLastName()) : '—';
            $trainings[] = [
                'model' => $t,
                'purchase_formatted' => $purchase->format('d.m.Y H:i'),
                'start_date_formatted' => $start->format('d.m.Y'),
                'start_time_formatted' => $start->format('H:i'),
                'start_raw' => $t->getStartDate(),
                'trainerName' => $trainerName,
            ];
        }

        return $this->html(compact('message', 'now', 'passes', 'trainings'));
    }
}
