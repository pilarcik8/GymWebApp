<?php

namespace App\Controllers;

use App\Models\GroupClass;
use App\Models\GroupClassParticipant;
use App\Models\TrainerInfo;
use App\Models\Image;
use App\Configuration;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use App\Models\Training;
use App\Models\Account;


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
    /*- Tvorba skupinových hodín pre trénerov -*/
    public function index(Request $request): Response
    {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        // skupinové hodiny
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

        // osobné tréningy tohto trénera
        $trainerId = $this->user->getId();
        $trainingsRaw = Training::getAll('`trainer_id` = ?', [$trainerId], '`start_date` DESC');

        $customerIds = [];
        foreach ($trainingsRaw as $t) {
            if ($t->getCustomerId() !== null) {
                $customerIds[] = $t->getCustomerId();
            }
        }
        $customerIds = array_values(array_unique($customerIds));

        $customersMap = [];
        if ($customerIds) {
            $placeholders = implode(',', array_fill(0, count($customerIds), '?'));
            $customers = Account::getAll("`id` IN ($placeholders)", $customerIds);
            foreach ($customers as $c) {
                $customersMap[$c->getId()] = $c->getFirstName() . ' ' . $c->getLastName();
            }
        }

        $personalTrainings = [];
        foreach ($trainingsRaw as $tr) {
            $start = $tr->getStartDate();
            $dt = $start ? new \DateTimeImmutable($start) : null;
            $personalTrainings[] = [
                'model' => $tr,
                'date' => $dt ? $dt->format('d.m. Y') : '',
                'time' => $dt ? $dt->format('H:i') : '',
                'customerName' => $tr->getCustomerId() && isset($customersMap[$tr->getCustomerId()])
                    ? $customersMap[$tr->getCustomerId()]
                    : '—',
            ];
        }

        return $this->html(compact('message', 'groupClasses', 'personalTrainings'));
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

    /* Edit profilu trénera */
    public function trainerProfileEditor(Request $request): Response
    {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $infos = TrainerInfo::getAll('`trainer_id` = ?', [$this->user->getId()]);
        $trainerInfo = $infos[0] ?? null;
        if (!$trainerInfo) {
            $trainerInfo = new TrainerInfo();
            $trainerInfo->setTrainerId($this->user->getId());
        }

        // všetky obrázky použiteľné pre trénerov (img_use = 'trainer')
        $trainerImages = Image::getAll('`img_use` = ?', ['trainer']);

        return $this->html(compact('message', 'trainerInfo', 'trainerImages'), 'trainer_profile_editor');
    }

    /* Edit profilu trénera - spracovanie formulára (bez obrázka) */
    public function editTrainerInfo(Request $request): Response
    {
        if (!$request->hasValue('editTrainerInfo')) {
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        $infos = TrainerInfo::getAll('`trainer_id` = ?', [$this->user->getId()]);
        $trainerInfo = $infos[0] ?? null;
        if (!$trainerInfo) {
            $trainerInfo = new TrainerInfo();
            $trainerInfo->setTrainerId($this->user->getId());
        }

        $short = trim((string)$request->post('short'));
        $description = trim((string)$request->post('description'));

        $purchaseCostRaw = $request->post('purchase_cost');
        $purchaseCost = is_numeric($purchaseCostRaw) ? (float)$purchaseCostRaw : 20.0;
        if ($purchaseCost < 0) {
            $purchaseCost = 0.0;
        }

        $trainerInfo->setShort($short);
        $trainerInfo->setDescription($description);
        $trainerInfo->setTrainerId($this->user->getId());
        $trainerInfo->setPurchaseCost($purchaseCost);
        $trainerInfo->save();

        $_SESSION['flash_message'] = 'Profil trénera bol uložený.';
        return $this->redirect($this->url('coach.trainerProfileEditor'));
    }

    public function editTrainerPhoto(Request $request): Response
    {
        if (!$request->hasValue('editTrainerPhoto')) {
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        $infos = TrainerInfo::getAll('`trainer_id` = ?', [$this->user->getId()]);
        $trainerInfo = $infos[0] ?? null;
        if (!$trainerInfo) {
            $trainerInfo = new TrainerInfo();
            $trainerInfo->setTrainerId($this->user->getId());
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_message'] = 'Nebola vybratá žiadna fotka alebo nastala chyba pri nahrávaní.';
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        $file = $_FILES['image'];
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        $maxBytes = 5 * 1024 * 1024;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset($allowed[$mime])) {
            $_SESSION['flash_message'] = 'Nepovolený typ súboru.';
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        if ($file['size'] > $maxBytes) {
            $_SESSION['flash_message'] = 'Súbor je príliš veľký (max 5 MB).';
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        if (false === @getimagesize($file['tmp_name'])) {
            $_SESSION['flash_message'] = 'Súbor nie je platný obrázok.';
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        $uploadDir = rtrim(Configuration::UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR . 'trainer' . DIRECTORY_SEPARATOR;
        $publicDir = __DIR__ . '/../../public/' . $uploadDir;
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }

        $ext = $allowed[$mime];
        $unique = bin2hex(random_bytes(10)) . '.' . $ext;
        $dest = $publicDir . $unique;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $_SESSION['flash_message'] = 'Chyba pri ukladaní súboru.';
            return $this->redirect($this->url('coach.trainerProfileEditor'));
        }

        $imageId = $trainerInfo->getImageId();
        if ($imageId) {
            $img = Image::getOne($imageId);
            if ($img) {
                // zmaž starý súbor, ak existuje
                $oldFilename = $img->getFilename();
                if ($oldFilename) {
                    $oldPath = $publicDir . $oldFilename;
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $img->setFilename($unique);
                $img->setUse('trainer');
                $img->setCreatedBy($this->user->getId());
                $img->save();
            }
        } else {
            $img = new Image();
            $img->setFilename($unique);
            $img->setUse('trainer');
            $img->setCreatedBy($this->user->getId());
            $img->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $img->save();
            $imageId = $img->getId();
        }

        $trainerInfo->setImageId($imageId);
        $trainerInfo->setTrainerId($this->user->getId());
        $trainerInfo->save();

        $_SESSION['flash_message'] = 'Fotografia bola aktualizovaná.';
        return $this->redirect($this->url('coach.trainerProfileEditor'));
    }
}
