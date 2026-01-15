<?php

namespace App\Controllers;

use App\Models\Account;
use App\Models\Image;
use App\Configuration;
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
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $accounts = Account::getAll();

        return $this->html(compact('message', 'accounts'));
    }

    /**
     * @throws \Exception
     */
    public function changeRole(Request $request): Response
    {
        if ($request->hasValue('changeRole')) {
            $id = (int)$request->post('id');
            $role = $request->post('role');

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

    /* GALLERY */
    public function gallery(Request $request): Response
    {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $images = Image::getAll(null, [], 'created_at DESC');

        return $this->html(compact('message', 'images'));
    }

    /**
     * Handle image upload from admin gallery.
     * Expects POST with file input 'image' and hidden 'uploadGalleryImage'.
     */
    public function uploadGalleryImage(Request $request): Response
    {
        /*-Načítanie a validácia upload formulára-*/
        if (!$request->hasValue('uploadGalleryImage')) {
            return $this->redirect($this->url('admin.gallery'));
        }

        // nenastala PHP upload chyba?
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_message'] = 'Nastala upload chyba. Overte si či súbor môže byť príliž veľký.';
            return $this->redirect($this->url('admin.gallery'));
        }

        // Načítať informácie o nahratom súbore
        $file = $_FILES['image'];
        // maximálna povolená veľkosť (5 MB)
        $maxBytes = 5 * 1024 * 1024; // 5 MB
        $allowed = ['image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp'];

        // zistiť typ
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset($allowed[$mime])) {
            // Ak MIME nie je povolený, nastaviť správu a presmerovať späť
            $_SESSION['flash_message'] = 'Neprijatelný typ súboru.';
            return $this->redirect($this->url('admin.gallery'));
        }

        // Odmietnuť súbory väčšie než limit
        if ($file['size'] > $maxBytes) {
            $_SESSION['flash_message'] = 'Súbor je privelký.';
            return $this->redirect($this->url('admin.gallery'));
        }

        // Ďalšia kontrola, či súbor je skutočný obrázok
        if (false === @getimagesize($file['tmp_name'])) {
            $_SESSION['flash_message'] = 'File is not a valid image.';
            return $this->redirect($this->url('admin.gallery'));
        }

        /*-Uloženie súboru a záznamu do DB-*/
        // Vytvoriť adresár pre nahrané súbory v public podľa Configuration::UPLOAD_DIR
        $uploadDir = rtrim(Configuration::UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR;
        $publicDir = __DIR__ . '/../../public/' . $uploadDir;
        if (!is_dir($publicDir)) mkdir($publicDir, 0755, true);

        // vytvor jedinečné meno súboru
        $ext = $allowed[$mime];
        $unique = bin2hex(random_bytes(10)) . '.' . $ext;
        $dest = $publicDir . $unique;

        // presun nahraný súbor z temp lokácie do finálneho adresára
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $_SESSION['flash_message'] = 'Prišlo k chybe pri uložovaní súboru.';
            return $this->redirect($this->url('admin.gallery'));
        }

        // Vytvoriť inštanciu
        $title = trim((string)$request->post('title')) ?: null;
        $alt = trim((string)$request->post('alt')) ?: null;

        $img = new Image();
        $img->setFromRequest(new Request());
        $img->setFilename($unique);
        $img->setTitle($title);
        $img->setAlt($alt);
        $img->setCreatedBy($this->user->getId());
        $img->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
        $img->save();

        return $this->redirect($this->url('admin.gallery'));
    }

    /**
     * Delete image by id (POST deleteGalleryImage)
     */
    public function deleteGalleryImage(Request $request): Response
    {
        if (!$request->hasValue('deleteGalleryImage')) {
            return $this->redirect($this->url('admin.gallery'));
        }

        $id = (int)$request->post('id');
        $img = Image::getOne($id);
        if (!$img) {
            $_SESSION['flash_message'] = 'Obrázok sme neboli schopní nájsť.';
            return $this->redirect($this->url('admin.gallery'));
        }

        // cesta
        $publicRoot = realpath(__DIR__ . '/../../public');
        $uploadDir = trim(str_replace(['\\','/'], DIRECTORY_SEPARATOR, Configuration::UPLOAD_DIR), DIRECTORY_SEPARATOR);
        $galleryDir = $publicRoot . DIRECTORY_SEPARATOR . $uploadDir . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR;

        $filename = basename($img->getFilename());
        $path = $galleryDir . $filename;

        // odstrániť súbor zo servera
        if (is_file($path)) {
            try {
                if (!@unlink($path)) {
                    $_SESSION['flash_message'] = 'Súbor sa nepodarilo odstrániť zo servera.';
                    // still attempt to delete DB record to keep DB consistent
                    $img->delete();
                    return $this->redirect($this->url('admin.gallery'));
                }
            } catch (\Throwable $e) {
                $_SESSION['flash_message'] = 'Chyba pri odstraňovaní súboru: ' . $e->getMessage();
                $img->delete();
                return $this->redirect($this->url('admin.gallery'));
            }
        } else {
            $_SESSION['flash_message'] = 'Súbor neexistoval na disku, záznam bude odstránený.';
            $img->delete();
            return $this->redirect($this->url('admin.gallery'));
        }

        $img->delete();
        return $this->redirect($this->url('admin.gallery'));
    }
}
