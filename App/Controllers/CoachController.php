<?php

namespace App\Controllers;

use App\Models\GroupClass;
use App\Models\GroupClassParticipant;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;


class CoachController extends BaseController
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

        if ($this->user->getRole() !== 'trainer')
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

        $raw = GroupClass::getAll('`trainer_id` = ?', [$this->user->getId()], 'start_datetime ASC');

        $classIds = array_map(function($g){ return $g->getId(); }, $raw);
        $reservationsMap = [];
        if (count($classIds)) {
            $ph = implode(',', array_fill(0, count($classIds), '?'));
            $parts = GroupClassParticipant::getAll("`group_class_id` IN ($ph)", $classIds);
            foreach ($parts as $p) {
                $cid = $p->getGroupClassId();
                if (!isset($reservationsMap[$cid])) $reservationsMap[$cid] = 0;
                $reservationsMap[$cid]++;
            }
        }

        $groupClasses = [];
        foreach ($raw as $gc) {
            $dt = new \DateTimeImmutable($gc->getStartDatetime());
            $id = $gc->getId();
            $groupClasses[] = [
                'model' => $gc,
                'date' => $dt->format('d.m. Y'),
                'time' => $dt->format('H:i'),
                'reservations' => isset($reservationsMap[$id]) ? $reservationsMap[$id] : 0,
            ];
        }

        return $this->html(compact('message', 'groupClasses'));
    }

    /**
     * @throws \Exception
     */
    public function createGroupClass(Request $request): Response
    {
        if ($request->hasValue('createGroupClass')) {
            $name = trim((string) $request->post('name'));
            $date = $request->post('date');
            $duration_minutes = (int)$request->post('duration_minutes');
            $trainer_id = (int)$request->post('trainer_id');
            $capacity = (int)$request->post('capacity');
            $description = $request->post('description');

            if ($description !== null) {
                $description = trim((string) $description);
                if ($description === '') {
                    $description = null;
                }
            }

            $classStart = \DateTime::createFromFormat('Y-m-d\\TH:i', $date);
            $minToStart = new \DateTime('now');
            $minToStart->modify('+24 hours');

            $classStartFmt = (clone $classStart)->format('Y-m-d H:i:s');
            $classEndFmt = ((clone $classStart)->modify('+'.$duration_minutes.' minutes'))->format('Y-m-d H:i:s');

            $trainer_conflicts = GroupClass::getAll(
                '`trainer_id` = ? AND `start_datetime` < ? AND DATE_ADD(`start_datetime`, INTERVAL `duration_minutes` MINUTE) > ?',
                [$trainer_id, $classEndFmt, $classStartFmt]
            );

            // kontrola ci v danom case uz nema naplanovanu hodinu/hodiny
            $conflictCount = count($trainer_conflicts);

            if ($conflictCount > 0) {
                $idsArray = [];

                foreach ($trainer_conflicts as $conflict) {
                    $idsArray[] = $conflict->getId();
                }

                if (count($idsArray) > 1) {
                    $last = array_pop($idsArray);
                    $ids = implode(', ', $idsArray) . ' a ' . $last;
                } else {
                    $ids = $idsArray[0];
                }
                if (count($idsArray) > 1) {
                    $_SESSION['flash_message'] =
                        "Tréner už má naplánované hodiny (ID: $ids) v tomto čase.";
                } else {
                    $_SESSION['flash_message'] =
                        "Tréner už má naplánovanú hodinu (ID: $ids) v tomto čase.";
                }
                return $this->redirect($this->url("coach.index"));
            }

            // kontrola ci je datum aspon 24 hod od aktualneho casu
            if ($classStart < $minToStart) {
                $_SESSION['flash_message'] = "Dátum musí byť aspoň: " . $classStart->format('d.m. Y H.i');
                return $this->redirect($this->url("coach.index"));
            }

            $gc_model = new GroupClass($name, $date, $duration_minutes, $trainer_id, $capacity, $description);
            $gc_model->save();
            $_SESSION['flash_message'] = "Hodina $name bola úspešne vytvorená.";
        }
        else {
            $_SESSION['flash_message'] = "Chyba pri vytváraní hodiny.";
        }

        return $this->redirect($this->url("coach.index"));
    }

    /**
     * @throws \Exception
     */
    public function deleteGroupClass(Request $request): Response {
        if ($request->hasValue('deleteGroupClass')) {
            $id = (int)$request->post('id');

            // účastníci hodiny
            $participants = GroupClassParticipant::getAll('`group_class_id` = ?', [$id]);
            foreach ($participants as $p) {
                $p->delete();
            }

            // hodina
            $groupClass = GroupClass::getOne($id);
            if (!$groupClass) {
                $_SESSION['flash_message'] = "Hodina s ID #$id nebola nájdená.";
                return $this->redirect($this->url("coach.index"));
            }

            $name = $groupClass->getName();
            $groupClass->delete();
            $_SESSION['flash_message'] = "Hodina s $name bola úspešne zmazaná.";
        } else {
            $_SESSION['flash_message'] = "Chyba pri mazaní hodiny.";
        }
        return $this->redirect($this->url("coach.index"));
    }

}
